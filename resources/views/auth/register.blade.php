@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
  <div class="backdrop-blur-sm bg-white/90 rounded-2xl shadow-lg p-10 w-full max-w-4xl mt-10">
    <h2 class="text-3xl font-bold text-left text-[#193048] mb-8">Register</h2>

    <form action="/register" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
      @csrf

      <!-- Nama -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" class="w-full px-4 py-3 bg-gray-100 rounded-2xl focus:outline-none" placeholder="Nama Lengkap">
      </div>

      <!-- No HP -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Nomor HP</label>
        <input type="text" name="phone" class="w-full px-4 py-3 rounded-2xl bg-white border border-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-400" placeholder="08xxxxxxxxx">
      </div>

      <!-- NRP -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">NRP</label>
        <input type="text" name="nrp" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="Cxxxxxxx">
      </div>

      <!-- Jurusan -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Jurusan</label>
        <input type="text" name="jurusan" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" value="Jurusan" readonly>
        <p class="text-xs text-gray-500 mt-1">*Note: Jurusan akan terisi otomatis berdasarkan NRP</p>
      </div>

      <!-- Password -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Password</label>
        <input type="password" name="password" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="**********">
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block font-semibold text-gray-700 mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-2xl bg-gray-100 focus:outline-none" placeholder="**********">
      </div>

      <!-- Tombol Register -->
      <div class="md:col-span-2 flex justify-center mt-4">
        <button type="submit" class="bg-[#3B9BC8] text-white py-3 px-10 rounded-full hover:bg-[#338AB0] transition">
          Register
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
