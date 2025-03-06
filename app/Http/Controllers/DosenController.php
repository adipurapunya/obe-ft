<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

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
use App\Models\Genkrs;
use App\Models\Speckrs;
use App\Models\Rps;
use App\Exports\TemplateExport;
use App\Imports\NilaiImport;
use Illuminate\Support\Facades\Log;

use Tuupola\Base62;

use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index() {
    $user = auth()->user();
        if ($user) {
            $roleName = $user->role->role_name;
        } else {
            $roleName = 'Unknown';
        }
    return view('dosen.index', compact('roleName'));
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

       $prodi_id = Auth::user()->prodi_id;
       $scp = DB::table('subcpls')
        ->leftjoin('cpls','cpls.id', '=', 'subcpls.cpl_id')
        ->join('dosens', 'dosens.prodi_id', '=', 'cpls.prodi_id')
        ->leftjoin('prodis','prodis.id', '=', 'dosens.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->where('dosens.id', Auth::id())
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

        $mata_kuliah = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('users.email', $user_email)
            ->where('kelas.semester_id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'matkuls.sks_teo', 'matkuls.sks_kuri', 'matkuls.sks_prak', 'matkuls.sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id')
            ->union(
                DB::table('kelas')
                ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
                ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('kelas.semester_id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                        'kelas.nama_kelas', 'matkuls.sks_teo', 'matkuls.sks_kuri', 'matkuls.sks_prak', 'matkuls.sks_lap',
                        'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id')
            )
            ->union(
                DB::table('kelas')
                ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
                ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('kelas.semester_id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                        'kelas.nama_kelas', 'matkuls.sks_teo', 'matkuls.sks_kuri', 'matkuls.sks_prak', 'matkuls.sks_lap',
                        'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id')
            )
            ->union(
                DB::table('kelas')
                ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
                ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('kelas.semester_id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                        'kelas.nama_kelas', 'matkuls.sks_teo', 'matkuls.sks_kuri', 'matkuls.sks_prak', 'matkuls.sks_lap',
                        'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id')
            )
            ->distinct()
            ->get();


        return view('dosen/t_ampu', compact('mata_kuliah', 'smtr', 'encrypted_semester_id'));
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
    //$semester_id = decrypt($encrypted_semester_id);
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
            ->join('kelas', 'kelas.id', '=', 'mkcpmks.kelas_id')
            ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', function($join) {
                $join->on('dosens.id', '=', 'kelas.dosen_satu')
                    ->orOn('dosens.id', '=', 'kelas.dosen_dua')
                    ->orOn('dosens.id', '=', 'kelas.dosen_tiga')
                    ->orOn('dosens.id', '=', 'kelas.dosen_empat');
            })
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'users.id', '=', 'dosens.user_id')
            ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas', 'mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mkcpmks.id as mkcpmk_id')
            ->distinct()
            ->get();       
    }
    //dd($encrypted_semester_id);
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
            ->select('subcpls.id as subcpl_id', 'subcpls.kode_subcpl', 'subcpls.desk_subcpl', 'matkuls.kode_mk', 'kelas.id as kelas_id')
            ->distinct()
            ->get();

        $existingCpmks = Mkcpmk::select('subcpl_id', 'kode_cpmk')->get();

        $kelasId = $mcpmk->pluck('kelas_id')->first();
        //dd($kelasId);

        return view('dosen.a_mkcpmk', compact('mcpmk', 'matkulId', 'existingCpmks', 'kelasId'));
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
        
        Log::info('Request Diterima: ', $request->all());
        
        $request->validate([
            'subcpl_id' => 'required|array',
            'subcpl_id.*' => 'required|integer',
            'kode_cpmk' => 'required|array',
            'kode_cpmk.*' => 'required|string',
            'desk_cpmk' => 'required|array',
            'desk_cpmk.*' => 'required|string',
            'kelas_id' => 'required|array',
            'kelas_id.*' => 'required|integer',
        ]);

        //dd($matkulId);

        $cpmkCount = count($request->kode_cpmk);

        if (count($request->kelas_id) == 1) {
            $kelas_id = array_fill(0, $cpmkCount, $request->kelas_id[0]);
        } else {
            $kelas_id = $request->kelas_id;
        }

        foreach ($request->kode_cpmk as $index => $kode_cpmk) {
            Mkcpmk::create([
                'subcpl_id' => $request->subcpl_id[$index],
                'matkul_id' => $matkulId,
                'kode_cpmk' => $kode_cpmk,
                'desk_cpmk' => $request->desk_cpmk[$index],
                'kelas_id' => $kelas_id[$index],
            ]);
        }

        return redirect()->route('dosen.t_mkcpmk');
    }

	public function e_mkcpmk($id)
    {
        $decryptID = Crypt::decryptString($id);
        $objek = Mkcpmk::findOrFail($decryptID);

        return view('dosen.e_mkcpmk', compact('objek'));
    }

    public function u_mkcpmk(Request $request, $id)
    {
        $objek = Mkcpmk::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('dosen/t_mkcpmk');
    }


    public function h_mkcpmk($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Mkcpmk::find($decryptID);
        $data->delete();
        return back();
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

        /*
        if ($existingSubCPMKs->isEmpty()) {
            return redirect()->back()->with('warning', 'Data CPMK pada kelas ini masih kosong. Silakan tambahkan dulu CPMK nya baru sub CPMK nya.');
        }
        */

        $mkcp = Mkcpmk::where('matkul_id', $matkul_id)
            ->join('matkuls', 'matkuls.id', '=', 'matkul_id')
            ->get();
        
        $nama_mk = $mkcp->first(); 
    
        if ($mkcp->isEmpty()) {
            return redirect()->back()->with('warning', 'CPMK pada kelas ini masih kosong. Silakan tambahkan dulu data CPMK nya.');
        }

        return view('dosen.a_mkscpmk', compact('mscpmk', 'matkul_id', 'existingSubCPMKs','nama_mk'));
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


	public function e_mkscpmk($id)
    {
        $decryptID = Crypt::decryptString($id);
        $objek = Mksubcpmk::findOrFail($decryptID);

        return view('dosen.e_mkscpmk', compact('objek'));
    }

    public function u_mkscpmk(Request $request, $id)
    {
        $objek = Mksubcpmk::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('dosen/t_mkscpmk');
    }


    public function h_mkscpmk($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Mksubcpmk::find($decryptID);
        $data->delete();
        return back();
    }
    // END MKSUBCPMK


    //TARGET CPMK
    public function t_tarcpmk(Request $request)
    {
        $smtr = Semester::all();
        $encrypted_semester_id = $request->input('semester_id');

        $mata_kuliah = [];
        $tarcpmk = [];

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

            /*
                $tarcpmk = DB::table('rubnilais')
                ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
                ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
                ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
                ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
                ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('semesters.id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'mkcpmks.kode_cpmk', 'desk_cpmk',
                        'kompnilais.jen_penilaian', 'rubnilais.id as rubni_id')
                ->distinct()
                ->get();
            */
                $tarcpmk = DB::table('rubnilais')
                ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
                ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
                ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
                ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->leftJoin('dosens as dosen_satu', 'dosen_satu.id', '=', 'kelas.dosen_satu')
                ->leftJoin('dosens as dosen_dua', 'dosen_dua.id', '=', 'kelas.dosen_dua')
                ->leftJoin('dosens as dosen_tiga', 'dosen_tiga.id', '=', 'kelas.dosen_tiga')
                ->leftJoin('dosens as dosen_empat', 'dosen_empat.id', '=', 'kelas.dosen_empat')
                ->leftJoin('users as user_satu', 'user_satu.id', '=', 'dosen_satu.user_id')
                ->leftJoin('users as user_dua', 'user_dua.id', '=', 'dosen_dua.user_id')
                ->leftJoin('users as user_tiga', 'user_tiga.id', '=', 'dosen_tiga.user_id')
                ->leftJoin('users as user_empat', 'user_empat.id', '=', 'dosen_empat.user_id')
                ->where(function($query) use ($user_email) {
                    $query->where('user_satu.email', $user_email)
                          ->orWhere('user_dua.email', $user_email)
                          ->orWhere('user_tiga.email', $user_email)
                          ->orWhere('user_empat.email', $user_email);
                })
                ->where('semesters.id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'mkcpmks.kode_cpmk', 'mkcpmks.desk_cpmk',
                         'kompnilais.jen_penilaian', 'rubnilais.id as rubni_id')
                ->distinct()
                ->get();

        }

        return view('dosen.t_tarcpmk', compact('mata_kuliah', 'tarcpmk', 'smtr', 'encrypted_semester_id'));
    }


    public function a_tarcpmk($matkul_id)
    {
        $user_email = Auth::user()->email;
        $matkul_id = Crypt::decryptString($matkul_id);
        $jenilai = Kompnilai::all();

        $kelas = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id') // Melakukan JOIN pada tabel matkuls
            ->where('kelas.matkul_id', $matkul_id)
            ->select('kelas.id as kelas_id', 'matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.semester_id')
            ->get();

        //$kelas_id = $kelas->first() ? $kelas->first()->kelas_id : null;
        $mkcp = Mkcpmk::where('matkul_id', $matkul_id)->get();

        $dataMK = $kelas->first(); 

        $kelas_id = [];
        foreach ($kelas as $kelas_id) {
            $kelas_id = $kelas_id;
        } 
        if ($mkcp->isEmpty()) {
            return redirect()->back()->with('warning', 'CPMK pada kelas ini masih kosong. Silakan tambahkan dulu data CPMK nya.');
        }
        return view('dosen.a_tarcpmk', compact('mkcp', 'matkul_id', 'jenilai', 'kelas','dataMK'));
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

        $data = $request->kelas_id;
        $semester_id = Crypt::encrypt($request->semester_id);

        $request->validate([
            'kode_cpmk.*' => 'required|exists:mkcpmks,kode_cpmk',
            'kompnilai_id.*' => 'required|exists:kompnilais,id',
        ]);

        foreach ($data as $kelas_id) {
            foreach ($request->kode_cpmk as $index => $kode_cpmk) {
                $mkcpmk = Mkcpmk::where('kode_cpmk', $kode_cpmk)->first();
                Rubnilai::create([
                    // 'matkul_id' => $matkul_id,
                    'mkcpmk_id' => $mkcpmk->id,
                    'kompnilai_id' => $request->kompnilai_id[$index],
                    'kelas_id' => $kelas_id,
                ]);
            } 
        }
        return redirect('/dosen/t_tarcpmk?semester_id='.$semester_id);
    }

    public function h_tarcpmk($id, $encrypted_semester_id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Rubnilai::find($decryptID);
        $data->delete();
        return redirect('/dosen/t_tarcpmk?semester_id='.$encrypted_semester_id);
        //return redirect('/dosen/t_tarcpmk');
    }
    // END TARGET CPMK

    public function t_inpnilai(Request $request)
    {
        $smtr = Semester::all();
        $encrypted_semester_id = $request->input('semester_id');
        $inilai = [];

        if ($encrypted_semester_id) {
            $semester_id = decrypt($encrypted_semester_id);
            $user_email = Auth::user()->email;

            // Mengambil data nilai dari semua kolom dosen (satu, dua, tiga, empat) sekaligus
            $inilai = DB::table('rubnilais')
                ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
                ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
                ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
                ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', function($join) {
                    // Menghubungkan dengan dosen_satu, dosen_dua, dosen_tiga, dosen_empat
                    $join->on('dosens.id', '=', 'kelas.dosen_satu')
                        ->orOn('dosens.id', '=', 'kelas.dosen_dua')
                        ->orOn('dosens.id', '=', 'kelas.dosen_tiga')
                        ->orOn('dosens.id', '=', 'kelas.dosen_empat');
                })
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('semesters.id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                        'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                        'rubnilais.id as rubni_id', 'kelas.nama_kelas',
                        'kelas.id as kelas_id', 'kelas.kode_kelas as kode_kelas', 'kelas.dosen_inputnilai')
                ->distinct()
                ->get();
        }
        $currentDosenId = Auth::user()->dosen->id;
        return view('dosen.t_inpnilai', compact('inilai', 'smtr', 'currentDosenId'));
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

    // public function a_inpnilai($kelas_id)
    // {
    //     $kelas_id = Crypt::decryptString($kelas_id);
    //     $kelas = Kelas::with('semester')->find($kelas_id);

    //     $mahasiswas = DB::table('kelas')
    //         ->join('mhs_kelas', 'kelas.id', '=', 'mhs_kelas.kelas_id')
    //         ->join('mahasiswas', 'mahasiswas.nim', '=', 'mhs_kelas.nim')
    //         ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
    //         // ->where('kelas.kelas_id', $kelas->kelas_id)
    //         // ->where('kelas.nama_kelas', $kelas->nama_kelas)
    //         ->select('kelas.*', 'mahasiswas.nama_mahasiswa', 'mahasiswas.nim')
    //         ->distinct()
    //         ->get();

    //     $inpnil = DB::table('rubnilais')
    //         ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
    //         ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
    //         ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
    //         ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kompnilais.jen_penilaian', 'kompnilais.label',
    //                 'rubnilais.id as rubnilai_id', 'kompnilais.id as kompnilai_id',
    //                 'mkcpmks.id as cpmk_id', 'mkcpmks.kode_cpmk')
    //         // ->where('matkuls.id', $kelas->matkul_id)
    //         ->distinct()
    //         ->get();

    //     return view('dosen.a_inpnilai', compact('inpnil', 'kelas_id', 'kelas', 'mahasiswas'));
    // }

    public function a_inpnilai($kelas_id)
    {
        $currentDosen = Auth::user()->dosen;

        if (!$currentDosen) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai dosen.');
        }

        $kelas_id = Crypt::decryptString($kelas_id);
        $kelas = Kelas::with('semester')->find($kelas_id);

        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $mahasiswas = DB::table('mhs_kelas')
            ->join('mahasiswas', 'mhs_kelas.nim', '=', 'mahasiswas.nim')
            ->where('mhs_kelas.kelas_id', $kelas_id)
            ->select('mahasiswas.nim', 'mahasiswas.nama_mahasiswa')
            ->distinct()
            ->get();

        $inpnil = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->where('matkuls.id', $kelas->matkul_id)
            ->select(
                'matkuls.kode_mk',
                'matkuls.nama_mk',
                'kompnilais.jen_penilaian',
                'kompnilais.label',
                'rubnilais.id as rubnilai_id',
                'kompnilais.id as kompnilai_id',
                'mkcpmks.id as cpmk_id',
                'mkcpmks.kode_cpmk'
            )
            ->distinct()
            ->get();

        if ($inpnil->isEmpty()) {
            return redirect()->back()->with('error', 'Rubrik nilai tidak ditemukan untuk mata kuliah ini.');
        }

        return view('dosen.a_inpnilai', compact('inpnil', 'kelas', 'kelas_id', 'mahasiswas'));
    }



    public function t_nilai($kelas_id)
    {
        $kelas_id = Crypt::decryptString($kelas_id);
        //echo" ini dia $kelas_id";

        $nilai = Inpnilai::join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
            ->join('mhs_kelas', function ($join) {
                $join->on('mhs_kelas.kelas_id', '=', 'inpnilais.kelas_id')
                     ->on('mhs_kelas.nim', '=', 'inpnilais.nim');
            })
            ->select(
                'inpnilais.nim',
                'inpnilais.nilai',
                'kompnilais.label as komponen_label',
                'mkcpmks.kode_cpmk',
                'mkcpmks.id as mkcpmk_id',
                'mahasiswas.nama_mahasiswa',
                'kelas.id as kelas_id'
            )
            ->where('inpnilais.kelas_id', $kelas_id)
            ->get();

            $groupedNilai = $nilai->groupBy('nim')->map(function ($group) {
                $kelas_id = $group->first()->kelas_id;

                $scores = $group->filter(function ($item) use ($kelas_id) {
                    return $item->kelas_id == $kelas_id;
                })->mapWithKeys(function ($item) {
                    return [$item->komponen_label => $item->nilai];
                });

                return [
                    'nama_mahasiswa' => $group->first()->nama_mahasiswa,
                    'nim' => $group->first()->nim,
                    'nilai' => $scores->toArray(),
                ];
            });

            // dd($groupedNilai);

        $averagePerCpmk = $nilai->groupBy('mkcpmk_id')->map(function ($group) use ($kelas_id) {
            $kelas_id_grup = $group->first()->kelas_id;

            if ($kelas_id_grup != $kelas_id) {
                return null;
            }

            $averages = $group->groupBy('nim')->map(function ($subGroup) {
                return $subGroup->avg('nilai');
            });
            // dd($averages);

            $trgt_nilai = DB::table('subcpls')
                ->join('mkcpmks', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
                ->where('mkcpmks.id', $group->first()->mkcpmk_id)
                ->value('subcpls.trgt_nilai');

            $statusPerMahasiswa = $averages->map(function ($average) use ($trgt_nilai) {
                return $average <= $trgt_nilai ? 'Tidak' : 'Tercapai';
            });

            return [
                'kode_cpmk' => $group->first()->kode_cpmk,
                'averages' => $averages,
                'trgt_nilai' => $trgt_nilai,
                'status' => $statusPerMahasiswa,
            ];
        })->filter()->values();



        $Datamatkul = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->join('prodis', 'matkuls.prodi_id', '=', 'prodis.id')
            ->where('kelas.id', $kelas_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.nama_kelas', 'semesters.keterangan as semester','prodis.nama_prodi', 'prodis.jenjang')
            ->first();

        $cekNilai = $nilai;
        //dd($Datamatkul);
        
        if ($cekNilai->isEmpty()) {
            return redirect()->back()->with('warning', 'Data Nilai belum diisi. Silakan isi dulu nilai nya sesuai target CPMK.');
        }

        return view('dosen.t_nilai', compact('groupedNilai', 'averagePerCpmk', 'Datamatkul'));
    }


    // RPS
    public function t_rps()
    {
        $user_email = Auth::user()->email;
        // $matkul_id = Crypt::decryptString($matkul_id);

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
                    'mksubcpmks.id as mkscpmk_id', 'prodis.id as prodi_id')
            ->distinct()
            ->get();

        $prodi_id = $mkscp->first()->prodi_id ?? null;

        $rps = DB::table('speckrs')
            ->join('genkrs', 'genkrs.matkul_id', '=', 'speckrs.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'speckrs.matkul_id')
            ->join('mksubcpmks', 'mksubcpmks.matkul_id', '=', 'speckrs.matkul_id')

            ->where('matkuls.prodi_id', $prodi_id)
            // ->where('users.email', $user_email)
            ->select('speckrs.*', 'genkrs.tgl_susun', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'genkrs.desk_singkat', 'genkrs.kajian','genkrs.pustaka', 'mksubcpmks.kode_scpmk',
                    'genkrs.mk_syarat', 'genkrs.keterangan', 'genkrs.lampiran', 'matkuls.kode_mk', 'matkuls.nama_mk')
            ->get();

            return view('dosen.t_rps', compact('mkscp', 'rps'));
    }


    public function a_rps($matkul_id)
    {

	$matkul_id = Crypt::decryptString($matkul_id);
	// $user_email = Auth::user()->email;
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
            ->where('matkuls.id', $matkul_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'mksubcpmks.id as mkscpmk_id')
            ->distinct()
            ->get();

            return view('dosen.a_rps', compact('mkscp', 'matkul_id'));
    }


    public function s_rps(Request $request, $matkul_id)
    {
    $validatedData = $request->validate([
        'desk_singkat' => 'required|string',
        'materi' => 'required|string',
        'pustaka' => 'required|string',
        'mk_syarat' => 'required|string',
        'keterangan' => 'required|string',
        'lampiran' => 'required|string',
        'pekan_ke' => 'required|array',
        'indikator' => 'required|array',
        'kriteria' => 'required|array',
        'luring' => 'required|array',
        'daring' => 'required|array',
        'mat_pustaka' => 'required|array',
        'bobot_nil' => 'required|array',
    ]);

    $rps = new Rps();
    $rps->desk_singkat = $validatedData['desk_singkat'];
    $rps->materi = $validatedData['materi'];
    $rps->pustaka = $validatedData['pustaka'];
    $rps->mk_syarat = $validatedData['mk_syarat'];
    $rps->keterangan = $validatedData['keterangan'];
    $rps->lampiran = $validatedData['lampiran'];
    $rps->matkul_id = Crypt::decrypt($matkul_id);

    $rps->save();

    foreach ($validatedData['pekan_ke'] as $key => $value) {
        $detail = new Rps();
        $detail->pekan_ke = $value;
        $detail->indikator = $validatedData['indikator'][$key];
        $detail->kriteria = $validatedData['kriteria'][$key];
        $detail->luring = $validatedData['luring'][$key];
        $detail->daring = $validatedData['daring'][$key];
        $detail->mat_pustaka = $validatedData['mat_pustaka'][$key];
        $detail->bobot_nil = $validatedData['bobot_nil'][$key];

        $detail->rps_id = $rps->id;
        $detail->save();
    }

    return redirect('/dosen/t_rps');
    }

public function a_genrps($matkul_id)
    {
        $user_id = Auth::user()->id;
        $dosenProdi = DB::table('dosens')->where('user_id', $user_id)->first();
        $prodi_id = $dosenProdi->prodi_id;

        $matkul_id = Crypt::decryptString($matkul_id);
        // $user_email = Auth::user()->email;
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
            ->where('matkuls.id', $matkul_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'mksubcpmks.id as mksubcpmk_id')
            ->distinct()
            ->get();

        $kajur = DB::table('kajurs')
            ->join('dosens','dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'dosens.prodi_id')
            ->join('users','users.id', '=', 'dosens.user_id')
            ->where('dosens.prodi_id', $prodi_id)
            ->where('kajurs.jabatan', 'Koordinator Kelompok Keahlian')
            ->select('kajurs.*', 'prodis.nama_prodi', 'users.name as nama_dosen', 'dosens.nip', 'dosens.nidn', 'kajurs.dosen_id as dosen_id')
            ->get();

            // dd($mkscp);
            // dd($kajur);

            return view('dosen.a_genrps', compact('mkscp', 'matkul_id', 'kajur'));
    }

    public function a_meetrps($matkul_id)
    {

        $matkul_id = Crypt::decryptString($matkul_id);
        $genkrs = Genkrs::where('matkul_id', $matkul_id)->first();
        // $user_email = Auth::user()->email;
        $mkscp = DB::table('mksubcpmks')
            ->join('mkcpmks', 'mkcpmks.matkul_id', '=', 'mksubcpmks.matkul_id')
            ->join('mkscpls', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            // ->join('kajurs', 'kajurs.dosen_id', '=', 'dosens.id')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            // ->where('users.email', $user_email)
            ->where('matkuls.id', $matkul_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'mksubcpmks.id as mksubcpmk_id')
            ->distinct()
            ->get();

            // dd($mkscp);

            return view('dosen.a_meetrps', compact('mkscp', 'matkul_id'));
    }


    public function s_meetrps(Request $request, $matkul_id)
    {
    // Validasi data yang masuk
    $genkrs_id = $request->input('genkrs_id');

    $request->validate([
        'pekan_ke.*' => 'required|integer',
        'mksubcpmk_id.*' => 'required|exists:mksubcpmks,id',
        'indikator.*' => 'required|string',
        'kritek.*' => 'required|string',
        'luring.*' => 'required|string',
        'daring.*' => 'required|string',
        'mat_pustaka.*' => 'required|string',
        'bobot_nil.*' => 'required|numeric',
    ]);

    foreach ($request->pekan_ke as $index => $pekan) {
        $speckr = new Speckrs();
        // $speckr->genkrs_id = $genkrs_id;
        $speckr->matkul_id = $matkul_id;
        $speckr->pekan_ke = $pekan;
        $speckr->mksubcpmk_id = $request->mksubcpmk_id[$index];
        $speckr->indikator = $request->indikator[$index];
        $speckr->kritek = $request->kritek[$index];
        $speckr->luring = $request->luring[$index];
        $speckr->daring = $request->daring[$index];
        $speckr->mat_pustaka = $request->mat_pustaka[$index];
        $speckr->bobot_nil = $request->bobot_nil[$index];

        $speckr->save();
    }

    return redirect()->back()->with('success', 'Data RPS berhasil disimpan.');
    }

    public function s_genrps (Request $request, $matkul_id)
    {
        $request->validate([
            'tgl_susun' => 'required|date',
            'desk_singkat' => 'required|string',
            'kajian' => 'required|string',
            'pustaka' => 'required|string',
            'mk_syarat' => 'required|string',
            'keterangan' => 'nullable|string',
            'lampiran' => 'nullable|string',
        ]);

        $genkrs = new Genkrs();
        $genkrs->matkul_id = $matkul_id;
        $genkrs->kajur_id = $request->input('kajur_id');
        $genkrs->tgl_susun = $request->input('tgl_susun');
        $genkrs->desk_singkat = $request->input('desk_singkat');
        $genkrs->kajian = $request->input('kajian');
        $genkrs->pustaka = $request->input('pustaka');
        $genkrs->mk_syarat = $request->input('mk_syarat');
        $genkrs->keterangan = $request->input('keterangan');
        $genkrs->lampiran = $request->input('lampiran');

        $genkrs->save();

        return redirect()->back()->with('success', 'Data RPS berhasil disimpan!');
    }


    public function createrps($matkul_id)
    {
        $user_id = Auth::user()->id;
        $dosenProdi = DB::table('dosens')->where('user_id', $user_id)->first();
        $prodi_id = $dosenProdi->prodi_id;

        $matkul_id = Crypt::decryptString($matkul_id);
        $mkscp = DB::table('mksubcpmks')
            ->join('mkcpmks', 'mkcpmks.matkul_id', '=', 'mksubcpmks.matkul_id')
            ->join('mkscpls', 'mkscpls.subcpl_id', '=', 'mkcpmks.subcpl_id')
            ->join('kelas', 'kelas.matkul_id', '=', 'mkscpls.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->join('cpls', 'cpls.id', '=', 'mkscpls.cpl_id')
            ->join('subcpls', 'subcpls.cpl_id', '=', 'cpls.id')
            ->join('genkrs', 'genkrs.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            // ->where('users.email', $user_email)
            ->where('matkuls.id', $matkul_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.rumpun_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk', 'semesters.nama_smtr',
                    'mkcpmks.desk_cpmk', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk', 'matkuls.sks_teo',
                    'mksubcpmks.id as mksubcpmk_id', 'prodis.nama_prodi', 'matkuls.sks_prak', 'genkrs.tgl_susun',
                    'cpls.kode_cpl', 'cpls.deskrip_cpl', 'subcpls.kode_subcpl', 'subcpls.desk_subcpl')
            ->distinct()
            ->orderBy('cpls.kode_cpl')
            ->get();

        $kajur = DB::table('kajurs')
            ->join('genkrs','kajur_id', '=', 'kajurs.dosen_id')
            ->join('dosens','dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('users','users.id', '=', 'dosens.user_id')
            ->where('dosens.prodi_id', $prodi_id)
            ->where('kajurs.jabatan', 'Koordinator Kelompok Keahlian')
            ->select('kajurs.*', 'prodis.nama_prodi', 'users.name as nama_dosen', 'dosens.nip', 'dosens.nidn', 'kajurs.dosen_id as dosen_id')
            ->get();

        $dosenPengembang = DB::table('kelas')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('users', 'users.id', '=', 'dosens.user_id')
            ->where('kelas.matkul_id', $matkul_id)
            ->select('users.name as nama_dosen')
            ->first();

        $ketuaJurusan = DB::table('kajurs')
            ->join('dosens', 'dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'users.id', '=', 'dosens.user_id')
            ->where('dosens.prodi_id', $prodi_id)
            ->where('kajurs.jabatan', 'Ketua Jurusan')
            ->select('users.name as nama_ketua_jurusan')
            ->first();

        $rps = DB::table('speckrs')
            ->join('genkrs', 'genkrs.matkul_id', '=', 'speckrs.matkul_id')
            ->join('matkuls', 'matkuls.id', '=', 'speckrs.matkul_id')
            ->join('mksubcpmks', 'mksubcpmks.matkul_id', '=', 'speckrs.matkul_id')
            ->where('matkuls.id', $matkul_id)
            ->select('speckrs.*', 'genkrs.tgl_susun', 'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk',
                    'genkrs.desk_singkat', 'genkrs.kajian','genkrs.pustaka',
                    'genkrs.mk_syarat', 'genkrs.keterangan', 'genkrs.lampiran')
            ->orderBy('pekan_ke', 'asc')->get();

        $pdf = Pdf::loadView('dosen.t_createrps', compact('mkscp', 'matkul_id', 'rps', 'kajur', 'dosenPengembang', 'ketuaJurusan'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Times-New-Roman',
                'font_size' => 11,
                'isRemoteEnabled' => true
            ]);

        return $pdf->stream('rps.pdf');

    }

    public function s_inpnilai(Request $request)
    {
        try {

            $data = $request->input('nilai');
            $kelas_id = Crypt::decryptString($request->kelas_id);

            //dd($request->rubnilai_id);

            
            
                //echo "Rubrik Nilai ID: " . $rub . "<br>";
            
        foreach ($request->rubnilai_id as $rub) {
            foreach ($data as $nim => $nilai_per_mahasiswa) {
                $total_nilai = 0;
                $jumlah_nilai = count($nilai_per_mahasiswa);

                foreach ($nilai_per_mahasiswa as $kompnilai_id => $nilai) {
                    Inpnilai::create([
                        'nim' => $nim,
                        'kompnilai_id' => $kompnilai_id,
                        'nilai' => $nilai,
                        'rubnilai_id' => $rub,
                        'kelas_id' => $kelas_id,
                    ]);
                    $total_nilai += $nilai;
                }
                $nilai_rata = $jumlah_nilai > 0 ? $total_nilai / $jumlah_nilai : 0;
                Inpnilai::where('nim', $nim);//->update(['nilai_rata' => $nilai_rata]);
            }
        }

            return redirect()->route('dosen.t_nilai', ['kelas_id' => $request->kelas_id])->with('success', 'Nilai berhasil diunggah dan diproses.');

        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    public function downloadTemplateExcel($kelas_id)
    {
        $kelas_id = Crypt::decryptString($kelas_id);
        $kelas = Kelas::with('semester')->find($kelas_id);

        $kode_mk = DB::table('matkuls')
            ->where('id', $kelas->matkul_id)
            ->value('kode_mk');

        $mahasiswas = DB::table('mhs_kelas')
            ->join('mahasiswas', 'mhs_kelas.nim', '=', 'mahasiswas.nim')
            ->where('mhs_kelas.kelas_id', $kelas_id)
            ->select('mahasiswas.nim', 'mahasiswas.nama_mahasiswa')
            ->distinct()
            ->get();

        $inpnil = DB::table('rubnilais')
            ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
            ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
            ->join('matkuls', 'matkuls.id', '=', 'mkcpmks.matkul_id')
            ->where('matkuls.id', $kelas->matkul_id)
            ->select('kompnilais.label')
            ->distinct()
            ->get();

        return Excel::download(new TemplateExport($mahasiswas, $inpnil), $kode_mk . '-' . $kelas->nama_kelas . '-template_nilai.xlsx');
    }

//cadangan script benar
    // public function uploadTemplateExcel(Request $request)
    // {
    //     $request->validate([
    //         'file_excel' => 'required|mimes:xlsx,xls',
    //     ]);

    //     try {
    //         $kelas_id = Crypt::decryptString($request->input('kelas_id'));
    //         $file = $request->file('file_excel');
    //         $data = Excel::toArray([], $file)[0];

    //         $header = array_map(fn($item) => strtolower(trim($item)), $data[0]);

    //         if (!in_array('nim', $header)) {
    //             return redirect()->back()->with('error', 'Kolom "NIM" wajib ada di file Excel.');
    //         }

    //         $nilaiColumns = array_diff($header, ['no.', 'nim', 'nama mahasiswa', 'absolut', 'relatif']);

    //         $komponenNilai = DB::table('kompnilais')
    //             ->pluck('id', DB::raw('LOWER(label)'));

    //         foreach ($nilaiColumns as $column) {
    //             if (!isset($komponenNilai[strtolower($column)])) {
    //                 return redirect()->back()->with('error', "Kolom nilai \"$column\" tidak ditemukan di database.");
    //             }
    //         }

    //         foreach ($data as $key => $row) {
    //             if ($key === 0) continue;

    //             $nim = trim($row[1]);
    //             if (!$nim) continue;

    //             $mahasiswa = DB::table('mahasiswas')->where('nim', $nim)->first();
    //             if (!$mahasiswa) {
    //                 return redirect()->back()->with('error', "Mahasiswa dengan NIM $nim tidak ditemukan.");
    //             }

    //             foreach ($nilaiColumns as $column) {
    //                 $nilai = $row[array_search($column, $header)] ?? null;

    //                 if (!is_numeric($nilai)) {
    //                     return redirect()->back()->with('error', "Nilai pada kolom \"$column\" untuk NIM $nim tidak valid.");
    //                 }

    //                 $rubrikNilai = DB::table('rubnilais')
    //                     ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
    //                     ->where('kompnilais.label', strtolower($column))
    //                     ->select('rubnilais.id as rub_id', 'kompnilais.id as komp_id')
    //                     ->first();

    //                 if (!$rubrikNilai) {
    //                     return redirect()->back()->with('error', "Rubrik nilai untuk kolom \"$column\" tidak ditemukan.");
    //                 }

    //                 DB::table('inpnilais')->updateOrInsert(
    //                     [
    //                         'kelas_id' => $kelas_id,
    //                         'nim' => $nim,
    //                         'kompnilai_id' => $rubrikNilai->komp_id,
    //                     ],
    //                     [
    //                         'rubnilai_id' => $rubrikNilai->rub_id,
    //                         'nilai' => $nilai,
    //                         'updated_at' => now(),
    //                     ]
    //                 );
    //             }
    //         }

    //         return redirect()->back()->with('success', 'Nilai berhasil diunggah.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage());
    //     }
    // }


//script baru coba
public function uploadTemplateExcel(Request $request)
{
    $request->validate([
        'file_excel' => 'required|mimes:xlsx,xls',
    ]);

    try {
        $kelas_id = Crypt::decryptString($request->input('kelas_id'));
        $file = $request->file('file_excel');
        $data = Excel::toArray([], $file)[0];

        $header = array_map(fn($item) => strtolower(trim($item)), $data[0]);

        if (!in_array('nim', $header)) {
            return redirect()->back()->with('error', 'Kolom "NIM" wajib ada di file Excel.');
        }

        $nilaiColumns = array_diff($header, ['no.', 'nim', 'nama mahasiswa', 'absolut', 'relatif']);

        $komponenNilai = DB::table('kompnilais')
            ->pluck('id', DB::raw('LOWER(label)'));

        foreach ($nilaiColumns as $column) {
            if (!isset($komponenNilai[strtolower($column)])) {
                return redirect()->back()->with('error', "Kolom nilai \"$column\" tidak ditemukan di database.");
            }
        }

        foreach ($data as $key => $row) {
            if ($key === 0) continue;

            $nim = trim($row[1]);
            if (!$nim) continue;

            $mahasiswa = DB::table('mahasiswas')->where('nim', $nim)->first();
            if (!$mahasiswa) {
                return redirect()->back()->with('error', "Mahasiswa dengan NIM $nim tidak ditemukan.");
            }

            foreach ($nilaiColumns as $column) {
                $nilai = $row[array_search($column, $header)] ?? null;

                if (!is_numeric($nilai)) {
                    return redirect()->back()->with('error', "Nilai pada kolom \"$column\" untuk NIM $nim tidak valid.");
                }

                $rubrikNilai = DB::table('rubnilais')
                    ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
                    ->where('rubnilais.kelas_id', $kelas_id)
                    ->where('kompnilais.label', strtolower($column))
                    ->select('rubnilais.id as rubnilai_id', 'kompnilais.id as kompnilai_id')
                    ->first();

                if (!$rubrikNilai) {
                    return redirect()->back()->with('error', "Rubrik nilai untuk kolom \"$column\" di kelas ini tidak ditemukan.");
                }

                DB::table('inpnilais')->updateOrInsert(
                    [
                        'kelas_id' => $kelas_id,
                        'nim' => $nim,
                        'kompnilai_id' => $rubrikNilai->kompnilai_id,
                    ],
                    [
                        'rubnilai_id' => $rubrikNilai->rubnilai_id,
                        'nilai' => $nilai,
                        'updated_at' => now(),
                    ]
                );
            }
        }
        return redirect()->back()->with('success', 'Nilai berhasil diunggah.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage());
    }
}

public function t_perkelas(Request $request)
    {
        $smtr = Semester::all();
        $encrypted_semester_id = $request->input('semester_id');
        $inilai = [];

        if ($encrypted_semester_id) {
            $semester_id = decrypt($encrypted_semester_id);
            $user_email = Auth::user()->email;

            $inilai = DB::table('rubnilais')
                ->join('kompnilais', 'kompnilais.id', '=', 'rubnilais.kompnilai_id')
                ->join('mkcpmks', 'mkcpmks.id', '=', 'rubnilais.mkcpmk_id')
                ->join('kelas', 'kelas.matkul_id', '=', 'mkcpmks.matkul_id')
                ->join('matkuls', 'matkuls.id', '=', 'kelas.matkul_id')
                ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
                ->join('dosens', function($join) {
                    $join->on('dosens.id', '=', 'kelas.dosen_satu')
                        ->orOn('dosens.id', '=', 'kelas.dosen_dua')
                        ->orOn('dosens.id', '=', 'kelas.dosen_tiga')
                        ->orOn('dosens.id', '=', 'kelas.dosen_empat');
                })
                ->join('users', 'dosens.user_id', '=', 'users.id')
                ->where('users.email', $user_email)
                ->where('semesters.id', $semester_id)
                ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'matkuls.id as matkul_id',
                        'mkcpmks.kode_cpmk', 'kompnilais.jen_penilaian',
                        'rubnilais.id as rubni_id', 'kelas.nama_kelas',
                        'kelas.id as kelas_id', 'kelas.kode_kelas as kode_kelas', 'kelas.dosen_inputnilai')
                ->distinct()
                ->get();
        }
        $currentDosenId = Auth::user()->dosen->id;
        return view('dosen.t_perkelas', compact('inilai', 'smtr', 'currentDosenId'));
    }


    public function t_lapkelas($kelas_id)
    {
        $kelas_id = Crypt::decryptString($kelas_id);

        $nilai = Inpnilai::join('mahasiswas', 'inpnilais.nim', '=', 'mahasiswas.nim')
            ->join('rubnilais', 'inpnilais.rubnilai_id', '=', 'rubnilais.id')
            ->join('mkcpmks', 'rubnilais.mkcpmk_id', '=', 'mkcpmks.id')
            ->join('kelas', 'kelas.id', '=', 'inpnilais.kelas_id')
            ->join('kompnilais', 'rubnilais.kompnilai_id', '=', 'kompnilais.id')
            ->join('mhs_kelas', function ($join) {
                $join->on('mhs_kelas.kelas_id', '=', 'inpnilais.kelas_id')
                    ->on('mhs_kelas.nim', '=', 'inpnilais.nim');
            })
            ->select(
                'inpnilais.nim',
                'inpnilais.nilai',
                'kompnilais.label as komponen_label',
                'mkcpmks.kode_cpmk',
                'mkcpmks.id as mkcpmk_id',
                'mahasiswas.nama_mahasiswa',
                'kelas.id as kelas_id'
            )
            ->where('inpnilais.kelas_id', $kelas_id)
            ->get();

        $groupedNilai = $nilai->groupBy('nim')->map(function ($group) {
            $scores = $group->mapWithKeys(function ($item) {
                return [$item->komponen_label => $item->nilai];
            });

            return [
                'nama_mahasiswa' => $group->first()->nama_mahasiswa,
                'nim' => $group->first()->nim,
                'nilai' => $scores->toArray(),
            ];
        });

        $averagePerCpmk = $nilai->groupBy('mkcpmk_id')->map(function ($group) use ($kelas_id) {
            $averages = $group->groupBy('nim')->map(function ($subGroup) {
                return $subGroup->avg('nilai'); // Rata-rata nilai untuk setiap mahasiswa
            });

            $trgt_nilai = DB::table('subcpls')
                ->join('mkcpmks', 'mkcpmks.subcpl_id', '=', 'subcpls.id')
                ->where('mkcpmks.id', $group->first()->mkcpmk_id)
                ->value('subcpls.trgt_nilai');

            $statusPerMahasiswa = $averages->map(function ($average) use ($trgt_nilai) {
                return $average >= $trgt_nilai ? 'Tercapai' : 'Tidak';
            });

            return [
                'kode_cpmk' => $group->first()->kode_cpmk,
                'averages' => $averages,
                'trgt_nilai' => $trgt_nilai,
                'status' => $statusPerMahasiswa,
            ];
        })->filter()->values();

        $statusTercapai = 0;
        $statusTidak = 0;

        $cpmkCodeTercapai = [];
        $cpmkCodeTidak = [];
        $cpmkLabels = [];

        foreach ($averagePerCpmk as $cpmk) {
            foreach ($cpmk['status'] as $key => $status) {
                if ($status === 'Tercapai') {
                    $statusTercapai++;
                    $cpmkCodeTercapai[] = $cpmk['kode_cpmk'];  // Menyimpan kode CPMK yang tercapai
                    $cpmkLabels[$cpmk['kode_cpmk']][] = 'Tercapai';
                } else {
                    $statusTidak++;
                    $cpmkCodeTidak[] = $cpmk['kode_cpmk'];  // Menyimpan kode CPMK yang tidak tercapai
                    $cpmkLabels[$cpmk['kode_cpmk']][] = 'Tidak Tercapai';
                }
            }
        }

        $cpmkCodeTercapai = array_unique($cpmkCodeTercapai);
        $cpmkCodeTidak = array_unique($cpmkCodeTidak);

        $chartData = [
            'statusTercapai' => $statusTercapai,
            'statusTidak' => $statusTidak,
            'cpmkLabels' => $cpmkLabels, // Pastikan cpmkLabels ada
            'cpmkCodeTercapai' => $cpmkCodeTercapai,
            'cpmkCodeTidak' => $cpmkCodeTidak
        ];
        // dd($chartData);

        $Datamatkul = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'kelas.semester_id', '=', 'semesters.id')
            ->where('kelas.id', $kelas_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.nama_kelas', 'semesters.keterangan as semester')
            ->first();

            // dd([
            //     'cpmkCodeTercapai' => $cpmkCodeTercapai,
            //     'cpmkCodeTidak' => $cpmkCodeTidak
            // ]);

        return view('dosen.t_lapkelas', compact('groupedNilai', 'averagePerCpmk', 'Datamatkul', 'chartData'));
    }



}
