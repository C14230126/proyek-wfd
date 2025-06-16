<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('role', function ($query) {
            $query->where('role', '!=', 'admin');
        })->with('role')->get();
    
        return view('listusers', compact('users'));
    }

    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('listusers')->with('success', 'User berhasil dihapus.');
    }

}
