@extends('layouts.app')
@section('title', 'Tambah Warga')

@section('content')
<div class="max-w-lg mx-auto space-y-5">
    <div>
        <a href="{{ route('admin.wargas.index') }}" class="text-sm text-gray-400 hover:text-white transition">← Kembali</a>
        <h1 class="text-2xl font-bold text-white mt-2">Tambah Warga Baru</h1>
        <p class="text-gray-400 text-sm mt-0.5">QR Code akan dibuat otomatis setelah menyimpan</p>
    </div>

    <div class="glass rounded-2xl p-6">
        @if($errors->any())
        <div class="mb-4 bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 text-sm text-red-400 space-y-1">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.wargas.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap Warga <span class="text-red-400">*</span></label>
                <input type="text" name="nama_warga" value="{{ old('nama_warga') }}"
                       placeholder="cth: Bapak Ahmad Supriyadi"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nomor Rumah <span class="text-red-400">*</span></label>
                <input type="text" name="no_rumah" value="{{ old('no_rumah') }}"
                       placeholder="cth: 12A atau B-05"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">RT / RW <span class="text-red-400">*</span></label>
                <input type="text" name="rt_rw" value="{{ old('rt_rw') }}"
                       placeholder="cth: RT 03/RW 07"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500
                              focus:outline-none focus:ring-2 focus:ring-brand-500 transition" required>
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('admin.wargas.index') }}"
                   class="flex-1 text-center py-3 rounded-xl bg-dark-700 text-gray-300 hover:bg-dark-600 transition text-sm font-medium">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-brand-600 hover:bg-brand-500 text-white font-semibold py-3 rounded-xl transition text-sm
                           shadow-lg shadow-brand-500/20">
                    Simpan & Buat QR
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
