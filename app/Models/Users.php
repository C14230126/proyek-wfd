<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
        public function role()
    {
        return $this->hasOne(Roles::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function peminjamanAdmin()
    {
        return $this->hasMany(Peminjaman::class, 'admin_id');
    }
}
