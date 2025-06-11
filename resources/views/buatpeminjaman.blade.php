@extends('layouts.app')


@section('content')
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Oke'
        });
    </script>
@endif

<div class="min-h-screen flex justify-center items-center px-4">
  <form action="{{ route('listpeminjaman.store') }}" method="POST" class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-8 w-full max-w-5xl">
    @csrf
    <h2 class="text-2xl font-bold text-[#193048] mb-6">Form Peminjaman</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <!-- Kiri -->
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Acara</label>
          <input required type="text" name="nama_acara" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Acara</label>
          <input required type="text" name="lokasi_acara" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Awal</label>
          <input required  type="date" name="tanggal_pinjam" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Peminjaman Akhir</label>
          <input required  type="date" name="tanggal_kembali" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
      </div>

      <!-- Tengah -->
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Awal Jam Pinjam</label>
          <input required  type="time" name="awal_jam_pinjem" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Akhir Jam Pinjam</label>
          <input required  type="time" name="akhir_jam_pinjem" class="w-full px-4 py-2 rounded bg-gray-100 focus:outline-none">
        </div>
      </div>

      <!-- Kanan: Tabel Barang -->
      <div class="col-span-1 md:col-span-1">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Barang yang ingin dipinjam</label>
        <table class="w-full text-sm border border-black rounded">
          <thead>
            <tr class="bg-gray-200">
              <th class="border px-2 py-1">Nama Barang</th>
              <th class="border px-2 py-1">Jumlah</th>
            </tr>
          </thead>
          <tbody id="barang-list">
            <tr>
              <td class="border p-1">
                <select name="barang_id[]" required class="w-full bg-white rounded px-2 py-1 border">
                  @foreach ($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->item }}</option>
                  @endforeach
                </select>
              </td>
              <td class="border p-1">
                <input type="number" name="jumlah[]" required min="1" class="w-full px-2 py-1 rounded bg-gray-100">
              </td>
            </tr>
          </tbody>
        </table>

        <button type="button" onclick="tambahBarang()" class="mt-2 text-sm text-blue-600 hover:underline">+ Tambah Barang</button>
      </div>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-4 mt-4">
      <a href="{{ route('home') }}" class="px-6 py-2 bg-red-500 text-white rounded-full hover:bg-red-600">Batal</a>
      <button type="submit" class="px-6 py-2 bg-[#3B9BC8] text-white rounded-full hover:bg-[#338AB0]">Simpan</button>
    </div>
  </form>
</div>

<script>
  function tambahBarang() {
    const row = `
    <tr>
      <td class="border p-1">
        <select name="barang_id[]" required  class="w-full bg-white rounded px-2 py-1 border">
          @foreach ($barangs as $barang)
            <option value="{{ $barang->id }}">{{ $barang->item }}</option>
          @endforeach
        </select>
      </td>
      <td class="border p-1">
        <input type="number" name="jumlah[]" required  min="1" class="w-full px-2 py-1 rounded bg-gray-100">
      </td>
    </tr>`;
    document.getElementById('barang-list').insertAdjacentHTML('beforeend', row);
  }
</script>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Oke'
        });
    </script>
@endif

@endsection
