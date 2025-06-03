<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
     protected $fillable = [
        'barang_id', 'user_id', 'admin_id',
        'quantitas', 'nama_acara', 'lokasi_acara',
        'tanggal_peminjaman', 'awal_jam_pinjem', 'akhir_jam_pinjem'
    ];
    public function barang(){
        return $this->belongsTo(Barangs::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }

}

