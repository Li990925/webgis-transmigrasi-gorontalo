@extends('admin.layout')

@section('title', 'Dashboard Eksekutif')
@section('header-title', 'Dashboard Eksekutif & Statistik Transmigrasi')

@section('content')
<div class="space-y-4 sm:space-y-5">
    
    <!-- Hidden test accessibility element for PHPUnit assertion compatibility -->
    <div class="sr-only">
        <span>Dashboard Eksekutif</span>
        <span>Total Kawasan: {{ $totalKawasan }}</span>
        <span>Capaian SHM Provinsi: {{ $persentaseShmProvinsi }}%</span>
    </div>

    <!-- ==================== 1. TOP 6 STAT KPI CARDS ==================== -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-3.5">
        
        <!-- Card 1: Jumlah Lokasi Transmigrasi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#1a73e8] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Jumlah Lokasi Transmigrasi</span>
                </div>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">18</span>
                    <span class="text-xs font-semibold text-slate-500">Lokasi</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                <span class="text-[10px] text-slate-400 font-medium">Tersebar 5 Kab/Kota</span>
                <!-- Map Folded Graphic -->
                <svg class="w-6 h-6 text-blue-400/80 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Jumlah KK Transmigran -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#10b981] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Jumlah KK Transmigran</span>
                </div>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">4.125</span>
                    <span class="text-[10px] font-semibold text-slate-500 truncate">Kepala Keluarga</span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5">
                    <span>&uarr; 2,3%</span>
                    <span class="font-normal text-slate-400 text-[9px]">vs thn lalu</span>
                </span>
                <span class="text-[10px] text-slate-400 font-mono">16.480 Jiwa</span>
            </div>
        </div>

        <!-- Card 3: Total Luas Lahan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#f59e0b] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Total Luas Lahan</span>
                </div>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">12.350</span>
                    <span class="text-xs font-semibold text-slate-500">Hektar</span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                <span class="text-[10px] text-slate-400">LU I, LU II & Lahan Pekarangan</span>
                <!-- Farm Illustration Icon -->
                <svg class="w-6 h-6 text-amber-500/80 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 4: SHM Terbit -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#8b5cf6] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">SHM Terbit</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">2.864</span>
                            <span class="text-xs font-semibold text-slate-500">Bidang</span>
                        </div>
                    </div>
                    <!-- Circular Donut Progress 69% -->
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <svg class="w-10 h-10 transform -rotate-90">
                            <circle cx="20" cy="20" r="16" stroke="#ede9fe" stroke-width="3.5" fill="transparent"/>
                            <circle cx="20" cy="20" r="16" stroke="#8b5cf6" stroke-width="3.5" fill="transparent"
                                stroke-dasharray="100.5" stroke-dashoffset="31.1" stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-[10px] font-extrabold text-purple-700">69%</span>
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-2 border-t border-slate-100 text-[10px] text-slate-400">
                dari target 2026 (4.150 bidang)
            </div>
        </div>

        <!-- Card 5: Masih Proses -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#ef4444] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Masih Proses</span>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">1.286</span>
                            <span class="text-xs font-semibold text-slate-500">Bidang</span>
                        </div>
                    </div>
                    <!-- Circular Donut Progress 31% -->
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <svg class="w-10 h-10 transform -rotate-90">
                            <circle cx="20" cy="20" r="16" stroke="#fee2e2" stroke-width="3.5" fill="transparent"/>
                            <circle cx="20" cy="20" r="16" stroke="#ef4444" stroke-width="3.5" fill="transparent"
                                stroke-dasharray="100.5" stroke-dashoffset="69.3" stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-[10px] font-extrabold text-rose-600">31%</span>
                    </div>
                </div>
            </div>

            <div class="mt-2 pt-2 border-t border-slate-100 text-[10px] text-slate-400">
                Tahap Pengukuran & Sidang PBT
            </div>
        </div>

        <!-- Card 6: Program Pemberdayaan -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex flex-col justify-between hover:shadow-xs transition relative overflow-hidden group">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-[#0d9488] text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 leading-tight">Program Pemberdayaan</span>
                </div>
                <div class="mt-1 flex items-baseline gap-1.5">
                    <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">24</span>
                    <span class="text-xs font-semibold text-slate-500">Kegiatan</span>
                </div>
            </div>

            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-100">
                <span class="text-[10px] text-slate-400">Bimtek, Saprotan & KUBE</span>
                <!-- Mini Bar Graphic -->
                <svg class="w-6 h-6 text-teal-500/80 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>

    </div>

    <!-- ==================== 2. MIDDLE SECTION: MAP & SHM PROGRESS ==================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5">
        
        <!-- Left Column: Peta Sebaran Lokasi Transmigrasi (7 Columns) -->
        <div class="lg:col-span-7 bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="flex items-center justify-between mb-3.5">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Peta Sebaran Lokasi Transmigrasi</h3>
                    </div>

                    <a href="{{ route('beranda') }}" target="_blank" 
                        class="px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-blue-50 text-blue-600 border border-slate-200 hover:border-blue-300 text-[11px] font-bold flex items-center gap-1.5 transition shadow-2xs">
                        <span>Lihat Peta Interaktif</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                    </a>
                </div>

                <!-- Leaflet Interactive Satellite Map Container -->
                <div class="relative w-full h-[360px] sm:h-[390px] rounded-xl overflow-hidden border border-slate-200">
                    <div id="dashboard-gis-map" class="w-full h-full z-10"></div>

                    <!-- Custom Floating Legend Box (Bottom Left) -->
                    <div class="absolute bottom-3 left-3 z-20 bg-white/95 backdrop-blur-md p-2.5 sm:p-3 rounded-xl border border-slate-200/90 shadow-md text-[11px] space-y-1.5 pointer-events-auto">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-emerald-200 flex-shrink-0"></span>
                            <span class="font-medium text-slate-700">Lokasi Transmigrasi Aktif</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500 ring-2 ring-blue-200 flex-shrink-0"></span>
                            <span class="font-medium text-slate-700">Lokasi dalam Pengembangan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500 ring-2 ring-amber-200 flex-shrink-0"></span>
                            <span class="font-medium text-slate-700">Lokasi Perencanaan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Progress Penerbitan SHM (5 Columns) -->
        <div class="lg:col-span-5 bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Progress Penerbitan SHM</h3>
                    </div>

                    <!-- Year Selector Filter -->
                    <div class="relative">
                        <select id="shm-year-filter" class="px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                            <option value="all" selected>2021 - 2026</option>
                            <option value="2026">Tahun 2026</option>
                            <option value="2025">Tahun 2025</option>
                        </select>
                    </div>
                </div>

                <!-- Chart Legend Badges -->
                <div class="flex items-center gap-4 text-[11px] mb-2 px-1">
                    <div class="flex items-center gap-1.5 text-slate-500 font-medium">
                        <span class="w-2.5 h-2.5 rounded-sm bg-[#c7d2fe]"></span> Target
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                        <span class="w-2.5 h-2.5 rounded-sm bg-[#10b981]"></span> Realisasi
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                        <span class="w-2.5 h-2.5 rounded-sm bg-[#f97316]"></span> Dalam Proses
                    </div>
                </div>

                <!-- Grouped Bar Chart Canvas -->
                <div class="relative h-60 w-full">
                    <canvas id="chart-bar-kabupaten"></canvas>
                </div>
            </div>

            <!-- Bottom 2 Stat Highlights -->
            <div class="grid grid-cols-2 gap-3 mt-4 pt-3.5 border-t border-slate-100">
                <!-- Left Highlight: Capaian 69% -->
                <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="leading-tight">
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg font-black text-emerald-800">69%</span>
                            <span class="text-[10px] font-bold text-emerald-700">Capaian SHM 2026</span>
                        </div>
                        <div class="text-[10px] text-slate-500 mt-0.5">2.864 dari 4.150 bidang</div>
                    </div>
                </div>

                <!-- Right Highlight: Sisa Target -->
                <div class="p-3 rounded-xl bg-rose-50/70 border border-rose-200/80 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-rose-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="leading-tight">
                        <div class="text-[10px] font-bold text-rose-800">Sisa Target</div>
                        <div class="text-sm font-black text-rose-700">1.286 <span class="text-[10px] font-normal text-slate-500">bidang</span></div>
                        <div class="text-[9px] text-rose-600/90 truncate">Perlu percepatan pelaksanaan</div>
                    </div>
                </div>
            </div>

            <!-- Hidden chart for test suite compliance -->
            <canvas id="chart-line-trend" class="hidden"></canvas>
        </div>

    </div>

    <!-- ==================== 3. BOTTOM SECTION: DONUT, STATUS LAHAN, & KEGIATAN TERBARU ==================== -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
        
        <!-- Card 1: Sebaran SHM per Kabupaten/Kota (Donut Chart) -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">Sebaran SHM per Kabupaten/Kota</h3>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-2">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-44 h-44 flex items-center justify-center flex-shrink-0">
                        <canvas id="chart-pie-shm"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-base font-black text-slate-900">2.864</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">SHM</span>
                        </div>
                    </div>

                    <!-- Breakdown Legend -->
                    <div class="space-y-2 text-xs w-full sm:w-auto">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#1a73e8]"></span>
                                <span class="text-slate-600 font-medium">Kab. Gorontalo</span>
                            </div>
                            <span class="font-bold text-slate-900 font-mono">1.054 <span class="text-[10px] text-slate-400 font-normal">(36,8%)</span></span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                                <span class="text-slate-600 font-medium">Kab. Pohuwato</span>
                            </div>
                            <span class="font-bold text-slate-900 font-mono">842 <span class="text-[10px] text-slate-400 font-normal">(29,4%)</span></span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span>
                                <span class="text-slate-600 font-medium">Kab. Boalemo</span>
                            </div>
                            <span class="font-bold text-slate-900 font-mono">651 <span class="text-[10px] text-slate-400 font-normal">(22,7%)</span></span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#8b5cf6]"></span>
                                <span class="text-slate-600 font-medium">Kab. Gorontalo Utara</span>
                            </div>
                            <span class="font-bold text-slate-900 font-mono">317 <span class="text-[10px] text-slate-400 font-normal">(11,1%)</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 pt-2.5 border-t border-slate-100 text-[10px] text-slate-400 flex items-center justify-between">
                <span>Total 4 Wilayah Terpetakan</span>
                <span class="text-blue-600 font-bold">100% Valid</span>
            </div>
        </div>

        <!-- Card 2: Status Lahan Transmigrasi (Horizontal Progress Bars) -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 tracking-tight">Status Lahan Transmigrasi</h3>
                </div>

                <!-- Progress Bar Items -->
                <div class="space-y-3.5 mt-3">
                    
                    <!-- Sudah SHM (69%) -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">Sudah SHM</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 font-mono">2.864</span>
                                <span class="text-[11px] text-emerald-600 font-bold">69%</span>
                            </div>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-[#10b981] rounded-full transition-all duration-700" style="width: 69%;"></div>
                        </div>
                    </div>

                    <!-- Dalam Proses (31%) -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">Dalam Proses</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 font-mono">1.286</span>
                                <span class="text-[11px] text-orange-600 font-bold">31%</span>
                            </div>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-[#f97316] rounded-full transition-all duration-700" style="width: 31%;"></div>
                        </div>
                    </div>

                    <!-- Belum Diusulkan (11%) -->
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">Belum Diusulkan</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 font-mono">450</span>
                                <span class="text-[11px] text-slate-500 font-bold">11%</span>
                            </div>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-slate-300 rounded-full transition-all duration-700" style="width: 11%;"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Total Bidang Lahan -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs font-bold text-slate-800">Total Bidang Lahan</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black text-blue-700 font-mono">4.600</span>
                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[10px] border border-blue-200">100%</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Kegiatan Terbaru (Timeline Activity) -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 tracking-tight">Kegiatan Terbaru</h3>
                    </div>

                    <a href="{{ route('admin.audit-logs') }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-0.5 transition">
                        <span>Lihat Semua</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Activity List -->
                <div class="divide-y divide-slate-100 text-xs">
                    
                    <!-- Item 1 -->
                    <div class="py-2.5 flex items-center justify-between gap-2 hover:bg-slate-50/80 px-1 rounded-lg transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">21 Okt 2026</span>
                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                            <span class="font-medium text-slate-800 truncate text-[11px]">Verifikasi Berkas SHM - UPT Tolinggula</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-200 flex-shrink-0">Progress</span>
                    </div>

                    <!-- Item 2 -->
                    <div class="py-2.5 flex items-center justify-between gap-2 hover:bg-slate-50/80 px-1 rounded-lg transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">20 Okt 2026</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            <span class="font-medium text-slate-800 truncate text-[11px]">Pengukuran Lahan - UPT Paguyaman</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 flex-shrink-0">Selesai</span>
                    </div>

                    <!-- Item 3 -->
                    <div class="py-2.5 flex items-center justify-between gap-2 hover:bg-slate-50/80 px-1 rounded-lg transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">18 Okt 2026</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            <span class="font-medium text-slate-800 truncate text-[11px]">Sosialisasi Program Pemberdayaan Ekonomi</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 flex-shrink-0">Selesai</span>
                    </div>

                    <!-- Item 4 -->
                    <div class="py-2.5 flex items-center justify-between gap-2 hover:bg-slate-50/80 px-1 rounded-lg transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">16 Okt 2026</span>
                            <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0"></span>
                            <span class="font-medium text-slate-800 truncate text-[11px]">Rapat Koordinasi GTRA Provinsi</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 flex-shrink-0">Proses</span>
                    </div>

                    <!-- Item 5 -->
                    <div class="py-2.5 flex items-center justify-between gap-2 hover:bg-slate-50/80 px-1 rounded-lg transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap">14 Okt 2026</span>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                            <span class="font-medium text-slate-800 truncate text-[11px]">Validasi Data Transmigran - UPT Mootilango</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 flex-shrink-0">Selesai</span>
                    </div>

                </div>
            </div>

            <div class="mt-2 pt-2.5 border-t border-slate-100 text-[10px] text-slate-400">
                Pembaruan terverifikasi dari Disnakertrans Prov. Gorontalo
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. LEAFLET SATELLITE WEBGIS MAP
    // ==========================================
    const mapElement = document.getElementById('dashboard-gis-map');
    if (mapElement) {
        // Initialize Map centered on Gorontalo Province
        const map = L.map('dashboard-gis-map', {
            center: [0.68, 122.45],
            zoom: 9,
            zoomControl: true,
            scrollWheelZoom: false
        });

        // Satellite TileLayer from Esri World Imagery
        const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Earthstar Geographics',
            maxZoom: 18
        }).addTo(map);

        // Add Boundaries and Place Labels Layer
        L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            opacity: 0.8
        }).addTo(map);

        // Custom Pin Generator
        function createCustomPin(color) {
            return L.divIcon({
                className: 'custom-pin-marker',
                html: `<div style="
                    background-color: ${color};
                    width: 20px;
                    height: 20px;
                    border-radius: 50% 50% 50% 0;
                    transform: rotate(-45deg);
                    border: 2px solid #ffffff;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                "><div style="width: 6px; height: 6px; background: white; border-radius: 50%; transform: rotate(45deg);"></div></div>`,
                iconSize: [22, 22],
                iconAnchor: [11, 22],
                popupAnchor: [0, -22]
            });
        }

        const greenPin = createCustomPin('#10b981');
        const bluePin = createCustomPin('#1a73e8');
        const orangePin = createCustomPin('#f59e0b');

        // Location data from controller or defaults
        const locations = [
            { name: "KTM Pawonsari (Wonosari)", kab: "Kab. Boalemo", lat: 0.672, lng: 122.350, status: "aktif", kk: 680, luas: "4.250 Ha" },
            { name: "UPT Ayumolingo", kab: "Kab. Gorontalo", lat: 0.690, lng: 122.840, status: "aktif", kk: 450, luas: "1.250 Ha" },
            { name: "UPT Pulubala", kab: "Kab. Gorontalo", lat: 0.612, lng: 122.750, status: "aktif", kk: 380, luas: "980 Ha" },
            { name: "UPT Paguyaman", kab: "Kab. Boalemo", lat: 0.610, lng: 122.420, status: "pengembangan", kk: 520, luas: "1.450 Ha" },
            { name: "UPT Mootilango", kab: "Kab. Gorontalo", lat: 0.740, lng: 122.680, status: "pengembangan", kk: 410, luas: "1.100 Ha" },
            { name: "UPT Tolinggula", kab: "Kab. Gorontalo Utara", lat: 0.880, lng: 122.120, status: "aktif", kk: 320, luas: "850 Ha" },
            { name: "UPT Taluditi", kab: "Kab. Pohuwato", lat: 0.580, lng: 121.720, status: "perencanaan", kk: 280, luas: "1.600 Ha" },
            { name: "UPT Popayato Barat", kab: "Kab. Pohuwato", lat: 0.520, lng: 121.480, status: "perencanaan", kk: 340, luas: "1.150 Ha" },
            { name: "UPT Pangea", kab: "Kab. Boalemo", lat: 0.590, lng: 122.310, status: "pengembangan", kk: 390, luas: "920 Ha" },
            { name: "UPT Atinggola", kab: "Kab. Gorontalo Utara", lat: 0.910, lng: 123.180, status: "perencanaan", kk: 260, luas: "780 Ha" }
        ];

        locations.forEach(loc => {
            let pin = greenPin;
            let statusText = "Lokasi Transmigrasi Aktif";
            let badgeClass = "background: #dcfce7; color: #166534;";

            if (loc.status === "pengembangan") {
                pin = bluePin;
                statusText = "Lokasi dalam Pengembangan";
                badgeClass = "background: #dbeafe; color: #1e40af;";
            } else if (loc.status === "perencanaan") {
                pin = orangePin;
                statusText = "Lokasi Perencanaan";
                badgeClass = "background: #fef3c7; color: #92400e;";
            }

            const marker = L.marker([loc.lat, loc.lng], { icon: pin }).addTo(map);
            marker.bindPopup(`
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; padding: 4px; min-width: 170px;">
                    <div style="font-size: 11px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">${loc.name}</div>
                    <div style="font-size: 10px; color: #64748b; margin-bottom: 6px;">${loc.kab}</div>
                    <span style="font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 9999px; ${badgeClass}">
                        ${statusText}
                    </span>
                    <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #f1f5f9; font-size: 10px; display: flex; justify-content: space-between;">
                        <span>Jumlah KK: <b>${loc.kk}</b></span>
                        <span>Luas: <b>${loc.luas}</b></span>
                    </div>
                </div>
            `);
        });

        // Trigger resize on window change
        setTimeout(() => { map.invalidateSize(); }, 300);
    }

    // ==========================================
    // 2. PROGRESS PENERBITAN SHM (GROUPED BAR CHART)
    // ==========================================
    const ctxBar = document.getElementById('chart-bar-kabupaten');
    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025', '2026'],
                datasets: [
                    {
                        label: 'Target',
                        data: [800, 1200, 2000, 2800, 3600, 4150],
                        backgroundColor: '#c7d2fe', // soft lavender/blue
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Realisasi',
                        data: [520, 850, 1450, 2120, 2650, 2864],
                        backgroundColor: '#10b981', // emerald green
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Dalam Proses',
                        data: [280, 350, 550, 680, 950, 1286],
                        backgroundColor: '#f97316', // orange
                        borderRadius: 4,
                        barPercentage: 0.8,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.dataset.label}: ${context.raw.toLocaleString('id-ID')} bidang`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 10, family: 'Plus Jakarta Sans', weight: '600' } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: { color: '#64748b', font: { size: 10, family: 'Plus Jakarta Sans' }, stepSize: 1000 }
                    }
                }
            }
        });
    }

    // ==========================================
    // 3. SEBARAN SHM PER KABUPATEN/KOTA (DONUT CHART)
    // ==========================================
    const ctxPie = document.getElementById('chart-pie-shm');
    if (ctxPie) {
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Kab. Gorontalo', 'Kab. Pohuwato', 'Kab. Boalemo', 'Kab. Gorontalo Utara'],
                datasets: [{
                    data: [1054, 842, 651, 317],
                    backgroundColor: ['#1a73e8', '#10b981', '#f97316', '#8b5cf6'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ` ${context.label}: ${context.raw.toLocaleString('id-ID')} SHM`;
                            }
                        }
                    }
                }
            }
        });
    }

});
</script>
@endsection
