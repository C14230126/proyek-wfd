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
        $messages = [
            'name.required' => 'Nama belum diisi.',
            'name.regex' => 'Nama hanya boleh mengandung huruf.',
            'no_hp.required' => 'Nomor HP belum diisi.',
            'no_hp.digits_between' => 'Nomor HP harus antara 9 sampai 15 digit.',
            'no_hp.numeric' => 'Nomor HP harus berupa angka.',
            'nrp.required' => 'NRP belum diisi.',
            'nrp.unique' => 'NRP sudah terdaftar.',
            'jurusan.required' => 'Jurusan belum diisi.',
            'email.required' => 'Email belum diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password belum diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi Password tidak sama.',
        ];

        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'no_hp' => 'required|numeric|digits_between:9,15',
            'nrp' => 'required|string|max:20|unique:users',
            'jurusan' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], $messages);

        $user = User::create([
            'name' => $request->name,
            'no_hp' => $request->no_hp,
            'nrp' => $request->nrp,
            'jurusan' => $request->jurusan,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'Requesting',
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang menunggu persetujuan admin.');
    }

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

            if (Auth::user()->status === 'Requesting') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui. Mohon tunggu verifikasi admin.',
                ])->onlyInput('email')
                    ->with('error', 'Login gagal! Akun Anda memerlukan persetujuan admin.');
            }

            return redirect()->intended('/')->with('success', 'Berhasil login! Welcome ' . Auth::user()->name . '.');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->onlyInput('email')
            ->with('error', 'Login gagal! Mohon periksa kembali email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
