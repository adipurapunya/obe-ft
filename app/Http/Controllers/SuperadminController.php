<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;


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
use App\Models\Admprodi;
use App\Models\Cpl;
use App\Models\SubCpl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use Maatwebsite\Excel\Facades\Excel;



class SuperadminController extends Controller
{
    public function index() {
        $user = auth()->user();
        if ($user) {
            $roleName = $user->role->role_name;
        } else {
            $roleName = 'Unknown';
        }

        return view('superadmin.index', compact('roleName'));
    }

    //USER
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
        $roles = Role::whereNotIn('role_name', $rolesToAvoid)->get();
        return view('superadmin.create_user', compact('roles'));
    }

    public function simpanuser(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'nip' => 'required|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'role_id' => 'required'
        ]);

        $rememberToken = Str::random(40);
        $encryptedToken = Crypt::encryptString($rememberToken);

        $data = [
            'name' => Request()->name,
            'nip' => Request()->nip,
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
    //END USER


    //PRODI
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

    public function h_prodi($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Prodi::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_prodi');
    }
    //END PRODI


    //ADMIN PRODI
    public function t_adminprodi()
    {
        $data = DB::table('admprodis')
        ->join('prodis','prodis.id', '=', 'admprodis.prodi_id')
        ->join('users','users.id', '=', 'admprodis.user_id')
        ->select('admprodis.*', 'prodis.nama_prodi', 'users.name')
        ->orderBy('admprodis.created_at', 'desc')
        ->get();

        return view ('superadmin.t_adminprodi', compact('data'));
    }

    public function a_adminprodi()
    {
        $prodi = Prodi::all();
        $users = User::all();

        return view('superadmin/a_adminprodi', compact('prodi', 'users'));
    }


    public function getUserNameProdi($user_id)
    {
        $user = User::findOrFail($user_id);
        return response()->json(['nama_adminprodi' => $user->name]);
    }

    public function s_adminprodi(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
            'prodi_id' => 'required',
            'koprodi' => 'required',
        ]);

        $nama_dosen = DB::table('users')->where('id', $request->user_id)->value('name');

        Admprodi::create([
            'user_id' => $request->user_id,
            'prodi_id' => $request->prodi_id,
            'koprodi' => $request->koprodi,
        ]);

        return redirect('superadmin/t_adminprodi');
    }

    public function e_adminprodi($id)
    {
        $prodi = Prodi::all();
        $users = User::all();

        $data = DB::table('admprodis')
        ->join('prodis','prodis.id', '=', 'admprodis.prodi_id')
        ->join('users','users.id', '=', 'admprodis.user_id')
        ->select('admprodis.*', 'prodis.nama_prodi')
        ->orderBy('created_at', 'desc')
        ->get();

        $decryptID = Crypt::decryptString($id);
        $objek = Admprodi::with('prodi', 'user')->findOrFail($decryptID);

        return view('superadmin.e_adminprodi', compact('objek','data', 'prodi', 'users'));
    }

    public function u_adminprodi(Request $request, $id)
    {
        $objek = Admprodi::findOrFail($id);
        $data = array();
        $data = $request->except('_token', '_method');
        $objek->update ($data);
        $data['objek'] = $objek;

        // dd($request->all());
        return redirect('superadmin/t_adminprodi');
    }

    public function h_adminprodi($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Admprodi::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_adminprodi');
    }
    //END ADMIN PRODI


    //DOSEN
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

    public function h_dosen($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Dosen::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_dosen');
    }
    //END DOSEN


    //MAHASISWA
    public function t_mahasiswa()
    {
        $data = DB::table('mahasiswas')
        ->join('prodis','prodis.id', '=', 'mahasiswas.prodi_id')
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

    public function h_mahasiswa($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Mahasiswa::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_mahasiswa');
    }
    //END MAHASISWA


    //KURIKULUM
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
        $objek = Kurikulum::findOrFail($decryptID);

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

    public function h_kurikulum($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Kurikulum::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_kurikulum');
    }
    //END KURIKULUM


    //SEMESTER
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

    public function h_semester($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Semester::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_semester');
    }
    //END SEMESTER

    public function t_matkul()
    {

        $matk = DB::table('matkuls')
            ->leftJoin('prodis', 'prodis.id', '=', 'matkuls.prodi_id')
            ->leftJoin('kurikulums', 'kurikulums.id', '=', 'matkuls.kurikulum_id')
            ->leftJoin('semesters', 'semesters.id', '=', 'matkuls.semester_id')
            ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr')
            ->orderBy('nama_smtr', 'asc')->get();

        $prodiList = Prodi::pluck('nama_prodi', 'id');

        return view('superadmin/t_matkul', compact('matk', 'prodiList'));
    }

    public function filterMatkul(Request $request)
    {
        $validated = $request->validate([
            'prodi_id' => 'nullable|exists:prodis,id',
        ]);

        $prodi_id = $request->input('prodi_id');

        if ($prodi_id) {
            $matk = DB::table('matkuls')
                ->leftJoin('prodis', 'prodis.id', '=', 'matkuls.prodi_id')
                ->leftJoin('kurikulums', 'kurikulums.id', '=', 'matkuls.kurikulum_id')
                ->leftJoin('semesters', 'semesters.id', '=', 'matkuls.semester_id')
                ->when($prodi_id, function ($query, $prodi_id) {
                    return $query->where('matkuls.prodi_id', $prodi_id);
                })
                ->select('matkuls.*', 'prodis.kopro', 'kurikulums.nama_kuri', 'semesters.nama_smtr')
                ->latest()->get();

            } else {
                $matk = [];
            }

        $prodiList = Prodi::pluck('nama_prodi', 'id');

        return view('superadmin/t_matkul', compact('matk', 'prodiList'));
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

    public function t_cpl(Request $request)
    {
        $prodi_id = $request->prodi_id;

        if ($prodi_id) {
            $cp = DB::table('cpls')
                    ->leftJoin('prodis as p', 'p.id', '=', 'cpls.prodi_id')
                    ->leftJoin('kurikulums as k', 'k.id', '=', 'cpls.kurikulum_id')
                    ->select('cpls.*', 'p.kopro', 'k.nama_kuri')
                    ->where('cpls.prodi_id', $prodi_id)
                    ->get();
        } else {
            $cp = [];
        }

        $prodiList = Prodi::pluck('nama_prodi', 'id');

        return view('superadmin.t_cpl', compact('cp', 'prodiList'));
    }

    public function h_cpl($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = Cpl::find($decryptID);
        $data->delete();
        return redirect('/superadmin/t_cpl');
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


    // public function t_cpmk()
    // {
    //     $cpm = DB::table('cpmks')
    //     ->leftjoin('matkuls','matkuls.id', '=', 'cpmks.matkul_id')
    //     ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
    //     ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
    //     ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
    //     // ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
    //     ->select('cpmks.*', 'matkuls.nama_mk', 'matkuls.kode_mk')
    //     ->latest()->get();

    //     return view('superadmin/t_cpmk', compact('cpm'));
    // }

    // public function a_cpmk()
    // {
    //     $matk = DB::table('matkuls')
    //     ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
    //     ->leftjoin('prodis','prodis.id', '=', 'matkuls.prodi_id')
    //     ->leftjoin('kurikulums','kurikulums.id', '=', 'matkuls.kurikulum_id')
    //     ->leftjoin('semesters','semesters.id', '=', 'matkuls.semester_id')
    //     // ->leftjoin('dosens','dosens.id', '=', 'matkuls.dosen_id')
    //     ->select('cpmks.*', 'matkuls.nama_mk', 'matkuls.kode_mk')
    //     ->latest()->get();


    //     return view('superadmin/a_cpmk', compact('matk','dosen','prodi_id'));
    // }


    public function t_pengesah()
    {
        $kajur = DB::table('kajurs')
            ->join('dosens','dosens.id', '=', 'kajurs.dosen_id')
            ->join('prodis','prodis.id', '=', 'dosens.prodi_id')
            ->join('users','users.id', '=', 'dosens.user_id')
            ->select('kajurs.*', 'prodis.nama_prodi', 'users.name as nama_dosen', 'dosens.nip', 'dosens.nidn')
            ->get();

        return view ('superadmin.t_pengesah', compact('kajur'));

    }


    public function t_subcpl(Request $request)
    {
        $prodiList = Prodi::pluck('nama_prodi', 'id');

        $query = DB::table('subcpls')
            ->leftJoin('cpls', 'cpls.id', '=', 'subcpls.cpl_id')
            ->leftJoin('prodis', 'prodis.id', '=', 'cpls.prodi_id')
            ->leftJoin('kurikulums', 'kurikulums.id', '=', 'cpls.kurikulum_id')
            ->select('subcpls.*', 'kurikulums.nama_kuri', 'cpls.kode_cpl');

        if ($request->has('prodi_id') && !empty($request->prodi_id)) {
            $query->where('prodis.id', $request->prodi_id);
        } else {
            $scp = [];
            return view('superadmin/t_subcpl', compact('scp', 'prodiList'));
        }

        $scp = $query->get();
        return view('superadmin/t_subcpl', compact('scp', 'prodiList'));
    }

    public function h_subcpl($id)
    {
        $decryptID = Crypt::decryptString($id);
        $data = SubCpl::find($decryptID);
        $data->delete();
        return redirect()->back();
    }


    public function t_mkcpmk(Request $request)
    {
    $smtr = Semester::all();
    $encrypted_semester_id = $request->input('semester_id');
    $mata_kuliah = [];
    $mkcp = [];

    if ($encrypted_semester_id) {
        $semester_id = decrypt($encrypted_semester_id);

        $mata_kuliah_satu = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'prodis.kopro', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_dua = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_dua')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'prodis.kopro', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_tiga = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_tiga')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'prodis.kopro', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
                    'dosens.nidn', 'prodis.nama_prodi', 'semesters.keterangan', 'kelas.matkul_id');

        $mata_kuliah_empat = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_empat')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.nama_kelas', 'prodis.kopro', 'sks_teo', 'sks_kuri', 'sks_prak', 'sks_lap',
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
            // ->where('users.email', $user_email)
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'users.name as nama_dosen',
                    'kelas.matkul_id', 'kelas.nama_kelas','mkcpmks.kode_cpmk',
                    'mkcpmks.desk_cpmk','mkcpmks.id as mkcpmk_id')
            ->distinct()
            ->get();
    }

    return view('superadmin/t_mkcpmk', compact('mata_kuliah', 'smtr', 'encrypted_semester_id', 'mkcp'));
    }


    public function t_mkscpmk(Request $request)
    {
    $smtr = Semester::all();
    $encrypted_semester_id = $request->input('semester_id');

    $mkscp = [];
    $mksucp = [];

    if ($encrypted_semester_id) {
        $semester_id = decrypt($encrypted_semester_id);

        $mkscp = DB::table('kelas')
            ->join('matkuls', 'kelas.matkul_id', '=', 'matkuls.id')
            ->join('semesters', 'semesters.id', '=', 'kelas.semester_id')
            ->join('dosens', 'dosens.id', '=', 'kelas.dosen_satu')
            ->join('prodis', 'prodis.id', '=', 'dosens.prodi_id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'kelas.matkul_id', 'prodis.kopro')
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
            ->where('semesters.id', $semester_id)
            ->select('matkuls.kode_mk', 'matkuls.nama_mk', 'mkcpmks.kode_cpmk',
                    'mksubcpmks.kode_scpmk', 'mksubcpmks.desk_scpmk', 'mksubcpmks.id as mkscpmk_id')
            ->distinct()
            ->get();
    }

    return view('superadmin/t_mkscpmk', compact('mkscp', 'mksucp', 'smtr', 'encrypted_semester_id'));
    }





}
