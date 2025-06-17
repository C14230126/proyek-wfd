@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto p-6 bg-white rounded-xl shadow mt-16">
    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Kolom 1: Info Peminjam -->
        <div class="space-y-3">
            <h3 class="text-lg font-semibold border-b pb-2">Informasi Peminjam</h3>
            <p><span class="font-medium">Nama:</span> {{ $pengajuan->user->name }}</p>
            <p><span class="font-medium">NRP/NIP:</span> {{ $pengajuan->user->nrp ?? $pengajuan->user->nip }}</p>
            <p><span class="font-medium">Kontak:</span> {{ $pengajuan->user->no_hp }}</p>
        </div>

        <!-- Kolom 2: Info Acara -->
        <div class="space-y-3">
            <h3 class="text-lg font-semibold border-b pb-2">Detail Acara</h3>
            <p><span class="font-medium">Nama Acara:</span> {{ $pengajuan->nama_acara }}</p>
            <p><span class="font-medium">Lokasi:</span> {{ $pengajuan->lokasi_acara }}</p>
            <p><span class="font-medium">Status:</span> 
                <span class="px-2 py-1 rounded-full text-xs 
                    {{ $pengajuan->status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                       ($pengajuan->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($pengajuan->status) }}
                </span>
            </p>
        </div>

        <!-- Kolom 3: Barang & Aksi -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold border-b pb-2">Detail Barang</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left border-b">Nama Barang</th>
                            <th class="px-4 py-2 text-left border-b">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan->details as $detail)
                        <tr>
                            <td class="px-4 py-2 border-b">{{ $detail->barang->item }}</td>
                            <td class="px-4 py-2 border-b">{{ $detail->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(auth()->user()->isAdmin() && $pengajuan->status === 'menunggu')
            <div class="flex flex-wrap gap-3 mt-4">
                <form method="POST" action="{{ route('pengajuan.decline', $pengajuan->id) }}" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-red-500 text-white px-4 py-2 rounded-full transition flex items-center justify-center gap-2">
                        
                        Decline
                    </button>
                </form>
                <form method="POST" action="{{ route('pengajuan.approve', $pengajuan->id) }}" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-blue-500 hover:bg-green-600 text-white px-6 py-2 rounded-full transition flex items-center justify-center gap-2">
                        
                        Approve
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

<script>
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
</script>
@endsection
