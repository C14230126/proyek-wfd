<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanNew extends Model
{
    protected $table = 'peminjaman_new';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'nama_acara',
        'lokasi_acara',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PeminjamanNewDetail::class, 'peminjaman_new_id');
    }
}

