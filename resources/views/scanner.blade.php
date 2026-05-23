@extends('layouts.app')

@section('title', 'Scanner QR')
@section('main-class', '')

@push('styles')
<style>
    #reader {
        border: none !important;
        background: transparent !important;
    }
    #reader * { border-color: rgba(34, 197, 94, 0.4) !important; }
    #reader video { border-radius: 20px; }
    #reader__scan_region { border-radius: 20px; overflow: hidden; }
    #reader__dashboard { display: none !important; }

    @keyframes scanline {
        0% { top: 0; opacity: 1; }
        100% { top: 100%; opacity: 0.3; }
    }
    .scanline {
        animation: scanline 2.5s ease-in-out infinite;
        background: linear-gradient(180deg, transparent, rgba(34, 197, 94, 0.8), transparent);
        height: 4px;
        position: absolute;
        width: 100%;
        left: 0;
    }

    @keyframes cornerPulse {
        0%,100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .corner { animation: cornerPulse 2s ease-in-out infinite; }

    @keyframes feedIn {
        from { opacity:0; transform: translateY(10px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .feed-item { animation: feedIn 0.3s ease-out forwards; }

    @keyframes successBounce {
        0%   { transform: scale(0.85); opacity: 0; }
        60%  { transform: scale(1.03); opacity: 1; }
        100% { transform: scale(1); }
    }
    .success-bounce { animation: successBounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
</style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in py-2">

    {{-- Header Stats --}}
    <div class="glass-card rounded-2xl p-4">
        <div class="grid grid-cols-3 gap-3">
            <div class="text-center p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                <div class="text-2xl font-black text-brand-600 dark:text-brand-400 font-outfit" id="stat-total">{{ $totalHariIni }}</div>
                <div class="text-[10px] uppercase font-bold text-slate-400 mt-1">Scan Sukses</div>
            </div>
            <div class="text-center p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                <div class="text-base sm:text-lg font-black text-emerald-600 dark:text-emerald-400 font-outfit truncate" id="stat-nominal">
                    Rp {{ number_format($nominalHariIni, 0, ',', '.') }}
                </div>
                <div class="text-[10px] uppercase font-bold text-slate-400 mt-1">Total Dana</div>
            </div>
            <div class="text-center p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5">
                <div class="text-lg font-black text-sky-600 dark:text-sky-400 font-outfit" id="stat-waktu">{{ now()->format('H:i') }}</div>
                <div class="text-[10px] uppercase font-bold text-slate-400 mt-1">Waktu</div>
            </div>
        </div>
    </div>

    {{-- Scanner Area --}}
    <div class="relative">
        <div class="glass-card rounded-3xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-xl">
            <div class="px-5 pt-4 pb-2 flex items-center justify-between border-b border-slate-100 dark:border-dark-800">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kamera Aktif</span>
                </div>
                <button id="btn-toggle-cam"
                    class="text-[11px] font-bold uppercase tracking-wider px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700 text-slate-600 dark:text-slate-300 transition duration-150">
                    ⏸ Jeda
                </button>
            </div>

            {{-- QR Reader Container --}}
            <div class="relative p-4 bg-slate-50 dark:bg-dark-900/40">
                <div id="reader" class="w-full rounded-2xl overflow-hidden bg-slate-900 shadow-inner" style="min-height: 280px;"></div>

                {{-- Scanline overlay --}}
                <div class="scanline absolute left-4 right-4 pointer-events-none" style="top: 0;"></div>

                {{-- Corner markers --}}
                <div class="corner absolute top-6 left-6 w-8 h-8 border-t-4 border-l-4 border-emerald-500 rounded-tl-xl pointer-events-none shadow-sm"></div>
                <div class="corner absolute top-6 right-6 w-8 h-8 border-t-4 border-r-4 border-emerald-500 rounded-tr-xl pointer-events-none shadow-sm"></div>
                <div class="corner absolute bottom-6 left-6 w-8 h-8 border-b-4 border-l-4 border-emerald-500 rounded-bl-xl pointer-events-none shadow-sm"></div>
                <div class="corner absolute bottom-6 right-6 w-8 h-8 border-b-4 border-r-4 border-emerald-500 rounded-br-xl pointer-events-none shadow-sm"></div>
            </div>
        </div>

        {{-- Instruksi --}}
        <p class="text-center text-xs font-medium text-slate-400 dark:text-slate-500 mt-3 font-outfit uppercase tracking-wider">
            Arahkan kamera ke kartu QR Code warga untuk mencatat jimpitan
        </p>
    </div>

    {{-- Feedback Result Card --}}
    <div id="result-card"
         class="glass-card rounded-3xl p-5 hidden transition-all duration-300 success-bounce"
         role="alert" aria-live="polite">
        <div class="flex items-start gap-4">
            <div id="result-icon" class="text-4xl flex-shrink-0">✅</div>
            <div class="flex-1 min-w-0">
                <div id="result-title" class="font-extrabold text-slate-800 dark:text-white text-lg tracking-tight font-outfit"></div>
                <div id="result-subtitle" class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium leading-relaxed"></div>
                <div id="result-detail" class="mt-4 flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    {{-- Riwayat Scan Hari Ini --}}
    <div class="glass-card rounded-3xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 dark:border-dark-800 flex items-center justify-between bg-slate-50/50 dark:bg-dark-900/30">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">📋 Riwayat Ronda Anda Malam Ini</h3>
            <span id="badge-riwayat"
                class="text-[10px] font-extrabold bg-brand-500/10 text-brand-600 dark:text-brand-400 px-2.5 py-1 rounded-full uppercase tracking-wider">
                {{ $riwayat->count() }} data
            </span>
        </div>

        <div id="riwayat-list" class="divide-y divide-slate-100 dark:divide-dark-800 max-h-72 overflow-y-auto">
            @forelse($riwayat as $item)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/30 dark:hover:bg-dark-900/20 transition duration-150">
                <div>
                    <div class="text-sm font-bold text-slate-800 dark:text-white font-outfit">{{ $item->warga->nama_warga }}</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-semibold">No. {{ $item->warga->no_rumah }} · {{ $item->warga->rt_rw }}</div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-extrabold text-emerald-600 dark:text-brand-400">
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    </div>
                    <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-0.5">{{ $item->created_at->format('H:i') }}</div>
                </div>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-slate-400 dark:text-slate-500 text-sm">
                Belum ada jimpitan yang dicatat ronda malam ini.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- html5-qrcode Library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
(function() {
    'use strict';

    // ── Konfigurasi ──────────────────────────────────────────────
    const SCAN_URL    = '/api/scan';
    const CSRF_TOKEN  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const COOLDOWN_MS = 3000; // jeda antar scan (ms)

    // ── State ────────────────────────────────────────────────────
    let isProcessing  = false;
    let lastScanTime  = 0;
    let scanCount     = parseInt(document.getElementById('stat-total').textContent) || 0;
    let nominalTotal  = {{ $nominalHariIni }};
    let cameraRunning = true;
    let html5QrCode;

    // ── UI Elements ──────────────────────────────────────────────
    const resultCard     = document.getElementById('result-card');
    const resultIcon     = document.getElementById('result-icon');
    const resultTitle    = document.getElementById('result-title');
    const resultSubtitle = document.getElementById('result-subtitle');
    const resultDetail   = document.getElementById('result-detail');
    const statTotal      = document.getElementById('stat-total');
    const statNominal    = document.getElementById('stat-nominal');

    // ── Jam realtime ─────────────────────────────────────────────
    function updateClock() {
        const now = new Date();
        document.getElementById('stat-waktu').textContent =
            now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }
    setInterval(updateClock, 30000);

    // ── Format Rupiah ─────────────────────────────────────────────
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // ── Tampilkan Feedback ───────────────────────────────────────
    function showResult(type, data) {
        resultCard.classList.remove('hidden', 'border-emerald-500/30', 'border-red-500/30', 'border-yellow-500/30', 'bg-emerald-500/5', 'bg-red-500/5', 'bg-amber-500/5');
        resultDetail.innerHTML = '';

        if (type === 'success') {
            resultCard.classList.add('border-emerald-500/30', 'bg-emerald-500/5');
            resultIcon.textContent = '✅';
            resultTitle.textContent   = 'Berhasil Dicatat!';
            resultTitle.className   = 'font-extrabold text-emerald-600 dark:text-brand-400 text-lg tracking-tight font-outfit';
            resultSubtitle.innerHTML  = `<span class="font-bold text-slate-700 dark:text-slate-300">${data.message.replace('✅ ','')}</span>`;
            resultDetail.innerHTML = `
                <span class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-gray-300 text-[10px] font-bold px-2.5 py-1 rounded-xl">🏠 No. ${data.warga.no_rumah}</span>
                <span class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-gray-300 text-[10px] font-bold px-2.5 py-1 rounded-xl">📍 ${data.warga.rt_rw}</span>
                <span class="bg-emerald-500/10 text-emerald-600 dark:text-brand-400 border border-emerald-500/20 text-[10px] font-extrabold px-2.5 py-1 rounded-xl uppercase tracking-wider">${data.jimpitan.nominal}</span>
                <span class="bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/5 text-slate-500 dark:text-gray-400 text-[10px] font-bold px-2.5 py-1 rounded-xl">🕐 ${data.jimpitan.waktu}</span>
            `;
            // Update stats
            scanCount++;
            nominalTotal += 2000;
            statTotal.textContent  = scanCount;
            statNominal.textContent = formatRupiah(nominalTotal);
            // Tambah ke riwayat
            prependRiwayat(data);

        } else if (type === 'warning') {
            resultCard.classList.add('border-yellow-500/30', 'bg-amber-500/5');
            resultIcon.textContent = '⚠️';
            resultTitle.textContent  = 'Sudah Tercatat';
            resultTitle.className   = 'font-extrabold text-amber-600 dark:text-amber-400 text-lg tracking-tight font-outfit';
            resultSubtitle.textContent = data.message.replace('⚠️ ', '');

        } else {
            resultCard.classList.add('border-red-500/30', 'bg-red-500/5');
            resultIcon.textContent = '❌';
            resultTitle.textContent  = 'Gagal';
            resultTitle.className   = 'font-extrabold text-red-600 dark:text-red-400 text-lg tracking-tight font-outfit';
            resultSubtitle.textContent = data.message || 'QR Code tidak dikenali.';
        }

        // Auto-hide setelah 4 detik untuk non-error
        if (type === 'success') {
            setTimeout(() => resultCard.classList.add('hidden'), 4500);
        }
    }

    // ── Tambah item ke riwayat realtime ──────────────────────────
    function prependRiwayat(data) {
        const list = document.getElementById('riwayat-list');
        const emptyEl = list.querySelector('.text-slate-400');
        if (emptyEl) emptyEl.parentElement.remove();

        const now = new Date();
        const jam = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;

        const div = document.createElement('div');
        div.className = 'feed-item flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/30 dark:hover:bg-dark-900/20 transition bg-emerald-500/5 dark:bg-emerald-500/5 border-b border-slate-100 dark:border-dark-800';
        div.innerHTML = `
            <div>
                <div class="text-sm font-bold text-slate-800 dark:text-white font-outfit">${data.warga.nama}</div>
                <div class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-semibold">No. ${data.warga.no_rumah} · ${data.warga.rt_rw}</div>
            </div>
            <div class="text-right">
                <div class="text-sm font-extrabold text-emerald-600 dark:text-brand-400">${data.jimpitan.nominal}</div>
                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase mt-0.5">${jam}</div>
            </div>`;
        list.insertBefore(div, list.firstChild);

        // Update badge
        document.getElementById('badge-riwayat').textContent = scanCount + ' data';
    }

    // ── Proses QR Code ───────────────────────────────────────────
    async function onQrDetected(decodedText) {
        const now = Date.now();
        if (isProcessing || (now - lastScanTime) < COOLDOWN_MS) return;

        isProcessing = true;
        lastScanTime = now;

        // Hapus whitespace dari token
        const token = decodedText.trim();

        try {
            const response = await fetch(SCAN_URL, {
                method:  'POST',
                headers: {
                    'Content-Type':     'application/json',
                    'X-CSRF-TOKEN':     CSRF_TOKEN,
                    'Accept':           'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ token }),
            });

            const data = await response.json();

            if (response.status === 201) {
                showResult('success', data);
            } else if (response.status === 409) {
                showResult('warning', data);
            } else {
                showResult('error', data);
            }

        } catch (err) {
            showResult('error', { message: 'Koneksi gagal. Pastikan internet aktif.' });
            console.error('Scan error:', err);
        } finally {
            setTimeout(() => { isProcessing = false; }, COOLDOWN_MS);
        }
    }

    // ── Inisialisasi html5-qrcode ─────────────────────────────────
    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 20,
        qrbox: { width: 240, height: 240 },
        aspectRatio: 1.0,
        disableFlip: false,
        rememberLastUsedCamera: true,
    };

    Html5Qrcode.getCameras().then(cameras => {
        if (!cameras || cameras.length === 0) {
            document.getElementById('reader').innerHTML =
                '<div class="text-center py-12 text-slate-400"><div class="text-4xl mb-3">📷</div><p class="text-sm">Kamera tidak ditemukan.</p></div>';
            return;
        }

        // Prefer kamera belakang
        const backCam = cameras.find(c => c.label.toLowerCase().includes('back') ||
                                          c.label.toLowerCase().includes('belakang') ||
                                          c.label.toLowerCase().includes('rear')) || cameras[cameras.length - 1];

        html5QrCode.start(
            backCam.id,
            config,
            onQrDetected,
            () => {} // ignore non-QR frames silently
        ).catch(err => {
            document.getElementById('reader').innerHTML =
                `<div class="text-center py-12 text-slate-400 px-4">
                    <div class="text-4xl mb-3">🚫</div>
                    <p class="text-sm">Izin kamera ditolak.</p>
                    <p class="text-xs mt-2 text-slate-500">Aktifkan izin kamera di pengaturan browser.</p>
                 </div>`;
        });
    });

    // ── Tombol Toggle Kamera ──────────────────────────────────────
    document.getElementById('btn-toggle-cam').addEventListener('click', function() {
        if (cameraRunning) {
            html5QrCode.pause(true);
            this.textContent = '▶ Lanjut';
            this.classList.remove('bg-slate-100', 'dark:bg-dark-800');
            this.classList.add('bg-emerald-500/20', 'text-emerald-600', 'dark:text-brand-400');
        } else {
            html5QrCode.resume();
            this.textContent = '⏸ Jeda';
            this.classList.add('bg-slate-100', 'dark:bg-dark-800');
            this.classList.remove('bg-emerald-500/20', 'text-emerald-600', 'dark:text-brand-400');
        }
        cameraRunning = !cameraRunning;
    });

})();
</script>
@endpush
