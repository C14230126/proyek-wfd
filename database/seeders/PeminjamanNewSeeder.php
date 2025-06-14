<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeminjamanNew;
use App\Models\User; // Digunakan untuk mendapatkan user_id
use Carbon\Carbon; // Digunakan untuk manipulasi tanggal

class PeminjamanNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Contoh 1: Peminjaman satu hari
        $tanggalPinjam1 = Carbon::parse('2025-06-15');
        $tanggalKembali1 = Carbon::parse('2025-06-15');
        $dailyTimes1 = [
            '2025-06-15' => [
                'start_time' => '08:00', 
                'end_time' => '12:00'
            ]
        ];

        PeminjamanNew::create([
            'user_id' => 2,
            'nama_acara' => 'Meeting Tahunan',
            'lokasi_acara' => 'Gedung W Ruang 101',
            'tanggal_pinjam' => $tanggalPinjam1,
            'tanggal_kembali' => $tanggalKembali1,
            'status' => 'disetujui',
            'daily_times' => $dailyTimes1, // Ini akan otomatis di-cast ke JSON oleh model
        ]);

        // Contoh 2: Peminjaman beberapa hari
        $tanggalPinjam2 = Carbon::parse('2025-06-16');
        $tanggalKembali2 = Carbon::parse('2025-06-18');
        $dailyTimes2 = [];
        $currentDate = $tanggalPinjam2->copy();
        while ($currentDate->lte($tanggalKembali2)) {
            $dateString = $currentDate->format('Y-m-d');
            $dailyTimes2[$dateString] = [
                'start_time' => '09:00',
                'end_time' => '16:00'
            ];
            $currentDate->addDay();
        }

        PeminjamanNew::create([
            'user_id' => 4,
            'nama_acara' => 'Workshop IT Kampus',
            'lokasi_acara' => 'Gedung P Auditorium',
            'tanggal_pinjam' => $tanggalPinjam2,
            'tanggal_kembali' => $tanggalKembali2,
            'status' => 'menunggu',
            'daily_times' => $dailyTimes2,
        ]);


        // Contoh 2: Peminjaman beberapa hari
        $tanggalPinjam2 = Carbon::parse('2025-06-16');
        $tanggalKembali2 = Carbon::parse('2025-06-18');
        $dailyTimes2 = [];
        $currentDate = $tanggalPinjam2->copy();
        while ($currentDate->lte($tanggalKembali2)) {
            $dateString = $currentDate->format('Y-m-d');
            $dailyTimes2[$dateString] = [
                'start_time' => '16:00',
                'end_time' => '20:00'
            ];
            $currentDate->addDay();
        }

        PeminjamanNew::create([
            'user_id' => 4,
            'nama_acara' => 'Pertemuan Komunitas Teknologi',
            'lokasi_acara' => 'Gedung P Auditorium',
            'tanggal_pinjam' => $tanggalPinjam2,
            'tanggal_kembali' => $tanggalKembali2,
            'status' => 'menunggu',
            'daily_times' => $dailyTimes2,
        ]);

        // Contoh 3: Peminjaman yang sudah selesai
        $tanggalPinjam3 = Carbon::parse('2025-06-01');
        $tanggalKembali3 = Carbon::parse('2025-06-03');
        $dailyTimes3 = [];
        $currentDate = $tanggalPinjam3->copy();
        while ($currentDate->lte($tanggalKembali3)) {
            $dateString = $currentDate->format('Y-m-d');
            $dailyTimes3[$dateString] = [
                'start_time' => '10:00',
                'end_time' => '15:00'
            ];
            $currentDate->addDay();
        }

        PeminjamanNew::create([
            'user_id' => 5,
            'nama_acara' => 'Pelatihan Desain Grafis',
            'lokasi_acara' => 'Gedung Q Lab Komputer',
            'tanggal_pinjam' => $tanggalPinjam3,
            'tanggal_kembali' => $tanggalKembali3,
            'status' => 'selesai',
            'daily_times' => $dailyTimes3,
        ]);
    }
}
