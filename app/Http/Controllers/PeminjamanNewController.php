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

        // Siapkan array untuk menandai tanggal-tanggal dengan peminjaman yang sedang diproses
        $datesWithProcessing = [];

        $today = Carbon::today('Asia/Jakarta')->startOfDay(); // Dapatkan hari ini

        foreach ($allPeminjamanInMonth as $peminjaman) {
            $startDate = Carbon::parse($peminjaman->tanggal_pinjam);
            $endDate = Carbon::parse($peminjaman->tanggal_kembali);

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Only process dates within the current month/year being displayed
                if ($date->month == $bulan && $date->year == $tahun) { // Removed '&& $peminjaman->status == 'disetujui'' to capture all statuses for marking
                    $dateString = $date->format('Y-m-d');

                    // Mark general peminjaman (e.g., for green dot)
                    if ($peminjaman->status === 'disetujui') {
                         $datesWithPeminjaman[$dateString] = true;
                    }

                    // If status is 'processing'
                    if ($peminjaman->status === 'processing') {
                        $datesWithProcessing[$dateString] = true;
                    }

                    // If status is not 'selesai' AND tanggal_kembali is past today
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
            'datesWithUnreturned',
            'datesWithProcessing',
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
        ], [
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

    public function update($id, $action)
    {
        try {
            // Find the PeminjamanNew record by its ID
            $peminjamanNew = PeminjamanNew::findOrFail($id);

            // Update the status
            $peminjamanNew->status = $action;

            // Save the changes to the database
            $peminjamanNew->save();

            // Redirect back with a success message
            // You can change the redirect path as needed
            return redirect()->back()->with('success', 'Status peminjaman berhasil diubah menjadi processing.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If the PeminjamanNew record is not found
            return redirect()->back()->with('error', 'Peminjaman tidak ditemukan.');
        } catch (\Exception $e) {
            // Catch any other potential errors
            return redirect()->back()->with('error', 'Gagal mengubah status peminjaman: ' . $e->getMessage());
        }
    }

    public function getJadwalByTanggal(Request $request)
    {
        // Validate that 'tanggal' parameter is provided
        $tanggal = $request->tanggal; // Use input() instead of direct property access for robustness
        if (empty($tanggal)) {
            Log::warning('getJadwalByTanggal: Tanggal tidak diberikan.', ['request_params' => $request->all()]);
            return response()->json(['error' => 'Tanggal tidak diberikan'], 400);
        }

        try {
            // Parse the target date and set to start of day for accurate comparison
            $targetDate = Carbon::parse($tanggal)->startOfDay();

            // Fetch all PeminjamanNew records that overlap with the target date.
            // We eager-load 'details.barang' to get item names and quantities.
            $peminjamanPadaTanggalIni = PeminjamanNew::with('details.barang')
                ->whereDate('tanggal_pinjam', '<=', $targetDate)
                ->whereDate('tanggal_kembali', '>=', $targetDate)
                ->get();

            $jadwalUntukHariIni = [];

            // Iterate through each fetched peminjaman to build the daily schedule array
            foreach ($peminjamanPadaTanggalIni as $peminjaman) {
                // Assuming 'daily_times' is cast to 'array' in your PeminjamanNew model.
                // Example in PeminjamanNew model: protected $casts = ['daily_times' => 'array'];
                $dailyTimes = $peminjaman->daily_times;

                // Log the raw daily_times data for debugging purposes
                Log::info("Processing Peminjaman ID: {$peminjaman->id} for date: {$tanggal}", ['daily_times_data' => $dailyTimes]);

                // Check if dailyTimes is a valid array and contains an entry for the specific target date.
                // This is crucial if daily_times stores schedules per day of the loan period.
                if (is_array($dailyTimes) && isset($dailyTimes[$tanggal])) {
                    $schedule = $dailyTimes[$tanggal];

                    // Log the specific schedule found for the date
                    Log::info("Schedule found for {$tanggal} on Peminjaman ID: {$peminjaman->id}", ['schedule' => $schedule]);

                    // Add the formatted peminjaman data to the result array
                    $jadwalUntukHariIni[] = [
                        'id' => $peminjaman->id,
                        'nama_acara' => $peminjaman->nama_acara,
                        'lokasi_acara' => $peminjaman->lokasi_acara,
                        'tanggal_pinjam' => $peminjaman->tanggal_pinjam->toDateString(), // Ensure date format
                        'tanggal_kembali' => $peminjaman->tanggal_kembali->toDateString(), // Ensure date format
                        'awal_jam_pinjam_harian' => $schedule['start_time'] ?? null,
                        'akhir_jam_pinjam_harian' => $schedule['end_time'] ?? null,
                        'status' => $peminjaman->status, // Include the status for frontend filtering
                        'barangs' => $peminjaman->details->map(function ($detail) {
                            return [
                                'item' => $detail->barang->item ?? 'Unknown Item',
                                'jumlah' => $detail->jumlah,
                            ];
                        })->toArray()
                    ];
                } else {
                    // Log a warning if no daily schedule is found for the specific date
                    // within the loan's daily_times, or if daily_times is malformed.
                    Log::warning("No daily schedule found for date {$tanggal} on Peminjaman ID: {$peminjaman->id} or daily_times is not array.", ['daily_times_raw' => $dailyTimes]);
                }
            }

            // Return the compiled schedule as a JSON response
            return response()->json($jadwalUntukHariIni);

        } catch (\Exception $e) {
            // Catch and log any exceptions for debugging
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
