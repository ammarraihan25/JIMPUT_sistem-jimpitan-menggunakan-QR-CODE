<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 14px;">LAPORAN KEUANGAN KAS JIMPITAN KAMPUNG RT 02/RW 02</th>
        </tr>
        <tr>
            <th colspan="5" style="font-style: italic; font-size: 10px;">Periode: {{ \Carbon\Carbon::parse($start_date)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->isoFormat('D MMMM Y') }}</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr style="background-color: #e2e8f0; font-weight: bold;">
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center;">No</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; width: 15px;">Tanggal</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; width: 40px;">Keterangan</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; width: 15px;">Tipe</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; width: 20px;">Nominal (Rp)</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center; width: 25px;">Petugas / Pencatat</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $index => $t)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
            <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($t['tanggal'])->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000000;">{{ $t['keterangan'] }}</td>
            <td style="border: 1px solid #000000; text-align: center; color: {{ $t['tipe'] == 'Pemasukan' ? '#16a34a' : '#dc2626' }};">
                {{ $t['tipe'] }}
            </td>
            <td style="border: 1px solid #000000; text-align: right;">{{ $t['nominal'] }}</td>
            <td style="border: 1px solid #000000;">{{ $t['petugas'] }}</td>
        </tr>
        @endforeach
        
        {{-- Total rows --}}
        <tr>
            <td colspan="6" style="height: 10px;"></td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="4" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL PEMASUKAN:</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; color: #16a34a;">{{ $total_masuk }}</td>
            <td style="border: 1px solid #000000;"></td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="4" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL PENGELUARAN:</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; color: #dc2626;">{{ $total_keluar }}</td>
            <td style="border: 1px solid #000000;"></td>
        </tr>
        <tr style="font-weight: bold; background-color: #cbd5e1;">
            <td colspan="4" style="border: 1px solid #000000; text-align: right; font-weight: bold;">SALDO KAS PERIODE INI:</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; color: #0369a1;">{{ $saldo }}</td>
            <td style="border: 1px solid #000000;"></td>
        </tr>
    </tbody>
</table>
