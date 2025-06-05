<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    function home() {
        return view('home');
    }    

    function listbarang() {
        return view('listbarang');
    }    

    function listpeminjaman() {
        return view('listpeminjaman');
    }    

    function listusers() {
        return view('listusers');
    }    

    function pengajuan() {
        return view('pengajuan');
    }    

    function welcome() {
        return view('welcome');
    }    

}
