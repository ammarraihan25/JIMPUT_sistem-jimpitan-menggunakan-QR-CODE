<?php

namespace App\Exports;

use App\Models\JimpitanMasuk;
use App\Models\JimpitanKeluar;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $masuks;
    protected $keluars;
    protected $start_date;
    protected $end_date;

    public function __construct($masuks, $keluars, $start_date, $end_date)
    {
        $this->masuks = $masuks;
        $this->keluars = $keluars;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        $transactions = collect();

        foreach ($this->masuks as $masuk) {
            $transactions->push([
                'tanggal' => $masuk->created_at->toDateString(),
                'tipe' => 'Pemasukan',
                'petugas' => $masuk->petugas ? $masuk->petugas->name : 'Sistem',
                'nominal' => $masuk->nominal,
                'keterangan' => 'Jimpitan warga: ' . ($masuk->warga ? $masuk->warga->nama_warga : '-') . ' (Rumah ' . ($masuk->warga ? $masuk->warga->no_rumah : '-') . ')'
            ]);
        }

        foreach ($this->keluars as $keluar) {
            $transactions->push([
                'tanggal' => $keluar->tanggal->toDateString(),
                'tipe' => 'Pengeluaran',
                'petugas' => $keluar->petugas ? $keluar->petugas->name : 'Sistem',
                'nominal' => $keluar->nominal,
                'keterangan' => $keluar->keterangan
            ]);
        }

        $transactions = $transactions->sortBy('tanggal');

        return view('admin.exports.keuangan_excel', [
            'transactions' => $transactions,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_masuk' => $this->masuks->sum('nominal'),
            'total_keluar' => $this->keluars->sum('nominal'),
            'saldo' => $this->masuks->sum('nominal') - $this->keluars->sum('nominal')
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['italic' => true, 'size' => 10]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}
