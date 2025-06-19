<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanNew;
use App\Models\Barangs; // Import model Barangs
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Import facade DB untuk transaksi
use Illuminate\Support\Facades\Log; // Import Log untuk debugging

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = \App\Models\PeminjamanNew::with(['user', 'details.barang'])->orderBy('created_at', 'desc')->get();
        return view('pengajuan', compact('pengajuans'));
    }

    public function approve($id)
    {
        // Validasi hak akses admin
        if (!auth()->user()->role || auth()->user()->role->role !== 'admin') {
            abort(403);
        }

        $pengajuan = PeminjamanNew::findOrFail($id);

        // Hanya setujui jika status masih 'menunggu'
        if ($pengajuan->status !== 'menunggu') {
            return redirect()->route('pengajuan.show', $id)->with('error', 'Pengajuan ini tidak dapat disetujui karena statusnya bukan \'menunggu\'.');
        }

        // Tidak perlu transaksi atau perubahan stok di sini karena stok sudah dikurangi saat pengajuan
        // Jika Anda ingin stok dikurangi HANYA saat disetujui, logika pengurangan stok harus dipindahkan ke sini
        // dan dihapus dari PeminjamanNewController@store.
        $pengajuan->update(['status' => 'disetujui']);

        return redirect()->route('pengajuan')->with('success', 'Pengajuan disetujui.');
    }

    public function decline($id)
    {
        // Validasi hak akses admin
        if (!auth()->user()->role || auth()->user()->role->role !== 'admin') {
            abort(403);
        }

        $pengajuan = PeminjamanNew::findOrFail($id);

        // Hanya tolak jika status bukan 'selesai' atau 'ditolak'
        if ($pengajuan->status === 'selesai' || $pengajuan->status === 'ditolak') {
            return redirect()->route('pengajuan.show', $id)->with('error', 'Pengajuan ini sudah selesai atau sudah ditolak. Tidak dapat diubah lagi.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok barang yang dipinjam saat pengajuan ditolak
            // Ini mengasumsikan stok dikurangi saat pengajuan, jadi perlu dikembalikan saat ditolak
            foreach ($pengajuan->details as $detail) {
                $barang = Barangs::find($detail->barang_id);
                if ($barang) {
                    $barang->jumlah_unit += $detail->jumlah;
                    $barang->save();
                    Log::info("Stok barang dikembalikan saat pengajuan ditolak. Barang ID: {$barang->id}, Item: {$barang->item}, Jumlah: {$detail->jumlah}, Stok Baru: {$barang->jumlah_unit}");
                } else {
                    Log::warning("Barang ID: {$detail->barang_id} tidak ditemukan saat mengembalikan stok untuk pengajuan ditolak.");
                }
            }

            $pengajuan->update(['status' => 'ditolak']);
            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan berhasil ditolak dan stok barang telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menolak pengajuan atau mengembalikan stok:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'pengajuan_id' => $id,
            ]);
            return redirect()->route('pengajuan')->with('error', 'Gagal menolak pengajuan atau mengembalikan stok: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pengajuan = \App\Models\PeminjamanNew::with(['user', 'details.barang'])->findOrFail($id);
        return view('pengajuandetail', compact('pengajuan'));
    }

    public function finish(Request $request, $id) // Menerima Request untuk mengambil data checkbox
    {
        // Validasi hak akses admin
        if (!auth()->user()->role || auth()->user()->role->role !== 'admin') {
            abort(403);
        }

        $pengajuan = \App\Models\PeminjamanNew::findOrFail($id);

        if ($pengajuan->status !== 'disetujui') {
            return back()->with('error', 'Pengajuan bukan dalam status disetujui. Tidak dapat ditandai selesai.');
        }

        // Validasi bahwa semua checkbox 'Dikembalikan' telah dicentang
        // Input name dari checkbox adalah `returned[]`.
        $returnedDetailIds = $request->input('returned', []);

        // Jika Anda ingin memastikan SEMUA barang harus dicentang untuk menyelesaikan peminjaman
        // Logika di Blade Anda sudah memastikan tombol hanya aktif jika semua dicentang
        if (count($returnedDetailIds) !== $pengajuan->details->count()) {
             return back()->with('error', 'Semua barang harus ditandai telah dikembalikan untuk menyelesaikan peminjaman.');
        }


        DB::beginTransaction();
        try {
            // Kembalikan stok barang yang dipinjam
            foreach ($pengajuan->details as $detail) { // Loop semua detail karena diasumsikan semua dikembalikan
                $barang = Barangs::find($detail->barang_id);
                if ($barang) {
                    $barang->jumlah_unit += $detail->jumlah;
                    $barang->save();
                    Log::info("Stok barang dikembalikan saat pengajuan selesai. Barang ID: {$barang->id}, Item: {$barang->item}, Jumlah: {$detail->jumlah}, Stok Baru: {$barang->jumlah_unit}");
                } else {
                    Log::warning("Barang ID: {$detail->barang_id} tidak ditemukan saat mengembalikan stok untuk pengajuan selesai.");
                }
            }

            $pengajuan->status = 'selesai';
            $pengajuan->save();
            DB::commit();
            return redirect()->route('pengajuan.show', $id)->with('success', 'Peminjaman berhasil ditandai sebagai selesai dan stok barang telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyelesaikan pengajuan atau mengembalikan stok:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'pengajuan_id' => $id,
            ]);
            return redirect()->route('pengajuan.show', $id)->with('error', 'Gagal menandai peminjaman selesai atau mengembalikan stok: ' . $e->getMessage());
        }
    }
}
