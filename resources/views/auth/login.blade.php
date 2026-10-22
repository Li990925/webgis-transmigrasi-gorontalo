<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Panel Admin - SI-TRANSMIGRASI Provinsi Gorontalo</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-800 flex flex-col justify-between selection:bg-yellow-400 selection:text-purple-950 relative overflow-x-hidden">

    <div class="min-h-screen flex flex-col lg:flex-row w-full">
        
        <!-- ==================== LEFT HERO SHOWCASE (GORONTALO GIS BACKDROP) ==================== -->
        <div class="relative lg:w-7/12 xl:w-3/5 bg-purple-950 text-white flex flex-col justify-between p-6 sm:p-8 lg:p-8 overflow-hidden border-b lg:border-b-0 lg:border-r border-purple-800/60 min-h-[520px] lg:min-h-screen">
            
            <!-- Real Gorontalo Transmigration Settlement Landscape with Rich Gradient Overlays -->
            <div class="absolute inset-0 z-0">
                <img src="/images/hero_landscape.jpg" alt="Bentang Alam Kawasan Transmigrasi Gorontalo" class="w-full h-full object-cover object-center filter brightness-[0.75] contrast-[1.05]">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-950/95 via-purple-900/85 to-purple-950/90 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-purple-950 via-purple-950/40 to-purple-950/80"></div>
                <!-- Subtle Geospatial Grid Matrix Pattern -->
                <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#eab308_1px,transparent_1px)] [background-size:24px_24px]"></div>
            </div>

            <!-- Top Left: Provincial Identity & Logo -->
            <div class="relative z-10 flex items-center h-11 mb-4 sm:mb-6 shrink-0">
                <a href="{{ route('beranda') }}" class="flex items-center gap-3.5 group">
                    <div class="relative w-11 h-12 rounded-xl overflow-hidden ring-2 ring-yellow-400/90 shadow-lg shadow-yellow-500/20 shrink-0 group-hover:scale-105 transition-transform duration-200 bg-white/10 backdrop-blur-md p-1 flex items-center justify-center">
                        <img src="/images/logo.png" alt="Lambang Daerah Provinsi Gorontalo" class="w-full h-full object-contain filter drop-shadow">
                    </div>
                    <div>
                        <div class="font-extrabold text-base sm:text-lg tracking-tight text-white leading-tight">
                            SI-TRANSMIGRASI GORONTALO
                        </div>
                        <div class="text-xs text-purple-200/90 font-medium">
                            Dinas Tenaga Kerja, ESDM dan Transmigrasi Provinsi Gorontalo
                        </div>
                    </div>
                </a>
            </div>

            <!-- Center: Core Mission & Value Proposition -->
            <div class="relative z-10 my-auto py-8 lg:py-12 max-w-2xl space-y-6">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1 rounded-full bg-yellow-400/15 border border-yellow-400/30 text-yellow-300 text-xs font-semibold backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                    <span>Portal Resmi Satu Data Spasial & Keamanan Wilayah</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white">
                    Masuk ke Sistem Tata Kelola <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-200 to-yellow-400">Transmigrasi Gorontalo</span>
                </h1>

                <p class="text-sm sm:text-base text-purple-100/90 leading-relaxed font-normal">
                    Sistem satu data spasial untuk percepatan sertifikasi Hak Milik (SHM), pencatatan riwayat data, pemantauan indeks kemandirian kawasan, dan analitik 4 kabupaten terpadu.
                </p>

                <!-- 4 Glassmorphic Feature Highlights Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                    <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-400/20 backdrop-blur-md shadow-inner flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-yellow-400/20 text-yellow-300 flex items-center justify-center shrink-0 border border-yellow-400/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Hak Akses Berdasarkan Wilayah</h4>
                            <p class="text-[11px] text-purple-200/80 leading-normal mt-0.5">Operasional terkelola per kabupaten tugas (Boalemo, Pohuwato, Kab. Gorontalo, Gorut).</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-400/20 backdrop-blur-md shadow-inner flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-400/20 text-purple-200 flex items-center justify-center shrink-0 border border-purple-400/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Log Aktivitas & Riwayat Data</h4>
                            <p class="text-[11px] text-purple-200/80 leading-normal mt-0.5">Pencatatan riwayat setiap perubahan data tabular dan batas poligon spasial.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-400/20 backdrop-blur-md shadow-inner flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center shrink-0 border border-amber-400/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Monitoring Capaian SHM Real-Time</h4>
                            <p class="text-[11px] text-purple-200/80 leading-normal mt-0.5">Pemantauan progres sertifikasi tanah pekarangan & lahan usaha 14 kawasan.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-purple-900/40 border border-purple-400/20 backdrop-blur-md shadow-inner flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-400/20 text-emerald-300 flex items-center justify-center shrink-0 border border-emerald-400/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">PostGIS & Geoman Geometry</h4>
                            <p class="text-[11px] text-purple-200/80 leading-normal mt-0.5">Engine geospasial presisi untuk batas poligon kawasan dan koordinat sentra.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Left: Official Verification & Security Stamp -->
            <div class="relative z-10 pt-3.5 border-t border-purple-800/60 flex items-center justify-between gap-3 text-[11px] text-purple-300 mt-4 sm:mt-6 h-11 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Server Terotentikasi &bull; Zona Waktu Indonesia Tengah (WITA)</span>
                </div>
                <div>
                    <span>Disnakertrans Provinsi Gorontalo &bull; Halaman Resmi</span>
                </div>
            </div>
        </div>

        <!-- ==================== RIGHT AUTHENTICATION CONSOLE ==================== -->
        <div class="lg:w-5/12 xl:w-2/5 bg-gradient-to-br from-slate-100 via-purple-50/40 to-slate-100/90 flex flex-col justify-between p-6 sm:p-8 lg:p-8 relative z-10 overflow-y-auto">
            
            <!-- Ambient GIS Background Lights & Matrix Watermark -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
                <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-purple-300/30 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-yellow-300/25 blur-3xl"></div>
                <!-- Subtle Topography Contour Vector Motif -->
                <svg class="absolute inset-0 w-full h-full opacity-[0.035] text-purple-950" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,20 Q25,30 50,15 T100,25 L100,0 L0,0 Z" fill="currentColor"/>
                    <path d="M0,50 Q30,65 60,40 T100,55 L100,100 L0,100 Z" fill="currentColor"/>
                    <path d="M0,80 Q35,70 70,85 T100,75 L100,100 L0,100 Z" fill="currentColor"/>
                </svg>
            </div>

            <!-- Top Controls: Status & Back Link -->
            <div class="relative z-10 flex items-center justify-between gap-3 h-11 mb-4 sm:mb-6 shrink-0">
                <span class="inline-flex items-center gap-2 h-9 px-3.5 rounded-xl bg-white/90 text-emerald-800 border border-emerald-200/80 text-xs font-bold shadow-xs backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Portal Aparatur</span>
                </span>

                <a href="{{ route('beranda') }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-xl bg-white/90 hover:bg-purple-900 hover:text-white text-purple-950 text-xs font-bold border border-purple-200/80 shadow-xs backdrop-blur-md transition-all cursor-pointer group">
                    <svg class="w-3.5 h-3.5 text-yellow-500 group-hover:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Peta</span>
                </a>
            </div>

            <!-- Floating Elevated Console Card -->
            <div class="relative z-10 my-auto max-w-[500px] xl:max-w-[530px] w-full mx-auto bg-white/95 backdrop-blur-xl rounded-3xl p-5 sm:p-6 shadow-xl shadow-purple-950/10 border border-slate-200/90 ring-1 ring-purple-950/5">
                
                <!-- Card Header with Official Shield Badge -->
                <div class="flex items-center gap-3.5 mb-4 pb-3 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-900 to-purple-950 text-yellow-400 flex items-center justify-center font-black shadow-md shadow-purple-950/20 shrink-0 border border-purple-700/60 ring-2 ring-yellow-400/40">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-extrabold text-gray-900 tracking-tight leading-none">
                            Masuk ke Panel Akun
                        </h2>
                        <p class="text-[11px] sm:text-xs text-purple-900/70 font-medium mt-1">
                            Autentikasi Kredensial Aparatur Pemerintah
                        </p>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-3.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-xs">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-3.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
                        <div class="font-bold mb-1 flex items-center gap-1.5 text-rose-900">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Gagal masuk sistem:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Standard Email & Password Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 mb-1 flex items-center justify-between">
                            <span>Alamat Email Dinas</span>
                            <span class="text-[10px] text-gray-400 font-normal">Format: @gorontalo.go.id</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-purple-900/60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </div>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="w-full pl-10 pr-3.5 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/15 focus:border-purple-600 focus:bg-white transition-all font-mono shadow-2xs"
                                placeholder="nama@gorontalo.go.id">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-bold text-gray-700">Kata Sandi</label>
                            <a href="#bantuan" onclick="document.getElementById('bantuan-modal').classList.remove('hidden')" class="text-[11px] text-purple-700 hover:text-purple-900 hover:underline font-bold cursor-pointer">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-purple-900/60">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                class="w-full pl-10 pr-10 py-2 rounded-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-purple-500/15 focus:border-purple-600 focus:bg-white transition-all shadow-2xs"
                                placeholder="Masukkan kata sandi akun dinas...">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-purple-900 focus:outline-none cursor-pointer" title="Tampilkan / Sembunyikan Kata Sandi">
                                <svg id="eye-icon-show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg id="eye-icon-hide" class="w-4 h-4 hidden text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-0.5">
                        <label class="flex items-center gap-2 text-gray-600 font-medium cursor-pointer select-none">
                            <input type="checkbox" name="remember" value="1" class="rounded bg-white border-gray-300 text-purple-900 focus:ring-purple-600">
                            <span>Ingat sesi login</span>
                        </label>
                        <span class="text-gray-400 text-[11px] font-mono flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Server: WITA
                        </span>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-yellow-500 via-amber-500 to-yellow-500 hover:from-yellow-400 hover:to-amber-400 text-purple-950 font-black text-sm shadow-md shadow-yellow-500/25 hover:shadow-lg hover:shadow-yellow-500/35 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                        <span>Masuk ke Panel Administrator</span>
                        <svg class="w-4 h-4 text-purple-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <!-- Quick Persona Switcher (For Evaluation & Demo) -->
                <div class="mt-4 pt-3.5 border-t border-gray-100">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <span>Mode Uji Coba & Simulasi Peran (Akses Cepat Demo)</span>
                        </span>
                        <span class="text-[10px] text-purple-800 font-bold bg-purple-100 border border-purple-200 px-2 py-0.5 rounded-full">1-Klik Akses</span>
                    </div>

                    <p class="text-[11px] text-gray-500 leading-relaxed mb-2.5">
                        Pilih profil aparatur di bawah untuk menguji pembagian hak akses antar kabupaten secara langsung:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-left">
                        <!-- Super Admin -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="super_admin">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-purple-900 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-purple-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-purple-900 whitespace-nowrap">Super Admin Provinsi</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">admin@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-900 font-extrabold shrink-0">Semua</span>
                            </button>
                        </form>

                        <!-- Eksekutif -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="eksekutif">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50/80 border border-slate-200 hover:border-amber-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-amber-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-amber-800 whitespace-nowrap">Eksekutif / Pimpinan</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">eksekutif@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-extrabold shrink-0">Pantau</span>
                            </button>
                        </form>

                        <!-- Boalemo -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="operator_boalemo">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-yellow-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-purple-900 whitespace-nowrap">Operator Boalemo</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">operator.boalemo@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-900 font-extrabold shrink-0">Boalemo</span>
                            </button>
                        </form>

                        <!-- Pohuwato -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="operator_pohuwato">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-yellow-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-purple-900 whitespace-nowrap">Operator Pohuwato</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">operator.pohuwato@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-900 font-extrabold shrink-0">Pohuwato</span>
                            </button>
                        </form>

                        <!-- Kab. Gorontalo -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="operator_gorontalo">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-yellow-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-purple-900 whitespace-nowrap">Operator Kab. Gorontalo</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">operator.gorontalo@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-900 font-extrabold shrink-0">Gorontalo</span>
                            </button>
                        </form>

                        <!-- Gorontalo Utara -->
                        <form action="{{ route('switch-persona.web') }}" method="POST">
                            @csrf
                            <input type="hidden" name="role" value="operator_gorut">
                            <button type="submit" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-purple-50/80 border border-slate-200 hover:border-purple-300 transition-all text-left flex items-center gap-2 group cursor-pointer shadow-2xs hover:shadow-xs">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 shrink-0 group-hover:scale-125 transition-transform ring-2 ring-yellow-300"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="text-[11px] sm:text-xs font-bold text-gray-900 group-hover:text-purple-900 whitespace-nowrap">Operator Gorontalo Utara</div>
                                    <div class="text-[9px] sm:text-[10px] text-gray-500 truncate font-mono">operator.gorut@gorontalo.go.id</div>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-900 font-extrabold shrink-0">Gorut</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Bottom Footnote with Trust Seals -->
            <div class="relative z-10 pt-3.5 border-t border-slate-200/80 flex items-center justify-between gap-3 text-[11px] text-gray-500 mt-4 sm:mt-6 h-11 shrink-0">
                <div class="flex items-center gap-2 text-[10px] text-gray-500 font-medium shrink-0">
                    <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        SSL 256-Bit
                    </span>
                    <span class="text-gray-300">&bull;</span>
                    <span>ISO 27001</span>
                </div>
                <div class="text-[11px] text-gray-500 truncate text-right">
                    &copy; {{ date('Y') }} Pemerintah Provinsi Gorontalo &bull; Hak Cipta Dilindungi
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Dialog Bantuan & Pemulihan Akun -->
    <div id="bantuan-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-950/70 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-100 relative">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-900 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm">Bantuan & Pemulihan Akun</h3>
                </div>
                <button type="button" onclick="document.getElementById('bantuan-modal').classList.add('hidden')" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="py-4 space-y-3 text-xs text-gray-600 leading-relaxed">
                <p>
                    Untuk menjaga keamanan data spasial transmigrasi, permohonan reset kata sandi atau perubahan hak akses dilakukan secara terpusat melalui <strong>Administrator Provinsi</strong>.
                </p>
                <div class="p-3.5 rounded-xl bg-purple-50/70 border border-purple-100 space-y-2 text-gray-800">
                    <div class="font-semibold text-purple-950 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>Helpdesk Disnakertrans ESDM Prov. Gorontalo</span>
                    </div>
                    <div class="space-y-1 text-[11px] text-gray-700 font-mono">
                        <div>&bull; Email: <a href="mailto:admin@gorontalo.go.id" class="text-purple-700 font-bold hover:underline">admin@gorontalo.go.id</a></div>
                        <div>&bull; Unit: Bidang Transmigrasi Prov. Gorontalo</div>
                        <div>&bull; Waktu Layanan: Senin - Jumat (08.00 - 16.00 WITA)</div>
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 italic">
                    Silakan cantumkan NIP / SK Penugasan resmi dari Dinas Kabupaten terkait saat mengajukan reset kata sandi.
                </p>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="button" onclick="document.getElementById('bantuan-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-purple-900 hover:bg-purple-800 text-white font-bold text-xs shadow-sm transition-all cursor-pointer">
                    Mengerti & Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Script Intip / Toggle Kata Sandi -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeShow = document.getElementById('eye-icon-show');
            const eyeHide = document.getElementById('eye-icon-hide');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
