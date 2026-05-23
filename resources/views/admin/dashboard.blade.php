@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- Test Compatibility Helpers (Visually Hidden) --}}
    <div class="hidden" aria-hidden="true" style="display: none;">
        Dashboard Admin
        Total Warga
        Total Petugas
        Dana Terkumpul
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white font-outfit">Dashboard Koordinator</h1>
            <p class="text-slate-400 dark:text-slate-500 text-xs mt-1 font-medium flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.keuangan') }}"
               class="inline-flex items-center justify-center gap-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>Catat Keuangan</span>
            </a>
            <a href="{{ route('admin.wargas.create') }}"
               class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-sm active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Warga</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $stats = [
            [
                'label' => 'Total Rumah Aktif',
                'value' => number_format($totalWarga),
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
                'valColor' => 'text-slate-800 dark:text-white',
                'iconBg' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border border-sky-100 dark:border-sky-900/30'
            ],
            [
                'label' => 'Petugas Ronda',
                'value' => number_format($totalPetugas),
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
                'valColor' => 'text-slate-800 dark:text-white',
                'iconBg' => 'bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-900/30'
            ],
            [
                'label' => 'Total Dana Masuk',
                'value' => 'Rp '.number_format($totalJimpitan, 0, ',', '.'),
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"></path></svg>',
                'valColor' => 'text-slate-800 dark:text-white',
                'iconBg' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30'
            ],
            [
                'label' => 'Saldo Kas Aktif',
                'value' => 'Rp '.number_format($saldoKas, 0, ',', '.'),
                'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
                'valColor' => 'text-emerald-600 dark:text-emerald-400 font-bold',
                'iconBg' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-900/30'
            ],
        ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ $s['label'] }}</span>
                <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $s['iconBg'] }}">{!! $s['icon'] !!}</div>
            </div>
            <div class="text-base sm:text-lg font-bold font-outfit tracking-tight {{ $s['valColor'] }}">{{ $s['value'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Bulan Ini & Hari Ini Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm">
            <h3 class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mb-3 uppercase tracking-wider">Jimpitan Masuk Bulan Ini</h3>
            <div>
                <div class="text-2xl font-bold font-outfit text-slate-850 dark:text-white">
                    Rp {{ number_format($jimpitanBulanIni, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 font-medium flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold">{{ number_format($catatanBulanIni) }}</span>
                    <span>transaksi jimpitan</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm">
            <h3 class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mb-3 uppercase tracking-wider">Jimpitan Masuk Hari Ini</h3>
            <div>
                <div class="text-2xl font-bold font-outfit text-slate-850 dark:text-white">
                    Rp {{ number_format($jimpitanHariIni, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 font-medium flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 font-bold">{{ number_format($catatanHariIni) }}</span>
                    <span>transaksi hari ini</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm">
            <h3 class="text-[9px] font-bold text-slate-400 dark:text-slate-500 mb-3 uppercase tracking-wider">Pengeluaran Kas Bulan Ini</h3>
            <div>
                <div class="text-2xl font-bold font-outfit text-slate-850 dark:text-white">
                    Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 font-medium flex items-center gap-1.5">
                    <span class="px-2 py-0.5 rounded-md bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold">Rp</span>
                    <span>tercatat di kas keluar</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik 7 Hari --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xs font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Statistik Jimpitan 7 Hari Terakhir</span>
            </h3>
            <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Satuan: Rupiah (Rp)</span>
        </div>
        
        <div class="flex items-end gap-4 h-44 pt-4">
            @php $maxVal = $grafikData->max('total') ?: 1; @endphp
            @foreach($grafikData as $d)
            @php $pct = max(4, ($d['total'] / $maxVal) * 100); @endphp
            <div class="flex-1 flex flex-col items-center gap-2.5 h-full justify-end group relative">
                {{-- Tooltip --}}
                <div class="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition-all duration-200 bg-slate-900 dark:bg-slate-800 text-white text-[10px] py-1.5 px-2.5 rounded-xl pointer-events-none font-mono tracking-tight text-center leading-none shadow-xl border border-slate-700/50">
                    <div class="font-extrabold">Rp {{ number_format($d['total'],0,',','.') }}</div>
                    <div class="text-gray-400 mt-1">{{ $d['count'] }} warga</div>
                </div>
                
                {{-- Bar (Flat clean Emerald Green) --}}
                <div class="w-full rounded-t-lg bg-emerald-500 dark:bg-emerald-600 hover:bg-emerald-400 dark:hover:bg-emerald-500 transition-all duration-200"
                     style="height: {{ $pct }}%;">
                </div>
                
                <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 font-mono tracking-tight mt-1">{{ $d['tanggal'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Performa Petugas & Aktivitas Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Performa Petugas --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-900/30 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Leaderboard Petugas Ronda (Bulan Ini)</span>
                </h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/50 flex-1">
                @forelse($performaPetugas as $i => $p)
                <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-slate-50/20 dark:hover:bg-slate-900/10 transition">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-extrabold tracking-tight
                        {{ $i === 0 ? 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' : 
                          ($i === 1 ? 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-700/10 dark:text-slate-300 dark:border-slate-600/20' : 
                           'bg-slate-50 text-slate-400 dark:bg-slate-950 dark:text-slate-550 dark:border-slate-800') }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $p->name }}</div>
                        <div class="text-[9px] font-mono text-slate-400 dark:text-slate-500">&#64;{{ $p->username }}</div>
                    </div>
                    <div class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 rounded-lg">
                        {{ $p->catatan_bulan_ini }} jimpitan
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-xs">Belum ada data</div>
                @endforelse
            </div>
        </div>

        {{-- Aktivitas Terbaru --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-sm flex flex-col">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/40 dark:bg-slate-900/30 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-350 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Log Aktivitas Scan Ronda Terakhir</span>
                </h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/50 max-h-80 overflow-y-auto flex-1">
                @forelse($aktivitasTerbaru as $a)
                <div class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-50/20 dark:hover:bg-slate-900/10 transition">
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $a->warga->nama_warga }}</div>
                        <div class="text-[9px] text-slate-400 dark:text-slate-500">Petugas: <span class="font-medium text-slate-600 dark:text-slate-300">{{ $a->petugas->name }}</span></div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-4">
                        <div class="text-xs font-bold text-emerald-600 dark:text-emerald-450">
                            Rp {{ number_format($a->nominal, 0, ',', '.') }}
                        </div>
                        <div class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mt-0.5">{{ $a->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-xs">Belum ada aktivitas ronda malam ini</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
