<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Barangs;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Support\Facades\Auth;


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

//     public function store(Request $request)
// {
//     $validated = $request->validate([
//         'nama_acara' => 'required|string',
//         'nama_peminjam' => 'required|string',
//         'nrp' => 'required|string',
//         'tanggal_pinjam' => 'required|date',
//         'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
//         'barang_id' => 'required|array',
//         'barang_id.*' => 'exists:barangs,id',
//         'jumlah' => 'required|array',
//         'jumlah.*' => 'integer|min:1',
//         'nama_acara' => 'required|string',
//         'lokasi_acara' => 'required|string',
//         'tanggal_pinjam' => $request->tanggal_pinjam,
//         'tanggal_kembali' => $request->tanggal_kembali,
//         'awal_jam_pinjem' => $request->awal_jam_pinjem,
//         'akhir_jam_pinjem' => $request->akhir_jam_pinjem,
//         'user_id' => Auth::id(), 
//     ]);
    

//     // Simpan header peminjaman
//     $peminjaman = Peminjaman::create([
//         'nama_acara' => $request->nama_acara,
//         'nama_peminjam' => $request->nama_peminjam,
//         'nrp' => $request->nrp,
//         'tanggal_pinjam' => $request->tanggal_pinjam,
//         'tanggal_kembali' => $request->tanggal_kembali,
//     ]);

//     // Simpan detail barang
//     foreach ($request->barang_id as $index => $barangId) {
//         $peminjaman->details()->create([
//             'barang_id' => $barangId,
//             'jumlah' => $request->jumlah[$index],
//         ]);
//     }

//     return redirect()->route('home')->with('success', 'Peminjaman berhasil disimpan');
// }
public function store(Request $request)
{
    $validated = $request->validate([
        'nama_acara' => 'required|string',
        'lokasi_acara' => 'required|string',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        'awal_jam_pinjem' => 'required',
        'akhir_jam_pinjem' => 'required',
        'barang_id' => 'required|array',
        'barang_id.*' => 'exists:barangs,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
    ]);

    // Simpan peminjaman
    $peminjaman = Peminjaman::create([
        'user_id' => Auth::id(),
        'admin_id' => 1, // Default admin atau bisa kosong
        'nama_acara' => $request->nama_acara,
        'lokasi_acara' => $request->lokasi_acara,
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_kembali' => $request->tanggal_kembali,
        'awal_jam_pinjem' => $request->awal_jam_pinjem,
        'akhir_jam_pinjem' => $request->akhir_jam_pinjem,
    ]);

    // Simpan detail peminjaman
        foreach ($request->barang_id as $index => $barangId) {
    $peminjaman->barangs()->attach($barangId, [
        'jumlah' => $request->jumlah[$index],
    ]);
}


    return redirect()->route('home')->with('success', 'Peminjaman berhasil disimpan');
}



}
