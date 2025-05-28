<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialReminderController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi');
Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
Route::get('/transaksi/data', [TransactionController::class, 'data'])->name('data');
Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');
Route::get('/get-kategori', function () {
  $kategori = \App\Models\Category::where('is_expense', request('is_expense'))->get();
  return response()->json($kategori);
});


Route::resource('/category', CategoryController::class);

Route::get('/laporan/bulanan', [LaporanController::class, 'laporanBulanan'])->name('laporan.bulanan');
Route::get('/laporan/harian', [LaporanController::class, 'laporanHarian'])->name('laporan.harian');

Route::get('/keuangan/masuk', [KeuanganController::class, 'masuk'])->name('keuangan.masuk');
Route::get('/keuangan/keluar', [KeuanganController::class, 'keluar'])->name('keuangan.keluar');

Route::resource('/target-keuangan', TargetController::class);

Route::resource('/reminder-keuangan', FinancialReminderController::class);


Route::get('/summary', [SummaryController::class, 'index'])->name('summary');
Route::post('/ai-summary', [SummaryController::class, 'generateSummary']);
// routes/api.php
