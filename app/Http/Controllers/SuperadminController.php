<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Kurikulum;
use App\Models\Semester;
use App\Models\Matkul;
use App\Imports\UserImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use Maatwebsite\Excel\Facades\Excel;



class SuperadminController extends Controller
{
    public function index() {
        return view('superadmin.index');
    }

    public function t_listuser(Request $request)
    {
        $data = DB::table('users')
        ->join('roles','roles.id', '=', 'users.role_id')
        ->select('users.*', 'roles.role_name')
        ->orderBy('created_at', 'desc')
        ->get();

        return view ('superadmin.t_listuser', compact('data'));
    }

    public function create_user()
    {
        $rolesToAvoid = ['pegawai1','pegawai2'];
        $roles = Role::all()->filter(function ($role) use ($rolesToAvoid) {
            return !in_array($role->name, $rolesToAvoid);
        });
        return view('superadmin.create_user', compact('roles'));
    }

    public function simpanuser(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'role_id' => 'required'
        ]);

        $rememberToken = Str::random(40);
        $encryptedToken = Crypt::encryptString($rememberToken);

        $data = [
            'name' => Request()->name,
            'email' => Request()->email,
            'password' => Hash::make(Request()->password),
            'role_id' => Request()->role_id,
            'remember_token' => $encryptedToken
        ];

        $data = DB::table('users')->insert($data);

        return redirect('superadmin/t_listuser');
    }

    public function e_listuser($id)
    {
        $roles = Role::whereNotIn('id', [5, 6])->get();

        $data = DB::table('users')
        ->join('roles','roles.id', '=', 'users.role_id')
        ->select('users.*', 'roles.role_name')
        ->orderBy('created_at', 'desc')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = User::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_listuser', compact('data', 'objek', 'roles'));
    }

    public function u_listuser(Request $request, $id)
    {
        $objek = User::findOrFail($id);
        $data = $request->except('_token', '_method');
            if(empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }
        $objek->update ($data);
        $data['objek'] = $objek;

        return redirect('superadmin/t_listuser')
                        ->with('success','User updated successfully');
    }

    public function h_user($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = User::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_listuser');
    }

    public function showImportUserForm()
    {
        return view('superadmin.import_user');
    }

    public function importUser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new UserImport, $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor file Excel: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Data user berhasil diimpor.');
    }


    public function t_prodi()
    {
        $data = [
            'prod' => Prodi::all(),
        ];

        return view('superadmin/t_prodi', $data);
    }

    public function a_prodi()
    {
        return view('superadmin/a_prodi');
    }

    public function s_prodi(Request $request)
    {
        $this->validate($request,[
            'kopro' => 'required',
            'nama_prodi' => 'required',
            'inisial' => 'required',
            'jenjang' => 'required',
            ],
            [
                'kopro.required' => 'Kode Prodi tidak boleh kosong',
                'nama_prodi.required' => 'Nama Prodi tidak boleh kosong',
                'inisial.required' => 'Inisial tidak boleh kosong',
                'jenjang.required' => 'Jenjang harus dipilih'
         ]);

         $data = [
            'kopro' => Request()->kopro,
            'nama_prodi' => Request()->nama_prodi,
            'inisial' => Request()->inisial,
            'jenjang' => Request()->jenjang,
        ];

         $data = DB::table('prodis')->insert($data);
         return redirect('superadmin/t_prodi');
    }

    public function e_prodi($id)
    {
        $decryptID = Crypt::decryptString($id);
        $objek = Prodi::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_prodi', compact('objek'));
    }

    public function u_prodi(Request $request, $id)
    {
        $objek = Prodi::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('superadmin/t_prodi');
    }


    public function t_dosen()
    {
        $data = DB::table('dosens')
        ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
        ->join('users','users.id', '=', 'dosens.user_id')
        ->select('dosens.*', 'prodis.nama_prodi', 'users.name')
        ->orderBy('created_at', 'desc')
        ->get();

        return view ('superadmin.t_dosen', compact('data'));
    }

    public function a_dosen()
    {
        $prodi = Prodi::all();
        $users = User::all();

        return view('superadmin/a_dosen', compact('prodi', 'users'));
    }


    public function getUserName($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json(['nama_dosen' => $user->name]);
    }

    public function s_dosen(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'nidn' => 'required',
            'nip' => 'required',
            'prodi_id' => 'required',
        ]);

        $nama_dosen = DB::table('users')->where('id', $request->user_id)->value('name');

        Dosen::create([
            'user_id' => $request->user_id,
            'nidn' => $request->nidn,
            'nip' => $request->nip,
            // 'nama_dosen' => $nama_dosen,
            'prodi_id' => $request->prodi_id,
        ]);

        return redirect('superadmin/t_dosen');
    }

    public function e_dosen($id)
    {
        $prodi = Prodi::all();

        $data = DB::table('dosens')
        ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
        ->join('users','users.id', '=', 'dosens.user_id')
        ->select('dosens.*', 'prodis.nama_prodi')
        ->orderBy('created_at', 'desc')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Dosen::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_dosen', compact('objek','data', 'prodi'));
    }

    public function u_dosen(Request $request, $id)
    {
        $objek = Dosen::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('superadmin/t_dosen');
    }


    public function t_mahasiswa()
    {
        $data = DB::table('mahasiswas')
        ->join('prodis','prodis.id', '=', 'mahasiswas.prodi_id')
        // ->join('users','users.id', '=', 'dosens.user_id')
        ->select('mahasiswas.*', 'prodis.nama_prodi')
        ->orderBy('created_at', 'desc')
        ->get();

        return view ('superadmin.t_mahasiswa', compact('data'));
    }

    public function a_mahasiswa()
    {
        $prodi = Prodi::all();

        return view('superadmin/a_mahasiswa', compact('prodi'));
    }

    public function s_mahasiswa(Request $request)
    {
        $this->validate($request,[
            'nim' => 'required',
            'nama_mahasiswa' => 'required',
            'angkatan' => 'required',
            'smt_angkatan' => 'required',
            'prodi_id' => 'required'
            ],
            [
                'nim.required' => 'Kode Prodi tidak boleh kosong',
                'nama_mahasiswa.required' => 'Nama Prodi tidak boleh kosong',
                'angkatan.required' => 'Inisial tidak boleh kosong',
                'smt_angkatan.required' => 'Semester Penerimaan harus dipilih',
                'prodi_id.required' => 'Prodi harus dipilih'
         ]);

         $data = [
            'nim' => Request()->nim,
            'nama_mahasiswa' => Request()->nama_mahasiswa,
            'angkatan' => Request()->angkatan,
            'smt_angkatan' => Request()->smt_angkatan,
            'prodi_id' => Request()->prodi_id
        ];

         $data = DB::table('mahasiswas')->insert($data);
         return redirect('superadmin/t_mahasiswa');
    }

    public function e_mahasiswa($id)
    {
        $prodi = Prodi::all();

        $data = DB::table('mahasiswas')
        ->join('prodis','prodis.id', '=', 'mahasiswas.prodi_id')
        ->select('mahasiswas.*', 'prodis.nama_prodi')
        ->orderBy('created_at', 'desc')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Mahasiswa::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_mahasiswa', compact('objek','data','prodi'));
    }

    public function u_mahasiswa(Request $request, $id)
    {
        $objek = Mahasiswa::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('superadmin/t_mahasiswa');
    }


    public function a_kurikulum()
    {
        $prod = Prodi::all();
        return view ('superadmin/a_kurikulum', compact('prod'));
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
                // 'filesk' => 'required|mimes:jpg,jpeg,JPG,JPEG,png,PNG,pdf,PDF|max:6000'
            ],
            [
                'prodi_id.required' => 'Wajib dipilih !!!',
                'kode_kuri.required' => 'Wajib diisi !!!',
                'tahun_kuri.required' => 'Wajib diisi !!!',
                'nama_kuri.required' => 'Wajib diisi !!!',
                'deskripsi.required' => 'Wajib diisi !!!',
                'sk_kuri.required' => 'Wajib diisi !!!',
                // 'filesk.required' => 'Wajib diisi !!!, Foto Max 6000 KB, jenis PDF, JPG, PNG, JPEG',
            ]
        );

        $data = [
            // 'user_id' => $request->user()->id,
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
        return redirect('superadmin/t_kurikulum');
    }

    public function e_kurikulum($id)
    {
        $prodi = Prodi::all();

        $data = DB::table('kurikulums')
        ->join('prodis','prodis.id', '=', 'kurikulums.prodi_id')
        ->select('kurikulums.*', 'prodis.nama_prodi')
        ->orderBy('created_at', 'desc')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Kurikulum::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_kurikulum', compact('objek','data','prodi'));
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
        return redirect('superadmin/t_kurikulum');
    }

    public function t_kurikulum()
    {
        $kuri = DB::table('kurikulums')
        ->leftjoin('prodis','prodis.id', '=', 'kurikulums.prodi_id')
        ->select('kurikulums.*', 'prodis.nama_prodi')
        ->latest()->get();

        return view('superadmin/t_kurikulum', compact('kuri'));
    }


    public function t_semester()
    {
        $data = [
            'semes' => Semester::all(),
        ];

        return view('superadmin/t_semester', $data);
    }

    public function a_semester()
    {
        return view('superadmin/a_semester');
    }

    public function s_semester(Request $request)
    {
        $this->validate($request,[
            'nama_smtr' => 'required',
            'semester' => 'required',
            'status' => 'required',
            'tahun' => 'required'
            ],
            [
                'nama_smtr.required' => 'Kode Prodi tidak boleh kosong',
                'semester.required' => 'Semester harus dipilih',
                'status.required' => 'Status harus dipilih',
                'tahun.required' => 'Tahun tidak boleh kosong'
         ]);

         $data = [
            'nama_smtr' => Request()->nama_smtr,
            'semester' => Request()->semester,
            'status' => Request()->status,
            'tahun' => Request()->tahun,
            'keterangan' => Request()->keterangan
        ];

         $data = DB::table('semesters')->insert($data);
         return redirect('superadmin/t_semester');
    }

    public function e_semester($id)
    {
        $decryptID = Crypt::decryptString($id);
        $objek = Semester::findOrFail($decryptID)->where('id', $decryptID)->get();

        return view('superadmin.e_semester', compact('objek'));
    }

    public function u_semester(Request $request, $id)
    {
        $objek = Semester::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;
        return redirect('superadmin/t_semester');
    }


    public function t_matkul()
    {
        $matk = DB::table('matkuls')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        // ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
        ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr' )
        ->latest()->get();

        return view('superadmin/t_matkul', compact('matk'));
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

    public function t_cpl()
    {
        $cp = DB::table('cpls')
        ->leftjoin('prodis','prodis.id', '=', 'cpls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'cpls.kurikulum_id')
        ->select('cpls.*', 'prodis.kopro', 'kurikulums.nama_kuri')
        ->latest()->get();

        return view('superadmin/t_cpl', compact('cp'));
    }

    public function a_cpl()
    {
        $prod = Prodi::all();
        $kuri = Kurikulum::all();

        return view ('superadmin/a_cpl', compact('prod','kuri'));
    }

    public function s_cpl(Request $request)
    {
        $this->validate($request,[
            'prodi_id' => 'required',
            'kurikulum_id' => 'required',
            'kode_cpl' => 'required',
            'deskrip_cpl' => 'required'
            ],
            [
                'prodi_id.required' => 'Prodi harus dipilih',
                'kurikulum_id.required' => 'Kurikulum harus dipilih',
                'kode_cpl.required' => 'Kode CPL tidak boleh kosong',
                'deskrip_cpl.required' => 'Deskripsi CPL tidak boleh kosong'
         ]);

         $data = [
            'prodi_id' => Request()->prodi_id,
            'kurikulum_id' => Request()->kurikulum_id,
            'kode_cpl' => Request()->kode_cpl,
            'deskrip_cpl' => Request()->deskrip_cpl,
        ];

         $data = DB::table('cpls')->insert($data);
         return redirect('superadmin/t_cpl');
    }


    public function t_cpmk()
    {
        $cpm = DB::table('cpmks')
        ->leftjoin('matkuls','matkuls.id', '=', 'cpmks.matkul_id')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        // ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
        ->select('cpmks.*', 'matkuls.nama_mk', 'matkuls.kode_mk')
        ->latest()->get();

        return view('superadmin/t_cpmk', compact('cpm'));
    }

    public function a_cpmk()
    {
        $matk = DB::table('matkuls')
        ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
        ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
        ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
        ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
        // ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
        ->select('cpmks.*', 'matkuls.nama_mk', 'matkuls.kode_mk')
        ->latest()->get();


        return view('superadmin/a_cpmk', compact('matk','dosen','prodi_id'));
    }





}
