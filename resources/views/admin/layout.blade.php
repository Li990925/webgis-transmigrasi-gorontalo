<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SISTEM INFORMASI TRANSMIGRASI PROVINSI GORONTALO</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,500;1,600&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Leaflet & Geoman CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="/css/leaflet-geoman.css"/>
    
    <!-- Leaflet JS & Chart.js -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-quote { font-family: 'Playfair Display', serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Custom scrollbars */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Banner landscape with dark overlay */
        .top-panoramic-banner {
            background-image: linear-gradient(90deg, rgba(7, 24, 48, 0.96) 0%, rgba(9, 34, 66, 0.86) 32%, rgba(13, 44, 82, 0.52) 52%, rgba(9, 34, 66, 0.86) 72%, rgba(7, 24, 48, 0.96) 100%), url('/images/hero_landscape.jpg');
            background-size: cover;
            background-position: center 38%;
        }

        /* Subtle glow for active items */
        .sidebar-active-glow {
            box-shadow: 0 4px 14px 0 rgba(26, 115, 232, 0.38);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-[#f0f4f9] text-gray-800 min-h-screen flex flex-col antialiased selection:bg-blue-600 selection:text-white">

    <!-- ==================== TOP PANORAMIC HEADER BANNER ==================== -->
    <header class="top-panoramic-banner w-full h-20 sm:h-22 border-b border-blue-950/40 px-3 sm:px-6 flex items-center justify-between text-white shadow-md relative z-30 select-none">
        
        <!-- Left: Branding & Official Emblem -->
        <div class="flex items-center gap-3">
            <button id="mobile-menu-btn" class="md:hidden p-1.5 rounded-lg bg-blue-950/60 text-white hover:bg-blue-900 border border-white/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-12 rounded-xl bg-white/10 backdrop-blur-md p-1 border border-white/30 shadow-sm flex items-center justify-center flex-shrink-0 group-hover:border-yellow-400 transition-colors">
                    <img src="/images/logo.png" alt="Lambang Daerah Provinsi Gorontalo" class="w-full h-full object-contain filter drop-shadow">
                </div>
                <div class="leading-tight">
                    <div class="text-[10px] sm:text-[11px] font-bold tracking-wider text-blue-100 uppercase">
                        SISTEM INFORMASI
                    </div>
                    <div class="text-sm sm:text-lg font-black tracking-wide text-white drop-shadow-xs">
                        TRANSMIGRASI
                    </div>
                    <div class="text-[9px] sm:text-[10px] font-bold tracking-widest text-yellow-300 uppercase">
                        PROVINSI GORONTALO
                    </div>
                </div>
            </a>
        </div>

        <!-- Center: Inspiring Motto Quote -->
        <div class="hidden lg:flex items-center justify-center max-w-xl text-center px-4">
            <div class="relative">
                <span class="text-2xl text-yellow-400/80 font-serif leading-none select-none">“</span>
                <p class="inline text-xs xl:text-sm font-serif-quote italic text-white/95 drop-shadow font-medium px-1.5">
                    Transmigrasi untuk Masyarakat Sejahtera dan Kawasan Berkembang
                </p>
                <span class="text-2xl text-yellow-400/80 font-serif leading-none select-none">”</span>
            </div>
        </div>

        <!-- Right: Time Widget, Notifications & Admin Profile -->
        <div class="flex items-center gap-2.5 sm:gap-3.5">
            
            <!-- Real-time Clock / Date Badge (Glassmorphism) -->
            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/40 backdrop-blur-md border border-white/15 text-white shadow-2xs">
                <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <div class="text-[11px] font-medium tracking-tight">
                    <span id="current-date-badge">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    <span class="text-white/40 mx-1">|</span>
                    <span id="current-time-badge" class="font-mono font-bold text-yellow-300">{{ now()->format('H:i') }} WITA</span>
                </div>
            </div>

            <!-- Notification Bell with Count -->
            <div class="relative">
                <button type="button" class="w-9 h-9 rounded-xl bg-slate-900/40 backdrop-blur-md border border-white/20 hover:bg-slate-900/60 text-white flex items-center justify-center transition shadow-2xs relative">
                    <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center shadow-xs border border-white/40">3</span>
                </button>
            </div>

            <!-- Admin Profile Badge Dropdown Trigger -->
            <div class="relative">
                <button onclick="document.getElementById('persona-modal').classList.remove('hidden')" 
                    class="flex items-center gap-2.5 pl-1.5 pr-2.5 py-1 rounded-xl bg-slate-900/45 backdrop-blur-md border border-white/20 hover:bg-slate-900/70 text-left transition shadow-2xs cursor-pointer group">
                    <div class="w-8 h-8 rounded-lg bg-yellow-500 text-slate-950 font-bold text-xs flex items-center justify-center shadow-xs overflow-hidden border border-yellow-300">
                        @if(Auth::user()->avatar ?? false)
                            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="hidden md:block leading-tight text-left">
                        <div class="text-xs font-bold text-white group-hover:text-yellow-300 transition-colors flex items-center gap-1">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div class="text-[10px] text-blue-200 truncate max-w-[140px]">
                            {{ Auth::user()->kabupaten ?? 'Provinsi Gorontalo' }}
                        </div>
                    </div>
                </button>
            </div>

        </div>
    </header>

    <!-- ==================== BODY WRAPPER (SIDEBAR + MAIN CONTENT) ==================== -->
    <div class="flex flex-1 min-h-[calc(100vh-80px)]">
        
        <!-- ==================== LEFT SIDEBAR (DEEP NAVY BLUE) ==================== -->
        <aside id="sidebar" class="w-64 bg-[#081e36] border-r border-[#0d2a4a] flex-shrink-0 flex flex-col justify-between hidden md:flex sticky top-0 h-[calc(100vh-80px)] z-20 shadow-lg text-white select-none">
            <div class="overflow-y-auto py-3 px-3 space-y-1">
                
                <!-- 1. Dashboard (Active Pill) -->
                <a href="{{ route('admin.dashboard') }}" 
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#1a73e8] text-white sidebar-active-glow' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Dashboard</span>
                    </div>
                </a>

                <!-- 2. Profil Transmigrasi -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>Profil Transmigrasi</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 3. Data Kawasan -->
                <a href="{{ route('admin.kawasan.index') }}" 
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.kawasan.*') ? 'bg-[#1a73e8] text-white sidebar-active-glow font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.kawasan.*') ? 'text-white' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Data Kawasan</span>
                    </div>
                </a>

                <!-- 4. Data UPT -->
                <a href="{{ route('admin.kawasan.index', ['tipe' => 'UPT']) }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Data UPT</span>
                    </div>
                </a>

                <!-- 5. Data Penempatan -->
                <a href="{{ route('admin.kawasan.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>Data Penempatan</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 6. Data Kepala Keluarga -->
                <a href="{{ route('admin.kawasan.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span>Data Kepala Keluarga</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 7. Data Lahan & SHM -->
                <a href="{{ route('admin.klasterisasi') }}" 
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.klasterisasi') ? 'bg-[#1a73e8] text-white sidebar-active-glow font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.klasterisasi') ? 'text-white' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Data Lahan & SHM</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 8. Pembangunan Sarana -->
                <a href="{{ route('admin.kawasan.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span>Pembangunan Sarana</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 9. Pemberdayaan Ekonomi -->
                <a href="{{ route('admin.kawasan.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Pemberdayaan Ekonomi</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 10. Monitoring & Evaluasi -->
                <a href="{{ route('admin.klasterisasi') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Monitoring & Evaluasi</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- 11. Peta Interaktif (Direct Link to WebGIS) -->
                <a href="{{ route('beranda') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span>Peta Interaktif</span>
                    </div>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>

                <!-- 12. Laporan -->
                <a href="{{ route('laporan.cetak') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Laporan</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                @if(Auth::user()->isSuperAdmin())
                <!-- 13. Master Data (User Management) -->
                <a href="{{ route('admin.users.index') }}" 
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-[#1a73e8] text-white sidebar-active-glow font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6"></path></svg>
                        <span>Master Data</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                @endif

                <!-- 14. Pengaturan -->
                <a href="{{ route('admin.audit-logs') }}" 
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.audit-logs') ? 'bg-[#1a73e8] text-white sidebar-active-glow font-bold' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.audit-logs') ? 'text-white' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        <span>Pengaturan</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

            </div>

            <!-- Sidebar Bottom: Gorontalo Watermark Map & Quick Actions -->
            <div class="p-3 border-t border-[#0e2a4a] bg-[#061527] space-y-2">
                
                <!-- Watermark Map Graphic: Authentic Gorontalo Boundary & Regencies -->
                <div class="px-3 py-2.5 rounded-xl bg-blue-950/40 border border-blue-900/40 flex flex-col items-center justify-center text-center group">
                    <img src="/images/gorontalo_map_silhouette.png" alt="Peta Provinsi Gorontalo" class="w-full h-auto max-h-14 object-contain opacity-80 group-hover:opacity-100 transition-opacity filter drop-shadow">
                    <div class="text-[10px] font-extrabold tracking-widest text-blue-200 uppercase mt-1.5">
                        PROVINSI GORONTALO
                    </div>
                </div>

                <!-- Simulation Persona Switcher -->
                <button type="button" onclick="document.getElementById('persona-modal').classList.remove('hidden')" 
                    class="w-full py-1.5 px-3 rounded-lg bg-blue-900/40 hover:bg-blue-900/70 text-blue-200 hover:text-white border border-blue-800/60 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    <span>Simulasi Peran Aparatur</span>
                </button>

                <!-- Logout Form -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="w-full py-1.5 px-3 rounded-lg bg-rose-950/30 hover:bg-rose-900/50 text-rose-300 hover:text-rose-100 border border-rose-900/40 text-[11px] font-semibold flex items-center justify-center gap-1.5 transition cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>

            </div>
        </aside>

        <!-- ==================== MAIN CONTENT AREA ==================== -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#f0f4f9]">
            
            <!-- System Flash Notifications -->
            @if (session('success') || session('error'))
            <div class="px-4 sm:px-6 pt-4">
                @if (session('success'))
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 font-bold">&times;</button>
                    </div>
                @endif
            </div>
            @endif

            <!-- Sub-header Bar for Page Title & Breadcrumbs -->
            @hasSection('header-title')
                <div class="px-4 sm:px-6 pt-3 pb-2 flex items-center justify-between border-b border-slate-200/60 bg-white/50 backdrop-blur-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-800">
                            @yield('header-title')
                        </h2>
                    </div>
                    <div class="text-[11px] text-slate-400 font-medium hidden sm:flex items-center gap-1.5">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Panel</a>
                        <span>&rsaquo;</span>
                        <span class="text-slate-700 font-semibold">@yield('title', 'Dashboard')</span>
                    </div>
                </div>
            @endif

            <!-- Main Page Dynamic Content -->
            <main class="flex-1 p-3.5 sm:p-5 lg:p-6 min-w-0">
                @yield('content')
            </main>

            <!-- Bottom Copyright Footer -->
            <footer class="border-t border-slate-200/80 bg-white/70 py-3 px-6 text-center text-[11px] text-slate-500">
                Sistem Informasi Transmigrasi &bull; Dinas Tenaga Kerja, ESDM dan Transmigrasi Provinsi Gorontalo &bull; &copy; {{ date('Y') }}
            </footer>

        </div>
    </div>

    <!-- ==================== MODAL PERSONA SWITCHER ==================== -->
    <div id="persona-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white border border-slate-200 rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4 text-slate-800">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Simulasi Hak Akses Aparatur</h3>
                        <p class="text-[11px] text-slate-500">Pilih salah satu dari 6 akun demo aparatur pemangku kepentingan</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('persona-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 text-lg font-bold p-1">&times;</button>
            </div>

            <div class="space-y-2">
                @php
                    $personas = [
                        ['role' => 'super_admin', 'title' => 'Super Admin Provinsi', 'email' => 'admin@gorontalo.go.id', 'badge' => 'Akses Penuh', 'badgeClass' => 'bg-purple-100 text-purple-900 border-purple-200'],
                        ['role' => 'eksekutif', 'title' => 'Eksekutif / Pimpinan', 'email' => 'eksekutif@gorontalo.go.id', 'badge' => 'Monitoring', 'badgeClass' => 'bg-amber-100 text-amber-900 border-amber-200'],
                        ['role' => 'operator_boalemo', 'title' => 'Operator Boalemo', 'email' => 'operator.boalemo@gorontalo.go.id', 'badge' => 'Kab. Boalemo', 'badgeClass' => 'bg-blue-100 text-blue-900 border-blue-200'],
                        ['role' => 'operator_pohuwato', 'title' => 'Operator Pohuwato', 'email' => 'operator.pohuwato@gorontalo.go.id', 'badge' => 'Kab. Pohuwato', 'badgeClass' => 'bg-indigo-100 text-indigo-900 border-indigo-200'],
                        ['role' => 'operator_gorontalo', 'title' => 'Operator Kab. Gorontalo', 'email' => 'operator.gorontalo@gorontalo.go.id', 'badge' => 'Kab. Gorontalo', 'badgeClass' => 'bg-purple-100 text-purple-900 border-purple-200'],
                        ['role' => 'operator_gorut', 'title' => 'Operator Gorontalo Utara', 'email' => 'operator.gorut@gorontalo.go.id', 'badge' => 'Gorut', 'badgeClass' => 'bg-teal-100 text-teal-900 border-teal-200'],
                    ];
                @endphp

                @foreach ($personas as $p)
                    <form action="{{ route('switch-persona.web') }}" method="POST">
                        @csrf
                        <input type="hidden" name="role" value="{{ $p['role'] }}">
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                        <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-300 transition-all text-left flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <div>
                                    <div class="text-xs font-bold text-slate-900 group-hover:text-blue-700">{{ $p['title'] }}</div>
                                    <div class="text-[10px] text-slate-500 font-mono">{{ $p['email'] }}</div>
                                </div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded font-bold border {{ $p['badgeClass'] }}">{{ $p['badge'] }}</span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        });

        // Dynamic Clock (WITA timezone UTC+8)
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const timeEl = document.getElementById('current-time-badge');
            if (timeEl) {
                timeEl.textContent = `${hours}:${minutes} WITA`;
            }
        }
        setInterval(updateClock, 30000);
    </script>
    
    @yield('scripts')
</body>
</html>
