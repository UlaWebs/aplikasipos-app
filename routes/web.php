<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\CoaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ====================================================
// GROUP 1: ZONA UMUM (ADMIN & KASIR BOLEH MASUK) ✅
// ====================================================
Route::middleware(['auth'])->group(function () {

    // 1. DASHBOARD (Isinya Dinamis: Admin liat Omzet, Kasir liat Log Pribadi)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. MESIN KASIR (Menu Utama Pegawai)
    Route::get('/kasir', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/kasir', [TransactionController::class, 'store'])->name('transactions.store');

    // 3. PROFILE USER (Ganti Password, Nama, dll)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ====================================================
// GROUP 2: ZONA KHUSUS ADMIN (KASIR DILARANG MASUK) ⛔
// ====================================================
Route::middleware(['auth', 'role:admin', 'prevent-back-history'])->group(function () {

    // 1. MASTER DATA (Dapur Toko)
    Route::resource('products', ProductController::class);

    Route::resource('restocks', RestockController::class);
    Route::get('/restocks/detail/{id}', [RestockController::class, 'detail'])->name('restocks.detail');
    Route::get('/restocks/detail/{id}/create', [RestockController::class, 'create_detail']);
    Route::post('/restocks/storedetail', [RestockController::class, 'storedetail'])->name('restocks.storedetail');
    Route::get('/restocks/detail/{id}/edit', [RestockController::class, 'edit_detail']);
    Route::patch('/restocks/detail/{id}', [RestockController::class, 'update_detail']);
    Route::get('/restocks/destroydetail/{id}', [RestockController::class, 'destroydetail'])->name('restocks.destroydetail');

    Route::resource('coas', CoaController::class);
    Route::get('/coa/{id}/edit', [CoaController::class, 'edit'])->name('coa.edit');
    Route::put('/coa/{id}', [CoaController::class, 'update'])->name('coa.update');

    // 2. LAPORAN KEUANGAN (Rahasia Perusahaan)
    Route::get('/laporan/jurnal-umum', [LaporanController::class, 'jurnalUmum'])->name('laporan.jurnal');
    Route::get('/laporan/buku-besar', [LaporanController::class, 'bukuBesar'])->name('laporan.buku_besar');
    Route::get('/laporan/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba_rugi');
    Route::get('/laporan/neraca', [LaporanController::class, 'neraca'])->name('laporan.neraca');
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');

    Route::get('/laporan/penjualan/data/{periode?}', [LaporanController::class, 'viewdatalaporan_penjualan'])->name('laporan.penjualan.data');
    Route::get('/laporan/pembelian/data/{periode?}', [LaporanController::class, 'viewdatalaporan_pembelian'])->name('laporan.pembelian.data');
});

require __DIR__ . '/auth.php';
