<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Login Petugas | Jimput</title>
    
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
                        }
                    }
                } 
            }
        }
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-grid {
            background-image: radial-gradient(circle at 1px 1px, rgba(16, 185, 129, 0.05) 1px, transparent 0);
            background-size: 24px 24px;
        }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float { animation: float 5s ease-in-out infinite; }
        @keyframes slideUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        .slide-up { animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    {{-- Script anti-flash tema --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 min-h-screen flex items-center justify-center bg-grid p-4 md:p-6 transition-colors duration-200">

    {{-- Ambient glowing orbs --}}
    <div class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-emerald-500/10 dark:bg-emerald-500/5 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-[400px] h-[400px] bg-teal-500/10 dark:bg-teal-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    {{-- Main Container (Split Screen Layout) --}}
    <div class="slide-up w-full max-w-5xl bg-white dark:bg-slate-900 rounded-[32px] shadow-2xl border border-slate-100 dark:border-slate-800/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px] z-10">
        
        <!-- Left Column: Marketing & Illustration (Desktop Only) -->
        <div class="hidden lg:flex lg:col-span-7 bg-gradient-to-br from-emerald-50/50 to-teal-50/50 dark:from-slate-900/60 dark:to-slate-950/40 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative blur backgrounds -->
            <div class="absolute -top-24 -left-24 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-brand-400/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="z-10 flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" class="h-9 object-contain" alt="Logo">
                <span class="text-xl font-extrabold font-outfit bg-gradient-to-r from-emerald-600 to-brand-800 dark:from-emerald-400 dark:to-teal-400 bg-clip-text text-transparent">Jimput</span>
            </div>
            
            <div class="z-10 my-auto text-center">
                <img src="{{ asset('ilustrasti.png') }}" class="max-w-[80%] mx-auto object-contain float max-h-[340px] drop-shadow-2xl rounded-2xl" alt="Ilustrasi Jimput">
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-8 font-outfit">Sistem Ronda & Jimpitan Digital</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs mt-2.5 max-w-md mx-auto leading-relaxed">Pencatatan jimpitan ronda malam berbasis QR Code untuk mewujudkan akuntabilitas dan transparansi keuangan paguyuban warga.</p>
            </div>
            
            <div class="z-10 flex justify-between text-[10px] font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest">
                <span>&copy; {{ date('Y') }} Paguyuban RT 02</span>
                <span>Kampung Hijau Digital</span>
            </div>
        </div>
        
        <!-- Right Column: Login Form (Full Height, No Smartphone Mockup) -->
        <div class="lg:col-span-5 flex flex-col justify-between p-8 md:p-12 bg-white dark:bg-slate-950 min-h-[580px] w-full">
            
            <div class="my-auto space-y-6">
                
                {{-- Logo & Brand --}}
                <div class="text-center">
                    <img src="{{ asset('logo.png') }}" class="h-20 mx-auto object-contain drop-shadow-sm" alt="Logo Jimput">
                    <p class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mt-3 font-outfit">Sistem Ronda Digital</p>
                </div>

                {{-- Heading --}}
                <div class="text-center">
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white font-outfit">Welcome back !</h3>
                    <p class="text-xs text-slate-400 mt-1">Masukkan kredensial untuk masuk ke sistem</p>
                </div>

                {{-- Validation Errors --}}
                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 rounded-2xl px-4 py-3 text-[11px] text-red-400 flex items-start gap-2.5">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 px-1">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                autocomplete="username"
                                autofocus
                                class="w-full bg-slate-55 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-full pl-11 pr-4 py-3.5 text-slate-800 dark:text-slate-200 text-xs
                                       placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       transition-all duration-200"
                                required>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2 px-1">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                class="w-full bg-slate-55 dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80 rounded-full pl-11 pr-11 py-3.5 text-slate-800 dark:text-slate-200 text-xs
                                       placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                       transition-all duration-200"
                                required>
                            <button type="button" id="toggle-password"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium px-2">
                        <label class="flex items-center cursor-pointer select-none">
                            <input type="checkbox" id="remember" name="remember"
                                class="w-3.5 h-3.5 rounded bg-slate-100 dark:bg-slate-900 border-slate-350 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-white dark:focus:ring-offset-slate-950">
                            <span class="ml-2">Remember me</span>
                        </label>
                        <a href="#" onclick="alert('Silakan hubungi Koordinator RT untuk mereset sandi Anda.')" class="hover:text-emerald-500 transition">Forgot password?</a>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="btn-login"
                            class="w-full bg-gradient-to-r from-emerald-500 to-brand-800 text-white font-bold
                                   py-3.5 rounded-full text-xs hover:from-emerald-600 hover:to-brand-900
                                   active:scale-[0.98] transition-all duration-200 shadow-md shadow-emerald-500/10">
                            Login
                        </button>
                    </div>
                </form>

                {{-- Sign up info --}}
                <div class="text-center text-[10px] text-slate-400">
                    <span>New user? <a href="#" onclick="alert('Silakan hubungi koordinator RT untuk membuat akun petugas ronda baru.')" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Sign Up</a></span>
                </div>
            </div>

            {{-- Collapsible credentials hint matching the mockup look --}}
            <div class="border-t border-slate-100 dark:border-slate-800/80 pt-4 mt-6">
                <details class="group cursor-pointer">
                    <summary class="flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-wider select-none list-none">
                        <span>Demo Credentials Hint</span>
                        <span class="transition group-open:rotate-180">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </summary>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-[9px] text-slate-500">
                        <div class="bg-slate-50 dark:bg-slate-900 p-2 rounded-xl border border-slate-100 dark:border-slate-800/40">
                            <div class="font-extrabold text-slate-400">Admin Account:</div>
                            <div class="mt-1">User: <span class="font-mono text-slate-800 dark:text-slate-300">admin</span></div>
                            <div>Pass: <span class="font-mono text-slate-800 dark:text-slate-300">admin123</span></div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900 p-2 rounded-xl border border-slate-100 dark:border-slate-800/40">
                            <div class="font-extrabold text-slate-400">Petugas Account:</div>
                            <div class="mt-1">User: <span class="font-mono text-slate-800 dark:text-slate-300">budi</span></div>
                            <div>Pass: <span class="font-mono text-slate-800 dark:text-slate-300">petugas123</span></div>
                        </div>
                    </div>
                </details>
            </div>
            
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const pwd = document.getElementById('password');
            const iconSvg = this.querySelector('svg');
            
            if (pwd.type === 'password') {
                pwd.type = 'text';
                iconSvg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                pwd.type = 'password';
                iconSvg.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });
    </script>
</body>
</html>
