@extends('layouts.app')
@section('title', 'Manajemen Warga')

@section('content')
<div class="space-y-5 animate-fade-in">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-outfit">Manajemen Warga</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-0.5 font-medium">Data warga & pembuat QR Code jimpitan</p>
        </div>
        <a href="{{ route('admin.wargas.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-600 hover:to-emerald-500 text-white text-sm font-semibold font-outfit px-4 py-2.5 rounded-xl transition shadow-lg shadow-emerald-500/10 active:scale-95 duration-200">
            ＋ Tambah Warga
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" class="relative">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Cari nama, nomor rumah, RT/RW..."
               class="w-full bg-slate-100 border border-slate-200 dark:bg-white/5 dark:border-white/10 rounded-xl px-4 py-3 pl-10 text-slate-800 dark:text-white text-sm
                      placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 transition">
        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
        @if($search)
        <a href="{{ route('admin.wargas.index') }}"
           class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-white text-sm">✕</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        {{-- Desktop Table --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-dark-800 bg-slate-50/50 dark:bg-dark-900/30">
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nama Warga</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">No. Rumah</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">RT/RW</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">QR Token</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Scan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-800">
                    @forelse($wargas as $i => $w)
                    <tr class="hover:bg-slate-50/40 dark:hover:bg-dark-900/20 transition duration-150">
                        <td class="px-5 py-4 text-slate-400 font-mono">{{ $wargas->firstItem() + $i }}</td>
                        <td class="px-5 py-4 font-bold text-slate-800 dark:text-white font-outfit">{{ $w->nama_warga }}</td>
                        <td class="px-5 py-4 text-slate-600 dark:text-slate-300 font-semibold">{{ $w->no_rumah }}</td>
                        <td class="px-5 py-4 text-slate-500 dark:text-slate-400">{{ $w->rt_rw }}</td>
                        <td class="px-5 py-4">
                            <button type="button" 
                                    onclick="openQrModal('{{ $w->nama_warga }}', '{{ $w->no_rumah }}', '{{ $w->rt_rw }}', '{{ $w->qr_token }}', '{{ route('admin.wargas.regenerate-qr', $w) }}', '{{ route('admin.wargas.cetak', $w) }}')"
                                    class="text-left focus:outline-none group">
                                <code class="text-xs text-brand-700 dark:text-brand-400 bg-brand-500/10 group-hover:bg-brand-500/20 px-2 py-1 rounded-lg break-all transition cursor-pointer font-mono font-bold tracking-tight">
                                    {{ Str::limit($w->qr_token, 20) }} 🔍
                                </code>
                            </button>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="font-extrabold text-brand-600 dark:text-brand-400 font-outfit bg-brand-500/10 px-2.5 py-1 rounded-full text-xs">{{ $w->jimpitan_masuks_count }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($w->aktif)
                                <span class="px-2.5 py-1 bg-emerald-500/10 dark:bg-brand-500/20 text-emerald-600 dark:text-brand-400 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700/20 text-slate-400 dark:text-slate-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                {{-- Cetak QR --}}
                                <a href="{{ route('admin.wargas.cetak', $w) }}" target="_blank"
                                   class="text-xs px-3 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all font-semibold font-outfit">
                                    🖨 Cetak
                                </a>
                                {{-- Edit --}}
                                <a href="{{ route('admin.wargas.edit', $w) }}"
                                   class="text-xs px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-dark-700 text-slate-600 dark:text-gray-300 hover:bg-slate-200 dark:hover:bg-dark-600 transition-all font-semibold font-outfit">
                                    Edit
                                </a>
                                {{-- Lihat QR --}}
                                <button type="button"
                                        onclick="openQrModal('{{ $w->nama_warga }}', '{{ $w->no_rumah }}', '{{ $w->rt_rw }}', '{{ $w->qr_token }}', '{{ route('admin.wargas.regenerate-qr', $w) }}', '{{ route('admin.wargas.cetak', $w) }}')"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-500/20 transition-all font-semibold font-outfit">
                                    🔍 QR
                                </button>
                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.wargas.destroy', $w) }}"
                                      onsubmit="return confirm('Hapus data {{ $w->nama_warga }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-all font-semibold font-outfit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                            @if($search)
                                Tidak ada warga dengan pencarian "{{ $search }}"
                            @else
                                Belum ada data warga. <a href="{{ route('admin.wargas.create') }}" class="text-brand-600 dark:text-brand-400 underline font-bold">Tambah sekarang</a>.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden divide-y divide-slate-100 dark:divide-dark-800">
            @forelse($wargas as $w)
            <div class="p-4 space-y-3.5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-white font-outfit">{{ $w->nama_warga }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-semibold">No. {{ $w->no_rumah }} · {{ $w->rt_rw }}</div>
                    </div>
                    @if($w->aktif)
                        <span class="px-2 py-0.5 bg-emerald-500/10 dark:bg-brand-500/20 text-emerald-600 dark:text-brand-400 rounded-full text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                    @else
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700/20 text-slate-400 dark:text-slate-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Nonaktif</span>
                    @endif
                </div>
                
                <button type="button"
                        onclick="openQrModal('{{ $w->nama_warga }}', '{{ $w->no_rumah }}', '{{ $w->rt_rw }}', '{{ $w->qr_token }}', '{{ route('admin.wargas.regenerate-qr', $w) }}', '{{ route('admin.wargas.cetak', $w) }}')"
                        class="block w-full text-left focus:outline-none group">
                    <code class="block text-xs text-brand-700 dark:text-brand-400 bg-brand-500/10 group-hover:bg-brand-500/20 px-2 py-1.5 rounded-lg break-all transition cursor-pointer font-mono text-center">
                        {{ $w->qr_token }} 🔍
                    </code>
                </button>
                
                <div class="grid grid-cols-2 gap-2 pt-1 font-outfit">
                    <button type="button"
                            onclick="openQrModal('{{ $w->nama_warga }}', '{{ $w->no_rumah }}', '{{ $w->rt_rw }}', '{{ $w->qr_token }}', '{{ route('admin.wargas.regenerate-qr', $w) }}', '{{ route('admin.wargas.cetak', $w) }}')"
                            class="text-center text-xs py-2 rounded-xl bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 font-semibold transition">🔍 Lihat QR</button>
                    
                    <a href="{{ route('admin.wargas.cetak', $w) }}" target="_blank"
                       class="text-center text-xs py-2 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-semibold transition">🖨 Cetak</a>
                    
                    <a href="{{ route('admin.wargas.edit', $w) }}"
                       class="text-center text-xs py-2 rounded-xl bg-slate-100 dark:bg-dark-700 text-slate-600 dark:text-gray-300 font-semibold transition">Edit</a>
                    
                    <form method="POST" action="{{ route('admin.wargas.destroy', $w) }}"
                          onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button class="w-full text-xs py-2 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-semibold transition">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-sm">Belum ada data warga.</div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-5">
        {{ $wargas->withQueryString()->links('pagination::simple-tailwind') }}
    </div>

</div>

{{-- QR Code Modal --}}
<div id="qr-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-dark-950/80 backdrop-blur-md hidden animate-fade-in" role="dialog" aria-modal="true">
    <div class="glass-card max-w-sm w-full rounded-3xl p-6 text-center space-y-4 shadow-2xl relative animate-slide-up border border-slate-200 dark:border-white/10">
        
        {{-- Modal Header --}}
        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-dark-800">
            <h3 class="text-xs font-bold tracking-wider text-brand-700 dark:text-brand-400 uppercase font-outfit">Detail QR Code</h3>
            <button type="button" onclick="closeQrModal()" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors duration-200 font-bold text-base">
                ✕
            </button>
        </div>
        
        {{-- QR Code Display --}}
        <div class="bg-white p-4 rounded-2xl inline-block shadow-inner mx-auto border border-slate-100">
            <img id="modal-qr-image" src="" alt="QR Code" class="w-48 h-48 block object-contain mx-auto">
        </div>

        {{-- Info Warga --}}
        <div class="space-y-1">
            <h2 id="modal-warga-nama" class="text-lg font-bold text-slate-800 dark:text-white font-outfit"></h2>
            <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span id="modal-warga-rumah" class="bg-slate-100 border border-slate-200 dark:bg-white/5 dark:border-white/10 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded-lg"></span>
                <span id="modal-warga-rt" class="bg-slate-100 border border-slate-200 dark:bg-white/5 dark:border-white/10 text-slate-600 dark:text-slate-300 px-2 py-0.5 rounded-lg"></span>
            </div>
        </div>

        {{-- Copyable Token --}}
        <div class="bg-slate-50 border border-slate-100 dark:bg-white/5 dark:border-white/5 rounded-xl p-3 text-left space-y-1">
            <div class="text-[9px] text-slate-400 dark:text-slate-500 uppercase font-bold tracking-wider">QR Token String</div>
            <code id="modal-qr-token" class="block text-[11px] text-brand-700 dark:text-brand-400 break-all select-all font-mono font-semibold"></code>
        </div>

        {{-- Modal Actions --}}
        <div class="grid grid-cols-2 gap-2 pt-2 font-outfit">
            <a id="modal-btn-print" href="" target="_blank"
               class="flex-1 text-center py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-lg shadow-emerald-500/20 transition-all duration-200">
                🖨 Cetak Kartu
            </a>
            <form id="modal-form-regen" method="POST" action="" onsubmit="return confirm('Buat ulang QR Code untuk warga ini? QR Code lama tidak akan dapat discan lagi.')">
                @csrf
                <button type="submit" class="w-full py-2.5 rounded-xl bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 text-xs font-bold transition-all duration-200">
                    🔄 Buat Ulang
                </button>
            </form>
        </div>
        
        <button type="button" onclick="closeQrModal()" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-700 dark:hover:bg-dark-600 text-slate-600 dark:text-slate-300 text-xs font-bold transition-all duration-200 mt-2">
            Tutup
        </button>
    </div>
</div>

{{-- Javascript for QR Modal --}}
<script>
    function openQrModal(nama, noRumah, rtRw, token, regenUrl, printUrl) {
        const modal = document.getElementById('qr-modal');
        const qrImage = document.getElementById('modal-qr-image');
        const wargaNama = document.getElementById('modal-warga-nama');
        const wargaRumah = document.getElementById('modal-warga-rumah');
        const wargaRt = document.getElementById('modal-warga-rt');
        const qrToken = document.getElementById('modal-qr-token');
        const btnPrint = document.getElementById('modal-btn-print');
        const formRegen = document.getElementById('modal-form-regen');

        // Set data
        wargaNama.textContent = nama;
        wargaRumah.textContent = '🏠 No. ' + noRumah;
        wargaRt.textContent = '📍 ' + rtRw;
        qrToken.textContent = token;
        btnPrint.href = printUrl;
        formRegen.action = regenUrl;
        
        // Set QR code source
        qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent(token) + '&ecc=H';

        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Disable background scrolling
    }

    function closeQrModal() {
        const modal = document.getElementById('qr-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Enable background scrolling
    }

    // Close on ESC key or clicking outside the card
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeQrModal();
    });
    
    document.getElementById('qr-modal').addEventListener('click', function(e) {
        if (e.target === this) closeQrModal();
    });
</script>
@endsection
