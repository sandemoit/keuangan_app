<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function masuk()
    {
        $query = Transaction::with('category:id,name,is_expense')
            ->where('type', 'income')
            ->select('id', 'category_id', 'amount', 'date_trx', 'payment_method', 'type', 'description')
            ->orderBy('date_trx', 'desc');

        if (request('start') && request('end')) {
            $start = Carbon::createFromFormat('d-m-Y', request('start'))->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', request('end'))->endOfDay();

            $query->whereBetween('date_trx', [$start, $end]);
        }

        $transaksi = $query->get();

        $title = 'Keuangan Masuk';
        return view('keuangan.pemasukan', compact('title', 'transaksi'));
    }

    public function keluar()
    {
        $query = Transaction::with('category:id,name,is_expense')
            ->where('type', 'expense')
            ->select('id', 'category_id', 'amount', 'date_trx', 'payment_method', 'type', 'description')
            ->orderBy('date_trx', 'desc');

        if (request('start') && request('end')) {
            $start = Carbon::createFromFormat('d-m-Y', request('start'))->startOfDay();
            $end = Carbon::createFromFormat('d-m-Y', request('end'))->endOfDay();

            $query->whereBetween('date_trx', [$start, $end]);
        }

        $transaksi = $query->get();

        $title = 'Keuangan Keluar';
        return view('keuangan.pengeluaran', compact('title', 'transaksi'));
    }
}
