@extends('layouts.app')
@section('title', 'Manajemen Keuangan')

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white font-outfit">Buku Kas & Keuangan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-xs mt-1 font-semibold">Monitor seluruh pemasukan ronda malam dan catat pengeluaran kas RT 02</p>
        </div>
        <button onclick="openModal('modal-keluar')"
                class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-400 hover:to-rose-500 text-white text-xs font-bold font-outfit px-4.5 py-3 rounded-xl transition duration-200 shadow-md shadow-red-500/10 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Catat Kas Keluar</span>
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="glass-card rounded-2xl p-5">
        <form method="GET" action="{{ route('admin.keuangan') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="start_date" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" name="start_date" value="{{ $start_date }}"
                       class="w-full bg-[#f8fafc] dark:bg-dark-950 border border-slate-200 dark:border-dark-800 rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
            </div>
            <div>
                <label for="end_date" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                <input type="date" id="end_date" name="end_date" value="{{ $end_date }}"
                       class="w-full bg-[#f8fafc] dark:bg-dark-950 border border-slate-200 dark:border-dark-800 rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="flex-1 bg-slate-800 dark:bg-emerald-600 hover:bg-slate-700 dark:hover:bg-emerald-500 text-white text-xs font-bold font-outfit px-4 py-3 rounded-xl transition duration-200 text-center shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.keuangan') }}" 
                   class="bg-slate-200 dark:bg-dark-800 hover:bg-slate-300 dark:hover:bg-dark-700/50 text-slate-700 dark:text-slate-300 p-3 rounded-xl transition duration-200" 
                   title="Reset Filter">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H18.22"></path>
                    </svg>
                </a>
            </div>
            
            {{-- Ekspor --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.keuangan.export.excel', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                   class="flex-1 border border-slate-200 dark:border-dark-800 bg-white dark:bg-dark-900 hover:bg-slate-50 dark:hover:bg-dark-800/50 text-slate-700 dark:text-slate-300 text-xs font-bold font-outfit px-3 py-3 rounded-xl transition duration-200 text-center shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Excel</span>
                </a>
                <a href="{{ route('admin.keuangan.export.pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}"
                   class="flex-1 border border-slate-200 dark:border-dark-800 bg-white dark:bg-dark-900 hover:bg-slate-50 dark:hover:bg-dark-800/50 text-slate-700 dark:text-slate-300 text-xs font-bold font-outfit px-3 py-3 rounded-xl transition duration-200 text-center shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>PDF Laporan</span>
                </a>
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card rounded-2xl p-5 border-l-4 border-emerald-500">
            <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2.5">Total Pemasukan (Periode Filter)</span>
            <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-outfit">
                Rp {{ number_format($total_masuk, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-1">Uang jimpitan yang dicatat dari scanner QR</p>
        </div>

        <div class="glass-card rounded-2xl p-5 border-l-4 border-rose-500">
            <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2.5">Total Pengeluaran (Periode Filter)</span>
            <div class="text-2xl font-extrabold text-rose-500 dark:text-rose-400 font-outfit">
                Rp {{ number_format($total_keluar, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-1">Pengeluaran dana sosial, ronda & operasional RT</p>
        </div>

        <div class="glass-card rounded-2xl p-5 border-l-4 border-sky-500 bg-sky-50/10 dark:bg-sky-950/5">
            <span class="block text-[10px] font-bold text-sky-600 dark:text-sky-400 uppercase tracking-wider mb-2.5">Saldo Riil Kas Saat Ini</span>
            <div class="text-2xl font-extrabold text-sky-600 dark:text-sky-400 font-outfit">
                Rp {{ number_format($saldo_riil, 0, ',', '.') }}
            </div>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-1">Kas murni tersisa (Pemasukan - Pengeluaran all-time)</p>
        </div>
    </div>

    {{-- Transaction History Table --}}
    <div class="glass-card rounded-3xl overflow-hidden flex flex-col">
        <div class="px-6 py-4.5 border-b border-slate-200 dark:border-dark-800/80 bg-slate-50/50 dark:bg-dark-900/30 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <span>Buku Laporan Transaksi Kas</span>
            </h3>
            <span class="text-[10px] bg-slate-100 dark:bg-dark-800 font-bold px-3 py-1 rounded-xl text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                {{ $transactions->count() }} Transaksi
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-800 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider bg-slate-50/20 dark:bg-dark-950/20">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kode Ref</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Pencatat</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-800 text-sm">
                    @forelse($transactions as $t)
                    <tr class="hover:bg-slate-50/30 dark:hover:bg-dark-900/10 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $t->tanggal->isoFormat('D MMM Y') }}</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $t->tanggal->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-xs px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-dark-800 text-slate-600 dark:text-slate-400 font-bold">
                                {{ $t->id }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800 dark:text-slate-200 leading-snug">{{ $t->keterangan }}</div>
                            <span class="text-[10px] uppercase font-extrabold tracking-wider mt-1 inline-flex items-center gap-1.5 {{ $t->tipe == 'masuk' ? 'text-emerald-500' : 'text-rose-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $t->tipe == 'masuk' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span>{{ $t->tipe == 'masuk' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600 dark:text-slate-400 font-medium">
                            {{ $t->petugas }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-extrabold text-base">
                            <span class="{{ $t->tipe == 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}">
                                {{ $t->tipe == 'masuk' ? '+' : '-' }} Rp {{ number_format($t->nominal, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 text-sm">
                            Tidak ada data transaksi kas pada periode filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Catat Pengeluaran Modal --}}
    <div id="modal-keluar" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-950/50 backdrop-blur-sm" onclick="closeModal('modal-keluar')"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white dark:bg-dark-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 dark:border-dark-800 animate-slide-up">
                <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-800 bg-slate-50/50 dark:bg-dark-900/40 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white font-outfit flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span>Catat Pengeluaran Kas Baru</span>
                    </h3>
                    <button onclick="closeModal('modal-keluar')" class="text-slate-400 hover:text-slate-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form method="POST" action="{{ route('admin.keuangan.keluar.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="nominal" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Nominal Pengeluaran (Rupiah)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" id="nominal" name="nominal" placeholder="Contoh: 50000" min="100" required
                                   class="w-full bg-[#f8fafc] dark:bg-dark-950 border border-slate-200 dark:border-dark-800 rounded-2xl pl-11 pr-4 py-3 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                        </div>
                    </div>

                    <div>
                        <label for="tanggal" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Tanggal Transaksi</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ now()->toDateString() }}" required
                               class="w-full bg-[#f8fafc] dark:bg-dark-950 border border-slate-200 dark:border-dark-800 rounded-2xl px-4 py-3 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label for="keterangan" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Deskripsi / Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Pembelian lampu jalan pos ronda RT 02" required
                                  class="w-full bg-[#f8fafc] dark:bg-dark-950 border border-slate-200 dark:border-dark-800 rounded-2xl px-4 py-3 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition resize-none"></textarea>
                    </div>
                    
                    <div class="pt-3 border-t border-slate-100 dark:border-dark-800 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('modal-keluar')"
                                class="bg-slate-100 hover:bg-slate-200 dark:bg-dark-800 dark:hover:bg-dark-700/50 text-slate-700 dark:text-slate-300 text-xs font-bold font-outfit px-4.5 py-3 rounded-xl transition duration-200">
                            Batal
                        </button>
                        <button type="submit"
                                class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white text-xs font-bold font-outfit px-5 py-3 rounded-xl transition duration-200 shadow-md shadow-emerald-500/10">
                            Simpan Catatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
