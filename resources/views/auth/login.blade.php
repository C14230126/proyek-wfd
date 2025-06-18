@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center">
    <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="backdrop-blur-sm bg-white/90 rounded-2xl shadow-lg p-12 w-full max-w-2xl mt-10 min-h-[500px] flex flex-col justify-center">
        @csrf

        <h2 class="text-3xl font-bold text-left text-[#193048] mb-8">Login</h2>

        <div class="space-y-6">
            <div>
                <label for="email" class="block text-base font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 rounded bg-gray-100 focus:outline-none" placeholder="Email">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-base font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded bg-gray-100 focus:outline-none" placeholder="Password">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" onclick="validateForm()" class="w-full bg-[#3B9BC8] text-white py-3 rounded-full hover:bg-[#338AB0] transition">
                Login
            </button>
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-[#3B9BC8] hover:underline">Daftar di sini</a></p>
        </div>
    </form>
</div>

<script>
    function validateForm() {

        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('loginForm');

        let errors = []; 

        // --- Validasi Email ---
        if (emailInput.value.trim() === '') {
            errors.push('Email tidak boleh kosong.');
        } else if (!isValidEmail(emailInput.value.trim())) {
            errors.push('Format email tidak valid.');
        }

        // --- Validasi Password ---
        if (passwordInput.value.trim() === '') {
            errors.push('Password tidak boleh kosong.');
        }

        if (errors.length > 0) {
            Swal.fire({
                icon: 'error', 
                title: 'Validasi Gagal!', 
                html: errors.join('<br>'), 
                showConfirmButton: true, 
            });
        } else {
            loginForm.submit();
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email); 
    }
</script>
@endsection