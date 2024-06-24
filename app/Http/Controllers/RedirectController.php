<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function cek() {
        if (auth()->user()->role_id === 1) {
            return redirect('/superadmin');
        }
        if (auth()->user()->role_id === 2) {
            return redirect()->intended('/dekanatadmin');
        }
        if (auth()->user()->role_id === 3) {
            return redirect()->intended('/prodiadmin');
        }
        if (auth()->user()->role_id === 4) {
            return redirect()->intended('/dosen');
        }
        if (auth()->user()->role_id === 5) {
            return redirect()->intended('/pegawai1');
        }
        else {
            return redirect('/pegawai2');
        }
    }
}
