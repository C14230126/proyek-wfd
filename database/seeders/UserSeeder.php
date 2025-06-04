<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'no_hp' => '081234567890',
            'status' => 'Accepted',
        ]);

        // Mahasiswa
        User::create([
            'name' => 'juan',
            'email' => 'juan@gmail.com',
            'password' => Hash::make('juan'),
            'no_hp' => '081234567891',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'arya',
            'email' => 'arya@gmail.com',
            'password' => Hash::make('arya'),
            'no_hp' => '081234567892',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'darrel',
            'email' => 'darrel@gmail.com',
            'password' => Hash::make('darrel'),
            'no_hp' => '081234567893',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'joshua',
            'email' => 'joshua@gmail.com',
            'password' => Hash::make('joshua'),
            'no_hp' => '081234567894',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'reynard',
            'email' => 'reynard@gmail.com',
            'password' => Hash::make('reynard'),
            'no_hp' => '081234567895',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'valentino',
            'email' => 'valentino@gmail.com',
            'password' => Hash::make('valentino'),
            'no_hp' => '081234567896',
            'status' => 'Accepted',
        ]);
    }
}
