<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            'status' => 'Accepted',
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
            $request->session()->regenerate(); // untuk keamanan sesi
            return redirect()->intended('/'); // redirect ke halaman utama
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email')
            ->with('error', 'Login gagal! Mohon periksa kembali email/password Anda.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
