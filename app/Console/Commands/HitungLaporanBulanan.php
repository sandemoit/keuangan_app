<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Laporan;
use App\Models\Transaction;
use Carbon\Carbon;

class HitungLaporanBulanan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sum:saldo';

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
        $now = Carbon::now();
        $bulan = date('m', strtotime($now));
        $tahun = $now->year;

        // Ambil transaksi bulan ini
        $transaksi = Transaction::whereMonth('date_trx', $bulan)
            ->whereYear('date_trx', $tahun)
            ->get();

        $totalPemasukan = $transaksi->where('type', 'income')->sum('amount');
        $totalPengeluaran = $transaksi->where('type', 'expense')->sum('amount');

        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Simpan ke laporan
        Laporan::updateOrCreate(
            [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'saldo' => $saldoAkhir
            ],
        );
    }
}
