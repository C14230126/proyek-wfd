<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanNew;
use App\Models\PeminjamanNewDetail;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PeminjamanNewController extends Controller
{

    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month); // default = bulan sekarang
        $tahun = $request->query('tahun', now()->year);  // default = tahun sekarang

        $current = Carbon::create($tahun, $bulan, 1);
        $prev = $current->copy()->subMonth();
        $next = $current->copy()->addMonth();

        $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endOfMonth = Carbon::create($tahun, $bulan, 1)->endOfMonth()->endOfDay();

        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday

        // Ambil semua peminjaman yang overlapping dengan bulan yang sedang dilihat
        $allPeminjamanInMonth = PeminjamanNew::where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('tanggal_pinjam', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('tanggal_kembali', [$startOfMonth, $endOfMonth])
                      ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                          $query->where('tanggal_pinjam', '<', $startOfMonth)
                                ->where('tanggal_kembali', '>', $endOfMonth);
                      });
            })
            ->get();

        // Siapkan array untuk menandai tanggal-tanggal yang memiliki peminjaman
        $datesWithPeminjaman = [];
        // Siapkan array untuk menandai tanggal-tanggal dengan peminjaman yang belum dikembalikan
        $datesWithUnreturned = [];

        $today = Carbon::today()->startOfDay(); // Dapatkan hari ini

        foreach ($allPeminjamanInMonth as $peminjaman) {
            $startDate = Carbon::parse($peminjaman->tanggal_pinjam);
            $endDate = Carbon::parse($peminjaman->tanggal_kembali);

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                if ($date->month == $bulan && $date->year == $tahun) {
                    $dateString = $date->format('Y-m-d');
                    $datesWithPeminjaman[$dateString] = true;

                    // Jika status bukan 'selesai' DAN tanggal_kembali sudah lewat hari ini
                    if ($peminjaman->status !== 'selesai' && $endDate->lt($today)) {
                        $datesWithUnreturned[$dateString] = true;
                    }
                }
            }
        }

        return view('listpeminjaman', compact(
            'bulan',
            'tahun',
            'daysInMonth',
            'firstDayOfWeek',
            'prev',
            'next',
            'current',
            'datesWithPeminjaman',
            'datesWithUnreturned' // ✅ Kirim data ini ke Blade
        ));
    }

    public function create()
    {
        $barangs = \App\Models\Barangs::all();
        return view('buatpeminjaman', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'nama_acara' => 'required|string|max:255',
            'lokasi_acara' => 'required|string|max:255',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah.*' => 'required|integer|min:1',
            // Validasi untuk daily_times yang sekarang dikirim dari form
            'daily_times' => 'required|array', // Pastikan daily_times adalah array
            'daily_times.*.start_time' => 'required|date_format:H:i', // Validasi format jam
            'daily_times.*.end_time' => 'required|date_format:H:i|after:daily_times.*.start_time', // Validasi format jam dan setelah start_time
        ],[
            'tanggal_pinjam.required' => 'Tanggal Peminjaman Awal wajib diisi.',
            'tanggal_pinjam.date' => 'Format Tanggal Peminjaman Awal tidak valid.',
            
            'tanggal_kembali.required' => 'Tanggal Peminjaman Akhir wajib diisi.',
            'tanggal_kembali.date' => 'Format Tanggal Peminjaman Akhir tidak valid.',
            'tanggal_kembali.after_or_equal' => 'Tanggal Peminjaman Akhir harus sama atau setelah Tanggal Peminjaman Awal.',
            
            'nama_acara.required' => 'Nama Acara wajib diisi.',
            'nama_acara.string' => 'Nama Acara harus berupa teks.',
            'nama_acara.max' => 'Nama Acara tidak boleh lebih dari :max karakter.',
            
            'lokasi_acara.required' => 'Lokasi Acara wajib diisi.',
            'lokasi_acara.string' => 'Lokasi Acara harus berupa teks.',
            'lokasi_acara.max' => 'Lokasi Acara tidak boleh lebih dari :max karakter.',
            
            'barang_id.*.required' => 'Barang yang ingin dipinjam wajib dipilih.',
            'barang_id.*.exists' => 'Barang yang dipilih tidak valid.',
            
            'jumlah.*.required' => 'Jumlah barang wajib diisi.',
            'jumlah.*.integer' => 'Jumlah barang harus berupa angka.',
            'jumlah.*.min' => 'Jumlah barang minimal :min.',
            
            'daily_times.required' => 'Jadwal jam peminjaman per hari wajib diisi.',
            'daily_times.array' => 'Format jadwal jam tidak valid.',
            
            'daily_times.*.start_time.required' => 'Jam awal untuk setiap hari wajib diisi.',
            'daily_times.*.start_time.date_format' => 'Format jam awal untuk setiap hari tidak valid (HH:MM).',
            
            'daily_times.*.end_time.required' => 'Jam akhir untuk setiap hari wajib diisi.',
            'daily_times.*.end_time.date_format' => 'Format jam akhir untuk setiap hari tidak valid (HH:MM).',
            'daily_times.*.end_time.after' => 'Jam akhir untuk setiap hari harus setelah jam awal.',
        ]);

        $peminjaman = PeminjamanNew::create([
            'user_id' => Auth::id(),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'nama_acara' => $request->nama_acara,
            'lokasi_acara' => $request->lokasi_acara,
            'status' => 'menunggu',
            'daily_times' => $request->daily_times, // ✅ Ini sudah benar jika di-cast di model
        ]);

        foreach ($request->barang_id as $index => $barangId) {
            PeminjamanNewDetail::create([
                'peminjaman_new_id' => $peminjaman->id,
                'barang_id' => $barangId,
                'jumlah' => $request->jumlah[$index],
            ]);
        }

        return redirect()->back()->with('success', 'Peminjaman berhasil disimpan!');
    }

    public function getJadwalByTanggal(Request $request)
    {

        $tanggal = $request->tanggal;
        if ($tanggal == null) {
            Log::warning('getJadwalByTanggal: Tanggal tidak diberikan.', ['request_params' => $request->all()]);
            return response()->json(['error' => 'Tanggal tidak diberikan'], 400);
        }

        try {
            $targetDate = Carbon::parse($tanggal)->startOfDay();

            $peminjamanPadaTanggalIni = PeminjamanNew::with('details.barang')
                ->whereDate('tanggal_pinjam', '<=', $targetDate)
                ->whereDate('tanggal_kembali', '>=', $targetDate)
                ->get();

            $jadwalUntukHariIni = [];

            foreach ($peminjamanPadaTanggalIni as $peminjaman) {
                // ✅ Perbaikan utama: Langsung akses daily_times, karena sudah di-cast ke 'array' di model
                $dailyTimes = $peminjaman->daily_times; 

                // Log untuk memeriksa data dailyTimes sebelum diakses
                Log::info("Processing Peminjaman ID: {$peminjaman->id} for date: {$tanggal}", ['daily_times_data' => $dailyTimes]);

                // Pastikan $dailyTimes adalah array dan memiliki key untuk tanggal ini
                if (is_array($dailyTimes) && isset($dailyTimes[$tanggal])) {
                    $schedule = $dailyTimes[$tanggal];
                    // Tambahkan log untuk schedule yang ditemukan
                    Log::info("Schedule found for {$tanggal} on Peminjaman ID: {$peminjaman->id}", ['schedule' => $schedule]);

                    $jadwalUntukHariIni[] = [
                        'id' => $peminjaman->id,
                        'nama_acara' => $peminjaman->nama_acara,
                        'lokasi_acara' => $peminjaman->lokasi_acara,
                        'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                        'tanggal_kembali' => $peminjaman->tanggal_kembali,
                        'awal_jam_pinjam_harian' => $schedule['start_time'] ?? null,
                        'akhir_jam_pinjam_harian' => $schedule['end_time'] ?? null,
                        'barangs' => $peminjaman->details->map(function($detail) {
                            return [
                                'item' => $detail->barang->item ?? 'Unknown Item',
                                'jumlah' => $detail->jumlah,
                            ];
                        })->toArray()
                    ];
                } else {
                    Log::warning("No daily schedule found for date {$tanggal} on Peminjaman ID: {$peminjaman->id} or daily_times is not array.", ['daily_times_raw' => $dailyTimes]);
                }
            }

            return response()->json($jadwalUntukHariIni);

        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error in getJadwalByTanggal:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'requested_tanggal' => $tanggal,
            ]);
            return response()->json(['error' => 'Terjadi kesalahan internal server saat memuat jadwal.'], 500);
        }
    }
}
