<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
     protected $fillable = [
        'barang_id', 'user_id', 'admin_id',
        'jumlah', 'nama_acara', 'lokasi_acara',
        'tanggal_pinjam','tanggal_kembali', 'awal_jam_pinjem', 'akhir_jam_pinjem'
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
    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
    public function barangs()
    {
        return $this->belongsToMany(Barangs::class, 'peminjaman_details')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }



}

