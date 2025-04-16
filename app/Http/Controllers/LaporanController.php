<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Laporan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function laporanHarian()
    {
        $title = 'Laporan Transaksi Harian';
        // Get the current date
        $tanggal = Carbon::now();
        $bulan = Carbon::now()->subMonth()->format('m');

        // Get the initial balance from the previous day
        $saldoAwalHariKemarin = Laporan::where('bulan', $bulan)->where('tahun', $tanggal->year)->value('saldo') ?? 0;

        $pemasukan = saldo_sum('income', $tanggal->format('Y-m-d'));
        $pengeluaran = saldo_sum('expense', $tanggal->format('Y-m-d'));
        $akumulasi = saldo_sum('selisih', $tanggal->format('Y-m-d'));
        $saldoAkhirHariIni = $saldoAwalHariKemarin + $pemasukan - $pengeluaran;

        // Store raw values for charts
        $pemasukanRaw = $pemasukan;
        $pengeluaranRaw = $pengeluaran;

        // Format values for display after calculations are complete
        $pemasukan = rupiah($pemasukan);
        $pengeluaran = rupiah($pengeluaran);
        $akumulasi = rupiah($akumulasi);
        $saldoAkhirHariIni = rupiah($saldoAkhirHariIni);

        // Ambil semua kategori dan total pengeluaran/income hari ini
        $kategoriExpense = Category::whereHas('transactions', function ($query) use ($tanggal) {
            $query->whereDate('date_trx', $tanggal)
                ->where('is_expense', 1);
        })
            ->withSum(['transactions' => function ($query) use ($tanggal) {
                $query->whereDate('date_trx', $tanggal)
                    ->where('is_expense', 1);
            }], 'amount')
            ->get();

        // Ambil semua kategori dan total income hari ini
        $kategoriIncome = Category::whereHas('transactions', function ($query) use ($tanggal) {
            $query->whereDate('date_trx', $tanggal)
                ->where('is_expense', 0);
        })
            ->withSum(['transactions' => function ($query) use ($tanggal) {
                $query->whereDate('date_trx', $tanggal)
                    ->where('is_expense', 0);
            }], 'amount')
            ->get();

        // Prepare chart data - make sure we convert to arrays
        $labelsIncome = $kategoriIncome->pluck('name')->toArray();
        $dataIncome = $kategoriIncome->pluck('transactions_sum_amount')->map(fn($val) => (float) $val)->toArray();
        $labelsExpense = $kategoriExpense->pluck('name')->toArray();
        $dataExpense = $kategoriExpense->pluck('transactions_sum_amount')->map(fn($val) => (float) $val)->toArray();

        // Generate some colors for the charts
        $colorsIncome = [];
        $colorsExpense = [];

        // Generate colors for each category
        foreach ($labelsIncome as $index => $label) {
            $colorsIncome[] = $this->getRandomColor($index);
        }

        foreach ($labelsExpense as $index => $label) {
            $colorsExpense[] = $this->getRandomColor($index + 10); // Offset to get different colors
        }

        return view('laporan.harian', compact(
            'title',
            'saldoAwalHariKemarin',
            'pemasukan',
            'pengeluaran',
            'akumulasi',
            'saldoAkhirHariIni',
            'kategoriExpense',
            'kategoriIncome',
            'labelsExpense',
            'dataExpense',
            'labelsIncome',
            'dataIncome',
            'colorsIncome',
            'colorsExpense',
            'pemasukanRaw',
            'pengeluaranRaw'
        ));
    }

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
        $akumulasi = saldo_sum('selisih', date('Y-m'));
        $saldoAkhirBulanIni = $saldoAwalBulanKemarin + $pemasukan - $pengeluaran;

        // Store raw values for charts
        $pemasukanRaw = $pemasukan;
        $pengeluaranRaw = $pengeluaran;

        // Format values for display after calculations are complete
        $pemasukan = rupiah($pemasukan);
        $pengeluaran = rupiah($pengeluaran);
        $akumulasi = rupiah($akumulasi);
        $saldoAkhirBulanIni = rupiah($saldoAkhirBulanIni);

        // Ambil semua kategori dan total pengeluaran/income bulan ini
        $kategoriExpense = Category::whereHas('transactions', function ($query) use ($bulans, $tahun) {
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

        // Prepare chart data - make sure we convert to arrays
        $labelsIncome = $kategoriIncome->pluck('name')->toArray();
        $dataIncome = $kategoriIncome->pluck('transactions_sum_amount')->map(fn($val) => (float) $val)->toArray();
        $labelsExpense = $kategoriExpense->pluck('name')->toArray();
        $dataExpense = $kategoriExpense->pluck('transactions_sum_amount')->map(fn($val) => (float) $val)->toArray();

        // Generate some colors for the charts
        $colorsIncome = [];
        $colorsExpense = [];

        // Generate colors for each category
        foreach ($labelsIncome as $index => $label) {
            $colorsIncome[] = $this->getRandomColor($index);
        }

        foreach ($labelsExpense as $index => $label) {
            $colorsExpense[] = $this->getRandomColor($index + 10); // Offset to get different colors
        }

        return view('laporan.bulanan', compact(
            'title',
            'saldoAwalBulanKemarin',
            'pemasukan',
            'pengeluaran',
            'akumulasi',
            'saldoAkhirBulanIni',
            'kategoriExpense',
            'kategoriIncome',
            'labelsExpense',
            'dataExpense',
            'labelsIncome',
            'dataIncome',
            'colorsIncome',
            'colorsExpense',
            'pemasukanRaw',
            'pengeluaranRaw'
        ));
    }

    private function getRandomColor($seed = null)
    {
        $colors = [
            '#4299E1',
            '#48BB78',
            '#F6AD55',
            '#F56565',
            '#9F7AEA',
            '#38B2AC',
            '#ED8936',
            '#667EEA',
            '#F687B3',
            '#68D391',
            '#FC8181',
            '#B794F4',
            '#4FD1C5',
            '#FBD38D',
            '#90CDF4'
        ];

        if ($seed !== null) {
            return $colors[$seed % count($colors)];
        }

        return $colors[array_rand($colors)];
    }
}
