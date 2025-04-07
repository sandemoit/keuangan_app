<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi');
Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
Route::get('/transaksi/data', [TransactionController::class, 'data'])->name('data');
Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');

Route::resource('/category', CategoryController::class);

Route::get('/laporan/bulanan', [LaporanController::class, 'laporanBulanan'])->name('laporan.bulanan');
