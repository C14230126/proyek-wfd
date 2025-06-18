<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'nip' => '1234567890',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'no_hp' => '081234567890',
            'status' => 'Accepted',
            'jurusan' => null,
        ]);

        User::create([
            'name' => 'arya',
            'nrp' => 'C14230126',
            'email' => 'arya@gmail.com',
            'password' => Hash::make('arya'),
            'no_hp' => '081211112222',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'valentino',
            'nrp' => 'C14230138',
            'email' => 'valentino@gmail.com',
            'password' => Hash::make('valentino'),
            'no_hp' => '081233334444',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'darrel',
            'nrp' => 'C14230235',
            'email' => 'darrel@gmail.com',
            'password' => Hash::make('darrel'),
            'no_hp' => '081255556666',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'juan',
            'nrp' => 'C14230124',
            'email' => 'juan@gmail.com',
            'password' => Hash::make('juan'),
            'no_hp' => '081277778888',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'joshua',
            'nrp' => 'C14230283',
            'email' => 'joshua@gmail.com',
            'password' => Hash::make('joshua'),
            'no_hp' => '081299990000',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'reynard',
            'nrp' => 'C14230155',
            'email' => 'reynard@gmail.com',
            'password' => Hash::make('reynard'),
            'no_hp' => '081311112222',
            'jurusan' => 'Teknologi Industri',
            'status' => 'Accepted',
        ]);
    }
}
