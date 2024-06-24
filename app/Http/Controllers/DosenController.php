<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Semester;
use App\Models\Dosen;
use App\Models\Kompnilai;
use App\Models\Mkcpl;
use App\Models\Mkcpmk;
use App\Models\SubCpl;
use App\Models\MkSubCpl;
use App\Models\Mksubcpmk;
use App\Models\Rubnilai;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Inpnilai;
use App\Models\Matkul;


class DosenController extends Controller
{
    public function index() {
        return view('dosen.index');
    }

    public function t_cpl()
    {
        $prodi_id = Auth::user()->prodi_id;

        $cp = DB::table('cpls')
        ->join('prodis', 'prodis.id', '=', 'cpls.prodi_id')
        ->join('dosens', 'dosens.prodi_id', '=', 'prodis.id')
        ->join('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
        ->where('dosens.id', Auth::id())
        ->select('cpls.*', 'prodis.kopro as kopro', 'kurikulums.nama_kuri')
        ->get();

        return view('dosen/t_cpl', compact('cp'));
    }

    public function t_subcpl()
    {
        $scp = DB::table('subcpls')
        ->leftjoin('cpls','cpls.id', '=', 'subcpls.cpl_id')
        ->join('dosens', 'dosens.prodi_id', '=', 'cpls.prodi_id')
        ->leftjoin('prodis','prodis.id', '=', 'dosens.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->select('subcpls.*', 'kurikulums.nama_kuri', 'cpls.kode_cpl')
        ->get();

        return view('dosen/t_subcpl', compact('scp'));
    }


    // MK DIAMPU
    public function t_ampu(Request $request)
    {
        $smtr = Semester::all();
        $encrypted_semester_id = $request->input('semester_id');

        if ($encrypted_semester_id) {
            $semester_id = decrypt($encrypted_semester_id);

        $user_email = Auth::user()->email;

        $mata_kuliah_satu = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('kelas.semester_id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_dua = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('kelas.semester_id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_tiga = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('kelas.semester_id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_empat = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('kelas.semester_id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah = $mata_kuliah_satu->union($mata_kuliah_dua)
            ->union($mata_kuliah_tiga)
            ->union($mata_kuliah_empat)
            ->get();

            // dd($mata_kuliah);

            return view('dosen/t_ampu', compact('mata_kuliah', 'smtr', 'encrypted_semester_id', 'encrypted_semester_id'));
        }
        return view('dosen/t_ampu', compact('smtr', 'encrypted_semester_id'));

    }
    // END MK DIAMPU


    // TAMPILAN CPMK
    public function t_cpmk()
    {
        $prodi_id = Auth::user()->prodi_id;

        $cpmka = DB::table('cpmks')
        ->join('kelas', 'kelas.id', '=', 'cpmks.kelas_id')
        ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
        ->join('dosens', 'dosens.prodi_id', '=', 'matkuls.prodi_id')
        ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
        ->where('dosens.id', Auth::id())
        ->select('cpmks.*', 'prodis.kopro as kopro', 'matkuls.nama_mk', 'matkuls.kode_mk', 'kelas.nama_kelas')
        ->latest()->get();

        return view('dosen/t_cpmk', compact('cpmka'));
    }
    // END TAMPILAN CPMK


    // MKCPMK
    public function t_mkcpmk(Request $request)
    {
    $smtr = Semester::all();
    $encrypted_semester_id = $request->input('semester_id');
    $mata_kuliah = [];
    $mkcp = [];

    if ($encrypted_semester_id) {
        $semester_id = decrypt($encrypted_semester_id);
        $user_email = Auth::user()->email;

        $mata_kuliah_satu = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_dua = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_tiga = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_empat = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah = $mata_kuliah_satu->union($mata_kuliah_dua)
            ->union($mata_kuliah_tiga)
            ->union($mata_kuliah_empat)
            ->get();

        $mkcp = DB::table('mkcpmks')
            ->join('mkscpls', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk','mkcpmks.id as mkcpmk_id')
            ->distinct()
            ->get();
    }

    return view('dosen/t_mkcpmk', compact('mata_kuliah', 'smtr', 'encrypted_semester_id', 'mkcp'));
    }



    public function a_mkcpmk($matkulId)
    {
        // $matkulId = Crypt::decryptString($matkul_id);
        $matkulId = request()->route()->parameter(('matkul_id'));
        $matkulId = Crypt::decryptString($matkulId);

        $mcpmk = MkSubCpl::where('mkscpls.matkul_id', $matkulId)
            ->join('subcpls', 'subcpls.id', '=', 'mkscpls.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->select('subcpls.id as subcpl_id', 'subcpls.kode_subcpl', 'subcpls.desk_subcpl', 'matkuls.kode_mk')
            ->distinct()
            ->get();

            $existingCpmks = Mkcpmk::select('subcpl_id', 'kode_cpmk')->get();
            // dd($mcpmk);

        return view('dosen.a_mkcpmk', compact('mcpmk', 'matkulId', 'existingCpmks'));
    }

    public function getSubCplDetail($subcplId)
    {
        $subcpl = MkSubCpl::join('subcpls', 'subcpls.id', '=', 'mkscpls.subcpl_id')
                      ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
                      ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
                      ->where('subcpls.id', $subcplId)
                      ->select('subcpls.kode_subcpl', 'subcpls.desk_subcpl', 'matkuls.kode_mk')
                      ->first();

        if ($subcpl) {
            return response()->json($subcpl);
        } else {
            return response()->json(['error' => 'Sub CPL not found'], 404);
        }
    }

    public function s_mkcpmk(Request $request, $matkulId)
    {
        // $kelas_id = Crypt::decryptString($matkul_id);
        // dd($request->all());
        // dd($matkulId);

        $request->validate([
            'subcpl_id' => 'required|array',
            'subcpl_id.*' => 'required|integer',
            'kode_cpmk' => 'required|array',
            'kode_cpmk.*' => 'required|string',
            'desk_cpmk' => 'required|array',
            'desk_cpmk.*' => 'required|string',
            // 'matkul_id' => 'required|integer'
        ]);

        // $matkulId = $request->matkul_id;

        foreach ($request->subcpl_id as $index => $subcpl_id) {
            Mkcpmk::create([
                'subcpl_id' => $subcpl_id,
                'matkul_id' => $matkulId,
                'kode_cpmk' => $request->kode_cpmk[$index],
                'desk_cpmk' => $request->desk_cpmk[$index],
            ]);
        }

        // dd($request->all());

        return redirect()->route('dosen.t_mkcpmk');
    }
    // END MKCPMK


    // MKSUBCPMK
    public function t_mkscpmk(Request $request)
    {
    $smtr = Semester::all();
    $encrypted_semester_id = $request->input('semester_id');

    $mkscp = [];
    $mksucp = [];

    if ($encrypted_semester_id) {
        $semester_id = decrypt($encrypted_semester_id);
        $user_email = Auth::user()->email;

        $mkscp = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.matkul_id')
            ->distinct()
            ->get();

        $mksucp = DB::table('mksubcpmks')
            ->join('mkcpmks', 'mksubcpmks.mkcpmk_id', '=', 'mkcpmks.id')
            // ->join('mkcpmks', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mksubcpmks.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'mkcpmks.kode_cpmk',
                    'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk', 'mksubcpmks.id as mkscpmk_id')
            ->distinct()
            ->get();
    }

    return view('dosen/t_mkscpmk', compact('mkscp', 'mksucp', 'smtr', 'encrypted_semester_id'));
    }



    public function a_mkscpmk($matkul_id)
    {
        $matkul_id = Crypt::decryptString($matkul_id);
        $mscpmk = Mkcpmk::where('matkul_id', $matkul_id)->get();
        $existingSubCPMKs = DB::table('mksubcpmks')
                ->join('mkcpmks', 'mksubcpmks.mkcpmk_id', '=', 'mkcpmks.id')
                ->where('mkcpmks.matkul_id', $matkul_id)
                ->select('mksubcpmks.kode_scpmk', 'mkcpmks.kode_cpmk')
                ->get();


            // dd($mscpmk);

        return view('dosen.a_mkscpmk', compact('mscpmk', 'matkul_id', 'existingSubCPMKs'));
    }


    public function getDeskCpmk($matkul_id, $kode_cpmk)
    {
        $mkcp = Mkcpmk::where('matkul_id', $matkul_id)
                       ->where('kode_cpmk', $kode_cpmk)
                       ->first();
        return response()->json($mkcp);
    }

    public function getExistingSubcpmks($matkul_id, $kode_cpmk)
    {
        $subcpmks = Mkcpmk::where('matkul_id', $matkul_id)
                            ->where('kode_cpmk', 'like', $kode_cpmk . '.%')
                            ->get();
        return response()->json($subcpmks);
    }

    public function s_mkscpmk(Request $request, $matkulId)
    {

        $validator = Validator::make($request->all(), [
            'kode_cpmk.*' => 'required',
            'desk_scpmk.*' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        foreach ($request->kode_cpmk as $index => $kode_cpmk) {
            $desk_scpmk = $request->desk_scpmk[$index];
            $kode_subcpmk = $request->kode_subcpmk[$index];

            $mkcpmk = DB::table('mkcpmks')->where('kode_cpmk', $kode_cpmk)->first();
            if ($mkcpmk) {
                DB::table('mksubcpmks')->insert([
                    'matkul_id' => $matkulId,
                    'mkcpmk_id' => $mkcpmk->id,
                    'kode_scpmk' => $kode_subcpmk,
                    'desk_scpmk' => $desk_scpmk,
                ]);
            } else {
                return redirect()->back()->withErrors(['msg' => 'Invalid Kode CPMK.'])->withInput();
            }
        }
        return redirect()->route('dosen.t_mkscpmk');
    }
    // END MKSUBCPMK


    //TARGET CPMK
    public function t_tarcpmk()
    {
        $user_email = Auth::user()->email;

        $mata_kuliah_satu = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_dua = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_tiga = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_empat = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah = $mata_kuliah_satu->union($mata_kuliah_dua)
            ->union($mata_kuliah_tiga)
            ->union($mata_kuliah_empat)
            ->get();


        $tarcpmk = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'mkcpmks.kode_cpmk',
                    'kompnilais.jen_penilaian', 'rubnilais.id as rubni_id')
            ->distinct()
            ->get();

        return view('dosen.t_tarcpmk', compact('mata_kuliah', 'tarcpmk'));
    }

    public function a_tarcpmk($matkul_id)
    {
        $user_email = Auth::user()->email;
        $matkul_id = Crypt::decryptString($matkul_id);
        $jenilai = Kompnilai::all();

        $mkcp = Mkcpmk::where('matkul_id', $matkul_id)->get();

        return view('dosen.a_tarcpmk', compact('mkcp', 'matkul_id', 'jenilai'));
    }

    public function getCpmkDescription($kodeCpmk)
    {
        $cpmk = Mkcpmk::where('kode_cpmk', $kodeCpmk)->first();

        if ($cpmk) {
            return response()->json(['desk_cpmk' => $cpmk->desk_cpmk]);
        } else {
            return response()->json(['desk_cpmk' => '']);
        }
    }

    public function s_tarcpmk(Request $request, $matkul_id)
    {
        $matkul_id = Crypt::decryptString($matkul_id);

        $request->validate([
            'kode_cpmk.*' => 'required|exists:mkcpmks,kode_cpmk',
            'kompnilai_id.*' => 'required|exists:kompnilais,id',
        ]);

        foreach ($request->kode_cpmk as $index => $kode_cpmk) {
            $mkcpmk = Mkcpmk::where('kode_cpmk', $kode_cpmk)->first();

            Rubnilai::create([
                'matkul_id' => $matkul_id,
                'mkcpmk_id' => $mkcpmk->id,
                'kompnilai_id' => $request->kompnilai_id[$index],
            ]);
        }

        return redirect('/dosen/t_tarcpmk');
    }
    // END TARGET CPMK


    // INPUT NILAI
    public function t_inpnilai()
    {
        $user_email = Auth::user()->email;

        $inilai_satu = DB::table('rubnilais')
        ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
        ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
        ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
        ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
        ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
        ->join('users', 'dosens.user_id', '=', 'users.id')
        ->where('users.email', $user_email)
        ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                    'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                    'rubnilais.id as rubni_id', 'kelas.nama_kelas','kelas.id as kelas_id' );


            $inilai_dua = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                    'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                    'rubnilais.id as rubni_id', 'kelas.nama_kelas','kelas.id as kelas_id');


            $inilai_tiga = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                    'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                    'rubnilais.id as rubni_id', 'kelas.nama_kelas','kelas.id as kelas_id');


            $inilai_empat = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                    'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                    'rubnilais.id as rubni_id', 'kelas.nama_kelas','kelas.id as kelas_id');


            $inilai = $inilai_satu->union($inilai_dua)
            ->union($inilai_tiga)
            ->union($inilai_empat)
            ->distinct()
            ->get();

            // dd($inilai);

        return view('dosen.t_inpnilai', compact('inilai'));

    }


    // public function a_inpnilai($kelas_id)
    // {
    //     $kelas_id = Crypt::decryptString($kelas_id);
    //     $kelas = Kelas::with('semester')->find($kelas_id);

    //     $mahasiswas = DB::table('mahasiswas')
    //         ->join('kelas', 'mahasiswas.id', '=', 'kelas.mahasiswa_id')
    //         ->where('kelas.matkul_id', $kelas->matkul_id)
    //         ->where('kelas.nama_kelas', $kelas->nama_kelas)
    //         ->select('mahasiswas.id', 'mahasiswas.nama_mahasiswa', 'mahasiswas.nim')
    //         ->distinct()
    //         ->get();

    //     $inpnil = DB::table('rubnilais')
    //         ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
    //         ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
    //         ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
    //         ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kompnilais.jen_penilaian',
    //                 'rubnilais.id as rubnilai_id', 'kompnilais.id as kompnilai_id')
    //         ->where('matkuls.id', $kelas->matkul_id)
    //         ->distinct()
    //         ->get();

    //     return view('dosen.a_inpnilai', compact('inpnil', 'kelas_id', 'kelas', 'mahasiswas'));
    // }

    public function a_inpnilai($kelas_id)
    {
    $kelas_id = Crypt::decryptString($kelas_id);
    $kelas = Kelas::with('semester')->find($kelas_id);

    $mahasiswas = DB::table('mahasiswas')
        ->join('kelas', 'mahasiswas.id', '=', 'kelas.mahasiswa_id')
        ->where('kelas.matkul_id', $kelas->matkul_id)
        ->where('kelas.nama_kelas', $kelas->nama_kelas)
        ->select('mahasiswas.id', 'mahasiswas.nama_mahasiswa', 'mahasiswas.nim')
        ->distinct()
        ->get();

    $inpnil = DB::table('rubnilais')
        ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
        ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
        ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
        ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kompnilais.jen_penilaian',
                'rubnilais.id as rubnilai_id', 'kompnilais.id as kompnilai_id',
                'mkcpmks.id as cpmk_id', 'mkcpmks.kode_cpmk')
        ->where('matkuls.id', $kelas->matkul_id)
        ->distinct()
        ->get();

    return view('dosen.a_inpnilai', compact('inpnil', 'kelas_id', 'kelas', 'mahasiswas'));
    }


    public function s_inpnilai(Request $request)
    {
        $data = $request->input('nilai');

        foreach ($data as $mahasiswa_id => $nilai_per_mahasiswa) {
            $total_nilai = 0;
            $jumlah_nilai = count($nilai_per_mahasiswa);

            foreach ($nilai_per_mahasiswa as $kompnilai_id => $nilai) {
                Inpnilai::create([
                    'mahasiswa_id' => $mahasiswa_id,
                    'kompnilai_id' => $kompnilai_id,
                    'nilai' => $nilai
                ]);

                $total_nilai += $nilai;
            }

            $nilai_rata = $jumlah_nilai > 0 ? $total_nilai / $jumlah_nilai : 0;
            Inpnilai::where('mahasiswa_id', $mahasiswa_id)->update(['nilai_rata' => $nilai_rata]);
        }

        return redirect('/dosen/t_nilai');
    }


    public function t_nilai()
    {
    $nilai = Inpnilai::join('mahasiswas', 'mahasiswas.id', '=', 'inpnilais.mahasiswa_id')
                ->join('kompnilais', 'kompnilais.id', '=', 'inpnilais.kompnilai_id')
                ->select('mahasiswas.nama_mahasiswa', 'mahasiswas.nim', 'inpnilais.*', 'kompnilais.jen_penilaian')
                ->get();

        $inpnil = Rubnilai::join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
                ->join('mkcpmks as mkcpmks1', 'mkcpmks1.id', '=', 'rubnilais.mkcpmk_id')
                ->join('matkuls', 'matkuls.id', '=', 'mkcpmks1.matkul_id')
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kompnilais.jen_penilaian',
                        'rubnilais.mkcpmk_id as cpmk_id', 'mkcpmks1.kode_cpmk')
                ->distinct()
                ->get();


    $matkul = Matkul::all();

    $grouped_nilai = $nilai->groupBy('mahasiswa_id')->map(function ($row) {
        $total_nilai = 0;
        $jumlah_nilai = 0;

        foreach ($row as $item) {
            $total_nilai += $item->nilai;
            $jumlah_nilai++;
        }

        $nilai_rata = $jumlah_nilai > 0 ? $total_nilai / $jumlah_nilai : 0;

        return [
            'nama_mahasiswa' => $row->first()->nama_mahasiswa,
            'nim' => $row->first()->nim,
            'nilai' => $row->pluck('nilai', 'jen_penilaian')->toArray(),
            'nilai_rata' => $nilai_rata,
        ];
    });

    $jen_penilaian = $nilai->pluck('jen_penilaian')->unique();

    $uniqueCpmk = $inpnil->pluck('cpmk_id')->unique();

    return view('dosen.t_nilai', compact('nilai', 'grouped_nilai', 'jen_penilaian', 'uniqueCpmk', 'inpnil', 'matkul'));
    }



    // RPS
    public function t_rps()
    {
        $user_email = Auth::user()->email;

        $mkscp = DB::table('mksubcpmks')
            ->join('mkcpmks', 'mkcpmks.matkul_id', '=', 'mksubcpmks.matkul_id')
            ->join('mkscpls', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'mksubcpmks.id as mkscpmk_id')
            ->distinct()
            ->get();

            return view('dosen.t_rps', compact('mkscp'));
    }

    public function a_rps($matkul_id)
    {
        $user_email = Auth::user()->email;
        $mkscp = DB::table('mksubcpmks')
            ->join('mkcpmks', 'mkcpmks.matkul_id', '=', 'mksubcpmks.matkul_id')
            ->join('mkscpls', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            // ->where('users.email', $user_email)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'mksubcpmks.id as mkscpmk_id')
            ->distinct()
            ->get();
    }


}
