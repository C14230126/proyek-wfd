<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barangs;
use App\Models\Kategori;

class BarangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil kategori dulu satu-satu biar gampang assign kategori_id
        $audio = Kategori::where('type', 'Audio')->first();
        $furniture = Kategori::where('type', 'Furniture')->first();
        $elektronik = Kategori::where('type', 'Elektronik')->first();

        Barangs::create([
            'item' => 'Speaker',
            'kategori_id' => $audio->id,
            'jumlah_unit' => 10,
            'lokasi' => 'Gedung T',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Kabel roll',
            'kategori_id' => $audio->id,
            'jumlah_unit' => 5,
            'lokasi' => 'Gedung P',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Kamera',
            'kategori_id' => $elektronik->id,
            'jumlah_unit' => 3,
            'lokasi' => 'Gedung Q',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Tripod',
            'kategori_id' => $elektronik->id,
            'jumlah_unit' => 4,
            'lokasi' => 'Gedung Q',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Mic',
            'kategori_id' => $audio->id,
            'jumlah_unit' => 8,
            'lokasi' => 'Gedung T',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Kursi',
            'kategori_id' => $furniture->id,
            'jumlah_unit' => 50,
            'lokasi' => 'Gedung W',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Meja',
            'kategori_id' => $furniture->id,
            'jumlah_unit' => 30,
            'lokasi' => 'Gedung W',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'HT',
            'kategori_id' => $elektronik->id,
            'jumlah_unit' => 7,
            'lokasi' => 'Gedung P',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Earpiece',
            'kategori_id' => $elektronik->id,
            'jumlah_unit' => 12,
            'lokasi' => 'Gedung P',
            'status' => 'Returned',
        ]);

        Barangs::create([
            'item' => 'Taplak Meja',
            'kategori_id' => $furniture->id,
            'jumlah_unit' => 15,
            'lokasi' => 'Gedung W',
            'status' => 'Returned',
        ]);
    }
}
