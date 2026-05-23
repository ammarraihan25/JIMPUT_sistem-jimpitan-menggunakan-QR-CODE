<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Jimput — Sistem Digitalisasi Jimpitan Warga Berbasis QR Code">
    <title>@yield('title', 'Jimput') | Sistem Jimpitan QR</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50:  '#f0fdf4',
                            100: '#dcfce7',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#064e3b', // Premium Dark Forest Green
                            900: '#022c22'
                        },
                        dark: {
                            950: '#0f172a', // Slate 900
                            900: '#1e293b', // Slate 800
                            800: '#334155', // Slate 700
                            700: '#475569', // Slate 600
                            600: '#64748b', // Slate 500
                            500: '#94a3b8',
                            400: '#cbd5e1'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -2px rgba(148, 163, 184, 0.08);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
        }
        .sidebar-active {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            border-left: 4px solid #4ade80;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f8fafc] text-slate-800 dark:bg-dark-950 dark:text-slate-200 min-h-screen flex transition-colors duration-200">

    {{-- Script anti-flash tema --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @auth
    {{-- Left Sidebar --}}
    <aside id="sidebar" 
           class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-800 text-emerald-100 flex flex-col justify-between border-r border-brand-900/30
                  -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none">
        
        <div>
            {{-- Sidebar Header --}}
            <div class="px-6 py-5 flex items-center justify-between border-b border-brand-900/40">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('scanner') }}" 
                   class="flex items-center gap-3 font-bold font-outfit text-xl tracking-tight text-white">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm">
                        <img src="{{ asset('logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                    </div>
                    <span class="bg-gradient-to-r from-white via-emerald-100 to-white bg-clip-text text-transparent">Jimput</span>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden text-emerald-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Sidebar User Profile (Brief) --}}
            <div class="px-4 py-4 border-b border-brand-900/30 bg-brand-900/25">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white font-bold flex items-center justify-center border-2 border-emerald-400/40 shadow-inner">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-white truncate leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider mt-0.5">
                            {{ auth()->user()->role }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Links --}}
            <nav class="px-3 py-4 space-y-1.5 font-outfit">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-900/30 hover:text-white transition-all duration-200 {{ Route::is('admin.dashboard') ? 'sidebar-active' : 'opacity-80 hover:opacity-100' }}">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.wargas.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-900/30 hover:text-white transition-all duration-200 {{ Route::is('admin.wargas.*') ? 'sidebar-active' : 'opacity-80 hover:opacity-100' }}">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Manajemen Warga</span>
                    </a>

                    <a href="{{ route('admin.keuangan') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-900/30 hover:text-white transition-all duration-200 {{ Route::is('admin.keuangan') ? 'sidebar-active' : 'opacity-80 hover:opacity-100' }}">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Keuangan & Laporan</span>
                    </a>
                @endif

                <a href="{{ route('scanner') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-900/30 hover:text-white transition-all duration-200 {{ Route::is('scanner') ? 'sidebar-active' : 'opacity-80 hover:opacity-100' }}">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm0 11h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm11-11h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                    <span>Scanner QR</span>
                </a>

                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('riwayat.hari-ini') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-brand-900/30 hover:text-white transition-all duration-200 {{ Route::is('riwayat.hari-ini') ? 'sidebar-active' : 'opacity-80 hover:opacity-100' }}">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Riwayat Scan</span>
                    </a>
                @endif
            </nav>
        </div>

        {{-- Sidebar Footer --}}
        <div class="p-4 border-t border-brand-900/30 bg-brand-950/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 bg-red-600/80 hover:bg-red-600 text-white font-semibold py-2.5 rounded-xl transition duration-200 text-sm shadow-lg shadow-red-950/20 hover:shadow-red-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    {{-- Main Right Container --}}
    <div class="flex-1 flex flex-col min-w-0 {{ auth()->check() ? 'lg:pl-64' : '' }}">
        
        {{-- Top Header --}}
        <header class="bg-white dark:bg-dark-900 border-b border-slate-200 dark:border-dark-800 sticky top-0 z-30 px-6 py-4 flex items-center justify-between transition-colors duration-200">
            <div class="flex items-center gap-3">
                @auth
                    <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 dark:text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-dark-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                @endauth
                <div class="flex flex-col">
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-extrabold font-outfit">Sistem Ronda & Jimpitan Digital</span>
                    <span class="text-base font-extrabold text-slate-800 dark:text-white tracking-tight leading-none mt-1">KAMPUNG RT 02/RW 02</span>
                </div>
            </div>

            <div class="flex items-center gap-3.5">
                {{-- Light/Dark Mode Switcher --}}
                <button id="theme-toggle" 
                        class="text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-dark-800 focus:outline-none rounded-xl text-sm p-2 transition-all duration-200 border border-slate-200 dark:border-dark-800 shadow-sm">
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707m2.828 9.9a5 5 0 117.072 0l-7.072 0z"></path>
                    </svg>
                </button>

                @auth
                <div class="hidden sm:flex items-center gap-2 border-l border-slate-200 dark:border-dark-800 pl-3.5">
                    <div class="text-right">
                        <div class="text-xs font-bold text-slate-800 dark:text-white leading-none">{{ auth()->user()->name }}</div>
                        <div class="text-[9px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase mt-1 leading-none tracking-wider">{{ auth()->user()->role }}</div>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div id="flash-msg"
             class="fixed top-24 right-6 z-50 animate-bounce bg-emerald-600 text-white px-5 py-3.5 rounded-2xl shadow-xl shadow-emerald-500/10 text-sm font-semibold max-w-sm flex items-center gap-3 border border-emerald-500/50">
            <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <script>setTimeout(() => { const el = document.getElementById('flash-msg'); if(el) el.remove(); }, 3500);</script>
        @endif

        {{-- Main View Area --}}
        <main class="@yield('main-class', 'max-w-6xl w-full mx-auto px-6 py-8') flex-1">
            @yield('content')
        </main>
    </div>

    {{-- Dark/Light Mode Switcher Logic --}}
    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Ganti ikon switcher berdasarkan tema aktif
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        const themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // Jika sebelumnya diatur di local storage
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });

        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        }
    </script>
    @stack('scripts')
</body>
</html>
