<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function handleRegister(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'no_hp' => $request->phone,
            'nrp' => $request->nrp,
            'jurusan' => $request->jurusan,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'Requesting', // Status awal saat pendaftaran
        ]);
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat.');
    }

    //$prefix = substr($validated['nrp'], 0, 2);
    //$jurusan = match($prefix) {
    //'11' => 'Informatika',
    //'22' => 'Sistem Informasi',
    //'33' => 'Desain Komunikasi Visual',
    //default => 'Tidak Dikenal',
    //};

    //}

    public function login()
    {
        return view('auth.login');
    }


    public function register()
    {
        return view('auth.register');
    }


    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek apakah status user bukan 'Requesting'
            if (Auth::user()->status !== 'Requesting') {
                return redirect()->intended('/'); // Lanjut ke halaman utama
            }

            // Jika status 'Requesting', logout & beri pesan error
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda sedang dalam proses verifikasi.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
