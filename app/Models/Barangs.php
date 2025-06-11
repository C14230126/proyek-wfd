<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangs extends Model
{
    protected $table = 'barangs';

        protected $fillable = ['kategori_id', 'item', 'jumlah_unit', 'lokasi', 'status'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // public function peminjamans()
    // {
    //     return $this->hasMany(Peminjaman::class);
    // }
    public function peminjamans()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_new_details')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }

}
