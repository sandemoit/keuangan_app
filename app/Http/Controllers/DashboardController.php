<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FinancialReminder;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $bulan = Carbon::now()->subMonth()->format('m');
        $bulans = $now->month;
        $tahun = $now->year;

        $title = 'Dashboard';
        $totalSaldo = rupiah(saldo_sum('saldo', date('Y-m')));
        $selisih = rupiah(saldo_sum('selisih', date('Y-m')));
        $income = rupiah(saldo_sum('income', date('Y-m')));
        $expense = rupiah(saldo_sum('expense', date('Y-m')));

        // Cari kategori pengeluaran tertinggi bulan ini
        $topExpenseCategories = Category::whereHas('transactions', function ($query) use ($bulans, $tahun) {
            $query->whereMonth('date_trx', $bulans)
                ->whereYear('date_trx', $tahun)
                ->where('is_expense', 1);
        })
            ->withSum(['transactions' => function ($query) use ($bulans, $tahun) {
                $query->whereMonth('date_trx', $bulans)
                    ->whereYear('date_trx', $tahun)
                    ->where('is_expense', 1);
            }], 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('category') // biar bisa akses nama kategori
            ->whereMonth('date_trx', $bulans)
            ->whereYear('date_trx', $tahun)
            ->orderByDesc('date_trx')
            ->take(5)
            ->get();

        $reminders = FinancialReminder::where('is_active', true)
            ->select('id', 'name', 'day_of_month', 'description', 'nominal', 'is_active')
            ->orderBy('day_of_month')
            ->get();

        return view('dashboard.index', compact('title', 'totalSaldo', 'income', 'expense', 'selisih', 'topExpenseCategories', 'recentTransactions', 'reminders'));
    }
}
