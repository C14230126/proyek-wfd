<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Barangs;
use App\Models\Kategori;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barangs::where('status', 'Returned')
            ->select('id', 'item', 'jumlah_unit')
            ->get();

        $kategoris = Kategori::all();

        return view('listbarang', compact('barangs', 'kategoris'));
    }

        public function store(Request $request)
    {
        if (Auth::user()->role->role === 'mahasiswa') {
            abort(403, 'Mahasiswa tidak diizinkan menambah barang.');
        }

        $validated = $request->validate([
            'item' => 'required|string',
            'jumlah_unit' => 'required|integer|min:0',
            'lokasi' => 'required|string',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        Barangs::create(array_merge($validated, ['status' => 'Returned']));

        return redirect()->route('listbarang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_unit' => 'required|integer|min:0',
        ]);

        $barang = \App\Models\Barangs::findOrFail($id);
        $barang->jumlah_unit = $validated['jumlah_unit'];
        $barang->save();

        return redirect()->route('listbarang.index')->with('success', 'Jumlah barang berhasil diperbarui.');
    }
}
