<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Informasi Geografis (WebGIS) Transmigrasi Provinsi Gorontalo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@1,500;1,600&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    <link rel="stylesheet" href="{{ asset('css/leaflet-geoman.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Pre-activate tab and restore sidebar/drawer width to prevent flicker on F5 refresh
        (function() {
            try {
                var valid = ['peta', 'tabular', 'statistik', 'pendukung'];
                var hash = window.location.hash.replace('#', '');
                var active = valid.indexOf(hash) !== -1 ? hash : localStorage.getItem('webgis_active_view');
                if (active && valid.indexOf(active) !== -1 && active !== 'peta') {
                    document.documentElement.setAttribute('data-active-view', active);
                }

                var savedSidebar = localStorage.getItem('webgis_sidebar_width');
                if (savedSidebar && window.innerWidth >= 1024) {
                    var sw = parseInt(savedSidebar, 10);
                    if (!isNaN(sw)) {
                        if (sw < 300) sw = 300;
                        var maxW = Math.floor(window.innerWidth * 0.7);
                        if (sw > maxW) sw = maxW;
                        document.documentElement.style.setProperty('--sidebar-width', sw + 'px');
                    }
                }

                var savedDrawer = localStorage.getItem('webgis_drawer_width');
                if (savedDrawer && window.innerWidth >= 640) {
                    var dw = parseInt(savedDrawer, 10);
                    if (dw >= 320 && dw <= Math.floor(window.innerWidth * 0.8)) {
                        document.documentElement.style.setProperty('--drawer-width', dw + 'px');
                    }
                }
            } catch(e) {}
        })();
    </script>

    <style>
        :root {
            --sidebar-width: 384px;
            --drawer-width: 470px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-serif-quote {
            font-family: 'Playfair Display', serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Custom scrollbars matching admin */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Top panoramic banner */
        .top-panoramic-banner {
            background-image: linear-gradient(90deg, rgba(7, 24, 48, 0.96) 0%, rgba(9, 34, 66, 0.86) 32%, rgba(13, 44, 82, 0.52) 52%, rgba(9, 34, 66, 0.86) 72%, rgba(7, 24, 48, 0.96) 100%), url('/images/hero_landscape.jpg');
            background-size: cover;
            background-position: center 38%;
        }

        /* Subtle glow for active items */
        .sidebar-active-glow {
            box-shadow: 0 4px 14px 0 rgba(26, 115, 232, 0.38);
        }
        .leaflet-container {
            font-family: inherit;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(226, 232, 240, 0.9);
            padding: 0;
            overflow: hidden;
        }
        .leaflet-popup-content {
            margin: 12px 14px;
            line-height: 1.4;
        }
        .glass-panel {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
        }

        /* Resizable Sidebar & Panels (Thin VS Code Style Splitter) */
        @media (min-width: 1024px) {
            #main-sidebar {
                width: var(--sidebar-width) !important;
                min-width: 300px;
                max-width: min(850px, 65vw);
                flex-shrink: 0 !important;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            #main-sidebar.is-collapsed {
                margin-left: calc(-1 * var(--sidebar-width)) !important;
                min-width: 0 !important;
                pointer-events: none;
            }
        }

        @media (min-width: 640px) {
            #detail-drawer {
                width: var(--drawer-width) !important;
                min-width: 340px;
                max-width: min(900px, 80vw);
            }
        }

        /* Resizing Interaction Active States */
        body.is-resizing-sidebar,
        body.is-resizing-drawer {
            cursor: col-resize !important;
            user-select: none !important;
            -webkit-user-select: none !important;
        }

        body.is-resizing-sidebar #webgis-map,
        body.is-resizing-sidebar iframe,
        body.is-resizing-drawer #webgis-map,
        body.is-resizing-drawer iframe {
            pointer-events: none !important;
        }

        body.is-resizing-sidebar #main-sidebar,
        body.is-resizing-drawer #detail-drawer {
            transition: none !important;
        }

        body.is-resizing-sidebar #sidebar-resizer {
            background-color: #007acc !important;
            width: 4px !important;
        }

        /* Instant View Persistence on F5 Refresh */
        html[data-active-view="tabular"] #view-peta,
        html[data-active-view="statistik"] #view-peta,
        html[data-active-view="pendukung"] #view-peta {
            display: none !important;
        }
        html[data-active-view="tabular"] #view-tabular {
            display: block !important;
        }
        html[data-active-view="statistik"] #view-statistik {
            display: flex !important;
        }
        html[data-active-view="pendukung"] #view-pendukung {
            display: block !important;
        }

        /* Header Nav Tab Highlights on F5 Refresh */
        html[data-active-view="tabular"] #nav-btn-peta,
        html[data-active-view="statistik"] #nav-btn-peta,
        html[data-active-view="pendukung"] #nav-btn-peta {
            background-color: transparent !important;
            color: #cbd5e1 !important;
            font-weight: 600 !important;
            box-shadow: none !important;
        }
        html[data-active-view="tabular"] #nav-btn-tabular {
            background-color: #1a73e8 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px 0 rgba(26, 115, 232, 0.38) !important;
        }
        html[data-active-view="statistik"] #nav-btn-statistik {
            background-color: #1a73e8 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px 0 rgba(26, 115, 232, 0.38) !important;
        }
        html[data-active-view="pendukung"] #nav-btn-pendukung {
            background-color: #1a73e8 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px 0 rgba(26, 115, 232, 0.38) !important;
        }
    </style>
</head>
<body class="h-full bg-[#f0f4f9] text-gray-800 antialiased flex flex-col overflow-hidden selection:bg-blue-600 selection:text-white">

    <!-- Flash Notifications for Success and Error -->
    @if (session('success'))
        <div id="flash-success-toast" class="fixed top-4 right-4 z-50 bg-[#081e36] text-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3 border border-yellow-400/50 transition-all duration-500 ease-out transform translate-y-0 opacity-100 pointer-events-none select-none">
            <span class="p-1.5 bg-yellow-500 text-slate-950 rounded-md font-bold shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </span>
            <div>
                <strong class="block text-xs font-bold text-yellow-400">Berhasil!</strong>
                <p class="text-xs text-blue-100 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        <script>
            setTimeout(function() {
                const toast = document.getElementById('flash-success-toast');
                if (toast) {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(function() {
                        if (toast) toast.remove();
                    }, 500);
                }
            }, 3000);
        </script>
    @endif

    @if ($errors->any())
        <div id="flash-error-toast" class="fixed top-4 right-4 z-50 bg-rose-900 border border-rose-500 text-white px-4 py-3 rounded-lg shadow-xl max-w-md">
            <div class="flex items-center justify-between mb-1.5">
                <strong class="text-xs font-bold text-rose-300 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Terjadi Kesalahan Validasi:
                </strong>
                <button onclick="document.getElementById('flash-error-toast').remove()" class="text-rose-400 hover:text-white text-sm font-bold cursor-pointer">&times;</button>
            </div>
            <ul class="text-[11px] list-disc pl-4 space-y-0.5 text-rose-200">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Navigation Header (Gorontalo Deep Navy & Gold Panoramic Banner) -->
    <header class="top-panoramic-banner w-full border-b border-blue-950/40 text-white z-40 shrink-0 px-3 sm:px-6 py-2.5 shadow-md select-none">
        <div class="flex items-center justify-between gap-4">
            <!-- Brand & Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('beranda') }}" aria-label="SI-TRANSMIGRASI PROVINSI GORONTALO" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-11 rounded-xl bg-white/10 backdrop-blur-md p-1 border border-white/25 shadow-sm flex items-center justify-center flex-shrink-0 group-hover:border-yellow-400 transition-colors">
                        <img src="/images/logo.png" alt="Lambang Daerah Provinsi Gorontalo" class="w-full h-full object-contain filter drop-shadow">
                    </div>
                    <div class="leading-tight">
                        <div class="text-[9px] sm:text-[10px] font-bold tracking-wider text-blue-200 uppercase">
                            SISTEM INFORMASI
                        </div>
                        <h1 class="text-sm sm:text-base font-black tracking-wide text-white drop-shadow-xs">
                            TRANSMIGRASI
                        </h1>
                        <div class="text-[9px] sm:text-[10px] font-bold tracking-widest text-yellow-300 uppercase">
                            PROVINSI GORONTALO
                        </div>
                    </div>
                </a>
            </div>

            <!-- View Switcher Tabs (Desktop) -->
            <div class="flex items-center bg-slate-900/60 backdrop-blur-md p-1 rounded-xl border border-white/15 text-xs font-semibold overflow-x-auto shadow-inner">
                <button onclick="window.switchAppView('peta')" id="nav-btn-peta" class="app-nav-tab flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition bg-[#1a73e8] text-white font-bold shadow-md sidebar-active-glow cursor-pointer" data-tab="peta">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span>Peta Spasial</span>
                </button>
                <button onclick="window.switchAppView('tabular')" id="nav-btn-tabular" class="app-nav-tab flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer" data-tab="tabular">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Data Tabular</span>
                </button>
                <button onclick="window.switchAppView('statistik')" id="nav-btn-statistik" class="app-nav-tab flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer" data-tab="statistik">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Eksekutif & Analitik</span>
                </button>
                <button onclick="window.switchAppView('pendukung')" id="nav-btn-pendukung" class="app-nav-tab flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition text-slate-300 hover:text-white hover:bg-white/10 cursor-pointer" data-tab="pendukung">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Sarana Pendukung</span>
                </button>
            </div>

            <!-- Header Right: Live Clock, Mass Management & RBAC Persona Switcher -->
            <div class="flex items-center gap-2.5">
                <!-- Real-time Clock / Date Badge (from admin layout) -->
                <div class="hidden xl:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/40 backdrop-blur-md border border-white/15 text-white shadow-2xs">
                    <svg class="w-4 h-4 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <div class="text-[11px] font-medium tracking-tight">
                        <span id="current-date-badge">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                        <span class="text-white/40 mx-1">|</span>
                        <span id="current-time-badge" class="font-mono font-bold text-yellow-300">{{ now()->format('H:i') }} WITA</span>
                    </div>
                </div>

                <!-- Dropdown Ekspor / Laporan -->
                <div class="relative group">
                    <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/45 backdrop-blur-md hover:bg-slate-900/70 text-white border border-white/20 text-xs font-semibold shadow-xs transition cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Unduh / Cetak</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute right-0 top-full mt-1 w-52 bg-white border border-slate-200 rounded-xl shadow-2xl p-1.5 hidden group-hover:block z-50 text-gray-700">
                        <a href="{{ route('data.export', ['format' => 'xlsx']) }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-900 rounded-lg transition font-medium">
                            <span class="text-blue-600 font-bold">XLSX</span>
                            <span>Ekspor Excel (.xlsx)</span>
                        </a>
                        <a href="{{ route('data.export', ['format' => 'csv']) }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-900 rounded-lg transition font-medium">
                            <span class="text-amber-600 font-bold">CSV</span>
                            <span>Ekspor CSV (.csv)</span>
                        </a>
                        <a href="{{ route('api.geojson') }}" target="_blank" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-900 rounded-lg transition font-medium">
                            <span class="text-yellow-600 font-bold">GEO</span>
                            <span>Unduh Spasial GeoJSON</span>
                        </a>
                        <hr class="border-slate-100 my-1">
                        <a href="{{ route('laporan.cetak') }}" target="_blank" class="flex items-center gap-2 px-3 py-2 text-xs text-blue-900 font-bold hover:bg-blue-50 rounded-lg transition">
                            <svg class="w-3.5 h-3.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                            <span>Cetak Dokumen Resmi (PDF)</span>
                        </a>
                    </div>
                </div>

                @guest
                    <!-- Tombol Masuk Aparatur (Mengarahkan ke Halaman Login Resmi) -->
                    <a href="{{ route('login') }}" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold border border-yellow-400 shadow-sm transition hover:scale-105 active:scale-95 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Masuk Aparatur</span>
                    </a>
                @endguest

                @auth
                    <!-- Tombol Masuk ke Panel Admin -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold border border-yellow-400 shadow-sm transition hover:scale-105 active:scale-95 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Panel Admin</span>
                    </a>

                    <!-- User Profile Dropdown -->
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2 pl-2 pr-2.5 py-1 rounded-xl bg-slate-900/45 backdrop-blur-md hover:bg-slate-900/70 border border-white/20 transition text-left cursor-pointer">
                            <div class="w-7 h-7 rounded-lg bg-yellow-500 text-slate-950 flex items-center justify-center text-xs font-bold shadow-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="hidden xl:block">
                                <span class="block text-[11px] font-bold text-white truncate max-w-[130px]">{{ auth()->user()->name }}</span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold 
                                    @if(auth()->user()->role === 'super_admin') bg-yellow-400/20 text-yellow-300
                                    @elseif(auth()->user()->role === 'operator_kabupaten') bg-amber-400/20 text-amber-300
                                    @else bg-blue-400/20 text-blue-200 @endif">
                                    {{ auth()->user()->role === 'super_admin' ? 'Super Admin' : (auth()->user()->role === 'operator_kabupaten' ? 'Operator ' . (auth()->user()->kabupaten ?? '') : 'Eksekutif') }}
                                </span>
                            </div>
                            <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-52 bg-white border border-slate-200 rounded-xl shadow-2xl p-1.5 hidden group-hover:block z-50 text-gray-800">
                            <div class="px-3 py-2 border-b border-slate-100 text-xs">
                                <span class="text-gray-400 text-[10px] block">Login sebagai:</span>
                                <strong class="text-gray-900 block truncate">{{ auth()->user()->name }}</strong>
                                <span class="text-blue-700 text-[10px] font-mono">{{ auth()->user()->email }}</span>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-900 rounded-lg transition mt-1 font-semibold">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span>Buka Panel Admin</span>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer text-left font-semibold">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER (Switchable Views) -->
    <div class="flex-1 relative overflow-hidden">

        <!-- ==================== VIEW 1: PETA SPASIAL (WEBGIS) ==================== -->
        <div id="view-peta" class="h-full w-full flex overflow-hidden relative">
            <!-- Left Sidebar: Filters & Kawasan Directory -->
            <aside id="main-sidebar" class="w-80 md:w-96 bg-white border-r border-gray-200 flex flex-col z-20 transition-all duration-300 ease-in-out absolute lg:relative inset-y-0 left-0 -translate-x-full lg:translate-x-0 shadow-xl lg:shadow-none shrink-0 overflow-hidden">
                <!-- Search & Filters -->
                <div class="p-3.5 sm:p-4 border-b border-gray-200 bg-gray-50/80">
                    <div class="flex items-center justify-between gap-2 mb-2.5 min-w-0">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="p-1.5 bg-blue-100 text-blue-900 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </span>
                            <h2 class="font-bold text-sm text-gray-900 truncate" title="Sebaran 4 Kabupaten">Sebaran 4 Kabupaten</h2>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span id="kawasan-count-badge" class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                {{ $totalKawasan }} Kawasan
                            </span>
                            <button type="button" id="btn-toggle-sidebar" class="p-1 rounded-lg hover:bg-gray-200 text-gray-500 hover:text-gray-900 transition cursor-pointer shrink-0" title="Sembunyikan Panel Direktori">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Instant Search -->
                    <div class="relative mb-2.5">
                        <input type="text" id="search-input" placeholder="Cari UPT, kecamatan, komoditas..." class="w-full bg-white border border-gray-300 rounded-xl pl-9 pr-3 py-2 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 shadow-2xs">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="grid grid-cols-2 gap-2">
                        <div class="min-w-0">
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate" title="Kabupaten">Kabupaten</label>
                            <select id="filter-kabupaten" class="w-full bg-white border border-gray-300 rounded-lg px-2 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-blue-600 truncate">
                                <option value="all">Semua 4 Kabupaten</option>
                                <option value="Kabupaten Boalemo">Kab. Boalemo</option>
                                <option value="Kabupaten Pohuwato">Kab. Pohuwato</option>
                                <option value="Kabupaten Gorontalo">Kab. Gorontalo</option>
                                <option value="Kabupaten Gorontalo Utara">Kab. Gorontalo Utara</option>
                            </select>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-1 truncate" title="Status Sertifikasi (SHM)">Status SHM</label>
                            <select id="filter-shm" class="w-full bg-white border border-gray-300 rounded-lg px-2 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-blue-600 truncate">
                                <option value="all">Semua Status</option>
                                <option value="tinggi">SHM Tinggi (≥ 75%)</option>
                                <option value="sedang">SHM Sedang (50% - 74%)</option>
                                <option value="rendah">Belum SHM (&lt; 50%)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Layer Toggles -->
                    <div class="mt-2.5 pt-2 border-t border-gray-200 flex flex-wrap items-center justify-between gap-1 text-xs text-gray-600">
                        <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-900 shrink-0">
                            <input type="checkbox" id="toggle-polygons" checked class="rounded text-blue-600 focus:ring-blue-600 w-3.5 h-3.5">
                            <span class="font-medium text-[11px]">Batas Poligon</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer hover:text-blue-900 shrink-0">
                            <input type="checkbox" id="toggle-markers" checked class="rounded text-blue-600 focus:ring-blue-600 w-3.5 h-3.5">
                            <span class="font-medium text-[11px]">Titik Sentra</span>
                        </label>
                    </div>
                </div>

                <!-- Scrollable Cards -->
                <div id="kawasan-list-container" class="flex-1 overflow-y-auto p-3 space-y-2.5 bg-gray-50/50">
                    <!-- Populated dynamically by webgis.js -->
                </div>

                <!-- Sidebar Footer Quick SHM Summary (Royal Gorontalo Blue & Gold) -->
                <div class="p-3.5 bg-[#081e36] text-white border-t border-[#0d2a4a] text-[11px] shadow-lg">
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-blue-200 font-semibold">Total Capaian Sertifikasi (SHM):</span>
                        <span class="font-extrabold text-yellow-400 font-mono text-sm">{{ $persentaseShmProvinsi }}%</span>
                    </div>
                    <div class="w-full bg-slate-900 h-2.5 rounded-full overflow-hidden mb-2.5 border border-slate-700">
                        <div class="bg-gradient-to-r from-yellow-400 to-amber-400 h-2.5 rounded-full" style="width: {{ $persentaseShmProvinsi }}%"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-[#0d2a4a]/80 p-2 rounded-xl border border-blue-900/60 shadow-inner">
                            <span class="text-yellow-300 font-bold text-[10px] uppercase block tracking-wider">Sudah Hak Milik</span>
                            <span class="font-extrabold text-white text-xs font-mono block mt-0.5">{{ number_format($totalKkShm) }} KK</span>
                        </div>
                        <div class="bg-[#0d2a4a]/80 p-2 rounded-xl border border-blue-900/60 shadow-inner">
                            <span class="text-blue-200 font-bold text-[10px] uppercase block tracking-wider">Belum Hak Milik</span>
                            <span class="font-extrabold text-white text-xs font-mono block mt-0.5">{{ number_format($totalKkNonShm) }} KK</span>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Desktop Draggable Resizer Splitter (Thin VS Code Style Sash) -->
            <div id="sidebar-resizer"
                 role="separator"
                 aria-orientation="vertical"
                 tabindex="0"
                 title="Tahan & geser untuk mengubah lebar panel (Klik 2x reset 384px)"
                 class="hidden lg:block w-[3px] hover:w-[4px] bg-slate-200 hover:bg-[#007acc] active:bg-[#005999] cursor-col-resize z-25 transition-all duration-150 select-none shrink-0 relative before:absolute before:-left-1.5 before:-right-1.5 before:top-0 before:bottom-0 before:z-10">
            </div>

            <!-- Map Area -->
            <main id="map-wrapper" class="flex-1 relative h-full w-full bg-gray-100 min-w-0">
                <div id="webgis-map" class="w-full h-full z-10"></div>

                <!-- Mode Pengambilan Titik Koordinat dari Peta Alert -->
                <div id="pick-coord-alert" class="hidden absolute top-4 left-1/2 -translate-x-1/2 z-30 bg-yellow-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-xl shadow-lg border-2 border-[#081e36] flex items-center gap-3">
                    <svg class="w-4 h-4 text-slate-950 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <span>Mode Ambil Koordinat Aktif: Silakan klik titik sentra kawasan pada peta</span>
                    <button type="button" onclick="document.getElementById('pick-coord-alert').classList.add('hidden'); document.body.style.cursor='default'; document.getElementById('form-modal').classList.remove('hidden');" class="bg-[#081e36] hover:bg-slate-900 text-white px-2.5 py-1 rounded-md text-[10px] font-semibold transition cursor-pointer">
                        Batal
                    </button>
                </div>

                <!-- Floating Geoman Geometry Editing Toolbar (Active when editing polygon) -->
                <div id="geometry-editor-toolbar" class="hidden absolute top-4 left-1/2 -translate-x-1/2 z-30 bg-[#081e36] text-white border-2 border-yellow-400 px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-4">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 animate-ping"></span>
                        <div>
                            <strong class="block text-xs font-bold text-yellow-300">Mode Editor Geometri Poligon PostGIS</strong>
                            <p class="text-[11px] text-blue-200">Menyunting: <span id="geometry-editing-target-name" class="font-bold text-white">Nama Kawasan</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="window.saveGeometryToPostGIS()" class="px-3 py-1.5 rounded-md bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan ke PostGIS</span>
                        </button>
                        <button onclick="window.cancelGeometryEditor()" class="px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition cursor-pointer">
                            Batal
                        </button>
                    </div>
                </div>

                <!-- Map Floating Base Layer Switcher & Controls (Top-Left) -->
                <div class="absolute top-4 left-4 z-20 flex flex-wrap items-center gap-2">
                    <!-- Floating Expand Sidebar Button (Shown when sidebar is collapsed) -->
                    <button type="button" id="btn-expand-sidebar" class="hidden bg-[#081e36] text-white px-3 py-1.5 rounded-xl border border-blue-950 shadow-md hover:bg-blue-900 transition flex items-center gap-2 text-xs font-bold cursor-pointer" title="Buka Kembali Panel Direktori Kawasan">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        <span>Buka Panel</span>
                    </button>

                    <div class="bg-white p-1 rounded-lg shadow-sm border border-slate-300 flex items-center gap-1 text-xs">
                        <button onclick="window.switchBaseMap('osm')" data-type="osm" class="basemap-btn px-2.5 py-1 rounded-md font-semibold transition bg-[#1a73e8] text-white shadow-xs cursor-pointer">
                            Peta Jalan (OSM)
                        </button>
                        <button onclick="window.switchBaseMap('satellite')" data-type="satellite" class="basemap-btn px-2.5 py-1 rounded-md font-medium transition bg-white text-slate-700 hover:bg-slate-100 cursor-pointer">
                            Citra Satelit
                        </button>
                        <button onclick="window.switchBaseMap('topo')" data-type="topo" class="basemap-btn px-2.5 py-1 rounded-md font-medium transition bg-white text-slate-700 hover:bg-slate-100 cursor-pointer">
                            Topografi
                        </button>
                        <button onclick="window.switchBaseMap('dark')" data-type="dark" class="basemap-btn px-2.5 py-1 rounded-md font-medium transition bg-white text-slate-700 hover:bg-slate-100 cursor-pointer">
                            Mode Gelap
                        </button>
                        <button onclick="window.switchBaseMap('positron')" data-type="positron" class="basemap-btn px-2.5 py-1 rounded-md font-medium transition bg-white text-slate-700 hover:bg-slate-100 cursor-pointer">
                            Terang
                        </button>
                    </div>

                    <button onclick="window.resetMapView()" class="bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 p-2 rounded-lg shadow-sm border border-slate-300 transition cursor-pointer" title="Kembali ke Tampilan Awal Provinsi Gorontalo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </button>

                    <button id="btn-fullscreen" class="bg-white hover:bg-slate-50 text-slate-700 hover:text-blue-600 p-2 rounded-lg shadow-sm border border-slate-300 transition cursor-pointer" title="Layar Penuh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    </button>
                </div>

                <!-- Floating Legend with SHM Certification Categories -->
                <div id="floating-legend" class="absolute bottom-5 right-4 z-20 bg-white p-3.5 rounded-lg shadow-md border border-slate-300 text-xs w-64 hidden sm:block transition-all">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="font-bold text-slate-900 text-[11px] uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            Legenda Hak Milik (SHM)
                        </h5>
                        <div class="flex items-center gap-1">
                            <button type="button" id="btn-toggle-legend" onclick="window.toggleLegendContent()" class="p-1 rounded hover:bg-slate-100 text-slate-500 hover:text-slate-800 cursor-pointer text-xs font-bold leading-none" title="Kecilkan/Buka Legenda">−</button>
                        </div>
                    </div>
                    <div id="legend-content" class="space-y-1.5 text-slate-700 text-[11px]">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-blue-600/30 border-2 border-blue-600 shrink-0"></span>
                            <span>SHM Tinggi (≥ 75% Bersertifikat)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-amber-500/30 border-2 border-amber-500 shrink-0"></span>
                            <span>SHM Sedang (50% - 74% Bersertifikat)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded bg-rose-500/20 border-2 border-dashed border-rose-500 shrink-0"></span>
                            <span>Belum SHM (&lt; 50% / Prioritas BPN)</span>
                        </div>
                        <div class="flex items-center gap-2 pt-1.5 border-t border-slate-200">
                            <div class="w-3 h-3 rounded-full bg-[#1a73e8] ring-2 ring-yellow-400 shrink-0"></div>
                            <span class="text-[10px] text-slate-600 font-medium">Sentra Fasilitas & Kantor UPT</span>
                        </div>
                    </div>
                </div>

                <!-- Slide-Out Detail Drawer (Right Side) -->
                <div id="detail-drawer" class="absolute top-0 right-0 h-full w-full sm:w-[470px] bg-white shadow-2xl z-30 transition-transform duration-300 ease-in-out translate-x-full flex flex-col border-l border-gray-200">
                    <!-- Left Edge Resizer for Drawer -->
                    <div id="drawer-resizer"
                         role="separator"
                         aria-orientation="vertical"
                         tabindex="0"
                         title="Tahan & geser untuk mengubah lebar panel detail (Klik ganda untuk reset ke 470px)"
                         class="hidden sm:flex absolute -left-1.5 top-0 bottom-0 w-3 hover:w-3.5 bg-transparent hover:bg-yellow-400/80 active:bg-yellow-500 cursor-col-resize z-40 items-center justify-center select-none group transition-all">
                        <div class="h-10 w-1 rounded-full bg-slate-400/60 group-hover:bg-[#081e36] transition-colors pointer-events-none"></div>
                    </div>

                    <!-- Drawer Header -->
                    <div class="relative bg-[#081e36] text-white p-5 border-b border-[#0d2a4a]">
                        <button onclick="window.closeDetailDrawer()" class="absolute top-4 right-4 p-1.5 text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition cursor-pointer" title="Tutup">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <div class="flex items-center gap-2 mb-2">
                            <span id="drawer-type" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-400/20 text-yellow-300 border border-yellow-400/30">
                                Kawasan Transmigrasi
                            </span>
                            <span id="drawer-tahun" class="text-xs text-blue-200 font-mono">Tahun 2008</span>
                        </div>
                        <h3 id="drawer-title" class="font-bold text-xl text-white tracking-tight leading-snug">Nama Kawasan</h3>
                        <p id="drawer-kabupaten" class="text-xs text-blue-200 mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            Kabupaten Boalemo
                        </p>
                    </div>

                    <!-- Landscape Image -->
                    <div class="relative h-44 bg-gray-900 overflow-hidden shrink-0">
                        <img src="/images/hero_landscape.jpg" alt="Foto Lanskap Kawasan Transmigrasi" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-3">
                            <span class="text-[11px] text-white/90 font-medium bg-black/40 backdrop-blur-xs px-2.5 py-0.5 rounded-md">
                                Dokumentasi Pemukiman & Lahan Usaha Tani
                            </span>
                        </div>
                    </div>

                    <!-- Drawer Scrollable Body -->
                    <div class="flex-1 overflow-y-auto p-5 space-y-4">
                        
                        <!-- SHM Certification Banner -->
                        <div class="bg-gradient-to-br from-blue-50/70 to-slate-50/40 p-4 rounded-xl border border-blue-200/80 shadow-2xs">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#1a73e8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Status Sertifikasi Hak Milik (SHM)
                                </h4>
                            </div>
                            <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden mb-2.5">
                                <div id="drawer-shm-bar" class="bg-[#1a73e8] h-2.5 rounded-full transition-all duration-500" style="width: 75%"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-white p-2.5 rounded-lg border border-blue-100 shadow-2xs">
                                    <span class="text-gray-500 text-[10px] block mb-0.5">Sudah Bersertifikat (SHM)</span>
                                    <strong id="drawer-shm-kk" class="text-blue-900 font-bold block text-sm">540 KK (79.41%)</strong>
                                    <span id="drawer-shm-luas" class="text-[11px] text-gray-600">3.375 Ha</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-lg border border-yellow-100 shadow-2xs">
                                    <span class="text-gray-500 text-[10px] block mb-0.5">Belum Punya Sertifikat (Non-SHM)</span>
                                    <strong id="drawer-non-shm-kk" class="text-yellow-700 font-bold block text-sm">140 KK (20.59%)</strong>
                                    <span id="drawer-non-shm-luas" class="text-[11px] text-gray-600">875 Ha (Proses BPN)</span>
                                </div>
                            </div>
                        </div>

                        <!-- General Population & Land Metrics -->
                        <div class="grid grid-cols-3 gap-2">
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-200 text-center">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block mb-0.5">Total KK</span>
                                <strong id="drawer-kk" class="text-gray-900 font-bold text-sm block">680 KK</strong>
                                <span id="drawer-jiwa" class="text-[10px] text-gray-500">2.720 Jiwa</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-200 text-center">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block mb-0.5">Total Luas</span>
                                <strong id="drawer-luas" class="text-blue-900 font-bold text-sm block">4.250 Ha</strong>
                                <span class="text-[10px] text-gray-500">Alokasi Lahan</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-xl border border-gray-200 text-center">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider block mb-0.5">Indeks Mandiri</span>
                                <strong id="drawer-skor" class="text-yellow-700 font-bold text-sm block">88/100</strong>
                                <span id="drawer-status" class="text-[10px] text-gray-500 truncate block">Mandiri</span>
                            </div>
                        </div>

                        <!-- Profil & Wilayah -->
                        <div>
                            <h4 class="font-bold text-xs text-gray-900 uppercase tracking-wider mb-1">Profil Kawasan</h4>
                            <p id="drawer-deskripsi" class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-200">
                                Deskripsi...
                            </p>
                        </div>

                        <!-- Komoditas Unggulan -->
                        <div>
                            <h4 class="font-bold text-xs text-gray-900 uppercase tracking-wider mb-1.5">Komoditas Unggulan</h4>
                            <div id="drawer-komoditas" class="flex flex-wrap gap-1.5"></div>
                        </div>

                        <!-- Sarana Pendukung -->
                        <div>
                            <h4 class="font-bold text-xs text-gray-900 uppercase tracking-wider mb-1.5">Infrastruktur & Sarana Pendukung</h4>
                            <div id="drawer-fasilitas" class="space-y-2"></div>
                        </div>

                        <!-- Koordinat Sentra -->
                        <div class="bg-[#081e36] text-white p-3.5 rounded-xl text-xs flex items-center justify-between font-mono shadow-sm">
                            <div>
                                <span class="text-blue-200 block text-[10px]">Titik Koordinat Sentra</span>
                                <span id="drawer-coords" class="text-yellow-400 font-bold">0.7200° N, 122.3500° E</span>
                            </div>
                            <button onclick="window.resetMapView()" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 rounded text-[11px] text-slate-200 transition cursor-pointer">
                                Reset Zoom
                            </button>
                        </div>

                        <!-- Action Buttons in Drawer -->
                        <div class="pt-2 border-t border-gray-200">
                            @auth
                                <div class="flex gap-2">
                                    <button id="drawer-geom-btn" class="flex-1 py-2 px-3 rounded-xl bg-[#1a73e8] hover:bg-blue-700 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                                        <span>Edit Poligon Canvas</span>
                                    </button>
                                    <button id="drawer-edit-btn" class="flex-1 py-2 px-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                                        <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit Data Tabular</span>
                                    </button>
                                </div>
                            @else
                                <div class="text-center py-2 px-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-600 text-[11px] flex items-center justify-between">
                                    <span>Hak penyuntingan data dibatasi.</span>
                                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-bold hover:underline flex items-center gap-1">
                                        <span>Masuk Aparatur</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- ==================== VIEW 2: DATA TABULAR ==================== -->
        <div id="view-tabular" class="h-full w-full bg-gray-50 flex flex-col overflow-hidden hidden">
            <!-- Table Header Bar -->
            <div class="p-4 bg-white border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shrink-0">
                <div>
                    <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <span>Data Tabular Transmigrasi Provinsi Gorontalo</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-semibold">{{ $totalKawasan }} Kawasan Tersebar</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar permukiman transmigrasi di Kab. Boalemo, Pohuwato, Kab. Gorontalo, dan Gorut beserta rincian SHM</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('data.export', ['format' => 'xlsx']) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-xs border border-yellow-600/30 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Unduh Excel (.xlsx)</span>
                    </a>
                    <a href="{{ route('laporan.cetak') }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#1a73e8] hover:bg-blue-700 text-white text-xs font-semibold shadow-xs border border-blue-950 transition">
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>Cetak Dokumen Resmi</span>
                    </a>
                </div>
            </div>

            <!-- Tabular Search & Filter Toolbar -->
            <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 shrink-0">
                <div class="flex flex-wrap items-center gap-2 flex-1 min-w-[280px]">
                    <div class="relative flex-1 min-w-[220px] max-w-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="tabular-search-input" placeholder="Cari nama kawasan, kecamatan, desa..." 
                            class="w-full pl-9 pr-3 py-1.5 rounded-xl bg-white border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] transition-all">
                    </div>
                    <select id="tabular-filter-kabupaten" class="py-1.5 px-3 rounded-xl bg-white border border-slate-300 text-xs text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1a73e8] transition-all cursor-pointer">
                        <option value="all">Semua Kabupaten</option>
                        <option value="Boalemo">Kab. Boalemo</option>
                        <option value="Gorontalo">Kab. Gorontalo</option>
                        <option value="Gorontalo Utara">Kab. Gorontalo Utara</option>
                        <option value="Pohuwato">Kab. Pohuwato</option>
                    </select>
                    <select id="tabular-filter-shm" class="py-1.5 px-3 rounded-xl bg-white border border-slate-300 text-xs text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1a73e8] transition-all cursor-pointer">
                        <option value="all">Semua Status SHM</option>
                        <option value="tinggi">SHM Tinggi (≥ 75%)</option>
                        <option value="sedang">SHM Sedang (50% – 74%)</option>
                        <option value="rendah">Belum SHM (&lt; 50%)</option>
                    </select>
                </div>
                <div class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                    <span>Menampilkan:</span>
                    <span id="tabular-row-count" class="font-bold text-slate-900 font-mono bg-white px-2 py-0.5 rounded border border-slate-200">{{ $totalKawasan }}</span>
                    <span>kawasan</span>
                </div>
            </div>

            <!-- Table Responsive Container -->
            <div class="flex-1 overflow-auto p-4">
                <div class="bg-white rounded-xl shadow-xs border border-slate-200 overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-[#081e36] text-white text-[11px] font-bold uppercase tracking-wider border-b-2 border-slate-700">
                            <tr class="divide-x divide-slate-700/60">
                                <th class="py-3 px-3 text-center w-12 font-bold text-slate-200">No</th>
                                <th class="py-3 px-3.5 font-bold text-slate-200">Nama Kawasan</th>
                                <th class="py-3 px-3.5 font-bold text-slate-200">Kabupaten</th>
                                <th class="py-3 px-3.5 font-bold text-slate-200">Kecamatan & Desa</th>
                                <th class="py-3 px-3 text-center w-16 font-bold text-slate-200">Tahun</th>
                                <th class="py-3 px-3.5 text-right font-bold text-slate-200">Jml Penduduk</th>
                                <th class="py-3 px-3.5 text-right font-bold text-slate-200">Sudah SHM</th>
                                <th class="py-3 px-3.5 text-right font-bold text-slate-200">Belum SHM</th>
                                <th class="py-3 px-3 text-center w-24 font-bold text-slate-200">% SHM</th>
                                <th class="py-3 px-3.5 text-right font-bold text-slate-200">Total Luas</th>
                                <th class="py-3 px-3.5 font-bold text-slate-200">Status</th>
                                <th class="py-3 px-3 text-center w-32 font-bold text-slate-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-700">
                            @foreach ($allKawasan as $idx => $k)
                                @php
                                    $shmBadge = $k->persentase_shm >= 75 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($k->persentase_shm >= 50 ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-rose-50 text-rose-800 border-rose-200');
                                @endphp
                                <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-blue-50/50 transition-colors divide-x divide-slate-200"
                                    data-nama="{{ strtolower($k->nama) }}"
                                    data-kabupaten="{{ strtolower($k->kabupaten) }}"
                                    data-kecamatan="{{ strtolower($k->kecamatan) }}"
                                    data-desa="{{ strtolower($k->desa) }}"
                                    data-shm="{{ $k->persentase_shm }}">
                                    <td class="py-2.5 px-3 text-center font-mono text-slate-500 font-semibold">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-3.5">
                                        <strong class="font-bold text-slate-900 block leading-tight">{{ $k->nama }}</strong>
                                        <span class="text-[10px] text-blue-700 font-semibold">{{ $k->tipe }}</span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-slate-800 font-medium whitespace-nowrap">
                                        {{ str_replace('Kabupaten ', '', $k->kabupaten) }}
                                    </td>
                                    <td class="py-2.5 px-3.5 text-slate-600">
                                        <span class="font-medium text-slate-800">{{ $k->kecamatan }}</span>, Desa {{ $k->desa }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-mono font-semibold text-slate-700">{{ $k->tahun_penempatan }}</td>
                                    <td class="py-2.5 px-3.5 text-right">
                                        <strong class="text-slate-900 font-bold font-mono">{{ number_format($k->jumlah_kk) }} KK</strong>
                                        <span class="text-[10px] text-slate-500 block">{{ number_format($k->jumlah_jiwa) }} Jiwa</span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-right font-mono">
                                        <strong class="text-blue-900 font-bold">{{ number_format($k->kk_shm) }} KK</strong>
                                        <span class="text-[10px] text-slate-500 font-normal block">{{ number_format($k->luas_shm_ha, 1) }} Ha</span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-right font-mono">
                                        <strong class="text-amber-800 font-bold">{{ number_format($k->kk_non_shm) }} KK</strong>
                                        <span class="text-[10px] text-slate-500 font-normal block">{{ number_format($k->luas_non_shm_ha, 1) }} Ha</span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold border {{ $shmBadge }}">
                                            {{ $k->persentase_shm }}%
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3.5 text-right font-mono font-semibold text-slate-800">
                                        {{ number_format($k->luas_ha, 1) }} Ha
                                    </td>
                                    <td class="py-2.5 px-3.5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-300">
                                            {{ $k->status_perkembangan }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button onclick="window.focusKawasanOnMap({{ $k->id }})" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-[#1a73e8] hover:text-white text-[#1a73e8] border border-blue-200 transition shadow-2xs font-bold text-[11px] cursor-pointer" title="Tampilkan di Peta Spasial & Buka Profil">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                                <span>Peta & Profil</span>
                                            </button>
                                            @auth
                                                <button onclick="window.openEditModal({{ $k->id }})" class="p-1.5 rounded-lg bg-slate-100 hover:bg-yellow-400 hover:text-slate-950 text-slate-600 border border-slate-300 transition shadow-2xs cursor-pointer" title="Edit Data (Aparatur)">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== VIEW 3: EKSEKUTIF & ANALITIK ==================== -->
        <div id="view-statistik" class="h-full w-full bg-gray-50 flex flex-col overflow-y-auto hidden p-5 sm:p-6 space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Pusat Analitik & Dasbor Eksekutif</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Analisis Komposisi Sertifikasi Hak Milik (SHM), Benchmark 4 Kabupaten, Tren Historis, dan Klasterisasi Prioritas ATR/BPN</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('laporan.cetak') }}" target="_blank" class="px-3.5 py-1.5 rounded-xl bg-[#1a73e8] hover:bg-blue-700 text-white text-xs font-semibold transition flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                        <span>Cetak Dokumen Resmi (PDF)</span>
                    </a>
                </div>
            </div>

            <!-- Top KPI Cards with PostGIS Area Feature -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Permukiman</span>
                    <strong class="text-2xl font-extrabold text-gray-900 block font-mono">{{ $totalKawasan }}</strong>
                    <span class="text-[11px] text-gray-500">KTM, UPT & SP</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Penduduk</span>
                    <strong class="text-2xl font-extrabold text-gray-900 block font-mono">{{ number_format($totalKk) }} <span class="text-xs font-normal">KK</span></strong>
                    <span class="text-[11px] text-gray-500">{{ number_format($totalJiwa) }} Jiwa Transmigran</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-sm">
                    <span class="text-[10px] font-bold text-blue-900 uppercase tracking-wider block mb-1">Capaian Sertifikasi Hak Milik (SHM)</span>
                    <strong class="text-2xl font-extrabold text-blue-900 block font-mono">{{ $persentaseShmProvinsi }}%</strong>
                    <span class="text-[11px] text-blue-700 font-medium">{{ number_format($totalKkShm) }} KK Sudah Sertifikat</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-yellow-200 shadow-sm">
                    <span class="text-[10px] font-bold text-yellow-700 uppercase tracking-wider block mb-1">Backlog Non-SHM</span>
                    <strong class="text-2xl font-extrabold text-yellow-700 block font-mono">{{ number_format($totalKkNonShm) }} <span class="text-xs font-normal">KK</span></strong>
                    <span class="text-[11px] text-yellow-800 font-medium">{{ number_format($totalLuasNonShmHa, 1) }} Ha Belum SHM</span>
                </div>
                <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-sm bg-gradient-to-br from-white to-blue-50/40">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold text-blue-900 uppercase tracking-wider block">Luas Geospasial</span>
                        <span class="text-[9px] px-1.5 py-0.5 rounded font-bold bg-blue-50 text-blue-700 border border-blue-200">Terverifikasi</span>
                    </div>
                    <strong id="kpi-postgis-area" class="text-2xl font-extrabold text-blue-900 block font-mono">{{ number_format($totalLuasHa, 1) }} Ha</strong>
                    <span class="text-[11px] text-blue-700 font-medium">Total Luas Terpetakan Spasial</span>
                </div>
            </div>

            <!-- Charts Row 1: Pie & Bar Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <!-- Chart 1: Donut/Pie Chart Komposisi SHM -->
                <div class="lg:col-span-5 bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                    <div class="mb-3">
                        <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                            <span>Komposisi Legalitas Hak Milik (SHM)</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">Provinsi</span>
                        </h3>
                        <p class="text-[11px] text-gray-500">Perbandingan jumlah KK transmigran bersertifikat SHM vs belum bersertifikat</p>
                    </div>
                    <div class="flex-1 min-h-[260px] relative">
                        <canvas id="chart-pie-shm"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Grouped Bar Chart Benchmark 4 Kabupaten -->
                <div class="lg:col-span-7 bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col">
                    <div class="mb-3">
                        <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                            <span>Benchmark Komparatif 4 Kabupaten Target</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-900 font-bold border border-yellow-200">Boalemo, Pohuwato, Kab. Gorontalo, Gorut</span>
                        </h3>
                        <p class="text-[11px] text-gray-500">Volume KK bersertifikat SHM dan Non-SHM di masing-masing kabupaten</p>
                    </div>
                    <div class="flex-1 min-h-[260px] relative">
                        <canvas id="chart-bar-regencies"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart Row 2: Line Chart Tren Historis 2005 - 2024 -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <div class="mb-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                            <span>Tren Historis Perkembangan Transmigrasi & Penerbitan SHM (2005 – 2024)</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">Historis 2 Dekade</span>
                        </h3>
                        <p class="text-[11px] text-gray-500">Progres kumulatif penempatan keluarga transmigran dan realisasi penerbitan sertifikat tanah</p>
                    </div>
                    <div class="text-xs text-gray-500 font-medium">
                        Sumber Data: Disnakertrans & Kanwil ATR/BPN Gorontalo
                    </div>
                </div>
                <div class="h-72 w-full relative">
                    <canvas id="chart-line-trend"></canvas>
                </div>
            </div>

            <!-- SECTION: KLASTERISASI SPASIAL PRIORITAS INTERVENSI ATR/BPN -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="font-bold text-base text-gray-900 flex items-center gap-2">
                            <span>Klasterisasi Spasial Multivariat & Rekomendasi Aksi ATR/BPN</span>
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-200">Algoritma Prioritas Intervensi</span>
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Penetapan zonasi prioritas percepatan legalitas tanah transmigrasi berdasarkan skor gabungan (Backlog KK, % Non-SHM, dan Luas Lahan Belum Bersertifikat)</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Prioritas I: Mendesak</span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Prioritas II: Menengah</span>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Prioritas III: Monitoring</span>
                    </div>
                </div>

                <!-- Dynamic Clustering Container (Populated by webgis.js) -->
                <div id="clustering-container">
                    <div class="p-8 text-center text-gray-400 text-xs">Memuat modul klasterisasi spasial...</div>
                </div>
            </div>
        </div>

        <!-- ==================== VIEW 4: SARANA PENDUKUNG ==================== -->
        <div id="view-pendukung" class="h-full w-full bg-gray-50 flex flex-col overflow-y-auto hidden p-5 sm:p-6 space-y-6">
            <!-- Header Section with Title & Search/Filter Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                        <span>Katalog Infrastruktur & Sarana Pendukung</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-semibold">{{ $totalKawasan }} Kawasan</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Kondisi fasilitas kesehatan, pendidikan, akses air bersih, elektrifikasi PLN, dan aksesibilitas jalan di {{ $totalKawasan }} permukiman transmigrasi</p>
                </div>
                
                <!-- Live Search and Filter for Sarana Pendukung -->
                <div class="flex flex-wrap items-center gap-2">
                    <div class="relative min-w-[220px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" id="pendukung-search-input" placeholder="Cari sarana, puskesmas, SD, jalan..." 
                            class="w-full pl-9 pr-3 py-1.5 rounded-xl bg-white border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] transition-all">
                    </div>
                    <select id="pendukung-filter-kabupaten" class="py-1.5 px-3 rounded-xl bg-white border border-slate-300 text-xs text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1a73e8] transition-all cursor-pointer">
                        <option value="all">Semua Kabupaten</option>
                        <option value="Boalemo">Kab. Boalemo</option>
                        <option value="Gorontalo">Kab. Gorontalo</option>
                        <option value="Gorontalo Utara">Kab. Gorontalo Utara</option>
                        <option value="Pohuwato">Kab. Pohuwato</option>
                    </select>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="pendukung-cards-grid">
                @foreach ($allKawasan as $item)
                    @php
                        $shmCardBadge = $item->persentase_shm >= 75 ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($item->persentase_shm >= 50 ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-rose-50 text-rose-800 border-rose-200');
                    @endphp
                    <div class="pendukung-card bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between"
                        data-nama="{{ strtolower($item->nama) }}"
                        data-kabupaten="{{ strtolower($item->kabupaten) }}"
                        data-fasilitas="{{ strtolower(json_encode($item->sarana_prasarana ?? [])) }}">
                        <div>
                            <!-- Card Header -->
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200/80">
                                    {{ $item->tipe }} • {{ str_replace('Kabupaten ', '', $item->kabupaten) }}
                                </span>
                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full border {{ $shmCardBadge }}">
                                    {{ $item->persentase_shm }}% SHM
                                </span>
                            </div>
                            
                            <h4 class="font-bold text-sm text-slate-900 leading-snug mb-0.5">
                                {{ $item->nama }}
                            </h4>
                            <p class="text-[11px] text-slate-500 mb-3">
                                Kec. {{ $item->kecamatan }}, Desa {{ $item->desa }}
                            </p>

                            <!-- Facility Details with Dedicated Icons -->
                            <div class="space-y-2.5 pt-3 border-t border-slate-100 text-xs">
                                <!-- Kesehatan -->
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center shrink-0 mt-0.5" title="Fasilitas Kesehatan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Kesehatan</span>
                                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">
                                            {{ $item->sarana_prasarana['kesehatan'] ?? 'Dalam pendataan dinas' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Pendidikan -->
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 border border-blue-200/60 flex items-center justify-center shrink-0 mt-0.5" title="Fasilitas Pendidikan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Pendidikan</span>
                                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">
                                            {{ $item->sarana_prasarana['pendidikan'] ?? 'Dalam pendataan dinas' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Air Bersih -->
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-cyan-50 text-cyan-600 border border-cyan-200/60 flex items-center justify-center shrink-0 mt-0.5" title="Air Bersih">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Air Bersih</span>
                                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">
                                            {{ $item->sarana_prasarana['air_bersih'] ?? 'Dalam pendataan dinas' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Listrik -->
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 mt-0.5" title="Elektrifikasi Listrik">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Elektrifikasi Listrik</span>
                                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">
                                            {{ $item->sarana_prasarana['listrik'] ?? 'Dalam pendataan dinas' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Akses Jalan -->
                                <div class="flex items-start gap-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 mt-0.5" title="Aksesibilitas Jalan">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Akses Jalan</span>
                                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">
                                            {{ $item->sarana_prasarana['akses_jalan'] ?? 'Dalam pendataan dinas' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="pt-4 mt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <span class="text-[11px] font-mono font-semibold text-slate-500">{{ number_format($item->jumlah_kk) }} KK</span>
                            <div class="flex items-center gap-1.5">
                                <button onclick="window.focusKawasanOnMap({{ $item->id }})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 active:scale-95 text-slate-950 text-xs font-bold shadow-2xs transition cursor-pointer">
                                    <span>Tinjau di Peta</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>



    <!-- ==================== MODAL 2: MASS DATA IMPORT ==================== -->
    <div id="import-modal" class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto hidden">
        <div class="relative bg-white border border-gray-200 rounded-xl max-w-lg w-full text-gray-800 shadow-2xl p-6">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Impor Data Sensus Massal</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Unggah berkas Excel (.xlsx) atau CSV untuk pembaruan data secara massal</p>
                </div>
                <button onclick="window.closeImportModal()" class="text-gray-400 hover:text-gray-700 p-1 text-lg font-bold cursor-pointer transition">&times;</button>
            </div>

            <form onsubmit="window.submitImportForm(event)" class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg border-2 border-dashed border-gray-200 text-center">
                    <svg class="w-10 h-10 text-blue-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <label class="block text-xs font-semibold text-gray-800 mb-1 cursor-pointer">
                        Pilih Berkas Excel / CSV
                        <input type="file" name="file" accept=".xlsx,.csv" required class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#081e36] file:text-white hover:file:bg-slate-900 mt-2 cursor-pointer transition">
                    </label>
                    <p class="text-[10px] text-gray-400 mt-1">Maksimal ukuran berkas 10 MB (Format: .xlsx, .csv)</p>
                </div>

                <div class="flex items-center justify-between text-xs text-gray-600 bg-blue-50/70 p-2.5 rounded-lg border border-blue-100">
                    <span>Butuh format acuan kolom sensus?</span>
                    <a href="{{ route('data.template') }}" class="text-blue-700 hover:text-blue-900 hover:underline font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh Template</span>
                    </a>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="window.closeImportModal()" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-md transition cursor-pointer">
                        Unggah & Proses Impor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL 3: TAMBAH / EDIT KAWASAN ==================== -->
    <div id="form-modal" class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-5 overflow-y-auto hidden">
        <div class="relative bg-white border border-gray-200 rounded-xl max-w-4xl w-full text-gray-800 shadow-2xl overflow-hidden my-auto max-h-[92vh] flex flex-col">
            <!-- Modal Header -->
            <div class="p-5 border-b border-blue-950/40 bg-[#081e36] text-white flex items-center justify-between shrink-0">
                <div>
                    <h3 id="form-modal-title" class="text-lg font-bold text-white flex items-center gap-2">
                        Tambah Kawasan Transmigrasi Baru
                    </h3>
                    <p class="text-xs text-slate-300 mt-0.5">Kelola data spasial kawasan, demografi penduduk, sertifikasi hak milik (SHM), dan sarana prasarana</p>
                </div>
                <button type="button" onclick="window.closeFormModal()" class="p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Form Scrollable Body -->
            <form id="kawasan-form" method="POST" action="/kawasan" class="overflow-y-auto flex-1 p-5 sm:p-6 space-y-6 bg-white">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="polygon_geojson" id="form-polygon-json">

                <!-- SECTION 1: IDENTITAS WILAYAH & ADMINISTRASI -->
                <div>
                    <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#1a73e8]"></span>
                        1. Identitas & Administrasi Kawasan (4 Kabupaten Target)
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kawasan / UPT <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" id="form-nama" required placeholder="Contoh: UPT Motilango SP-1" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Kawasan <span class="text-rose-500">*</span></label>
                            <select name="tipe" id="form-tipe" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                                <option value="UPT">UPT (Unit Pemukiman Transmigrasi)</option>
                                <option value="KTM">KTM (Kota Terpadu Mandiri)</option>
                                <option value="SP">SP (Satuan Pemukiman)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kabupaten <span class="text-rose-500">*</span></label>
                            <select name="kabupaten" id="form-kabupaten" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                                <option value="Kabupaten Boalemo">Kabupaten Boalemo</option>
                                <option value="Kabupaten Pohuwato">Kabupaten Pohuwato</option>
                                <option value="Kabupaten Gorontalo">Kabupaten Gorontalo</option>
                                <option value="Kabupaten Gorontalo Utara">Kabupaten Gorontalo Utara</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                            <input type="text" name="kecamatan" id="form-kecamatan" required placeholder="Nama Kecamatan" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Desa <span class="text-rose-500">*</span></label>
                            <input type="text" name="desa" id="form-desa" required placeholder="Nama Desa" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Penempatan <span class="text-rose-500">*</span></label>
                            <input type="number" name="tahun_penempatan" id="form-tahun" required min="1970" max="2030" placeholder="2008" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status Perkembangan</label>
                            <select name="status_perkembangan" id="form-status" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                                <option value="Mandiri">Mandiri</option>
                                <option value="Berkembang">Berkembang</option>
                                <option value="Pembinaan">Pembinaan</option>
                                <option value="Perencanaan">Perencanaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Skor Kemandirian (0-100)</label>
                            <input type="number" name="skor_kemandirian" id="form-skor" min="0" max="100" placeholder="85" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DEMOGRAFI & SERTIFIKASI HAK MILIK (SHM) -->
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            2. Demografi Penduduk & Sertifikasi Hak Milik (SHM)
                        </h4>
                        <span id="form-persentase-preview" class="text-xs font-bold font-mono px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                            0% SHM
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Total Penduduk (KK) <span class="text-rose-500">*</span></label>
                            <input type="number" name="jumlah_kk" id="form-jumlah-kk" required min="0" placeholder="500" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Total Jiwa <span class="text-rose-500">*</span></label>
                            <input type="number" name="jumlah_jiwa" id="form-jumlah-jiwa" required min="0" placeholder="2000" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div class="bg-blue-50/70 p-2.5 rounded-xl border border-blue-200">
                            <label class="block text-xs font-semibold text-blue-950 mb-1">Sudah Hak Milik (SHM) <span class="text-rose-500">*</span></label>
                            <input type="number" name="kk_shm" id="form-kk-shm" required min="0" placeholder="400" class="w-full bg-white border border-blue-300 rounded-xl px-3 py-2 text-xs text-blue-950 font-bold focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-mono transition">
                            <span class="text-[10px] text-blue-700 mt-1 block font-medium">KK Bersertifikat Resmi ATR/BPN</span>
                        </div>
                        <div class="bg-yellow-50/80 p-2.5 rounded-xl border border-yellow-200">
                            <label class="block text-xs font-semibold text-yellow-950 mb-1">Belum Punya SHM (Non-SHM) <span class="text-rose-500">*</span></label>
                            <input type="number" name="kk_non_shm" id="form-kk-non-shm" required min="0" placeholder="100" class="w-full bg-white border border-yellow-300 rounded-xl px-3 py-2 text-xs text-yellow-950 font-bold focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 font-mono transition">
                            <span class="text-[10px] text-yellow-800 mt-1 block font-medium">KK Dalam Proses Sertifikasi</span>
                        </div>
                    </div>

                    <!-- Alokasi Luas Lahan -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 mt-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Total Luas Lahan (Ha) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.1" name="luas_ha" id="form-luas-ha" required min="0" placeholder="3500" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-blue-900 mb-1">Luas Lahan Sudah SHM (Ha)</label>
                            <input type="number" step="0.1" name="luas_shm_ha" id="form-luas-shm" min="0" placeholder="Otomatis terisi jika kosong" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-yellow-800 mb-1">Luas Lahan Belum SHM (Ha)</label>
                            <input type="number" step="0.1" name="luas_non_shm_ha" id="form-luas-non-shm" min="0" placeholder="Otomatis terisi jika kosong" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:bg-white transition font-mono">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: SPASIAL (KOORDINAT PETA) & KOMODITAS -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#1a73e8]"></span>
                        3. Koordinat Batas Wilayah Spasial & Komoditas Unggulan
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5">
                        <div class="sm:col-span-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Latitude (° N) <span class="text-rose-500">*</span></label>
                            <input type="number" step="any" name="latitude" id="form-latitude" required placeholder="0.7200" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div class="sm:col-span-4">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Longitude (° E) <span class="text-rose-500">*</span></label>
                            <input type="number" step="any" name="longitude" id="form-longitude" required placeholder="122.3500" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition font-mono">
                        </div>
                        <div class="sm:col-span-4 flex items-end">
                            <button type="button" onclick="window.pickCoordinateFromMap()" class="w-full bg-[#081e36] hover:bg-slate-900 text-white font-semibold text-xs py-2 px-3 rounded-xl transition flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Ambil dari Peta Spasial</span>
                            </button>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Komoditas Unggulan (Pisahkan koma)</label>
                            <input type="text" name="komoditas_unggulan" id="form-komoditas" placeholder="Jagung Hibrida, Padi Sawah, Kelapa Dalam, Kakao" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div class="sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Asal Transmigran</label>
                            <input type="text" name="asal_transmigran" id="form-asal" placeholder="Jawa Timur, Jawa Tengah, DIY, Transmigran Lokal" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div class="sm:col-span-12">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi & Profil Kawasan</label>
                            <textarea name="deskripsi" id="form-deskripsi" rows="2" placeholder="Uraian gambaran umum kawasan transmigrasi..." class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: SARANA PRASARANA PENDUKUNG -->
                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                        4. Sarana & Prasarana Pendukung
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Fasilitas Kesehatan</label>
                            <input type="text" name="sarana_prasarana[kesehatan]" id="form-fasilitas-kesehatan" placeholder="Pustu, Poskesdes, Bidan Desa" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Fasilitas Pendidikan</label>
                            <input type="text" name="sarana_prasarana[pendidikan]" id="form-fasilitas-pendidikan" placeholder="SD Negeri, PAUD, SMP Terdekat" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Air Bersih & Sanitasi</label>
                            <input type="text" name="sarana_prasarana[air_bersih]" id="form-fasilitas-air" placeholder="Perpipaan Gravitasi, Sumur Bor" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Elektrifikasi / Listrik</label>
                            <input type="text" name="sarana_prasarana[listrik]" id="form-fasilitas-listrik" placeholder="Jaringan PLN 24 Jam / PLTS" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Aksesibilitas Jalan</label>
                            <input type="text" name="sarana_prasarana[akses_jalan]" id="form-fasilitas-jalan" placeholder="Aspal Hotmix, Rabat Beton JUT" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 mb-1">Sarana Ekonomi / Pertanian</label>
                            <input type="text" name="sarana_prasarana[ekonomi]" id="form-fasilitas-ekonomi" placeholder="Koperasi Desa, Gudang & Pengering Jagung" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Form Footer Buttons -->
                <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3 shrink-0">
                    <button type="button" onclick="window.closeFormModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-md transition hover:scale-105 active:scale-95 flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Data Kawasan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update Live WITA Time Badge
        setInterval(function() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const timeEl = document.getElementById('current-time-badge');
            if (timeEl) timeEl.textContent = `${hours}:${minutes} WITA`;
        }, 1000);

        // Tabular View Real-time Search & Filter
        const tabSearch = document.getElementById('tabular-search-input');
        const tabKabFilter = document.getElementById('tabular-filter-kabupaten');
        const tabShmFilter = document.getElementById('tabular-filter-shm');
        const tabRowCount = document.getElementById('tabular-row-count');

        function filterTabularRows() {
            const searchVal = (tabSearch?.value || '').toLowerCase().trim();
            const kabVal = tabKabFilter?.value || 'all';
            const shmVal = tabShmFilter?.value || 'all';

            const rows = document.querySelectorAll('#view-tabular tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const nama = (row.dataset.nama || '').toLowerCase();
                const kab = (row.dataset.kabupaten || '').toLowerCase();
                const kec = (row.dataset.kecamatan || '').toLowerCase();
                const desa = (row.dataset.desa || '').toLowerCase();
                const shm = parseFloat(row.dataset.shm || '0');

                let matchSearch = !searchVal || nama.includes(searchVal) || kab.includes(searchVal) || kec.includes(searchVal) || desa.includes(searchVal);
                let matchKab = (kabVal === 'all') || kab.includes(kabVal.toLowerCase());
                let matchShm = true;
                if (shmVal === 'tinggi') matchShm = shm >= 75;
                else if (shmVal === 'sedang') matchShm = shm >= 50 && shm < 75;
                else if (shmVal === 'rendah') matchShm = shm < 50;

                if (matchSearch && matchKab && matchShm) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (tabRowCount) tabRowCount.textContent = visibleCount;
        }

        tabSearch?.addEventListener('input', filterTabularRows);
        tabKabFilter?.addEventListener('change', filterTabularRows);
        tabShmFilter?.addEventListener('change', filterTabularRows);

        // Sarana Pendukung Real-time Search & Filter
        const pendSearch = document.getElementById('pendukung-search-input');
        const pendKabFilter = document.getElementById('pendukung-filter-kabupaten');

        function filterPendukungCards() {
            const searchVal = (pendSearch?.value || '').toLowerCase().trim();
            const kabVal = pendKabFilter?.value || 'all';

            const cards = document.querySelectorAll('.pendukung-card');
            cards.forEach(card => {
                const nama = (card.dataset.nama || '').toLowerCase();
                const kab = (card.dataset.kabupaten || '').toLowerCase();
                const fasilitas = (card.dataset.fasilitas || '').toLowerCase();

                let matchSearch = !searchVal || nama.includes(searchVal) || kab.includes(searchVal) || fasilitas.includes(searchVal);
                let matchKab = (kabVal === 'all') || kab.includes(kabVal.toLowerCase());

                if (matchSearch && matchKab) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        pendSearch?.addEventListener('input', filterPendukungCards);
        pendKabFilter?.addEventListener('change', filterPendukungCards);
    </script>
</body>
</html>
