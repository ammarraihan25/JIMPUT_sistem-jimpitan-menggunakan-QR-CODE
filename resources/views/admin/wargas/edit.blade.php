@extends('layouts.app')
@section('title', 'Edit Warga')

@section('content')
<div class="max-w-lg mx-auto space-y-5">
    <div>
        <a href="{{ route('admin.wargas.index') }}" class="text-sm text-gray-400 hover:text-white transition">← Kembali</a>
        <h1 class="text-2xl font-bold text-white mt-2">Edit Data Warga</h1>
        <p class="text-gray-400 text-sm mt-0.5">Perbarui informasi warga</p>
    </div>

    {{-- QR Token Info --}}
    <div class="glass rounded-xl px-4 py-3 flex items-center gap-3">
        <span class="text-2xl">🔲</span>
        <div class="flex-1 min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">QR Token Aktif</div>
            <code class="text-xs text-brand-400 break-all">{{ $warga->qr_token }}</code>
        </div>
    </div>

    <div class="glass rounded-2xl p-6">
        @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-sm text-red-400 space-y-1">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.wargas.update', $warga) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap Warga</label>
                <input type="text" name="nama_warga" value="{{ old('nama_warga', $warga->nama_warga) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nomor Rumah</label>
                <input type="text" name="no_rumah" value="{{ old('no_rumah', $warga->no_rumah) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">RT / RW</label>
                <input type="text" name="rt_rw" value="{{ old('rt_rw', $warga->rt_rw) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div class="flex items-center gap-3 p-3 bg-white/3 rounded-xl">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" id="aktif" name="aktif" value="1"
                       {{ old('aktif', $warga->aktif) ? 'checked' : '' }}
                       class="w-4 h-4 rounded bg-white/5 border-white/20 text-brand-500 focus:ring-brand-500">
                <label for="aktif" class="text-sm text-gray-300">Warga masih aktif (QR dapat discan)</label>
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('admin.wargas.index') }}"
                   class="flex-1 text-center py-3 rounded-xl bg-dark-700 text-gray-300 hover:bg-dark-600 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3 rounded-xl transition text-sm
                           shadow-lg shadow-brand-500/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
