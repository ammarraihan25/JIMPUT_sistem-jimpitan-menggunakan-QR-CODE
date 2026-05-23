<?php

namespace App\Http\Controllers;

use App\Models\JimpitanMasuk;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JimpitanController extends Controller
{
    /**
     * Tampilkan halaman scanner QR
     */
    public function scanner()
    {
        $petugas = Auth::user();
        $totalHariIni = JimpitanMasuk::where('user_id', $petugas->id)
            ->whereDate('created_at', today())
            ->count();

        $nominalHariIni = JimpitanMasuk::where('user_id', $petugas->id)
            ->whereDate('created_at', today())
            ->sum('nominal');

        $riwayat = JimpitanMasuk::with('warga')
            ->where('user_id', $petugas->id)
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();

        return view('scanner', compact('petugas', 'totalHariIni', 'nominalHariIni', 'riwayat'));
    }

    /**
     * API Endpoint: Proses scan QR token → simpan jimpitan
     * POST /api/scan
     */
    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = trim($request->token);

        // Cari warga berdasarkan token
        $warga = Warga::where('qr_token', $token)->where('aktif', true)->first();

        if (!$warga) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ QR Code tidak dikenali atau warga tidak aktif.',
                'code'    => 'INVALID_TOKEN',
            ], 404);
        }

        // Cek apakah sudah dicatat hari ini (anti duplikat dalam 30 detik)
        $sudahDicatat = JimpitanMasuk::where('warga_id', $warga->id)
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($sudahDicatat) {
            return response()->json([
                'status'  => 'warning',
                'message' => '⚠️ Jimpitan ' . $warga->nama_warga . ' baru saja dicatat. Tunggu sebentar.',
                'warga'   => [
                    'nama'     => $warga->nama_warga,
                    'no_rumah' => $warga->no_rumah,
                    'rt_rw'    => $warga->rt_rw,
                ],
                'code'    => 'DUPLICATE_SCAN',
            ], 409);
        }

        // Simpan jimpitan
        $jimpitan = JimpitanMasuk::create([
            'warga_id' => $warga->id,
            'user_id'  => Auth::id(),
            'nominal'  => 2000,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => '✅ Berhasil! Jimpitan ' . $warga->nama_warga . ' telah dicatat.',
            'warga'   => [
                'nama'     => $warga->nama_warga,
                'no_rumah' => $warga->no_rumah,
                'rt_rw'    => $warga->rt_rw,
            ],
            'jimpitan' => [
                'id'       => $jimpitan->id,
                'nominal'  => 'Rp ' . number_format($jimpitan->nominal, 0, ',', '.'),
                'waktu'    => $jimpitan->created_at->format('H:i:s'),
            ],
        ], 201);
    }

    /**
     * Riwayat scan hari ini (AJAX refresh)
     */
    public function riwayatHariIni()
    {
        $riwayat = JimpitanMasuk::with('warga')
            ->where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($item) => [
                'nama'     => $item->warga->nama_warga,
                'no_rumah' => $item->warga->no_rumah,
                'nominal'  => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                'waktu'    => $item->created_at->format('H:i'),
            ]);

        return response()->json([
            'status'  => 'success',
            'riwayat' => $riwayat,
            'total'   => $riwayat->count(),
        ]);
    }
}
