<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = \App\Models\Barangs::where('status', 'Returned')
            ->select('item', 'jumlah_unit')
            ->get();

        return view('listbarang', compact('barangs'));
    }
}
