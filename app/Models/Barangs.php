<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangs extends Model
{
    protected $table = 'barangs';

    protected $fillable = [
        'kategori_id',
        'item',
        'jumlah_unit',
        'lokasi',
        'status',
    ];

    /**
     * Relasi ke kategori barang (many-to-one)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi many-to-many ke peminjaman melalui tabel pivot 'peminjaman_new_details'
     */
    public function peminjamans()
    {
        return $this->belongsToMany(PeminjamanNew::class, 'peminjaman_new_details')
                    ->withPivot('jumlah')
                    ->withTimestamps();
    }
}
