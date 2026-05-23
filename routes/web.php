<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JimpitanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WargaController;
use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Jimput: Sistem Jimpitan QR Code
|--------------------------------------------------------------------------
*/

// === AUTH ROUTES ===
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === PETUGAS ROUTES ===
Route::middleware(['auth'])->group(function () {
    Route::get('/scanner', [JimpitanController::class, 'scanner'])->name('scanner');
    Route::get('/riwayat-hari-ini', [JimpitanController::class, 'riwayatHariIni'])->name('riwayat.hari-ini');
});

// === ADMIN ROUTES ===
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Warga
    Route::resource('wargas', WargaController::class);
    Route::get('/wargas/{warga}/cetak', [WargaController::class, 'cetakQr'])->name('wargas.cetak');
    Route::post('/wargas/{warga}/regenerate-qr', [WargaController::class, 'regenerateQr'])->name('wargas.regenerate-qr');

    // Keuangan & Laporan
    Route::get('/keuangan', [FinancialReportController::class, 'index'])->name('keuangan');
    Route::post('/keuangan/keluar', [FinancialReportController::class, 'storeKeluar'])->name('keuangan.keluar.store');
    Route::get('/keuangan/export/excel', [FinancialReportController::class, 'exportExcel'])->name('keuangan.export.excel');
    Route::get('/keuangan/export/pdf', [FinancialReportController::class, 'exportPdf'])->name('keuangan.export.pdf');
});

