<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('name', 'admin')->first();
        $juan = User::where('name', 'juan')->first();
        $arya = User::where('name', 'arya')->first();
        $darrel = User::where('name', 'darrel')->first();
        $joshua = User::where('name', 'joshua')->first();
        $reynard = User::where('name', 'reynard')->first();
        $valentino = User::where('name', 'valentino')->first();

        // Admin role
        Roles::create([
            'user_id' => $admin->id,
            'role' => 'admin',
        ]);

        // Semua mahasiswa punya role mahasiswa
        foreach ([$juan, $arya, $darrel, $joshua, $reynard, $valentino] as $user) {
            Roles::create([
                'user_id' => $user->id,
                'role' => 'mahasiswa',
            ]);
        }
    }
}
