<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JimpitanMasuk;
use App\Models\JimpitanKeluar;
use App\Exports\FinancialReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Tampilkan Halaman Keuangan & Laporan
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Format dates for query
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Ambil data Pemasukan (Jimpitan Masuk)
        $masuksQuery = JimpitanMasuk::with(['warga', 'petugas'])
            ->whereBetween('created_at', [$start, $end]);

        // Ambil data Pengeluaran (Jimpitan Keluar)
        $keluarsQuery = JimpitanKeluar::with('petugas')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Hitung total untuk periode terfilter
        $totalMasukFilter = $masuksQuery->sum('nominal');
        $totalKeluarFilter = $keluarsQuery->sum('nominal');

        // Hitung akumulasi sepanjang waktu (saldo riil saat ini)
        $totalMasukAllTime = JimpitanMasuk::sum('nominal');
        $totalKeluarAllTime = JimpitanKeluar::sum('nominal');
        $saldoRiil = $totalMasukAllTime - $totalKeluarAllTime;

        $masuks = $masuksQuery->get();
        $keluars = $keluarsQuery->get();

        // Satukan transaksi untuk timeline / tabel log
        $transactions = collect();

        foreach ($masuks as $masuk) {
            $transactions->push((object)[
                'id' => 'IN-' . $masuk->id,
                'tanggal' => $masuk->created_at,
                'tipe' => 'masuk',
                'nominal' => $masuk->nominal,
                'keterangan' => 'Jimpitan: ' . ($masuk->warga ? $masuk->warga->nama_warga : 'Warga') . ' (Rumah ' . ($masuk->warga ? $masuk->warga->no_rumah : '-') . ')',
                'petugas' => $masuk->petugas ? $masuk->petugas->name : 'Sistem'
            ]);
        }

        foreach ($keluars as $keluar) {
            $transactions->push((object)[
                'id' => 'OUT-' . $keluar->id,
                'tanggal' => Carbon::parse($keluar->tanggal),
                'tipe' => 'keluar',
                'nominal' => $keluar->nominal,
                'keterangan' => $keluar->keterangan,
                'petugas' => $keluar->petugas ? $keluar->petugas->name : 'Sistem'
            ]);
        }

        // Urutkan transaksi berdasarkan tanggal terbaru
        $transactions = $transactions->sortByDesc(function ($t) {
            return $t->tanggal->timestamp;
        })->values();

        return view('admin.keuangan', [
            'transactions' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_masuk' => $totalMasukFilter,
            'total_keluar' => $totalKeluarFilter,
            'saldo_riil' => $saldoRiil,
        ]);
    }

    /**
     * Catat Pengeluaran Baru
     */
    public function storeKeluar(Request $request)
    {
        $validated = $request->validate([
            'nominal' => 'required|integer|min:100',
            'keterangan' => 'required|string|max:500',
            'tanggal' => 'required|date',
        ]);

        JimpitanKeluar::create([
            'user_id' => Auth::id(),
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'],
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('admin.keuangan')->with('success', 'Pengeluaran kas jimpitan berhasil dicatat!');
    }

    /**
     * Ekspor Laporan Excel
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $masuks = JimpitanMasuk::with(['warga', 'petugas'])->whereBetween('created_at', [$start, $end])->get();
        $keluars = JimpitanKeluar::with('petugas')->whereBetween('tanggal', [$startDate, $endDate])->get();

        return Excel::download(
            new FinancialReportExport($masuks, $keluars, $startDate, $endDate),
            "laporan_keuangan_jimpitan_{$startDate}_to_{$endDate}.xlsx"
        );
    }

    /**
     * Ekspor Laporan PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $masuks = JimpitanMasuk::with(['warga', 'petugas'])->whereBetween('created_at', [$start, $end])->get();
        $keluars = JimpitanKeluar::with('petugas')->whereBetween('tanggal', [$startDate, $endDate])->get();

        $transactions = collect();

        foreach ($masuks as $masuk) {
            $transactions->push((object)[
                'tanggal' => $masuk->created_at->toDateString(),
                'tipe' => 'Pemasukan',
                'petugas' => $masuk->petugas ? $masuk->petugas->name : 'Sistem',
                'nominal' => $masuk->nominal,
                'keterangan' => 'Jimpitan warga: ' . ($masuk->warga ? $masuk->warga->nama_warga : '-') . ' (Rumah ' . ($masuk->warga ? $masuk->warga->no_rumah : '-') . ')'
            ]);
        }

        foreach ($keluars as $keluar) {
            $transactions->push((object)[
                'tanggal' => $keluar->tanggal->toDateString(),
                'tipe' => 'Pengeluaran',
                'petugas' => $keluar->petugas ? $keluar->petugas->name : 'Sistem',
                'nominal' => $keluar->nominal,
                'keterangan' => $keluar->keterangan
            ]);
        }

        $transactions = $transactions->sortBy('tanggal')->values();

        $pdf = Pdf::loadView('admin.exports.keuangan_pdf', [
            'transactions' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_masuk' => $masuks->sum('nominal'),
            'total_keluar' => $keluars->sum('nominal'),
            'saldo' => $masuks->sum('nominal') - $keluars->sum('nominal')
        ]);

        return $pdf->download("laporan_keuangan_jimpitan_{$startDate}_to_{$endDate}.pdf");
    }
}
