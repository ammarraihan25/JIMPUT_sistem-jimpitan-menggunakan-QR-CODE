<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $wargas = Warga::when($search, function ($q) use ($search) {
                $q->where('nama_warga', 'like', "%$search%")
                  ->orWhere('no_rumah', 'like', "%$search%")
                  ->orWhere('rt_rw', 'like', "%$search%");
            })
            ->withCount('jimpitanMasuks')
            ->latest()
            ->paginate(20);

        return view('admin.wargas.index', compact('wargas', 'search'));
    }

    public function create()
    {
        return view('admin.wargas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_warga' => 'required|string|max:100',
            'no_rumah'   => 'required|string|max:20',
            'rt_rw'      => 'required|string|max:20',
        ]);

        $warga = Warga::create([
            'nama_warga' => $request->nama_warga,
            'no_rumah'   => $request->no_rumah,
            'rt_rw'      => $request->rt_rw,
            'qr_token'   => 'token-warga-' . Str::random(12) . '-' . time(),
            'aktif'      => true,
        ]);

        return redirect()->route('admin.wargas.index')
            ->with('success', 'Warga ' . $warga->nama_warga . ' berhasil ditambahkan!');
    }

    public function edit(Warga $warga)
    {
        return view('admin.wargas.edit', compact('warga'));
    }

    public function update(Request $request, Warga $warga)
    {
        $request->validate([
            'nama_warga' => 'required|string|max:100',
            'no_rumah'   => 'required|string|max:20',
            'rt_rw'      => 'required|string|max:20',
            'aktif'      => 'boolean',
        ]);

        $warga->update($request->only(['nama_warga', 'no_rumah', 'rt_rw', 'aktif']));

        return redirect()->route('admin.wargas.index')
            ->with('success', 'Data warga berhasil diperbarui!');
    }

    public function destroy(Warga $warga)
    {
        $warga->delete();
        return redirect()->route('admin.wargas.index')
            ->with('success', 'Data warga berhasil dihapus.');
    }

    /**
     * Regenerate QR token untuk warga
     */
    public function regenerateQr(Warga $warga)
    {
        $warga->update([
            'qr_token' => 'token-warga-' . Str::random(12) . '-' . time(),
        ]);
        return redirect()->route('admin.wargas.index')
            ->with('success', 'QR Code ' . $warga->nama_warga . ' berhasil diperbarui!');
    }

    /**
     * Cetak QR Code warga
     */
    public function cetakQr(Warga $warga)
    {
        return view('admin.wargas.cetak', compact('warga'));
    }
}
