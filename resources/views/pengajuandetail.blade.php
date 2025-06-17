@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 bg-white rounded-xl shadow mt-16">
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div>
            <p><strong>Nama Acara:</strong> {{ $pengajuan->nama_acara }}</p>
            <p><strong>Nama Peminjam:</strong> {{ $pengajuan->user->name }}</p>
            <p><strong>NRP:</strong> {{ $pengajuan->user->nrp }}</p>
        </div>
        <div>
            <p><strong>Tanggal Pinjam:</strong> {{ $pengajuan->tanggal_pinjam }}</p>
            <p><strong>Tanggal Kembali:</strong> {{ $pengajuan->tanggal_kembali }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pengajuan->status) }}</p>
        </div>
        <div>
            <p><strong>Barang yang ingin dipinjam:</strong></p>
            <table class="w-full border border-black mt-2">
                <thead><tr><th class="border px-2">Nama Barang</th><th class="border px-2">Jumlah</th></tr></thead>
                <tbody>
                @foreach($pengajuan->details as $detail)
                    <tr>
                        <td class="border px-2">{{ $detail->barang->item }}</td>
                        <td class="border px-2">{{ $detail->jumlah }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="flex gap-3 mt-4">
                <form method="POST" action="{{ route('pengajuan.decline', $pengajuan->id) }}">
                    @csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded-full">Decline</button>
                </form>
                <form method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}">
                    @csrf
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-full">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
