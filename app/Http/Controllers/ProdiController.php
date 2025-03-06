<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Kurikulum;
use App\Models\Semester;
use App\Models\Matkul;
use App\Models\Cpl;
use App\Models\Kajur;
use App\Models\SubCpl;
use App\Models\MkSubCpl;
use App\Models\User;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Imports\MahasiswaImport;
//use Yajra\DataTables\Facades\DataTables;
use DataTables;

use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProdiController extends Controller
{
    public function index() {
        return view('prodiadmin.index');
    }

    // LAPORAN
    public function t_laporan_cpmk() {

        $smtr = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        // Ambil kurikulum
        $kurikulums = DB::table('kurikulums')
            ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
            ->where('prodis.id', $prodi_id)
            ->select('kurikulums.*')
            ->get();

        // Ambil CPL
        $cpel = DB::table('cpls')
            ->join('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->join('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
            ->where('admprodis.prodi_id', $prodi_id)
            ->select('cpls.*', 'cpls.id as cpl_id')
            ->get();

        // Ambil SubCPL yang sudah ada berdasarkan prodi_id, lalu group berdasarkan kode_cpl untuk mencari angka terbesar
        $existingSubCPLs = DB::table('subcpls')
            ->join('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
            ->where('cpls.prodi_id', $prodi_id)  // Filter hanya berdasarkan prodi_id yang sesuai
            ->select('subcpls.kode_subcpl', 'cpls.kode_cpl')
            ->get()
            ->groupBy('kode_cpl')
            ->map(function ($group) {
                return $group->max(function ($item) {
                    // Ambil angka setelah tanda titik
                    $index = explode('.', $item->kode_subcpl)[1] ?? 0;
                    return (int) $index;
                });
            });

        return view ('prodiadmin/t_laporan_cpmk', compact('smtr','kurikulums', 'cpel', 'existingSubCPLs', 'prodi_id'));
    }

    /*
    public function getCapaianCPL(Request $request)
    {
        $kurikulum_id = $request->kurikulum_id;
        $cpl_id = $request->cpl_id;
        $nim = $request->nim;

        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;
  
        $nilai = DB::table('inpnilais')
        ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
        ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
        ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
        ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
        ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
        ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
        ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
        ->join('kurikulums', 'matkuls.kurikulum_id', '=', 'kurikulums.id')
        ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
        ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
        ->join('mhs_kelas', function ($join) {
            $join->on('mhs_kelas.kelas_id', '=', 'inpnilais.kelas_id')
                 ->on('mhs_kelas.nim', '=', 'inpnilais.nim');
        })
        ->select([
            'inpnilais.nim',
            'kurikulums.nama_kuri as KURIKULUM',
            'inpnilais.nilai as NILAI_MHS',
            'kompnilais.jen_penilaian as komponen_label',
            'mkcpmks.kode_cpmk as KODE_CPMK',
            'mkcpmks.id as ID_CPMK',
            'subcpls.kode_subcpl as KODE_SUB_CPL',
            'subcpls.cpl_id as ID_CPL',
            'subcpls.trgt_nilai as NILAI_BATAS_SUBCPL',
            'cpls.kode_cpl as KD_CPL',
            'mahasiswas.nama_mahasiswa AS NAMA_MHS',
            'kelas.nama_kelas as KODE_KELAS',
            'matkuls.kode_mk as KODE_MK',
            'matkuls.nama_mk as NAMA_MK',
            'matkuls.prodi_id as ID_PRODI',
            'kelas.id as ID_KELAS',
            'semesters.keterangan as ID_SEMESTER',
            // Menambahkan kolom STATUS
            DB::raw("
                CASE 
                    WHEN inpnilais.nilai >= subcpls.trgt_nilai 
                    THEN 'Tercapai' 
                    ELSE 'Tidak Tercapai' 
                END as STATUS
            ")
        ])
        ->where('matkuls.prodi_id', $prodi_id)
        ->where('mahasiswas.nim', $nim)
        ->where('kurikulums.id', $kurikulum_id)
        //->where('subcpls.cpl_id', $cpl_id)
        ->get();


        $rata_subcpl = DB::table('inpnilais')
        ->select(
            'subcpls.cpl_id AS ID_CPL',
            'cpls.kode_cpl AS KODE_CPL',
            'subcpls.id AS ID_SUB_CPL',
            'subcpls.kode_subcpl AS KODE_SUB_CPL',
            'subcpls.bobot AS BOBOT',
            DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
        )
        ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
        ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
        ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
        ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
        ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
        ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
        ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
        ->where('mahasiswas.nim', $nim)
        //->whereBetween('cpls.id', [1, 14]) // Sesuai dengan BETWEEN 1 AND 14 di SQL
        ->groupBy('subcpls.id')
        ->orderBy('ID_SUB_CPL', 'ASC')
        ->get();

        $subQueryCPL = DB::table('inpnilais')
        ->select(
            'subcpls.cpl_id',
            'cpls.kode_cpl',
            'subcpls.id AS ID_SUB_CPL',
            'subcpls.bobot',
            DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
        )
        ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
        ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
        ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
        ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
        ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
        ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
        ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
        ->where('mahasiswas.nim', $nim)
        ->groupBy('subcpls.id', 'cpls.kode_cpl', 'subcpls.bobot', 'subcpls.cpl_id');

   
    $queryCPL = DB::table(DB::raw("({$subQueryCPL->toSql()}) as nilai_subcpl"))
        ->mergeBindings($subQueryCPL)
        ->select(
            'nilai_subcpl.cpl_id AS ID_CPL',
            'nilai_subcpl.kode_cpl AS KODE_CPL',
            DB::raw('SUM(nilai_subcpl.bobot * nilai_subcpl.RATA_RATA_NILAI_SUBCPL) / SUM(nilai_subcpl.bobot) AS NILAI_CPL')
        )
        ->groupBy('nilai_subcpl.cpl_id', 'nilai_subcpl.kode_cpl')
        ->orderBy('nilai_subcpl.cpl_id', 'ASC')
        ->get();
        return response()->json([
            'rata_subcpl' => DataTables::of($rata_subcpl)->addIndexColumn()->make(true),
            'nilai_keseluruhan' => DataTables::of($nilai)->addIndexColumn()->make(true),
            'nilai_cpl' => DataTables::of($queryCPL)->addIndexColumn()->make(true)
        ]);
    }
    */
    public function t_laporan_cpl_subcpl_prodi(Request $request)
    {
        $smtr = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        // Ambil kurikulum
        $kurikulums = DB::table('kurikulums')
            ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
            ->where('prodis.id', $prodi_id)
            ->select('kurikulums.*')
            ->get();

        return view ('prodiadmin/t_laporan_cpl_subcpl_prodi', compact('smtr','kurikulums', 'prodi_id'));
    }

    

    public function getCapaianCPL(Request $request)
    {
        /*
        $request->validate([
            'kurikulum_id' => 'required|integer',
            'cpl_id' => 'nullable|integer',
            'nim' => 'required|string|exists:mahasiswas,nim'
        ]);
        */
        //Log::info('masuk sini data semester : ', $request->semester_id);

        $user_id = Auth::id();
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();

        if (!$adminProdi) {
            return response()->json(['error' => 'Admin Prodi tidak ditemukan!'], 400);
        }
        $prodi_id = $adminProdi->prodi_id;
        $nim = $request->nim;
        $kurikulum_id = Crypt::decrypt($request->kurikulum_id);
        $semester_id = Crypt::decrypt($request->semester_id);
        $sem = "";
        if($semester_id == 9) {
            $sem =  "Semua Semester";
        }

        //Log::info('masuk sini data semester : ', $semester_id);
        //$kurikulum_id = $request->kurikulum_id;
        //$semester_id = decrypt($request->semester_id);
        //dd($semester_id);

        // Query Nilai Mahasiswa
        $nilai = DB::table('inpnilais')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('kurikulums', 'matkuls.kurikulum_id', '=', 'kurikulums.id')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
            ->where('matkuls.prodi_id', $prodi_id)
            ->where('mahasiswas.nim', $nim)
            ->where('kurikulums.id', $kurikulum_id)
            //->where('semesters.id', $semester_id)
            ->when($semester_id != 9, function ($query) use ($semester_id) {
                    return $query->where('semesters.id', $semester_id);
                })
            ->select([
                'inpnilais.nim',
                'kurikulums.nama_kuri as KURIKULUM',
                'inpnilais.nilai as NILAI_MHS',
                'kompnilais.jen_penilaian as komponen_label',
                'mkcpmks.kode_cpmk as KODE_CPMK',
                'subcpls.kode_subcpl as KODE_SUB_CPL',
                'cpls.kode_cpl as KD_CPL',
                'kelas.nama_kelas as KODE_KELAS',
                'semesters.keterangan as ID_SEMESTER',
                'mahasiswas.nama_mahasiswa AS NAMA_MHS',
                'matkuls.kode_mk as KODE_MK',
                'matkuls.nama_mk as NAMA_MK',
                DB::raw("CASE WHEN inpnilais.nilai >= subcpls.trgt_nilai THEN 'Tercapai' ELSE 'Tidak Tercapai' END as STATUS")
            ])
            ->get();

        // Query Rata-rata SubCPL
        $rata_subcpl = DB::table('inpnilais')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            //->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->where('mahasiswas.nim', $nim)
            //->where('kelas.semester_id', $semester_id)
            ->when($semester_id != 9, function ($query) use ($semester_id) {
                    return $query->where('kelas.semester_id', $semester_id);
                })
            ->groupBy('subcpls.id')
            ->orderBy('subcpls.id', 'ASC')
            ->select([
                'subcpls.cpl_id AS ID_CPL',
                'cpls.kode_cpl AS KODE_CPL',
                'subcpls.id AS ID_SUB_CPL',
                'subcpls.kode_subcpl AS KODE_SUB_CPL',
                'subcpls.bobot AS BOBOT',
                'subcpls.trgt_nilai AS TARGET_NILAI',
                //DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
                DB::raw('ROUND(AVG(inpnilais.nilai), 2) AS RATA_RATA_NILAI_SUBCPL')
            ])
            ->get();

        // Query untuk mendapatkan semua Sub-CPL berdasarkan Kurikulum & Prodi
        $subcplProdi = DB::table('subcpls')
        ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
        ->where('cpls.kurikulum_id', $kurikulum_id)
        ->where('cpls.prodi_id', $prodi_id)
        ->select([
            'subcpls.cpl_id AS ID_CPL',
            'cpls.kode_cpl AS KODE_CPL',
            'subcpls.id AS ID_SUB_CPL',
            'subcpls.kode_subcpl AS KODE_SUB_CPL',
            'subcpls.bobot AS BOBOT',
            'subcpls.trgt_nilai AS TARGET_NILAI',
        ])
        ->get();

        $subcplProdiList = $subcplProdi->pluck('ID_SUB_CPL')->toArray();
        //Log::info("subcplProdiList: ", $subcplProdiList); // Ambil daftar semua ID_SUB_CPL dari CPL Prodi
        $existingSubCPLs = $rata_subcpl->pluck('ID_SUB_CPL')->toArray(); // Ambil daftar ID_SUB_CPL yang sudah ada nilainya dari rata_subcpl
        //Log::info("existingSubCPLs: ", $existingSubCPLs);
        $missingSubCPLs = array_diff($subcplProdiList, $existingSubCPLs); // Cari ID_SUB_CPL yang belum memiliki nilai (harus diset ke 0)

        foreach ($missingSubCPLs as $idSubCpl) {
            $subcplData = $subcplProdi->firstWhere('ID_SUB_CPL', $idSubCpl);

            $rata_subcpl->push((object) [
                'ID_CPL' => $subcplData->ID_CPL,
                'KODE_CPL' => $subcplData->KODE_CPL,
                'ID_SUB_CPL' => $idSubCpl,
                'KODE_SUB_CPL' => $subcplData->KODE_SUB_CPL,
                'BOBOT' => $subcplData->BOBOT,
                'TARGET_NILAI'=> $subcplData->TARGET_NILAI,
                'RATA_RATA_NILAI_SUBCPL' => 0
            ]);
        }
        // **Sort hasil akhir berdasarkan ID_SUB_CPL agar lebih rapi**
        $rata_subcpl = $rata_subcpl->sortBy('ID_SUB_CPL')->values();    

        // Query CPL dengan perhitungan bobot sub-CPL
        $subQueryCPL = DB::table('inpnilais')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            //->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->where('mahasiswas.nim', $nim)
            //->where('kelas.semester_id', $semester_id)
            ->when($semester_id != 9, function ($query) use ($semester_id) {
                return $query->where('kelas.semester_id', $semester_id);
            })
            ->groupBy('subcpls.id', 'cpls.kode_cpl', 'subcpls.bobot', 'subcpls.cpl_id')
            ->select([
                'subcpls.cpl_id',
                'cpls.kode_cpl',
                'subcpls.id AS ID_SUB_CPL',
                'subcpls.bobot',
                DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
                //DB::raw('ROUND(AVG(inpnilais.nilai), 2) AS RATA_RATA_NILAI_SUBCPL')
            ]);

        $queryCPL = DB::table(DB::raw("({$subQueryCPL->toSql()}) as nilai_subcpl"))
            ->mergeBindings($subQueryCPL)
            ->select(
                'nilai_subcpl.cpl_id AS ID_CPL',
                'nilai_subcpl.kode_cpl AS KODE_CPL',
                //DB::raw('SUM(nilai_subcpl.bobot * nilai_subcpl.RATA_RATA_NILAI_SUBCPL) / SUM(nilai_subcpl.bobot) AS NILAI_CPL')
                DB::raw('ROUND(SUM(nilai_subcpl.bobot * nilai_subcpl.RATA_RATA_NILAI_SUBCPL) / SUM(nilai_subcpl.bobot), 2) AS NILAI_CPL')
            )
            ->groupBy('nilai_subcpl.cpl_id', 'nilai_subcpl.kode_cpl')
            ->orderBy('nilai_subcpl.cpl_id', 'ASC')
            ->get();
        
        $cplProdi = DB::table('cpls')
            ->where('kurikulum_id', $kurikulum_id)
            ->where('prodi_id', $prodi_id)
            ->get();

        // **Tambahkan KODE_CPL yang tidak ada di queryCPL**
        $cplProdiList = $cplProdi->pluck('kode_cpl')->toArray(); // Ambil daftar semua KODE_CPL dari CPL Prodi
        $existingCPLs = $queryCPL->pluck('KODE_CPL')->toArray(); // Ambil daftar KODE_CPL yang sudah ada nilainya dari queryCPL
        $missingCPLs = array_diff($cplProdiList, $existingCPLs); // Cari KODE_CPL yang belum memiliki nilai (harus diset ke 0)

        // Tambahkan data dengan NILAI_CPL = 0 jika tidak ada di queryCPL
        foreach ($missingCPLs as $kodeCpl) {
            $queryCPL->push((object) [
                'ID_CPL' => null, // ID_CPL tidak diketahui, bisa dikosongkan atau diambil dari $cplProdi jika perlu
                'KODE_CPL' => $kodeCpl,
                'NILAI_CPL' => 0
            ]);
        }

        // **Sort hasil akhir berdasarkan KODE_CPL agar lebih rapi**
        $queryCPL = $queryCPL->sortBy('KODE_CPL')->values();

        return response()->json([
            'rata_subcpl' => $rata_subcpl,
            'nilai_keseluruhan' => $nilai,
            'nilai_cpl' => $queryCPL,
            'sem' =>$sem
        ]);
    }

    public function getCapaianCPLProdi(Request $request)
    {
        $user_id = Auth::id();
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();

        if (!$adminProdi) {
            return response()->json(['error' => 'Admin Prodi tidak ditemukan!'], 400);
        }
        $prodi_id = $adminProdi->prodi_id;
        $kurikulum_id = Crypt::decrypt($request->kurikulum_id);
        $semester_id = Crypt::decrypt($request->semester_id);

        // Query Nilai Mahasiswa
        $nilai = DB::table('inpnilais')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('kurikulums', 'matkuls.kurikulum_id', '=', 'kurikulums.id')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
            ->where('matkuls.prodi_id', $prodi_id)
            //->where('mahasiswas.nim', $nim)
            ->where('matkuls.kurikulum_id', $kurikulum_id)
            ->where('kelas.semester_id', $semester_id)
            ->select([
                'inpnilais.nim',
                'kurikulums.nama_kuri as KURIKULUM',
                'inpnilais.nilai as NILAI_MHS',
                'kompnilais.jen_penilaian as komponen_label',
                'mkcpmks.kode_cpmk as KODE_CPMK',
                'subcpls.kode_subcpl as KODE_SUB_CPL',
                'cpls.kode_cpl as KD_CPL',
                'kelas.nama_kelas as KODE_KELAS',
                'semesters.keterangan as ID_SEMESTER',
                'mahasiswas.nama_mahasiswa AS NAMA_MHS',
                'matkuls.kode_mk as KODE_MK',
                'matkuls.nama_mk as NAMA_MK',
                DB::raw("CASE WHEN inpnilais.nilai >= subcpls.trgt_nilai THEN 'Tercapai' ELSE 'Tidak Tercapai' END as STATUS")
            ])
            ->get();

        // Query Rata-rata SubCPL
        $rata_subcpl = DB::table('inpnilais')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            //->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            //->where('mahasiswas.nim', $nim)
            ->where('kelas.semester_id', $semester_id)
            ->where('matkuls.prodi_id', $prodi_id)
            ->where('matkuls.kurikulum_id', $kurikulum_id)
            ->groupBy('subcpls.id')
            ->orderBy('subcpls.id', 'ASC')
            ->select([
                'subcpls.cpl_id AS ID_CPL',
                'cpls.kode_cpl AS KODE_CPL',
                'subcpls.id AS ID_SUB_CPL',
                'subcpls.kode_subcpl AS KODE_SUB_CPL',
                'subcpls.bobot AS BOBOT',
                'subcpls.trgt_nilai AS TARGET_NILAI',
                //DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
                DB::raw('ROUND(AVG(inpnilais.nilai), 2) AS RATA_RATA_NILAI_SUBCPL')
            ])
            ->get();
        
        // Query untuk mendapatkan semua Sub-CPL berdasarkan Kurikulum & Prodi
        $subcplProdi = DB::table('subcpls')
        ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
        ->where('cpls.kurikulum_id', $kurikulum_id)
        ->where('cpls.prodi_id', $prodi_id)
        ->select([
            'subcpls.cpl_id AS ID_CPL',
            'cpls.kode_cpl AS KODE_CPL',
            'subcpls.id AS ID_SUB_CPL',
            'subcpls.kode_subcpl AS KODE_SUB_CPL',
            'subcpls.bobot AS BOBOT',
            'subcpls.trgt_nilai AS TARGET_NILAI',
        ])
        ->get();

        $subcplProdiList = $subcplProdi->pluck('ID_SUB_CPL')->toArray();
        //Log::info("subcplProdiList: ", $subcplProdiList); // Ambil daftar semua ID_SUB_CPL dari CPL Prodi
        $existingSubCPLs = $rata_subcpl->pluck('ID_SUB_CPL')->toArray(); // Ambil daftar ID_SUB_CPL yang sudah ada nilainya dari rata_subcpl
        //Log::info("existingSubCPLs: ", $existingSubCPLs);
        $missingSubCPLs = array_diff($subcplProdiList, $existingSubCPLs); // Cari ID_SUB_CPL yang belum memiliki nilai (harus diset ke 0)

        foreach ($missingSubCPLs as $idSubCpl) {
            $subcplData = $subcplProdi->firstWhere('ID_SUB_CPL', $idSubCpl);

            $rata_subcpl->push((object) [
                'ID_CPL' => $subcplData->ID_CPL,
                'KODE_CPL' => $subcplData->KODE_CPL,
                'ID_SUB_CPL' => $idSubCpl,
                'KODE_SUB_CPL' => $subcplData->KODE_SUB_CPL,
                'BOBOT' => $subcplData->BOBOT,
                'TARGET_NILAI'=> $subcplData->TARGET_NILAI,
                'RATA_RATA_NILAI_SUBCPL' => 0
            ]);
        }
        // **Sort hasil akhir berdasarkan ID_SUB_CPL agar lebih rapi**
        $rata_subcpl = $rata_subcpl->sortBy('ID_SUB_CPL')->values();

        // Query CPL dengan perhitungan bobot sub-CPL
        $subQueryCPL = DB::table('inpnilais')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('subcpls', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
            ->join('cpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            //->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            //->where('mahasiswas.nim', $nim)
            ->where('kelas.semester_id', $semester_id)
            ->where('matkuls.prodi_id', $prodi_id)
            ->where('matkuls.kurikulum_id', $kurikulum_id)
            ->groupBy('subcpls.id', 'cpls.kode_cpl', 'subcpls.bobot', 'subcpls.cpl_id')
            ->select([
                'subcpls.cpl_id',
                'cpls.kode_cpl',
                'subcpls.id AS ID_SUB_CPL',
                'subcpls.bobot',
                //DB::raw('AVG(inpnilais.nilai) AS RATA_RATA_NILAI_SUBCPL')
                DB::raw('ROUND(AVG(inpnilais.nilai), 2) AS RATA_RATA_NILAI_SUBCPL')
            ]);

        $queryCPL = DB::table(DB::raw("({$subQueryCPL->toSql()}) as nilai_subcpl"))
            ->mergeBindings($subQueryCPL)
            ->select(
                'nilai_subcpl.cpl_id AS ID_CPL',
                'nilai_subcpl.kode_cpl AS KODE_CPL',
                //DB::raw('SUM(nilai_subcpl.bobot * nilai_subcpl.RATA_RATA_NILAI_SUBCPL) / SUM(nilai_subcpl.bobot) AS NILAI_CPL')
                DB::raw('ROUND(SUM(nilai_subcpl.bobot * nilai_subcpl.RATA_RATA_NILAI_SUBCPL) / SUM(nilai_subcpl.bobot), 2) AS NILAI_CPL')
            )
            ->groupBy('nilai_subcpl.cpl_id', 'nilai_subcpl.kode_cpl')
            ->orderBy('nilai_subcpl.cpl_id', 'ASC')
            ->get();
        
        $cplProdi = DB::table('cpls')
            ->where('kurikulum_id', $kurikulum_id)
            ->where('prodi_id', $prodi_id)
            ->get();
        
        // **Tambahkan KODE_CPL yang tidak ada di queryCPL**
        $cplProdiList = $cplProdi->pluck('kode_cpl')->toArray(); // Ambil daftar semua KODE_CPL dari CPL Prodi
        $existingCPLs = $queryCPL->pluck('KODE_CPL')->toArray(); // Ambil daftar KODE_CPL yang sudah ada nilainya dari queryCPL
        $missingCPLs = array_diff($cplProdiList, $existingCPLs); // Cari KODE_CPL yang belum memiliki nilai (harus diset ke 0)

        // Tambahkan data dengan NILAI_CPL = 0 jika tidak ada di queryCPL
        foreach ($missingCPLs as $kodeCpl) {
            $queryCPL->push((object) [
                'ID_CPL' => null, // ID_CPL tidak diketahui, bisa dikosongkan atau diambil dari $cplProdi jika perlu
                'KODE_CPL' => $kodeCpl,
                'NILAI_CPL' => 0
            ]);
        }

        // **Sort hasil akhir berdasarkan KODE_CPL agar lebih rapi**
        $queryCPL = $queryCPL->sortBy('KODE_CPL')->values();

        return response()->json([
            'rata_subcpl' => $rata_subcpl,
            'nilai_keseluruhan' => $nilai,
            'nilai_cpl' => $queryCPL
        ]);
    }



    //KURIKULUM
    public function t_kurikulum()
    {
        $kuri = DB::table('kurikulums')
        ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'kurikulums.prodi_id')
        ->leftJoin('users', 'users.id', '=', 'admprodis.user_id')
        ->where('users.email', '=', Auth::guard('web')->user()->email)
        ->select('kurikulums.*')
        ->latest()->get();

        return view('prodiadmin/t_kurikulum', compact('kuri'));
    }

    public function a_kurikulum()
    {
        $user_id = Auth::id();

        $data = DB::table('admprodis')
            ->join('users', 'users.id', '=', 'admprodis.user_id')
            ->where('users.id', $user_id)
            ->value('admprodis.prodi_id');

        return view ('prodiadmin/a_kurikulum', compact('data'));
    }

    public function s_kurikulum(Request $request)
    {
        Request()->validate(
            [
                'prodi_id' => 'required',
                'kode_kuri' => 'required',
                'tahun_kuri' => 'required',
                'deskripsi' => 'required',
                'nama_kuri' => 'required',
                'sk_kuri' => 'required',
            ],
            [
                'prodi_id.required' => 'Wajib dipilih !!!',
                'kode_kuri.required' => 'Wajib diisi !!!',
                'tahun_kuri.required' => 'Wajib diisi !!!',
                'nama_kuri.required' => 'Wajib diisi !!!',
                'deskripsi.required' => 'Wajib diisi !!!',
                'sk_kuri.required' => 'Wajib diisi !!!',
            ]
        );

        $data = [
            'prodi_id' => Request()->prodi_id,
            'kode_kuri' => Request()->kode_kuri,
            'deskripsi' => Request()->deskripsi,
            'tahun_kuri' => Request()->tahun_kuri,
            'nama_kuri' => Request()->nama_kuri,
            'sk_kuri' => Request()->sk_kuri,
        ];

        $filesk = $request->file('filesk');
        if ($request->hasFile('filesk')) {
            $filesk_name = pathinfo($filesk->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = strtolower($filesk->getClientOriginalExtension());
            $filesk_full_name = $filesk_name . '.' . $ext;
            $upload_path = 'SK-Kurikulum';
            $filesk_url = $upload_path . '/' . $filesk_full_name;
            $success = $filesk->move($upload_path, $filesk_full_name);
            $data['filesk'] = $filesk_full_name;
        }

        $data = DB::table('kurikulums')->insert($data);
        return redirect('prodiadmin/t_kurikulum');
    }

    public function e_kurikulum($id)
    {
        $user_id = Auth::id();

        $prodi = DB::table('admprodis')
            ->join('users', 'users.id', '=', 'admprodis.user_id')
            ->join('prodis', 'prodis.id', '=', 'admprodis.prodi_id')
            ->where('users.id', $user_id)
            ->select('prodis.nama_prodi')
            ->first();

        $decryptID = Crypt::decryptString($id);
        $objek = Kurikulum::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('prodiadmin.e_kurikulum', compact('objek','prodi'));
    }

    public function u_kurikulum (Request $request, $id)
    {
        $objek = Kurikulum::findOrFail($id);
        $data = array();

        $filesk = $request->file('filesk');
        if ($request->hasFile('filesk')) {
            $filesk_name = pathinfo($filesk->getClientOriginalName(), PATHINFO_FILENAME);
            $ext = strtolower($filesk->getClientOriginalExtension());
            $filesk_full_name = $filesk_name . '.' . $ext;
            $upload_path = 'SK-Kurikulum';
            $filesk_url = $upload_path . '/' . $filesk_full_name;
            $success = $filesk->move($upload_path, $filesk_full_name);
            $data['filesk'] = $filesk_full_name;
        }

        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('prodiadmin/t_kurikulum');
    }

    public function h_kurikulum($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Kurikulum::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_kurikulum');
    }
    //END KURIKULUM


    //DOSEN
    public function t_dosen()
    {

        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $data = DB::table('dosens')
        ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
        //->join('admprodis', 'admprodis.prodi_id', '=', 'dosens.prodi_id')
        ->join('users','users.id', '=', 'dosens.user_id')
        //->where('dosens.prodi_id', $prodi_id)
        ->select('dosens.*', 'prodis.nama_prodi', 'users.name as nama_dosen')
        //->orderBy('dosens.created_at', 'desc')
        ->get();

        return view ('prodiadmin.t_dosen', compact('data'));
    }

public function e_dosen($id)
    {
        $decryptID = Crypt::decryptString($id);
        $objek = Dosen::with('user')->findOrFail($decryptID);

        return view('prodiadmin.e_dosen', compact('objek'));
    }

    public function u_dosen(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->nidn = $request->nidn;
        $dosen->nip = $request->nip;
        $dosen->save();

        $dosen->user->name = $request->nama_dosen;
        $dosen->user->save();

        return redirect('/prodiadmin/t_dosen');
    }


    //MATA KULIAH
    public function t_matkul()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'matkuls.prodi_id')
        ->where('matkuls.prodi_id', $prodi_id)
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->orderBy('semester_id', 'asc')->get();

        return view('prodiadmin/t_matkul', compact('matk'));
    }

    public function a_matkul()
    {
        $semes = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;
        $kuri = DB::table('kurikulums')
            ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
            ->where('prodis.id', $prodi_id)
            ->select('kurikulums.*')
            ->get();

        return view ('prodiadmin/a_matkul', compact('kuri','semes'));
    }

    public function s_matkul(Request $request)
    {
        $request->validate([
            'nama_mk' => 'required',
            'kode_mk' => 'required',
            'sks_kuri' => 'required|numeric|min:1',
            'sks_teo' => 'required|numeric|min:0',
            'sks_prak' => 'required|numeric|min:0',
            'sks_lap' => 'required|numeric|min:0',
        ],
        [
            'nama_mk.required' => 'Nama Mata Kuliah tidak boleh kosong',
            'kode_mk.required' => 'Kode Mata Kuliah tidak boleh kosong',
            'sks_kuri.required' => 'SKS Kurikulum harus diisi',

        ]);

        $total_sks = $request->sks_teo + $request->sks_prak + $request->sks_lap;

        if ($total_sks != $request->sks_kuri) {
            return redirect()->back()->with('error', 'Total SKS Teori + SKS Praktik + SKS Praktik Lapangan harus sama dengan SKS Kurikulum.');
        }

        $mataKuliah = new Matkul;
        $mataKuliah->nama_mk = $request->nama_mk;
        $mataKuliah->kode_mk = $request->kode_mk;
        $mataKuliah->prodi_id = $request->prodi_id;
        $mataKuliah->semester_id = $request->semester_id;
        $mataKuliah->kurikulum_id = $request->kurikulum_id;
        $mataKuliah->sks_teo = $request->sks_teo;
        $mataKuliah->sks_prak = $request->sks_prak;
        $mataKuliah->sks_lap = $request->sks_lap;
        $mataKuliah->sks_kuri = $request->sks_kuri;
        $mataKuliah->status = $request->status;
        $mataKuliah->rumpun_mk = $request->rumpun_mk;
        $mataKuliah->save();

        return redirect('prodiadmin/t_matkul');
    }


public function e_matkul($id)
    {

        $semes = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;
        $kuri = DB::table('kurikulums')
        ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
        ->where('prodis.id', $prodi_id)
        ->select('kurikulums.*')
        ->get();


        $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'matkuls.prodi_id')
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->latest()->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Matkul::findOrFail($decryptID);

        return view('prodiadmin.e_matkul', compact('objek', 'kuri', 'matk', 'semes'));
    }

    public function u_matkul(Request $request, $id)
    {
        $objek = Matkul::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('prodiadmin/t_matkul');
    }

    public function h_matkul($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Matkul::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_matkul');
    }


    //KELAS
    public function t_kelas(Request $request)
    {
        $semes = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $semester_id = null;
        if ($request->has('semester_id') && !empty($request->semester_id)) {
            try {
                $semester_id = decrypt($request->semester_id);
            } catch (\Exception $e) {
                $semester_id = null;
            }
        }

        $query = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('dosens as dosen_satu', 'kelas.dosen_satu', '=', 'dosen_satu.id')
            ->join('users as user_dosen_satu', 'dosen_satu.user_id', '=', 'user_dosen_satu.id')
            ->leftJoin('dosens as dosen_dua', 'kelas.dosen_dua', '=', 'dosen_dua.id')
            ->leftJoin('users as user_dosen_dua', 'dosen_dua.user_id', '=', 'user_dosen_dua.id')
            ->leftJoin('dosens as dosen_tiga', 'kelas.dosen_tiga', '=', 'dosen_tiga.id')
            ->leftJoin('users as user_dosen_tiga', 'dosen_tiga.user_id', '=', 'user_dosen_tiga.id')
            ->leftJoin('dosens as dosen_empat', 'kelas.dosen_empat', '=', 'dosen_empat.id')
            ->leftJoin('users as user_dosen_empat', 'dosen_empat.user_id', '=', 'user_dosen_empat.id')
            // ->join('mahasiswas', 'kelas.nim', '=', 'mahasiswas.nim')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->select('kelas.*','matkuls.nama_mk','user_dosen_satu.name as nama_dosen_satu',
                    'user_dosen_dua.name as nama_dosen_dua','user_dosen_tiga.name as nama_dosen_tiga',
                    'user_dosen_empat.name as nama_dosen_empat','semesters.nama_smtr')

            ->where('matkuls.prodi_id', $prodi_id);

            if ($semester_id) {
                $query->where('kelas.semester_id', $semester_id);
            }

            $kelas = $query->get();

    return view('prodiadmin/t_kelas', compact('kelas', 'semes', 'semester_id'));

    }

    public function a_kelas()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $dosen = DB::table('dosens')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            //->where('dosens.prodi_id', $prodi_id)
            ->select('dosens.id', 'users.name')
            ->get();

        $matkul = DB::table('matkuls')
            ->where('prodi_id', $prodi_id)
            ->select('id', 'nama_mk')
            ->get();

        $mahasiswa = DB::table('mahasiswas')
            ->where('prodi_id', $prodi_id)
            ->select('id', 'nama_mahasiswa')
            ->get();

        $semesters = DB::table('semesters')->get();

        return view('prodiadmin/a_kelas', compact('dosen', 'matkul', 'mahasiswa', 'semesters'));
    }


    public function s_kelas(Request $request)
    {
    $request->validate([
        'matkul_id' => 'required|exists:matkuls,id',
        'urut' => 'required|integer|min:1',
        'nama_kelas' => 'required|string|max:1',
        'semester_id' => 'required|exists:semesters,id',
        'dosen_satu' => 'required|exists:dosens,id',
        'dosen_dua' => 'nullable|different:dosen_satu|exists:dosens,id',
        'dosen_tiga' => 'nullable|different:dosen_satu,dosen_dua|exists:dosens,id',
        'dosen_empat' => 'nullable|different:dosen_satu,dosen_dua,dosen_tiga|exists:dosens,id',
        'dosen_inputnilai' => 'required|in:1,2,3,4',
    ]);

    $matkul = Matkul::findOrFail($request->matkul_id);

    $kodeMatkul = $matkul->kode_mk;
    $kodeKelas = $kodeMatkul . '-' . $request->urut . $request->nama_kelas;

    $dosenInputNilai = null;

    if ($request->dosen_inputnilai == '1') {
        $dosenInputNilai = $request->dosen_satu;
    } elseif ($request->dosen_inputnilai == '2') {
        $dosenInputNilai = $request->dosen_dua;
    } elseif ($request->dosen_inputnilai == '3') {
        $dosenInputNilai = $request->dosen_tiga;
    } elseif ($request->dosen_inputnilai == '4') {
        $dosenInputNilai = $request->dosen_empat;
    }
    // dd($dosenInputNilai);
    $kelas = Kelas::create([
        'matkul_id' => $request->matkul_id,
        'urut' => $request->urut,
        'nama_kelas' => $request->nama_kelas,
        'kode_kelas' => $kodeKelas,
        'semester_id' => $request->semester_id,
        'dosen_satu' => $request->dosen_satu,
        'dosen_dua' => $request->dosen_dua,
        'dosen_tiga' => $request->dosen_tiga,
        'dosen_empat' => $request->dosen_empat,
        'dosen_inputnilai' => $dosenInputNilai,
    ]);

    return redirect('prodiadmin/t_kelas');
    }


    public function e_kelas($id)
    {
        $decryptID = Crypt::decryptString($id);
        $kelas = Kelas::with(['matkul', 'semester'])->findOrFail($decryptID);

        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $dosen = DB::table('dosens')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            //->where('dosens.prodi_id', $prodi_id)
            ->select('dosens.id', 'users.name')
            ->get();

        $semesters = Semester::all();
        $matkul = Matkul::all();

        return view('prodiadmin.e_kelas', compact('kelas', 'semesters', 'matkul', 'dosen'));
    }


    public function u_kelas(Request $request, $id)
    {

        $decryptID = Crypt::decryptString($id);
        $kelas = Kelas::findOrFail($decryptID);

        $dosenInputNilai = null;
        if ($request->dosen_inputnilai == '1') {
            $dosenInputNilai = $request->dosen_satu;
        } elseif ($request->dosen_inputnilai == '2') {
            $dosenInputNilai = $request->dosen_dua;
        } elseif ($request->dosen_inputnilai == '3') {
            $dosenInputNilai = $request->dosen_tiga;
        } elseif ($request->dosen_inputnilai == '4') {
            $dosenInputNilai = $request->dosen_empat;
        }

        $kelas->update([
            'semester_id' => $request->semester_id,
            'matkul_id' => $request->matkul_id,
            'urut' => $request->urut,
            'nama_kelas' => $request->nama_kelas,
            'dosen_satu' => $request->dosen_satu,
            'dosen_dua' => $request->dosen_dua,
            'dosen_tiga' => $request->dosen_tiga,
            'dosen_empat' => $request->dosen_empat,
            'dosen_inputnilai' => $dosenInputNilai,
        ]);

        return redirect('prodiadmin/t_kelas');

    }

    public function h_kelas($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Kelas::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_kelas');
    }


    public function t_mhskelas(Request $request)
    {
        $semes = Semester::all();
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $semester_id = null;
        if ($request->has('semester_id') && !empty($request->semester_id)) {
            try {
                $semester_id = decrypt($request->semester_id);
            } catch (\Exception $e) {
                $semester_id = null;
            }
        }

        $query = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('dosens as dosen_satu', 'kelas.dosen_satu', '=', 'dosen_satu.id')
            ->join('users as user_dosen_satu', 'dosen_satu.user_id', '=', 'user_dosen_satu.id')
            ->leftJoin('dosens as dosen_dua', 'kelas.dosen_dua', '=', 'dosen_dua.id')
            ->leftJoin('users as user_dosen_dua', 'dosen_dua.user_id', '=', 'user_dosen_dua.id')
            ->leftJoin('dosens as dosen_tiga', 'kelas.dosen_tiga', '=', 'dosen_tiga.id')
            ->leftJoin('users as user_dosen_tiga', 'dosen_tiga.user_id', '=', 'user_dosen_tiga.id')
            ->leftJoin('dosens as dosen_empat', 'kelas.dosen_empat', '=', 'dosen_empat.id')
            ->leftJoin('users as user_dosen_empat', 'dosen_empat.user_id', '=', 'user_dosen_empat.id')
            ->leftJoin('mhs_kelas', 'kelas.id', '=', 'mhs_kelas.kelas_id')
            ->leftJoin('mahasiswas', 'mhs_kelas.nim', '=', 'mahasiswas.nim')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->select('kelas.*','matkuls.nama_mk','user_dosen_satu.name as nama_dosen_satu',
                    'user_dosen_dua.name as nama_dosen_dua','user_dosen_tiga.name as nama_dosen_tiga',
                    'user_dosen_empat.name as nama_dosen_empat','semesters.nama_smtr',
                    'mahasiswas.nama_mahasiswa as nama_mahasiswa', 'mhs_kelas.nim as nim')

            // ->whereNotNull('mhs_kelas.nim')
            ->where('matkuls.prodi_id', $prodi_id);

            if ($semester_id) {
                $query->where('kelas.semester_id', $semester_id);
            }

            $kelas = $query->get();

    return view('prodiadmin/t_mhskelas', compact('kelas', 'semes', 'semester_id'));
    }

    public function formImportExcel()
    {
        return view('prodiadmin.import_excel');
    }


    public function importMahasiswaKelas(Request $request, $id)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

            $file = $request->file('file_excel');
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);

            foreach ($data[0] as $index => $row) {
                if ($index == 0) continue;

                $nim = $row[0];

                if (!empty($nim)) {
                    DB::table('mhs_kelas')->updateOrInsert([
                        'kelas_id' => $id,
                        'nim' => $nim,
                    ]);
                }
            }
            return redirect('prodiadmin/t_mhskelas');
    }

    public function s_importMahasiswa(Request $request)
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        //Log::info("Masokk");

        $request->validate([
            'file_mhs' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file_mhs');
        $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);

        //Log::info("Data dari Excel: ", $data);

            foreach ($data[0] as $index => $row) {
                if ($index == 0) continue;

                $nim = $row[0];
                $nama_mahasiswa = $row[1];
                $angkatan = $row[2];
                $smt_angkatan = $row[3];
                //$prodi_id = $row[4]; 
                $jenis_kelamin = $row[4];

                //Log::info("NIM: ", $nim);

                if (!empty($nim)) {
                    DB::table('mahasiswas')->updateOrInsert([
                        'nim' => $nim,
                        'nama_mahasiswa' => $nama_mahasiswa,
                        'angkatan' => $angkatan,
                        'smt_angkatan' => $smt_angkatan,
                        'prodi_id' => $prodi_id, // Ambil langsung dari user
                        'jenis_kelamin' => $jenis_kelamin,
                    ]);
                }
            }
            return redirect('prodiadmin/a_mahasiswa');
    }


    public function hapusMahasiswa($encryptedId)
    {
        try {
            // Dekripsi ID mahasiswa
            $id = Crypt::decryptString($encryptedId);

            // Cek apakah mahasiswa ada di database
            $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();

            if ($mahasiswa) {
                DB::table('mahasiswas')->where('id', $id)->delete();
                return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendekripsi data!');
        }
    }

    public function editMahasiswa($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $mahasiswa = DB::table('mahasiswas')->where('id', $id)->first();

            if (!$mahasiswa) {
                return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan!');
            }

            return view('prodiadmin.editMahasiswa', compact('mahasiswa', 'encryptedId'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendekripsi data!');
        }
    }

    public function updateMahasiswa(Request $request)
    {
        try {
            $id = Crypt::decryptString($request->encryptedId);

            DB::table('mahasiswas')
                ->where('id', $id)
                ->update([
                    'nim' => $request->nim,
                    'nama_mahasiswa' => $request->nama_mahasiswa,
                    'angkatan' => $request->angkatan,
                    'smt_angkatan' => $request->smt_angkatan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]);

            return redirect('prodiadmin/a_mahasiswa')->with('success', 'Mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data!');
        }
    }

    public function downloadTemplateMahasiswa()
    {
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header kolom di Excel
        $sheet->setCellValue('A1', 'NIM');
        $sheet->setCellValue('B1', 'Nama Mahasiswa');
        $sheet->setCellValue('C1', 'Angkatan');
        $sheet->setCellValue('D1', 'Semester Angkatan');
        $sheet->setCellValue('E1', 'Jenis Kelamin (L/P)');

        // Contoh data sebagai referensi
        $sheet->setCellValue('A2', '7780250010');
        $sheet->setCellValue('B2', 'Coba 1');
        $sheet->setCellValue('C2', '2025');
        $sheet->setCellValue('D2', 'Ganjil');
        $sheet->setCellValue('E2', 'L');

        $sheet->setCellValue('A3', '7780250011');
        $sheet->setCellValue('B3', 'Coba 2');
        $sheet->setCellValue('C3', '2026');
        $sheet->setCellValue('D3', 'Ganjil');
        $sheet->setCellValue('E3', 'P');

        // Simpan file ke memori
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Import_Mahasiswa.xlsx';
        $filePath = storage_path($fileName);
        $writer->save($filePath);

        // Kirim file sebagai response untuk di-download
        return Response::download($filePath)->deleteFileAfterSend(true);
    }



    //CPL
    public function t_cpl(Request $request)
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        try {
            $kurikulum_id = $request->kurikulum ? decrypt($request->kurikulum) : null;
            $cplData = Kurikulum::where('id', $kurikulum_id)->get();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }


        $kurikulums = DB::table('kurikulums')->where('prodi_id', $prodi_id)->get();

        $cp = DB::table('cpls')
            ->leftJoin('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->leftJoin('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
            ->where('admprodis.prodi_id', $prodi_id)
            ->when($kurikulum_id, function ($query, $kurikulum_id) {
                return $query->where('cpls.kurikulum_id', $kurikulum_id);
            })
            ->select('cpls.*', 'prodis.kopro', 'kurikulums.nama_kuri')
            ->get();

        return view('prodiadmin/t_cpl', compact('cp', 'kurikulums'));
    }

    public function a_cpl()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $prod = DB::table('admprodis')
            ->join('prodis', 'prodis.id', '=', 'admprodis.prodi_id')
            ->join('kurikulums', 'kurikulums.prodi_id', '=', 'admprodis.prodi_id')
            ->join('users', 'users.id', '=', 'admprodis.user_id')
            ->where('admprodis.prodi_id', $prodi_id)
            ->select('admprodis.prodi_id', 'prodis.nama_prodi', 'kurikulums.nama_kuri', 'kurikulums.id as kurikulum_id')
            ->get();

        $maxKodeCplPerKurikulum = DB::table('cpls')
            ->select('kurikulum_id', DB::raw('MAX(CAST(SUBSTRING(kode_cpl, 5) AS UNSIGNED)) as max_kode_cpl'))
            ->groupBy('kurikulum_id')
            ->pluck('max_kode_cpl', 'kurikulum_id');

        return view ('prodiadmin/a_cpl', compact('prod', 'maxKodeCplPerKurikulum'));
    }

    public function s_cpl(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'kurikulum_id' => 'required',
            'kode_cpl' => 'required|array',
            'deskrip_cpl' => 'required|array',
            'prodi_id' => 'required',
        ]);

        $kurikulum_id = $request->kurikulum_id;
        $kode_cpl = $request->kode_cpl;
        $deskrip_cpl = $request->deskrip_cpl;
        $prodi_id = $request->prodi_id;

        $totalKodeCPL = count($kode_cpl);

        for ($i = 0; $i < $totalKodeCPL; $i++) {
            $cpl = new Cpl();
            $cpl->kurikulum_id = $kurikulum_id;
            $cpl->kode_cpl = $kode_cpl[$i];
            $cpl->deskrip_cpl = $deskrip_cpl[$i];
            $cpl->prodi_id = $prodi_id;
            $cpl->save();
        }

         return redirect('prodiadmin/t_cpl');
    }

    public function e_cpl($id)
    {

        $kur = DB::table('kurikulums')
        ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'kurikulums.prodi_id')
        ->leftJoin('users', 'users.id', '=', 'admprodis.user_id')
        ->where('users.email', '=', Auth::guard('web')->user()->email)
        ->select('kurikulums.*')
        ->latest()->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Cpl::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('prodiadmin.e_cpl', compact('objek', 'kur'));
    }

    public function u_cpl(Request $request, $id)
    {
        $objek = Cpl::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('prodiadmin/t_cpl');
    }

    public function h_cpl($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Cpl::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_cpl');
    }


    //SUB CPL
    public function t_subcpl(Request $request)
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $kurikulum_id = null;
        try {
            $kurikulum_id = $request->kurikulum ? decrypt($request->kurikulum) : null;
            $cplData = Kurikulum::where('id', $kurikulum_id)->get();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        $kurikulums = DB::table('kurikulums')->where('prodi_id', $prodi_id)->get();

        $scp = DB::table('subcpls')
        ->leftjoin('cpls','cpls.id', '=', 'subcpls.cpl_id')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->where('admprodis.prodi_id', $prodi_id)
        ->select('subcpls.*', 'kurikulums.nama_kuri', 'cpls.kode_cpl')
        ->get();

        return view('prodiadmin/t_subcpl', compact('scp', 'kurikulums'));
    }


    //backup kode benar
    // public function a_subcpl()
    // {
    // $user_id = Auth::user()->id;
    //     $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
    //     $prodi_id = $adminProdi->prodi_id;

    //     $kurikulums = DB::table('kurikulums')
    //         ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
    //         ->where('prodis.id', $prodi_id)
    //         ->select('kurikulums.*')
    //         ->get();

    //     $cpel = DB::table('cpls')
    //         ->join('prodis', 'prodis.id', '=', 'cpls.prodi_id')
    //         ->join('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
    //         ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
    //         ->where('admprodis.prodi_id', $prodi_id)
    //         ->select('cpls.*', 'cpls.id as cpl_id')
    //         ->get();

    //         $existingSubCPLs = DB::table('subcpls')
    //         ->join('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
    //         ->select('subcpls.kode_subcpl', 'cpls.kode_cpl')
    //         ->get()
    //         ->groupBy('kode_cpl')
    //         ->map(function ($group) {
    //             return [
    //                 'kode_cpl' => $group->first()->kode_cpl,
    //                 'max_number' => $group->max(function ($item) {
    //                     return (int) explode('.', $item->kode_subcpl)[1];
    //                 })
    //             ];
    //         });

    //         // dd($existingSubCPLs);

    // return view('prodiadmin/a_subcpl', compact('kurikulums', 'cpel', 'existingSubCPLs'));
    // }

    public function a_subcpl()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        // Ambil kurikulum
        $kurikulums = DB::table('kurikulums')
            ->join('prodis', 'prodis.id', '=', 'kurikulums.prodi_id')
            ->where('prodis.id', $prodi_id)
            ->select('kurikulums.*')
            ->get();

        // Ambil CPL
        $cpel = DB::table('cpls')
            ->join('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->join('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
            ->where('admprodis.prodi_id', $prodi_id)
            ->select('cpls.*', 'cpls.id as cpl_id')
            ->get();

        // Ambil SubCPL yang sudah ada berdasarkan prodi_id, lalu group berdasarkan kode_cpl untuk mencari angka terbesar
        $existingSubCPLs = DB::table('subcpls')
            ->join('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
            ->where('cpls.prodi_id', $prodi_id)  // Filter hanya berdasarkan prodi_id yang sesuai
            ->select('subcpls.kode_subcpl', 'cpls.kode_cpl')
            ->get()
            ->groupBy('kode_cpl')
            ->map(function ($group) {
                return $group->max(function ($item) {
                    // Ambil angka setelah tanda titik
                    $index = explode('.', $item->kode_subcpl)[1] ?? 0;
                    return (int) $index;
                });
            });

        return view('prodiadmin/a_subcpl', compact('kurikulums', 'cpel', 'existingSubCPLs', 'prodi_id'));
    }


    public function s_subcpl(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'cpl_id' => 'required',
	        'kurikulum_id' => 'required',
            'kode_subcpl' => 'required|array',
            'desk_subcpl' => 'required|array',
            'bobot' => 'required|array',
            'trgt_nilai' => 'required|array',
        ]);

        $totalKodeSubCPL = count($request->kode_subcpl);

        if ($totalKodeSubCPL !== count($request->desk_subcpl) ||
            $totalKodeSubCPL !== count($request->bobot) ||
            $totalKodeSubCPL !== count($request->trgt_nilai)) {
            return redirect()->back()->withErrors(['message' => 'Jumlah input Sub CPL tidak konsisten']);
        }

        $cpl_id = $request->cpl_id;
        $kurikulum_id = $request->kurikulum_id;
        $kode_subcpl = $request->kode_subcpl;
        $desk_subcpl = $request->desk_subcpl;
        $bobot = $request->bobot;
        $trgt_nilai = $request->trgt_nilai;

        for ($i = 0; $i < $totalKodeSubCPL; $i++) {
            $scpl = new SubCpl();
            $scpl->cpl_id = $cpl_id;
	        $scpl->kurikulum_id = $kurikulum_id;
            $scpl->kode_subcpl = $kode_subcpl[$i];
            $scpl->desk_subcpl = $desk_subcpl[$i];
            $scpl->bobot = $bobot[$i];
            $scpl->trgt_nilai = $trgt_nilai[$i];
            $scpl->save();
        }

        return redirect('prodiadmin/t_subcpl');
    }


    public function e_subcpl($id)
    {

        $cpel = DB::table('cpls')
            ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
            ->join('users', 'users.id', '=', 'admprodis.user_id')
            ->where('users.email', '=', Auth::guard('web')->user()->email)
            ->select('cpls.*')
            ->get();

        $decryptID = Crypt::decryptString($id);

        $objek = SubCpl::findOrFail($decryptID);

        return view('prodiadmin.e_subcpl', compact('objek', 'cpel'));
    }


    public function u_subcpl(Request $request, $id)
    {
        $validated = $request->validate([
            'cpl_id' => 'required|string',
            'kode_subcpl' => 'required|string',
            'desk_subcpl' => 'required|string',
            'bobot' => 'required|string',
            'trgt_nilai' => 'required|string',
        ]);

        $subCpl = SubCpl::findOrFail($id);
        $subCpl->update($validated);

        return redirect('/prodiadmin/t_subcpl');
    }

    public function h_subcpl($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = SubCpl::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_subcpl');
    }


    //SUB MK Sub CPL
    public function t_mksubcpl()
    {

        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'matkuls.prodi_id')
        ->where('admprodis.prodi_id', $prodi_id)
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->get();

        $mkscp = DB::table('mkscpls')
                ->join('subcpls','subcpls.id', '=', 'mkscpls.subcpl_id')
                ->join('matkuls','matkuls.id', '=', 'mkscpls.matkul_id')
                ->join('cpls','cpls.id', '=', 'subcpls.cpl_id')
                ->join('prodis','prodis.id', '=', 'cpls.prodi_id')
                ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
                ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
                ->where('admprodis.prodi_id', $prodi_id)
                ->select('mkscpls.id','matkuls.kode_mk', 'matkuls.nama_mk', 'cpls.kode_cpl', 'subcpls.kode_subcpl')
                ->groupBy('mkscpls.id','matkuls.kode_mk', 'matkuls.nama_mk', 'cpls.kode_cpl', 'subcpls.kode_subcpl')
                ->get();

        return view('prodiadmin/t_mksubcpl', compact('mkscp', 'matk'));
    }


    public function a_mksubcpl($matkul_id)
    {
        // $cpel = DB::table('cpls')
        //     ->leftjoin('prodis', 'prodis.id', '=', 'cpls.prodi_id')
        //     ->leftjoin('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
        //     ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        //     ->select('cpls.*', 'prodis.kopro', 'kurikulums.nama_kuri')
        //     ->get();

        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

	$scp = DB::table('subcpls')
            ->leftjoin('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
            ->leftjoin('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->leftjoin('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
	    ->where('admprodis.prodi_id', $prodi_id)
            ->select('subcpls.*', 'kurikulums.nama_kuri', 'cpls.id', 'cpls.kode_cpl')
            ->get();


        return view('prodiadmin.a_mksubcpl', compact('scp', 'matkul_id'));
    }

    public function s_mksubcpl(Request $request)
    {
        $decryptedMatkulId = Crypt::decryptString($request->matkul_id);
        $request->validate([
            'matkul_id' => 'required',
            'cpl_id.*' => 'required',
            'subcpl_checkbox.*.*' => 'required',
            ]);

        foreach ($request->cpl_id as $cplId) {
            $subcplIds = $request->subcpl_checkbox[$cplId];

        foreach ($subcplIds as $subcplId) {
            $mkscpl = new MkSubcpl();
            $mkscpl->matkul_id = $decryptedMatkulId;
            $mkscpl->cpl_id = $cplId;
            $mkscpl->subcpl_id = $subcplId;
            $mkscpl->save();
            }
        }

        return redirect('prodiadmin/t_mksubcpl');
    }


    public function getSubcplsByCpl(Request $request)
    {
        $cplId = $request->input('cpl_id');
        $subcpls = Subcpl::where('cpl_id', $cplId)->get();

        return response()->json(['subcpls' => $subcpls]);
    }


    public function h_mksubcpl($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = MkSubCpl::find($decryptID);
        $data->delete();
        return redirect('/prodiadmin/t_mksubcpl');
    }



    public function t_pengesah()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodi_id = $adminProdi->prodi_id;

        $kajur = DB::table('kajurs')
            ->join('dosens','dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'dosens.prodi_id')
            ->join('users','users.id', '=', 'dosens.user_id')
            ->where('admprodis.prodi_id', $prodi_id)
            ->select('kajurs.*', 'prodis.nama_prodi', 'users.name as nama_dosen', 'dosens.nip', 'dosens.nidn')
            ->orderBy('jabatan', 'asc')->get();

        return view ('prodiadmin.t_pengesah', compact('kajur'));

    }

    public function a_pengesah()
    {
        $userEmail = Auth::guard('web')->user()->email;

        $prodiId = DB::table('admprodis')
            ->join('users', 'admprodis.user_id', '=', 'users.id')
            ->where('users.email', '=', $userEmail)
            ->value('prodi_id');

        $dosens = DB::table('dosens')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->select('dosens.id as dosen_id', 'dosens.nidn', 'dosens.nip', 'users.name as nama_dosen')
            ->where('dosens.prodi_id', $prodiId)
            ->get();

        return view('prodiadmin/a_pengesah', compact('dosens'));
    }

    public function getDosenData($dosen_id)
    {
        $dosen = DB::table('dosens')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->select('dosens.id as dosen_id', 'dosens.nidn', 'dosens.nip', 'users.name as nama_dosen')
            ->where('dosens.id', $dosen_id)
            ->first();

        return response()->json($dosen);
    }

    public function s_pengesah(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required',
            'jabatan' => 'required',
        ],
        [
            'dosen_id.required' => 'Dosen harus dipilih',
            'jabatan.required' => 'Jabatan harus diisi',
        ]);

        $pengesah = new Kajur;
        $pengesah->dosen_id = $request->dosen_id;
        $pengesah->jabatan = $request->jabatan;
        $pengesah->save();

        return redirect('prodiadmin/t_pengesah');
    }

public function e_pengesah($id)
    {
        $decryptID = Crypt::decryptString($id);
        $pengesah = Kajur::findOrFail($decryptID);
        $userEmail = Auth::guard('web')->user()->email;

        $prodiId = DB::table('admprodis')
            ->join('users', 'admprodis.user_id', '=', 'users.id')
            ->where('users.email', '=', $userEmail)
            ->value('prodi_id');

        $dosens = DB::table('dosens')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->select('dosens.id as dosen_id', 'dosens.nidn', 'dosens.nip', 'users.name as nama_dosen')
            ->where('dosens.prodi_id', $prodiId)
            ->get();

        return view('prodiadmin/e_pengesah', compact('dosens', 'pengesah'));
    }

    public function u_pengesah(Request $request, $id)
    {
        $objek = Kajur::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('prodiadmin/t_pengesah');
    }


    public function a_mahasiswa()
    {
        $user_id = Auth::user()->id;
        $adminProdi = DB::table('admprodis')->where('user_id', $user_id)->first();
        $prodiId = $adminProdi->prodi_id;

        $mahasiswas = DB::table('mahasiswas')
            ->where('prodi_id', $prodiId)
            ->get();

        return view('prodiadmin.a_mahasiswa', compact('prodiId', 'mahasiswas'));
    }

    public function t_peserta_kelas($kelas_id)
    {
        $kelas_id_ = Crypt::decryptString($kelas_id);
 
        $Datamatkul = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->where('kelas.id', $kelas_id_)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.nama_kelas', 'semesters.keterangan as semester')
            ->first();

        $mahasiswas_kelas = DB::table('mhs_kelas')
            ->join('mahasiswas', 'mahasiswas.nim', '=', 'mhs_kelas.nim')
            ->where('kelas_id', $kelas_id_)
            ->get();

        //echo "$kelas_id";

        return view('prodiadmin.t_peserta_kelas', compact('Datamatkul', 'mahasiswas_kelas'));
    }

    public function s_mahasiswa(Request $request)
    {
        $prodiId = auth()->user()->Admprodi->prodi_id;

        $request->validate([
            'nim' => 'required|unique:mahasiswas,nim',
            'nama_mahasiswa' => 'required|string|max:255',
            'angkatan' => 'required|integer|min:2000|max:' . date('Y'),
            'smt_angkatan' => 'required|string',
        ]);

        Mahasiswa::create([
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'angkatan' => $request->angkatan,
            'smt_angkatan' => $request->smt_angkatan,
            'prodi_id' => $prodiId, // Ambil langsung dari user
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil ditambahkan.');

    }

    public function importMahasiswa(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Format file dan ukuran maksimum 2MB
        ]);

        // Ambil prodi_id dari user yang sedang login
        $prodiId = auth()->user()->Admprodi->prodi_id;

        try {
            // Debug: Pastikan file diterima
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'File tidak ditemukan.');
            }

            // Debug: Baca isi file Excel sebelum import
            $data = Excel::toArray([], $request->file('file'));
            // dd($data);
            if (empty($data) || count($data[0]) === 0) {
                return redirect()->back()->with('error', 'File Excel kosong atau tidak terbaca.');
            }

            // Debug: Log isi file Excel
            Log::info('Data Excel yang akan diimport:', $data);

            // Import data menggunakan MahasiswaImport
            Excel::import(new MahasiswaImport($prodiId), $request->file('file'));

            return redirect()->back()->with('success', 'Data Mahasiswa berhasil diimport!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangani validasi error
            $failures = $e->failures();
            $errorMessage = 'Gagal mengimport data. Kesalahan pada baris: ';
            foreach ($failures as $failure) {
                $errorMessage .= 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '. ';
            }
            Log::error($errorMessage);
            return redirect()->back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            // Tangani kesalahan lainnya
            Log::error('Kesalahan saat import: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
        }
    }


}
