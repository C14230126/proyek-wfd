@extends('layouts.app')

@section('content')
<div class="mt-14 mb-10 flex justify-center" x-data="{ openAddModal: false, openEditModal: false, selectedId: '', selectedJumlah: '' }">
    <div class="bg-white rounded-2xl p-6 w-[1400px] shadow-md">
        <h2 class="text-2xl font-semibold mb-6">List Barang</h2>

        @foreach ($barangs as $barang)
            <div class="flex justify-between items-center bg-gray-100 rounded-2xl px-6 py-4 mb-4">
                <span class="text-lg font-medium">{{ $barang->item }}</span>
                <span class="text-lg font-semibold text-gray-700">Sisa {{ $barang->jumlah_unit }}</span>
            </div>
        @endforeach

        @php
            $isMahasiswa = strtolower(Auth::user()->role->role) === 'mahasiswa';
        @endphp

        <div class="flex justify-end mt-4 gap-2">
            @unless($isMahasiswa)
                <button @click="openEditModal = true"
                    class="bg-white border border-black rounded px-4 py-2 text-sm hover:bg-blue-600 hover:text-white transition">
                    Edit Barang
                </button>
                <button @click="openAddModal = true"
                    class="bg-white border-2 border-black rounded-full w-10 h-10 flex items-center justify-center text-2xl hover:bg-black hover:text-white transition">
                    +
                </button>
            @endunless
        </div>
    </div>

    <!-- Modal Edit Barang -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
        <div @click.outside="openEditModal = false" class="bg-white p-6 rounded-xl w-full max-w-md shadow-xl">
            <h3 class="text-xl font-bold mb-4">Edit Jumlah Barang</h3>
            <form :action="`/listbarang/${selectedId}`" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold">Pilih Barang</label>
                    <select x-model="selectedId" @change="selectedJumlah = $event.target.options[$event.target.selectedIndex].dataset.jumlah" name="id" class="w-full px-4 py-2 rounded bg-gray-100" required>
                        <option value="" disabled selected>Pilih barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-jumlah="{{ $barang->jumlah_unit }}">{{ $barang->item }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" x-model="selectedJumlah" min="0" class="w-full px-4 py-2 rounded bg-gray-100 border" required>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div @click.outside="openAddModal = false" class="bg-white p-6 rounded-xl w-full max-w-lg shadow-lg">
            <h3 class="text-xl font-bold mb-4">Tambah Barang</h3>
            <form action="{{ route('listbarang.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold">Nama Item</label>
                    <input type="text" name="item" class="w-full px-4 py-2 rounded bg-gray-100" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold">Jumlah Unit</label>
                    <input type="number" name="jumlah_unit" min="0" class="w-full px-4 py-2 rounded bg-gray-100" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold">Lokasi</label>
                    <input type="text" name="lokasi" class="w-full px-4 py-2 rounded bg-gray-100" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold">Kategori</label>
                    <select name="kategori_id" class="w-full px-4 py-2 rounded bg-gray-100" required>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
