<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function dologin(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            $encryptedToken = $request->cookie('remember_token');

            try {
                $decryptedToken = Crypt::decryptString($encryptedToken);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

                return back()->with('error', 'Token tidak valid');
            }

            $user = User::where('remember_token', $decryptedToken)->first();


        if (auth()->attempt($credentials)) {

            $request->session()->regenerate();

            if (auth()->user()->role_id === 1) {
                return redirect()->intended('/superadmin');
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
                return redirect()->intended('/pegawai2');
            }

        } else {

            return redirect()->route('login')->with('error', 'Token tidak valid');
        }
        }

        return back()->with('error', 'email atau password salah');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
