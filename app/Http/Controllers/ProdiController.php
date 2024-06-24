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
use Illuminate\Support\Facades\Log;


class ProdiController extends Controller
{
    public function index() {
        return view('prodiadmin.index');
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
                'filesk' => 'required|mimes:jpg,jpeg,JPG,JPEG,png,PNG,pdf,PDF|max:6000'
            ],
            [
                'prodi_id.required' => 'Wajib dipilih !!!',
                'kode_kuri.required' => 'Wajib diisi !!!',
                'tahun_kuri.required' => 'Wajib diisi !!!',
                'nama_kuri.required' => 'Wajib diisi !!!',
                'deskripsi.required' => 'Wajib diisi !!!',
                'sk_kuri.required' => 'Wajib diisi !!!',
                'filesk.required' => 'Wajib diisi !!!, Foto Max 6000 KB, jenis PDF, JPG, PNG, JPEG',
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



    //DOSEN
    public function t_dosen()
    {
        $data = DB::table('dosens')
        ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'dosens.prodi_id')
        ->join('users','users.id', '=', 'dosens.user_id')
        ->select('dosens.*', 'prodis.nama_prodi', 'users.name as nama_dosen')
        ->orderBy('dosens.created_at', 'desc')
        ->get();

        return view ('prodiadmin.t_dosen', compact('data'));
    }


    //MATA KULIAH
    public function t_matkul()
    {
        $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'matkuls.prodi_id')
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->latest()->get();

        return view('prodiadmin/t_matkul', compact('matk'));
    }

    public function a_matkul()
    {
        $prod = Prodi::all();
        $kuri = Kurikulum::all();
        $semes = Semester::all();
        return view ('superadmin/a_matkul', compact('prod','kuri','semes'));
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
            return redirect()->back()->with('error', 'Total SKS harus sama dengan SKS Kurikulum.');
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

        return redirect('superadmin/t_matkul');
    }


    //CPL
    public function t_cpl()
    {
        $cp = DB::table('cpls')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->select('cpls.*', 'prodis.kopro', 'kurikulums.nama_kuri')
        ->get();

        return view('prodiadmin/t_cpl', compact('cp'));
    }

    public function a_cpl()
    {
        $prod = DB::table('admprodis')
            ->join('prodis', 'prodis.id', '=', 'admprodis.prodi_id')
            ->join('kurikulums', 'kurikulums.prodi_id', '=', 'admprodis.prodi_id')
            ->join('users', 'users.id', '=', 'admprodis.user_id')
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
    public function t_subcpl()
    {
        $scp = DB::table('subcpls')
        ->leftjoin('cpls','cpls.id', '=', 'subcpls.cpl_id')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->select('subcpls.*', 'kurikulums.nama_kuri', 'cpls.kode_cpl')
        ->get();

        return view('prodiadmin/t_subcpl', compact('scp'));
    }

    public function a_subcpl()
    {
        $cpel = DB::table('cpls')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->select('cpls.*', 'cpls.id as cpl_id')
        ->get();

        $existingSubCPLs = DB::table('subcpls')
        ->select('kode_subcpl')
        ->get()
        ->groupBy(function ($item) {
            return explode('.', $item->kode_subcpl)[0];
        })
        ->map(function ($group) {
            return $group->max(function ($item) {
                return (int) explode('.', $item->kode_subcpl)[1];
            });
        });

        // dd($existingSubCPLs);

        return view ('prodiadmin/a_subcpl', compact('cpel', 'existingSubCPLs'));
    }


    public function s_subcpl(Request $request)
    {
        $request->validate([
            'cpl_id' => 'required',
            'kode_subcpl' => 'required|array',
            'desk_subcpl' => 'required|array',
        ]);

        $cpl_id = $request->cpl_id;
        $kode_subcpl = $request->kode_subcpl;
        $desk_subcpl = $request->desk_subcpl;

        $totalKodeSubCPL = count($kode_subcpl);

        for ($i = 0; $i < $totalKodeSubCPL; $i++) {
            $scpl = new SubCpl();
            $scpl->cpl_id = $cpl_id;
            $scpl->kode_subcpl = $kode_subcpl[$i];
            $scpl->desk_subcpl = $desk_subcpl[$i];
            $scpl->save();
        }

        return redirect('prodiadmin/t_subcpl');
    }

    public function e_subcpl($id)
    {

        $cpel = DB::table('cpls')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        // ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->join('users', 'users.id', '=', 'admprodis.user_id')
        ->where('users.email', '=', Auth::guard('web')->user()->email)
        ->select('cpls.*')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = SubCpl::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('prodiadmin.e_subcpl', compact('objek', 'cpel'));
    }

    public function u_subcpl(Request $request, $id)
    {
        $objek = SubCpl::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('prodiadmin/t_subcpl');
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
    $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'matkuls.prodi_id')
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->get();

    $mkscp = DB::table('mkscpls')
        ->join('subcpls','subcpls.id', '=', 'mkscpls.subcpl_id')
        ->join('matkuls','matkuls.id', '=', 'mkscpls.matkul_id')
        ->join('cpls','cpls.id', '=', 'subcpls.cpl_id')
        ->join('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
        ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'cpls.kode_cpl', 'subcpls.kode_subcpl')
        ->groupBy('matkuls.kode_mk', 'matkuls.nama_mk', 'cpls.kode_cpl', 'subcpls.kode_subcpl')
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

        $scp = DB::table('subcpls')
            ->leftjoin('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
            ->leftjoin('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->leftjoin('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'cpls.prodi_id')
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



    public function t_pengesah()
    {
        $kajur = DB::table('kajurs')
            ->join('dosens','dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('admprodis', 'admprodis.prodi_id', '=', 'dosens.prodi_id')
            ->join('users','users.id', '=', 'dosens.user_id')
            ->select('kajurs.*', 'prodis.nama_prodi', 'users.name as nama_dosen', 'dosens.nip', 'dosens.nidn')
            ->get();

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

}
