<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeminjamanNew;
use App\Models\PeminjamanNewDetail;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class PeminjamanNewController extends Controller
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

        $peminjaman = PeminjamanNew::with('details.barang')->get();

        return view('listpeminjaman', compact(
        'bulan', 'tahun', 'daysInMonth', 'firstDayOfWeek',
        'prev', 'next', 'current'
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
    ]);

    $peminjaman = PeminjamanNew::create([
        'user_id' => Auth::id(),
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_kembali' => $request->tanggal_kembali,
        'nama_acara' => $request->nama_acara,
        'lokasi_acara' => $request->lokasi_acara,
        'status' => 'menunggu',
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

}

