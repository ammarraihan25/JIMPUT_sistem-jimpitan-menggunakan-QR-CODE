<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $warga->nama_warga }}</title>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        jakarta: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .no-print {
                display: none !important;
            }
            .print-card {
                border: 2px dashed #000000 !important;
                box-shadow: none !important;
                background: white !important;
                margin: 0 auto !important;
                max-width: 100% !important;
            }
        }
        
        .card-glow {
            box-shadow: 0 20px 40px -15px rgba(34, 197, 94, 0.15);
        }
    </style>
</head>
<body class="bg-[#0a0f1e] text-slate-200 min-h-screen flex flex-col items-center justify-center p-4 font-jakarta">

    {{-- Action Controls (Hidden on Print) --}}
    <div class="no-print w-full max-w-md flex justify-between items-center mb-6">
        <a href="{{ route('admin.wargas.index') }}" 
           class="flex items-center gap-2 text-sm text-slate-400 hover:text-white transition-colors duration-200 font-outfit">
            <span>&larr;</span> Kembali ke Manajemen Warga
        </a>
        <button onclick="window.print()" 
                class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold font-outfit px-4 py-2.5 rounded-xl hover:from-emerald-400 hover:to-green-500 active:scale-95 transition-all duration-200 shadow-lg shadow-emerald-500/25">
            <span>&nbsp;🖨&nbsp;</span> Cetak Kartu QR
        </button>
    </div>

    {{-- Printable Card --}}
    <div class="print-card w-full max-w-sm bg-slate-900 border border-slate-800 rounded-3xl p-6 text-center card-glow relative overflow-hidden transition-all duration-300">
        {{-- Decorative corner marks --}}
        <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-emerald-500/30 rounded-tl-2xl"></div>
        <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-emerald-500/30 rounded-tr-2xl"></div>
        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-emerald-500/30 rounded-bl-2xl"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-emerald-500/30 rounded-br-2xl"></div>

        {{-- Header / Logo --}}
        <div class="mb-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-emerald-500/10 rounded-2xl mb-3 text-emerald-400 text-2xl font-bold">
                ⚡
            </div>
            <h2 class="text-sm font-semibold tracking-widest text-emerald-400 font-outfit uppercase">KARTU JIMPITAN WARGA</h2>
            <p class="text-[10px] text-slate-400 font-outfit uppercase mt-0.5 tracking-wider">SISTEM QR CODE DIGITAL</p>
        </div>

        <hr class="border-slate-800 my-4 no-print">

        {{-- QR Code Image --}}
        <div class="bg-white p-4 rounded-2xl inline-block shadow-inner mx-auto my-3 border border-slate-100">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($warga->qr_token) }}&ecc=H" 
                 alt="QR Code {{ $warga->nama_warga }}"
                 class="w-48 h-48 block object-contain">
        </div>

        {{-- Resident / House Info --}}
        <div class="mt-4 space-y-1">
            <h1 class="text-xl font-bold text-white tracking-tight font-outfit">{{ $warga->nama_warga }}</h1>
            <div class="flex items-center justify-center gap-1.5 text-sm text-slate-400 font-medium">
                <span class="bg-slate-800 text-slate-300 px-2 py-0.5 rounded-lg text-xs border border-slate-700/50">🏠 No. {{ $warga->no_rumah }}</span>
                <span class="bg-slate-800 text-slate-300 px-2 py-0.5 rounded-lg text-xs border border-slate-700/50">📍 {{ $warga->rt_rw }}</span>
            </div>
        </div>

        {{-- Footer / Instructions --}}
        <div class="mt-6 pt-4 border-t border-slate-800/80">
            <p class="text-[10px] text-slate-500 leading-relaxed max-w-[240px] mx-auto">
                Silakan tempel kartu ini di dekat pintu depan rumah Anda untuk memudahkan petugas ronda memindai (scan) jimpitan malam Anda.
            </p>
        </div>
    </div>

    {{-- Realtime status message (Only on Screen) --}}
    <p class="no-print text-center text-xs text-slate-500 mt-6 font-outfit">
        &copy; {{ date('Y') }} Jimput · Sistem Jimpitan Digital RT/RW
    </p>

</body>
</html>
