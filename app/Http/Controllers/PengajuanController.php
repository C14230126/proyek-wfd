<?php

namespace App\Http\Controllers;

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
        $pengajuan = PeminjamanNew::findOrFail($id);
        $pengajuan->status = 'disetujui';
        $pengajuan->save();
        return redirect()->route('pengajuan')->with('success', 'Pengajuan disetujui.');
    }

    public function decline($id)
    {
        $pengajuan = PeminjamanNew::findOrFail($id);
        $pengajuan->status = 'ditolak';
        $pengajuan->save();
        return redirect()->route('pengajuan')->with('error', 'Pengajuan ditolak.');
    }

    public function show($id)
    {
        $pengajuan = \App\Models\PeminjamanNew::with(['user', 'details.barang'])->findOrFail($id);
        return view('pengajuandetail', compact('pengajuan'));
    }

}
