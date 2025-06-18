@extends('layouts.app')
@section('content')
<div class="mt-14 mb-6 flex justify-center relative">
    {{-- Tombol kiri DI LUAR container --}}
    <a href="{{ route('listpeminjaman.index', ['bulan' => $prev->month, 'tahun' => $prev->year]) }}"
       class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-sky-400 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-bold hover:bg-sky-500 z-20 shadow-lg">
        &lt;
    </a>

    {{-- Kalender Box --}}
    <div class="w-full max-w-[1200px] flex rounded-xl overflow-hidden shadow-md bg-white relative">
        {{-- Sidebar kiri --}}
        <div class="bg-[#0F2B5B] w-[60px] flex items-center justify-center">
            <div class="text-white font-bold text-md leading-tight text-center rotate-[-90deg] whitespace-nowrap">
                {{ strtoupper($current->translatedFormat('F')) }} {{ $current->year }}
            </div>
        </div>
        {{-- Kalender --}}
        <div class="flex-1 px-10 py-6">
            <div class="grid grid-cols-7 gap-y-4 gap-x-6 text-center font-semibold mb-2 text-lg">
                <div class="text-red-500">Su</div>
                <div class="text-sky-700">Mo</div>
                <div class="text-sky-700">Tu</div>
                <div class="text-sky-700">We</div>
                <div class="text-sky-700">Th</div>
                <div class="text-sky-700">Fr</div>
                <div class="text-sky-700">Sa</div>
            </div>

            <div class="grid grid-cols-7 gap-y-4 gap-x-6 text-center text-sky-700 text-lg">
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div></div>
                @endfor

                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $tanggal = \Carbon\Carbon::create($tahun, $bulan, $day)->format('Y-m-d');
                        $dayOfWeek = \Carbon\Carbon::create($tahun, $bulan, $day)->dayOfWeek;
                        $textColor = $dayOfWeek === 0 ? 'text-red-500' : 'text-sky-700';

                        // Cek apakah tanggal ini memiliki peminjaman
                        $hasPeminjaman = isset($datesWithPeminjaman[$tanggal]);
                        $hasProcess = isset($datesWithProcessing[$tanggal]);


                        // Tentukan apakah tombol harus di-gray out
                        $isPastDay = \Carbon\Carbon::parse($tanggal)->lt(\Carbon\Carbon::today());
                        $buttonClasses = '';
                        $isDisabled = false; // Flag untuk atribut disabled

                        // JIKA ANDA HANYA INGIN MAHASISWA TIDAK BISA KLIK HARI LALU
                        // Jika Anda ingin disabled HANYA jika hari sudah lewat DAN user adalah Mahasiswa:
                        $loggedInUser = auth()->user();
                        if($isPastDay && isset($datesWithUnreturned[$tanggal]) && $loggedInUser->isAdmin()) { // Jika hari sudah lewat dan tidak ada peminjaman yang belum dikembalikan
                            $buttonClasses .= ' bg-gray-200 text-gray-400';
                        } else if ($isPastDay && $loggedInUser->isMahasiswa()) {
                            $isDisabled = true;
                            $buttonClasses .= ' bg-gray-200 text-gray-400 cursor-not-allowed';
                        }else if ($isPastDay) {
                            $buttonClasses .= ' bg-gray-200 text-gray-400';
                        } else if (!$isPastDay) { // Pastikan untuk hari ini/mendatang, tombol tetap aktif
                            $buttonClasses .= ' hover:bg-blue-100 transition';
                        }
                    @endphp
                    <button
                        onclick="loadJadwal('{{ $tanggal }}')"
                        class="relative border rounded-lg py-2 {{ $buttonClasses }} {{ $textColor }} flex flex-col items-center justify-center"
                        @if ($isDisabled) disabled @endif> {{-- Gunakan flag $isDisabled --}}
                        {{ $day }}
                        @if ($hasPeminjaman && !$isPastDay)
                            <span class="absolute bottom-1 right-1 w-2 h-2 bg-green-500 rounded-full animate-pulse"
                                  title="Ada peminjaman pada tanggal ini"></span>
                        @endif
                        @if ($hasProcess)
                            <span class="absolute bottom-1 right-1 w-2 h-2 bg-amber-300 rounded-full animate-pulse"
                                  title="Peminjaman pada tanggal ini dalam process"></span>
                        @endif
                        @if ($hasPeminjaman && $isPastDay && isset($datesWithUnreturned[$tanggal]) && $datesWithUnreturned[$tanggal]) {{-- Tampilkan indikator merah jika sudah lewat dan ada yang belum dikembalikan --}}
                            <span class="absolute bottom-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-ping"
                                  title="Ada peminjaman yang belum dikembalikan!"></span>
                        @endif
                    </button>
                @endfor
            </div>
            {{-- Tambahkan container untuk jadwal harian --}}
            <div id="jadwal-container" class="mt-8 p-4 bg-gray-50 rounded-lg shadow-inner">
                <p class="text-gray-600 text-center">Klik tanggal untuk melihat jadwal peminjaman.</p>
            </div>
        </div>
    </div>

    {{-- Tombol kanan DI LUAR container --}}
    <a href="{{ route('listpeminjaman.index', ['bulan' => $next->month, 'tahun' => $next->year]) }}"
       class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-sky-400 text-white rounded-full w-10 h-10 flex items-center justify-center text-xl font-bold hover:bg-sky-500 z-20 shadow-lg">
        &gt;
    </a>
</div>
<script>
function loadJadwal(tanggal) {
    fetch(`/peminjaman/jadwal/${tanggal}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            let html = `
                <h3 class="text-xl font-bold mb-4 text-gray-800">Jadwal untuk ${formatDate(tanggal)}</h3>
                <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-2 text-left border-b border-gray-200">Waktu Pinjam</th>
                            <th class="px-4 py-2 text-left border-b border-gray-200">Nama Acara</th>
                            <th class="px-4 py-2 text-left border-b border-gray-200">Lokasi Acara</th>
                            <th class="px-4 py-2 text-left border-b border-gray-200">Barang Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            const filteredData = data.filter(item => item.status !== 'menunggu');

            if (filteredData.length === 0) {
                html += `
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada peminjaman untuk tanggal ini.</td>
                    </tr>
                `;
            } else {
                filteredData.forEach(item => {
                    const waktuPinjam = `${item.awal_jam_pinjam_harian ?? ''} - ${item.akhir_jam_pinjam_harian ?? ''}`;
                    
                    let barangListHtml = '';
                    if (item.barangs && Array.isArray(item.barangs) && item.barangs.length > 0) {
                        barangListHtml = item.barangs.map(barang => 
                            `<span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-1 mb-1 px-2.5 py-0.5 rounded-full">${barang.item} (${barang.jumlah})</span>`
                        ).join('');
                    } else {
                        barangListHtml = '-';
                    }

                    html += `
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">${waktuPinjam}</td>
                            <td class="px-4 py-3">${item.nama_acara}</td>
                            <td class="px-4 py-3">${item.lokasi_acara}</td>
                            <td class="px-4 py-3">${barangListHtml}</td>
                        </tr>
                    `;
                });
            }

            html += `</tbody></table></div>`;
            document.getElementById('jadwal-container').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading schedule:', error);
            document.getElementById('jadwal-container').innerHTML = `
                <div class="text-red-500 text-center py-4">Terjadi kesalahan saat memuat jadwal. Silakan coba lagi.</div>
            `;
        });
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}
</script>

@endsection
