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
                    @endphp
                    <button
                        onclick="loadJadwal('{{ $tanggal }}')"
                        class="border rounded-lg py-2 hover:bg-blue-100 transition {{ $textColor }}">
                        {{ $day }}
                    </button>
                @endfor
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
        .then(response => response.json())
        .then(data => {
            let html = `
                <h3 class="text-xl font-bold mb-4">Jadwal untuk ${tanggal}</h3>
                <table class="w-full table-fixed border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="w-1/3 border px-2 py-1">Waktu</th>
                            <th class="border px-2 py-1">Acara</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            const jamList = [
                '07:30:00','08:30:00','09:30:00','10:30:00','11:30:00','12:30:00','13:30:00'
            ];

            jamList.forEach((jam, index) => {
                let akhirJam = jamList[index + 1];
                if (!akhirJam) return;

                let item = data.find(d => d.awal_jam_pinjem === jam);

                html += `
                    <tr>
                        <td class="border px-2 py-1">${jam} - ${akhirJam}</td>
                        <td class="border px-2 py-1 ${item ? 'bg-red-500 text-white font-bold' : ''}">
                            ${item ? item.nama_acara + '<br><small>' + item.lokasi_acara + '</small>' : '-'}
                        </td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;

            document.getElementById('jadwal-container').innerHTML = html;
        });
}
</script>

@endsection
