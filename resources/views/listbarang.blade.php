@extends('layouts.app')

@section('content')
<div class="mt-14 mb-10 flex justify-center">
    <div class="bg-white rounded-2xl p-6 w-[1400px] shadow-md">
        <h2 class="text-2xl font-semibold mb-6">List Barang</h2>

        @foreach ($barangs as $barang)
            <div class="flex justify-between items-center bg-gray-100 rounded-2xl px-6 py-4 mb-4">
                <span class="text-lg font-medium">{{ $barang->item }}</span>
                <span class="text-lg font-semibold text-gray-700">Sisa {{ $barang->jumlah_unit }}</span>
            </div>
        @endforeach

        {{-- Tombol tambah --}}
        <div class="flex justify-end mt-4">
            <a href="#"
               class="bg-white border-2 border-black rounded-full w-10 h-10 flex items-center justify-center text-2xl hover:bg-black hover:text-white transition">
                +
            </a>
        </div>
    </div>
</div>
@endsection
