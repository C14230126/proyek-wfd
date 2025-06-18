@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="backdrop-blur-sm bg-white/90 rounded-2xl shadow-lg p-10 w-full max-w-4xl mt-10">
        <h2 class="text-3xl font-bold text-left text-[#193048] mb-8">Register</h2>
        <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            @csrf
            <div>
                <label for="name" class="block font-semibold text-gray-700 mb-1">Nama</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-3 bg-gray-100 rounded-2xl focus:outline-none" placeholder="Nama Lengkap" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="no_hp" class="block font-semibold text-gray-700 mb-1">Nomor HP</label>
                <input type="text" id="no_hp" name="no_hp" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="08xxxxxxxxx" value="{{ old('no_hp') }}">
                @error('no_hp')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nrp" class="block font-semibold text-gray-700 mb-1">NRP</label>
                <input type="text" id="nrp" name="nrp" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="Cxxxxxxx" value="{{ old('nrp') }}">
                @error('nrp')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="jurusan" class="block font-semibold text-gray-700 mb-1">Jurusan</label>
                <input type="text" id="jurusan" name="jurusan" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" value="{{ old('jurusan', '') }}" readonly>
                <p class="text-xs text-gray-500 mt-1">*Note: Jurusan akan terisi otomatis berdasarkan NRP</p>
            </div>

            <div>
                <label for="email" class="block font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-gray-100 rounded-2xl focus:outline-none" placeholder="email@example.com" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="**********">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="**********">
                @error('password_confirmation')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2 flex justify-center mt-4">
                <button type="submit" class="bg-[#3B9BC8] text-white py-3 px-10 rounded-full hover:bg-[#338AB0] transition">
                    Register
                </button>
            </div>

            <div class="md:col-span-2 text-center mt-4">
                <p class="text-sm text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="text-[#3B9BC8] hover:underline">Login di sini</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nrpInput = document.getElementById('nrp');
        const jurusanInput = document.getElementById('jurusan');

        if (nrpInput && jurusanInput) {
            nrpInput.addEventListener('input', function() {
                const nrpValue = this.value.trim().toUpperCase();
                let jurusan = '';

                if (nrpValue.length >= 1) {
                    const firstChar = nrpValue.substring(0, 1);

                    switch (firstChar) {
                        case 'A':
                        case 'E':
                        case 'F':
                            jurusan = 'Humaniora dan Industri Kreatif';
                            break;
                        case 'B':
                            jurusan = 'Teknik Sipil & Perencanaan';
                            break;
                        case 'C':
                            jurusan = 'Teknologi Industri';
                            break;
                        case 'D':
                            jurusan = 'School of Business and Management';
                            break;
                        case 'G':
                            jurusan = 'Ilmu Pendidikan';
                            break;
                        case 'H':
                            jurusan = 'Humaniora dan Industri Kreatif';
                            break;
                        default:
                            jurusan = 'Tidak Dikenal';
                    }
                }
                jurusanInput.value = jurusan;
            });
        }
    });
</script>
@endsection