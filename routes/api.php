<?php

use App\Http\Controllers\JimpitanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Jimput QR Scan Endpoint
|--------------------------------------------------------------------------
| Endpoint ini dipanggil oleh JavaScript Fetch API di halaman scanner.
| Dilindungi dengan session auth (web guard) karena menggunakan cookie
| CSRF yang sama dengan web routes.
*/

Route::middleware(['auth:web'])->group(function () {
    // POST /api/scan — proses QR token dari scanner petugas
    Route::post('/scan', [JimpitanController::class, 'scan'])->name('api.scan');
});
