<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeminjamanNew;
use App\Models\PeminjamanNewDetail;
use App\Models\Barangs; // Digunakan untuk mendapatkan barang_id

class PeminjamanNewDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua peminjaman yang ada
        $peminjamanNew = PeminjamanNew::all();
        // Ambil semua barang yang ada
        $barangs = Barangs::all();

        foreach ($peminjamanNew as $peminjaman) {
            // Untuk setiap peminjaman, tambahkan 1 hingga 3 detail barang
            $numberOfDetails = rand(1, 3);
            $selectedBarangIds = $barangs->random($numberOfDetails)->pluck('id')->toArray();

            foreach ($selectedBarangIds as $barangId) {
                PeminjamanNewDetail::create([
                    'peminjaman_new_id' => $peminjaman->id,
                    'barang_id' => $barangId,
                    'jumlah' => rand(1, 5), // Jumlah barang acak antara 1 dan 5
                ]);
            }
        }
    }
}
