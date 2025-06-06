<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Barangs;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->query('bulan', now()->month); // default = bulan sekarang
        $tahun = $request->query('tahun', now()->year);  // default = tahun sekarang

        $current = Carbon::create($tahun, $bulan, 1);
        $prev = $current->copy()->subMonth();
        $next = $current->copy()->addMonth();

        $startOfMonth = Carbon::create($tahun, $bulan, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 = Sunday

        return view('listpeminjaman', compact(
        'bulan', 'tahun', 'daysInMonth', 'firstDayOfWeek',
        'prev', 'next', 'current'
        ));
    }
    public function create()
    {
        $barangs = Barangs::where('status', 'Returned')->get();
        return view('buatpeminjaman', compact('barangs'));
    }
    public function getJadwalByTanggal($tanggal)
    {
        $peminjamans = Peminjaman::where('tanggal_peminjaman', $tanggal)->get();

        return response()->json($peminjamans);
    }
}
