<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeminjamanDetail extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_details';

    protected $fillable = [
        'peminjaman_id',
        'barang_id',
        'jumlah',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(PeminjamanNew::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barangs::class);
    }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class PeminjamanDetail extends Model
// {
//         protected $fillable = ['peminjaman_id', 'barang_id', 'jumlah'];

//     public function peminjaman()
//     {
//         return $this->belongsTo(Peminjaman::class);
//     }

//     public function barang()
//     {
//         return $this->belongsTo(Barangs::class);
//     }
// }
