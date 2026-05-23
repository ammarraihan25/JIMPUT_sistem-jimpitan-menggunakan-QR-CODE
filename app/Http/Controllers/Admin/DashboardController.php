<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JimpitanMasuk;
use App\Models\JimpitanKeluar;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard admin - rekap dan statistik
     */
    public function index()
    {
        // Statistik utama
        $totalWarga     = Warga::where('aktif', true)->count();
        $totalPetugas   = User::where('role', 'petugas')->count();
        $totalJimpitan  = JimpitanMasuk::sum('nominal');
        $totalCatatan   = JimpitanMasuk::count();

        // Pengeluaran dan Saldo Kas
        $totalPengeluaran = JimpitanKeluar::sum('nominal');
        $saldoKas        = $totalJimpitan - $totalPengeluaran;

        // Bulan ini
        $jimpitanBulanIni = JimpitanMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('nominal');

        $catatanBulanIni = JimpitanMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Hari ini
        $jimpitanHariIni = JimpitanMasuk::whereDate('created_at', today())->sum('nominal');
        $catatanHariIni  = JimpitanMasuk::whereDate('created_at', today())->count();

        // Warga belum bayar bulan ini
        $sudahBayarIds = JimpitanMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->pluck('warga_id')
            ->unique();

        $belumBayar = Warga::where('aktif', true)
            ->whereNotIn('id', $sudahBayarIds)
            ->count();

        // Aktivitas terbaru (20 data)
        $aktivitasTerbaru = JimpitanMasuk::with(['warga', 'petugas'])
            ->latest()
            ->take(20)
            ->get();

        // Performa petugas bulan ini
        $performaPetugas = User::where('role', 'petugas')
            ->withCount(['jimpitanMasuks as catatan_bulan_ini' => function ($q) {
                $q->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
            }])
            ->orderByDesc('catatan_bulan_ini')
            ->get();

        $pengeluaranBulanIni = JimpitanKeluar::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('nominal');

        // Data grafik 7 hari terakhir
        $grafikData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);
            $grafikData->push([
                'tanggal' => $tgl->format('d/m'),
                'total'   => JimpitanMasuk::whereDate('created_at', $tgl)->sum('nominal'),
                'count'   => JimpitanMasuk::whereDate('created_at', $tgl)->count(),
            ]);
        }

        return view('admin.dashboard', compact(
            'totalWarga', 'totalPetugas', 'totalJimpitan', 'totalCatatan',
            'totalPengeluaran', 'saldoKas', 'pengeluaranBulanIni',
            'jimpitanBulanIni', 'catatanBulanIni',
            'jimpitanHariIni', 'catatanHariIni',
            'belumBayar', 'aktivitasTerbaru', 'performaPetugas', 'grafikData'
        ));
    }
}
