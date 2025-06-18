@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 bg-white rounded-xl shadow mt-16 mb-8">
    <h2 class="text-xl font-bold mb-4">Nama Kegiatan</h2>
    @foreach($pengajuans as $item)
    <div class="flex justify-between items-center bg-gray-200 px-4 py-3 rounded-2xl mb-2">
        <span class="text-lg font-semibold">{{ $item->nama_acara }}</span>
        <a href="{{ route('pengajuan.show', $item->id) }}" 
        class="bg-white text-black px-4 py-1 rounded-full shadow-sm hover:bg-gray-100 transition">
            Tinjau
        </a>
    </div>
    @endforeach
</div>
@endsection
