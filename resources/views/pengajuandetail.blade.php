@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 bg-white rounded-xl shadow mt-16">
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Kolom 1: Informasi Peminjam -->
        <div class="space-y-3 p-4 bg-gray-50 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold border-b pb-2 text-gray-800">Informasi Peminjam</h3>
            <p><span class="font-medium text-gray-700">Nama:</span> {{ $pengajuan->user->name }}</p>
            <p><span class="font-medium text-gray-700">NRP/NIP:</span> {{ $pengajuan->user->nrp ?? $pengajuan->user->nip }}</p>
            <p><span class="font-medium text-gray-700">Kontak:</span> {{ $pengajuan->user->no_hp }}</p>
        </div>

        <!-- Kolom 2: Info Acara & Jadwal Peminjaman -->
        <div class="space-y-3 p-4 bg-gray-50 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold border-b pb-2 text-gray-800">Detail Acara</h3>
            <p><span class="font-medium text-gray-700">Nama Acara:</span> {{ $pengajuan->nama_acara }}</p>
            <p><span class="font-medium text-gray-700">Lokasi:</span> {{ $pengajuan->lokasi_acara }}</p>

            <h4 class="font-semibold mt-4 text-gray-800">Jadwal Peminjaman:</h4>
            <p><span class="font-medium text-gray-700">Tanggal Mulai:</span> {{ \Carbon\Carbon::parse($pengajuan->tanggal_pinjam)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
            <p><span class="font-medium text-gray-700">Tanggal Kembali:</span> {{ \Carbon\Carbon::parse($pengajuan->tanggal_kembali)->locale('id')->isoFormat('D MMMM YYYY') }}</p>

            @if(!empty($pengajuan->daily_times))
                <h4 class="font-semibold mt-4 text-gray-800">Waktu Harian:</h4>
                <ul class="list-disc list-inside text-gray-700">
                    @php
                        // Ensure daily_times is treated as an array even if it's a string from DB (JSON)
                        $dailyTimes = is_string($pengajuan->daily_times) ? json_decode($pengajuan->daily_times, true) : $pengajuan->daily_times;
                    @endphp
                    @foreach($dailyTimes as $date => $times)
                        <li>
                            {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY') }}: {{ $times['start_time'] }} - {{ $times['end_time'] }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600 italic">Tidak ada detail waktu harian spesifik.</p>
            @endif

            <p class="mt-4"><span class="font-medium text-gray-700">Status Pengajuan:</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $pengajuan->status === 'disetujui' ? 'bg-green-100 text-green-800' :
                       ($pengajuan->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($pengajuan->status) }}
                </span>
            </p>
            <p class="text-sm text-gray-600 italic"><span class="font-medium">Diajukan pada:</span> {{ \Carbon\Carbon::parse($pengajuan->created_at)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
        </div>

        <!-- Kolom 3: Barang & Aksi -->
        <div class="space-y-4 p-4 bg-gray-50 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold border-b pb-2 text-gray-800">Detail Barang</h3>
            <div class="overflow-x-auto rounded-md border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengajuan->details as $detail)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $detail->barang->item }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $detail->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action buttons for admin --}}
            @if(auth()->check() && auth()->user()->role && auth()->user()->role->role === 'admin' && $pengajuan->status === 'menunggu')
            <div class="flex flex-col md:flex-row gap-3 mt-4">
                <form method="POST" action="{{ route('pengajuan.decline', $pengajuan->id) }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out flex items-center justify-center gap-2 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Tolak
                    </button>
                </form>
                <form method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" class="w-full">
                    @csrf
                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition duration-300 ease-in-out flex items-center justify-center gap-2 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Setujui
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = this.action.includes('approve') ? 'menyetujui' : 'menolak';
            Swal.fire({
                title: `Apakah Anda yakin ingin ${action} pengajuan ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Ya, ${action}`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endsection
