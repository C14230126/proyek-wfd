<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->truncate();

        $kategori = ['Elektronik', 'Audio', 'Furniture'];

        foreach ($kategori as $type) {
            DB::table('kategori')->insert([
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
