<?php

namespace App\Http\Controllers;
use App\Models\PeminjamanNew;
use Illuminate\Http\Request;

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
        $pengajuan->update(['status' => 'ditolak']);
        return redirect()->route('pengajuan')->with('error', 'Pengajuan ditolak.');
    }

    public function show($id)
    {
        $pengajuan = \App\Models\PeminjamanNew::with(['user', 'details.barang'])->findOrFail($id);
        return view('pengajuandetail', compact('pengajuan'));
    }

}
