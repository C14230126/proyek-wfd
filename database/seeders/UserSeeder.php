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
            'nrp' => '1234567890',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'no_hp' => '081234567890',
            'status' => 'Accepted',
        ]);

        // Mahasiswa
        User::create([
            'name' => 'juan',
            'nrp' => '11111111',
            'email' => 'juan@gmail.com',
            'password' => Hash::make('juan'),
            'no_hp' => '081234567891',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'arya',
            'nrp' => '22222222',
            'email' => 'arya@gmail.com',
            'password' => Hash::make('arya'),
            'no_hp' => '081234567892',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'darrel',
            'nrp' => '33333333',
            'email' => 'darrel@gmail.com',
            'password' => Hash::make('darrel'),
            'no_hp' => '081234567893',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'joshua',
            'nrp' => '44444444',
            'email' => 'joshua@gmail.com',
            'password' => Hash::make('joshua'),
            'no_hp' => '081234567894',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'reynard',
            'nrp' => '55555555',
            'email' => 'reynard@gmail.com',
            'password' => Hash::make('reynard'),
            'no_hp' => '081234567895',
            'status' => 'Accepted',
        ]);

        User::create([
            'name' => 'valentino',
            'nrp' => '66666666',
            'email' => 'valentino@gmail.com',
            'password' => Hash::make('valentino'),
            'no_hp' => '081234567896',
            'status' => 'Accepted',
        ]);
    }
}
