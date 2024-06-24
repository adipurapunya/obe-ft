<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\DekanatController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\ProdiController;
use App\Models\Mkcpmk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


Route::group(['middleware' => 'guest'], function() {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'dologin']);

});

Route::group(['middleware' => ['auth', 'checkrole:1,2,3,4,5,6']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/redirect', [RedirectController::class, 'cek']);
});


Route::group(['middleware' => ['auth', 'checkrole:1']], function() {
    Route::get('/superadmin', [SuperadminController::class, 'index']);
    Route::get('/superadmin/t_listuser', [SuperadminController::class, 't_listuser']);
    Route::get('/superadmin/create_user', [SuperadminController::class, 'create_user']);
    Route::post('/superadmin/simpanuser', [SuperadminController::class, 'simpanuser']);
    Route::get('/superadmin/e_listuser/{id}', [SuperadminController::class, 'e_listuser']);
    Route::put('/superadmin/u_listuser/{id}', [SuperadminController::class, 'u_listuser']);
    Route::get('/superadmin/h_user/{id}', [SuperadminController::class, 'h_user']);
    Route::get('/superadmin/import_user_form', [SuperadminController::class, 'showImportUserForm'])->name('import_user_form');
    Route::post('/superadmin/import_user', [SuperadminController::class, 'importUser'])->name('import_user');
    Route::get('/superadmin/download_template', function () {
        $path = storage_path('app/public/template_user.xlsx');
        return response()->download($path, 'template_user.xlsx');
    })->name('download_template');

    Route::get('/getUserName/{user_id}', [SuperadminController::class, 'getUserName']);


    Route::get('/superadmin/t_prodi', [SuperadminController::class, 't_prodi']);
    Route::get('/superadmin/a_prodi', [SuperadminController::class, 'a_prodi']);
    Route::post('/superadmin/s_prodi', [SuperadminController::class, 's_prodi']);
    Route::get('/superadmin/e_prodi/{id}', [SuperadminController::class, 'e_prodi']);
    Route::put('/superadmin/u_prodi/{id}', [SuperadminController::class, 'u_prodi']);
    Route::get('/superadmin/h_user/{id}', [SuperadminController::class, 'h_user']);

    Route::get('/superadmin/t_dosen', [SuperadminController::class, 't_dosen']);
    Route::get('/superadmin/a_dosen', [SuperadminController::class, 'a_dosen']);
    Route::post('/superadmin/s_dosen', [SuperadminController::class, 's_dosen']);
    Route::get('/superadmin/e_dosen/{id}', [SuperadminController::class, 'e_dosen']);
    Route::put('/superadmin/u_dosen/{id}', [SuperadminController::class, 'u_dosen']);
    Route::get('/superadmin/h_dosen/{id}', [SuperadminController::class, 'h_dosen']);

    Route::get('/superadmin/t_mahasiswa', [SuperadminController::class, 't_mahasiswa']);
    Route::get('/superadmin/a_mahasiswa', [SuperadminController::class, 'a_mahasiswa']);
    Route::post('/superadmin/s_mahasiswa', [SuperadminController::class, 's_mahasiswa']);
    Route::get('/superadmin/e_mahasiswa/{id}', [SuperadminController::class, 'e_mahasiswa']);
    Route::put('/superadmin/u_mahasiswa/{id}', [SuperadminController::class, 'u_mahasiswa']);
    Route::get('/superadmin/h_mahasiswa/{id}', [SuperadminController::class, 'h_mahasiswa']);

    Route::get('/superadmin/t_kurikulum', [SuperadminController::class, 't_kurikulum']);
    Route::get('/superadmin/a_kurikulum', [SuperadminController::class, 'a_kurikulum']);
    Route::post('/superadmin/s_kurikulum', [SuperadminController::class, 's_kurikulum']);
    Route::get('/superadmin/e_kurikulum/{id}', [SuperadminController::class, 'e_kurikulum']);
    Route::put('/superadmin/u_kurikulum/{id}', [SuperadminController::class, 'u_kurikulum']);
    Route::get('/superadmin/h_kurikulum/{id}', [SuperadminController::class, 'h_kurikulum']);

    Route::get('/superadmin/t_semester', [SuperadminController::class, 't_semester']);
    Route::get('/superadmin/a_semester', [SuperadminController::class, 'a_semester']);
    Route::post('/superadmin/s_semester', [SuperadminController::class, 's_semester']);
    Route::get('/superadmin/e_semester/{id}', [SuperadminController::class, 'e_semester']);
    Route::put('/superadmin/u_semester/{id}', [SuperadminController::class, 'u_semester']);
    Route::get('/superadmin/h_semester/{id}', [SuperadminController::class, 'h_semester']);

    Route::get('/superadmin/t_matkul', [SuperadminController::class, 't_matkul']);
    Route::get('/superadmin/a_matkul', [SuperadminController::class, 'a_matkul']);
    Route::post('/superadmin/s_matkul', [SuperadminController::class, 's_matkul']);
    Route::get('/superadmin/e_matkul/{id}', [SuperadminController::class, 'e_matkul']);
    Route::put('/superadmin/u_matkul/{id}', [SuperadminController::class, 'u_matkul']);
    Route::get('/superadmin/h_matkul/{id}', [SuperadminController::class, 'h_matkul']);

    Route::get('/superadmin/t_cpl', [SuperadminController::class, 't_cpl']);
    Route::get('/superadmin/a_cpl', [SuperadminController::class, 'a_cpl']);
    Route::post('/superadmin/s_cpl', [SuperadminController::class, 's_cpl']);
    Route::get('/superadmin/e_cpl/{id}', [SuperadminController::class, 'e_cpl']);
    Route::put('/superadmin/u_cpl/{id}', [SuperadminController::class, 'u_cpl']);
    Route::get('/superadmin/h_cpl/{id}', [SuperadminController::class, 'h_cpl']);

    Route::get('/superadmin/t_cpmk', [SuperadminController::class, 't_cpmk']);
    Route::get('/superadmin/a_cpmk', [SuperadminController::class, 'a_cpmk']);
    Route::post('/superadmin/s_cpmk', [SuperadminController::class, 's_cpmk']);
    Route::get('/superadmin/e_cpmk/{id}', [SuperadminController::class, 'e_cpmk']);
    Route::put('/superadmin/u_cpmk/{id}', [SuperadminController::class, 'u_cpmk']);
    Route::get('/superadmin/h_cpmk/{id}', [SuperadminController::class, 'h_cpmk']);

    Route::get('/superadmin/t_subcpmk', [SuperadminController::class, 't_subcpmk']);
    Route::get('/superadmin/a_subcpmk', [SuperadminController::class, 'a_subcpmk']);
    Route::post('/superadmin/s_subcpmk', [SuperadminController::class, 's_subcpmk']);
    Route::get('/superadmin/e_subcpmk/{id}', [SuperadminController::class, 'e_subcpmk']);
    Route::put('/superadmin/u_subcpmk/{id}', [SuperadminController::class, 'u_subcpmk']);
    Route::get('/superadmin/h_subcpmk/{id}', [SuperadminController::class, 'h_subcpmk']);
});

Route::group(['middleware' => ['auth', 'checkrole:2']], function() {
    Route::get('/dekanatadmin', [DekanatController::class, 'index']);
});


// ADMIN PRODI
Route::group(['middleware' => ['auth', 'checkrole:3']], function() {
    Route::get('/prodiadmin', [ProdiController::class, 'index']);

    Route::get('/prodiadmin/t_kurikulum', [ProdiController::class, 't_kurikulum']);
    Route::get('/prodiadmin/a_kurikulum', [ProdiController::class, 'a_kurikulum']);
    Route::post('/prodiadmin/s_kurikulum', [ProdiController::class, 's_kurikulum']);
    Route::get('/prodiadmin/e_kurikulum/{id}', [ProdiController::class, 'e_kurikulum']);
    Route::put('/prodiadmin/u_kurikulum/{id}', [ProdiController::class, 'u_kurikulum']);
    Route::get('/prodiadmin/h_kurikulum/{id}', [ProdiController::class, 'h_kurikulum']);

    Route::get('/prodiadmin/t_dosen', [ProdiController::class, 't_dosen']);

    Route::get('/prodiadmin/t_matkul', [ProdiController::class, 't_matkul']);
    Route::get('/prodiadmin/a_matkul', [ProdiController::class, 'a_matkul']);
    Route::post('/prodiadmin/s_matkul', [ProdiController::class, 's_matkul']);
    Route::get('/prodiadmin/e_matkul/{id}', [ProdiController::class, 'e_matkul']);
    Route::put('/prodiadmin/u_matkul/{id}', [ProdiController::class, 'u_matkul']);
    Route::get('/prodiadmin/h_matkul/{id}', [ProdiController::class, 'h_matkul']);

    Route::get('/prodiadmin/t_cpl', [ProdiController::class, 't_cpl']);
    Route::get('/prodiadmin/a_cpl', [ProdiController::class, 'a_cpl']);
    Route::post('/prodiadmin/s_cpl', [ProdiController::class, 's_cpl']);
    Route::get('/prodiadmin/e_cpl/{id}', [ProdiController::class, 'e_cpl']);
    Route::put('/prodiadmin/u_cpl/{id}', [ProdiController::class, 'u_cpl']);
    Route::get('/prodiadmin/h_cpl/{id}', [ProdiController::class, 'h_cpl']);

    Route::get('/prodiadmin/t_subcpl', [ProdiController::class, 't_subcpl']);
    Route::get('/prodiadmin/a_subcpl', [ProdiController::class, 'a_subcpl']);
    Route::post('/prodiadmin/s_subcpl', [ProdiController::class, 's_subcpl']);
    Route::get('/prodiadmin/e_subcpl/{id}', [ProdiController::class, 'e_subcpl']);
    Route::put('/prodiadmin/u_subcpl/{id}', [ProdiController::class, 'u_subcpl']);
    Route::get('/prodiadmin/h_subcpl/{id}', [ProdiController::class, 'h_subcpl']);

    Route::get('/prodiadmin/t_mksubcpl', [ProdiController::class, 't_mksubcpl']);
    Route::get('/prodiadmin/a_mksubcpl/{id}', [ProdiController::class, 'a_mksubcpl']);
    Route::post('/prodiadmin/s_mksubcpl', [ProdiController::class, 's_mksubcpl']);
    Route::get('/prodiadmin/e_mksubcpl/{id}', [ProdiController::class, 'e_mksubcpl']);
    Route::put('/prodiadmin/u_mksubcpl/{id}', [ProdiController::class, 'u_mksubcpl']);
    Route::get('/prodiadmin/h_mksubcpl/{id}', [ProdiController::class, 'h_mksubcpl']);
    Route::get('/prodiadmin/form', 'ProdiController@a_mksubcpl')->name('form');
    Route::get('get/subcpls/by/cpl', [ProdiController::class, 'getSubcplsByCpl'])->name('get.subcpls.by.cpl');
    Route::post('submit-selected-subcpls', 'ProdiController@saveSelectedSubcpls')->name('submit.selected.subcpls');
    Route::post('/simpan-selected-subcpls', [ProdiController::class, 'simpanSelectedSubcpls'])->name('simpan.selected.subcpls');
    Route::get('get/subcpl/details', [ProdiController::class, 'getSubcplDetails'])->name('get.subcpl.details');

    Route::post('/prodiadmin/processForm', 'ProdiController@processForm')->name('process.form');

    Route::get('/prodiadmin/t_pengesah', [ProdiController::class, 't_pengesah']);
    Route::get('/prodiadmin/a_pengesah', [ProdiController::class, 'a_pengesah']);
    Route::post('/prodiadmin/s_pengesah', [ProdiController::class, 's_pengesah']);

    Route::get('/getDosenData/{dosen_id}', [ProdiController::class, 'getDosenData']);

});


// DOSEN
Route::group(['middleware' => ['auth', 'checkrole:4']], function() {
    Route::get('/dosen', [DosenController::class, 'index']);
    Route::get('/dosen/t_cpl', [DosenController::class, 't_cpl']);
    Route::get('/dosen/t_subcpl', [DosenController::class, 't_subcpl']);

    Route::get('/dosen/t_cpmk', [DosenController::class, 't_cpmk']);
    Route::get('/dosen/a_cpmk', [DosenController::class, 'a_cpmk']);

    Route::get('/dosen/t_ampu', [DosenController::class, 't_ampu']);

    Route::get('/dosen/t_mkcpl', [DosenController::class, 't_mkcpl']);
    Route::get('/dosen/a_mkcpl', [DosenController::class, 'a_mkcpl']);
    Route::post('/dosen/s_mkcpl', [DosenController::class, 's_mkcpl']);

    Route::get('/dosen/t_mkcpmk', [DosenController::class, 't_mkcpmk'])->name('dosen.t_mkcpmk');
    Route::get('/dosen/a_mkcpmk/{matkul_id}', [DosenController::class, 'a_mkcpmk']);
    Route::post('/dosen/s_mkcpmk/{matkulId}', [DosenController::class, 's_mkcpmk']);

    Route::get('/dosen/t_mkscpmk', [DosenController::class, 't_mkscpmk'])->name('dosen.t_mkscpmk');
    Route::get('/dosen/a_mkscpmk/{matkul_id}', [DosenController::class, 'a_mkscpmk']);
    Route::post('/dosen/s_mkscpmk/{matkul_id}', [DosenController::class, 's_mkscpmk']);

    Route::get('/dosen/t_rps', [DosenController::class, 't_rps'])->name('dosen.rps');
    Route::get('/dosen/a_rps/{matkul_id}', [DosenController::class, 'a_rps']);
    Route::post('/dosen/s_rps/{matkul_id}', [DosenController::class, 's_rps']);

    // Route::get('/get-subcpl-description', 'DosenController@getSubcplDescription');
    Route::get('/subcpls/{matkulId}', [DosenController::class, 'getSubcpl'])->name('subcpls.get');
    Route::get('/subcpls/details/{id}', [DosenController::class, 'getSubcplDetail'])->name('subcpls.details');

    Route::get('/get-desk-cpmk/{matkul_id}/{kode_cpmk}', [DosenController::class, 'getDeskCpmk'])->name('mkcpmks.details');
    Route::get('/get-existing-subcpmks/{matkul_id}/{kode_cpmk}', [DosenController::class, 'getExistingSubcpmks']);
    Route::post('/generate-next-cpmk', [DosenController::class, 'generateNextCpmk']);


    Route::get('/dosen/t_tarcpmk', [DosenController::class, 't_tarcpmk']);
    Route::get('/dosen/a_tarcpmk/{matkul_id}', [DosenController::class, 'a_tarcpmk']);
    Route::post('/dosen/s_tarcpmk/{matkul_id}', [DosenController::class, 's_tarcpmk']);
    Route::get('/cpmk/description/{kode_cpmk}', [DosenController::class, 'getCpmkDescription']);

    Route::get('/dosen/t_inpnilai', [DosenController::class, 't_inpnilai']);
    Route::get('/dosen/a_inpnilai/{kelas_id}', [DosenController::class, 'a_inpnilai']);
    Route::post('/dosen/s_inpnilai', [DosenController::class, 's_inpnilai']);
    Route::get('/dosen/t_nilai', [DosenController::class, 't_nilai']);

});
