<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanNewDetail extends Model
{
    protected $table = 'peminjaman_new_detail';

    protected $fillable = [
        'peminjaman_new_id', 'barang_id', 'jumlah',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanNew::class, 'peminjaman_new_id');
    }

    public function barang()
    {
        // return $this->belongsTo(Barangs::class);
        return $this->belongsTo(Barangs::class, 'barang_id');

    }
}
