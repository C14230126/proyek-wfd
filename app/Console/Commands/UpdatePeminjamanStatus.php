<?php

namespace App\Console\Commands;

use App\Models\PeminjamanNew;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdatePeminjamanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-peminjaman-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today('Asia/Jakarta')->startOfDay();

        // Find peminjaman records that are 'disetujui'
        // and whose loan period (tanggal_pinjam to tanggal_kembali)
        // includes today or has started but not yet ended
        $peminjamansToUpdate = PeminjamanNew::where('status', 'disetujui')
            ->where('tanggal_pinjam', '<=', $today)
            ->where('tanggal_kembali', '>=', $today)
            ->get();

        $count = 0;
        foreach ($peminjamansToUpdate as $peminjaman) {
            // Ensure we don't accidentally re-update if the status is already processing
            if ($peminjaman->status !== 'processing') {
                $peminjaman->status = 'processing';
                $peminjaman->save();
                $count++;
            }
        }

        $this->info("{$count} PeminjamanNew records updated to 'processing' status.");

        return Command::SUCCESS;
    }
}
