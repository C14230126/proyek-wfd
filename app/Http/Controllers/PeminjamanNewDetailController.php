<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanNew;
use App\Models\PeminjamanNewDetail;
use App\Models\Barangs;
use Illuminate\Http\Request;

class PeminjamanNewDetailController extends Controller
{
    /**
     * Menampilkan semua detail barang dari satu peminjaman
     */
    public function index($id)
    {
        $peminjaman = PeminjamanNew::findOrFail($id);
        $details = PeminjamanNewDetail::where('peminjaman_new_id', $id)
                    ->with('barangs')
                    ->get();

        return view('peminjaman.detail', compact('peminjaman', 'details'));
    }

    /**
     * Menyimpan barang baru ke detail peminjaman
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Pastikan peminjaman nya ada
        $peminjaman = PeminjamanNew::findOrFail($id);

        // Buat detail peminjaman baru
        PeminjamanNewDetail::create([
            'peminjaman_new_id' => $peminjaman->id,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('peminjaman.detail', $id)
                         ->with('success', 'Barang berhasil ditambahkan ke peminjaman.');
    }
}
