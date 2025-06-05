<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangs;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('listpeminjaman');
    }
    public function create()
    {
        $barangs = Barangs::where('status', 'Returned')->get();
        return view('buatpeminjaman', compact('barangs'));
    }
    public function store(Request $request)
    {
        
    }
}
