<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanNew extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_new'; // Pastikan nama tabel benar jika tidak konvensi

    protected $fillable = [
        'user_id',
        'nama_acara',
        'lokasi_acara',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'daily_times', // PASTIKAN INI ADA DI FILLABLE
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'daily_times' => 'array', // Pastikan ini ada dan tipenya 'array'
    ];

    // Tambahkan relasi lain jika ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PeminjamanNewDetail::class, 'peminjaman_new_id');
    }
}