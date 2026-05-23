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

// Moved session-based /api/scan route to routes/web.php to properly start sessions and validate CSRF tokens.
