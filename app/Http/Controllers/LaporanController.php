<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Laporan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function laporanBulanan()
    {
        $title = 'Laporan Transaksi Bulanan';
        // Kurangi 1 bulan
        $now = Carbon::now();
        $bulan = Carbon::now()->subMonth()->format('m');
        $bulans = $now->month;
        $tahun = $now->year;

        // Get the initial balance from the previous month's report
        $saldoAwalBulanKemarin = Laporan::where('bulan', $bulan)->where('tahun', $tahun)->value('saldo') ?? 0;
        $pemasukan = saldo_sum('income', date('Y-m'));
        $pengeluaran = saldo_sum('expense', date('Y-m'));
        $akumulasi = saldo_sum('totalSaldo', date('Y-m'));
        $saldoAkhirBulanIni = $saldoAwalBulanKemarin + $pemasukan - $pengeluaran;

        // Format values for display after calculations are complete
        $pemasukan = rupiah($pemasukan);
        $pengeluaran = rupiah($pengeluaran);
        $akumulasi = rupiah($akumulasi);
        $saldoAkhirBulanIni = rupiah($saldoAkhirBulanIni);

        // Ambil semua kategori dan total pengeluaran/income bulan ini
        $kateogirExpense = Category::whereHas('transactions', function ($query) use ($bulans, $tahun) {
            $query->whereMonth('date_trx', $bulans)
                ->whereYear('date_trx', $tahun)
                ->where('is_expense', 1);
        })
            ->withSum(['transactions' => function ($query) use ($bulans, $tahun) {
                $query->whereMonth('date_trx', $bulans)
                    ->whereYear('date_trx', $tahun)
                    ->where('is_expense', 1);
            }], 'amount')
            ->get();

        // Ambil semua kategori dan total income bulan ini
        $kategoriIncome = Category::whereHas('transactions', function ($query) use ($bulans, $tahun) {
            $query->whereMonth('date_trx', $bulans)
                ->whereYear('date_trx', $tahun)
                ->where('is_expense', 0);
        })
            ->withSum(['transactions' => function ($query) use ($bulans, $tahun) {
                $query->whereMonth('date_trx', $bulans)
                    ->whereYear('date_trx', $tahun)
                    ->where('is_expense', 0);
            }], 'amount')
            ->get();

        return view('laporan.bulanan', compact('title', 'saldoAwalBulanKemarin', 'pemasukan', 'pengeluaran', 'akumulasi', 'saldoAkhirBulanIni', 'kateogirExpense', 'kategoriIncome'));
    }
}
