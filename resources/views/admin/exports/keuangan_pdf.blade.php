<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Jimpitan</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #334155;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 11px;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-box td {
            padding: 10px 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }
        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }
        .val-masuk { color: #16a34a; }
        .val-keluar { color: #dc2626; }
        .val-saldo { color: #0284c7; }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #cbd5e1;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-masuk {
            color: #15803d;
            font-weight: bold;
        }
        .badge-keluar {
            color: #b91c1c;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature-title {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 60px;
        }
        .signature-name {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Keuangan Kas Jimpitan</h1>
        <p>RT 02 / RW 02 - Ronda Malam Digital Kampung</p>
        <p style="font-weight: bold; margin-top: 3px; color: #475569;">
            Periode Laporan: {{ \Carbon\Carbon::parse($start_date)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->isoFormat('D MMMM Y') }}
        </p>
    </div>

    {{-- Ringkasan --}}
    <table class="summary-box">
        <tr>
            <td style="width: 33.3%;">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-value val-masuk">Rp {{ number_format($total_masuk, 0, ',', '.') }}</div>
            </td>
            <td style="width: 33.3%;">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value val-keluar">Rp {{ number_format($total_keluar, 0, ',', '.') }}</div>
            </td>
            <td style="width: 33.3%; background: #f0f9ff; border-color: #bae6fd;">
                <div class="summary-label" style="color: #0369a1;">Saldo Periode</div>
                <div class="summary-value val-saldo">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Tabel Transaksi --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 13%;" class="text-center">Tanggal</th>
                <th style="width: 47%;">Keterangan</th>
                <th style="width: 12%;" class="text-center">Tipe</th>
                <th style="width: 23%;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $t)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $t->keterangan }}</td>
                <td class="text-center">
                    @if($t->tipe == 'Pemasukan')
                        <span class="badge-masuk">MASUK</span>
                    @else
                        <span class="badge-keluar">KELUAR</span>
                    @endif
                </td>
                <td class="text-right" style="font-weight: bold; color: {{ $t->tipe == 'Pemasukan' ? '#15803d' : '#b91c1c' }}">
                    Rp {{ number_format($t->nominal, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #94a3b8;">
                    Tidak ada transaksi dalam periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <table class="footer" style="width: 100%;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0; color: #475569;">Yogyakarta, {{ now()->isoFormat('D MMMM Y') }}</p>
                <div class="signature-title">Koordinator Paguyuban RT 02</div>
                <div class="signature-name">Administrator Koordinator</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 2px;">Sistem Ronda Digital Jimput</div>
            </td>
        </tr>
    </table>

</body>
</html>
