@extends('admin.layout')

@section('title', 'Klasterisasi & Prioritas ATR/BPN')
@section('header-title', 'Matriks Prioritas Intervensi Sertifikasi ATR/BPN')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-900 border border-blue-200 text-xs font-bold mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                    Metode Resmi: Simple Additive Weighting (SAW)
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">Matriks Prioritas Intervensi Sertifikasi Hak Milik</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Klasifikasi prioritas penanganan legalitas tanah transmigrasi berbasis pembobotan multi-kriteria untuk rekomendasi resmi ke Kementerian ATR/BPN.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('beranda') }}" target="_blank" class="px-3.5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#081e36]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span>Peta Spasial GIS</span>
                </a>
                <a href="{{ route('laporan.cetak') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#081e36] hover:bg-[#0d2a4a] text-white text-xs font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Cetak Laporan Resmi</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Methodology Transparency Card -->
    <div class="rounded-xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 bg-gradient-to-r from-[#07192d] via-[#081e36] to-[#0d2a4a] text-white">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-yellow-400/20 text-yellow-300 flex items-center justify-center font-black text-sm">
                        SAW
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-white">Metodologi Simple Additive Weighting (SAW) / Pembobotan Kriteria</h2>
                        <p class="text-[11px] text-slate-300">Standar pengambilan keputusan transparan, adil, dan dapat diaudit secara manual oleh aparatur dinas.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-yellow-400/20 text-yellow-300 text-[11px] font-semibold border border-yellow-400/30">
                    Transparansi 100%
                </span>
            </div>
        </div>

        <div class="p-5 sm:p-6 space-y-5">
            <p class="text-xs text-gray-600 leading-relaxed">
                Metode <strong>Simple Additive Weighting (SAW)</strong> mengevaluasi setiap kawasan transmigrasi secara terukur berdasarkan 3 kriteria berbobot. Skor urgensi akhir berada pada rentang <strong>0 hingga 100 poin</strong>, di mana semakin tinggi skor menunjukkan semakin mendesaknya kawasan tersebut untuk mendapatkan percepatan program sertifikasi (seperti PTSL Terpadu, GTRA, atau penuntasan HPL) dari Kementerian ATR/BPN.
            </p>

            <!-- 3 Criteria Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kriteria 1 -->
                <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-900 font-mono">KRITERIA 1 (C1)</span>
                            <span class="text-sm font-extrabold text-blue-900 font-mono">Bobot 40%</span>
                        </div>
                        <h4 class="text-xs font-bold text-gray-900 mt-2">Kesenjangan Hak Milik (% Non-SHM)</h4>
                        <p class="text-[11px] text-gray-600 mt-1">
                            Persentase kepala keluarga yang belum mengantongi SHM terhadap total KK penempatan kawasan.
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-blue-200/60 text-[10px] text-blue-800 font-mono">
                        Formula: ( % Non-SHM / 100 ) × 40
                    </div>
                </div>

                <!-- Kriteria 2 -->
                <div class="p-4 rounded-xl bg-indigo-50/70 border border-indigo-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-200 text-indigo-900 font-mono">KRITERIA 2 (C2)</span>
                            <span class="text-sm font-extrabold text-indigo-900 font-mono">Bobot 35%</span>
                        </div>
                        <h4 class="text-xs font-bold text-gray-900 mt-2">Volume KK Terdampak (KK Non-SHM)</h4>
                        <p class="text-[11px] text-gray-600 mt-1">
                            Jumlah absolut kepala keluarga riil yang membutuhkan kepastian hukum atas tanah pemukimannya.
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-indigo-200/60 text-[10px] text-indigo-800 font-mono">
                        Formula: ( KK Non-SHM / Max KK ) × 35
                    </div>
                </div>

                <!-- Kriteria 3 -->
                <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-900 font-mono">KRITERIA 3 (C3)</span>
                            <span class="text-sm font-extrabold text-amber-900 font-mono">Bobot 25%</span>
                        </div>
                        <h4 class="text-xs font-bold text-gray-900 mt-2">Beban Luas Lahan (Ha Belum SHM)</h4>
                        <p class="text-[11px] text-gray-600 mt-1">
                            Estimasi luasan fisik lahan usaha dan pekarangan yang membutuhkan pengukuran spasial oleh Kantah ATR/BPN.
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-amber-200/60 text-[10px] text-amber-800 font-mono">
                        Formula: ( Luas Non-SHM / Max Luas ) × 25
                    </div>
                </div>
            </div>

            <!-- Formula Summary & Classification Rules -->
            <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-700">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900">Rumus Komposit:</span>
                    <span class="font-mono text-[11px] bg-white px-2 py-1 rounded border border-gray-300 text-slate-900 font-semibold">
                        Skor Urgensi = Skor(C1 40%) + Skor(C2 35%) + Skor(C3 25%)
                    </span>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <span class="inline-flex items-center gap-1 font-semibold text-rose-700"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Prioritas I: Skor &ge; 55 atau Non-SHM &ge; 50%</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-700"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Prioritas II: Skor 30 – 54.99</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Prioritas III: Skor &lt; 30</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Priority Tier Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Prioritas I -->
        <div class="p-5 rounded-xl bg-white border border-rose-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                        PRIORITAS I
                    </span>
                    <span class="text-2xl font-extrabold text-rose-600 font-mono">
                        {{ count($clustering['prioritas_1'] ?? []) }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mt-3">Mendesak / Intervensi Khusus</h3>
                <p class="text-xs text-gray-600 mt-1">Capaian sertifikasi &lt; 50% atau skor urgensi komposit &ge; 55 poin.</p>

                <div class="mt-4 pt-3 border-t border-gray-100 space-y-2">
                    <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Arah Kebijakan ATR/BPN:</div>
                    <ul class="text-xs text-gray-700 space-y-1.5 list-disc list-inside">
                        <li>Gugus Tugas Reforma Agraria (GTRA) percepatan.</li>
                        <li>Penyelesaian tumpang tindih kawasan hutan / HGU.</li>
                        <li>Alokasi kuota PTSL Terpadu khusus transmigrasi.</li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 text-[11px] text-gray-500 font-medium">Intervensi mendesak tahun anggaran berjalan.</div>
        </div>

        <!-- Prioritas II -->
        <div class="p-5 rounded-xl bg-white border border-amber-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                        PRIORITAS II
                    </span>
                    <span class="text-2xl font-extrabold text-amber-600 font-mono">
                        {{ count($clustering['prioritas_2'] ?? []) }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mt-3">Penanganan Menengah / Terjadwal</h3>
                <p class="text-xs text-gray-600 mt-1">Skor urgensi komposit berada pada rentang 30.00 – 54.99 poin.</p>

                <div class="mt-4 pt-3 border-t border-gray-100 space-y-2">
                    <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Arah Kebijakan ATR/BPN:</div>
                    <ul class="text-xs text-gray-700 space-y-1.5 list-disc list-inside">
                        <li>Fokus sertifikasi Lahan Usaha II (LU-II).</li>
                        <li>Verifikasi batas patok bidang tanah fisik.</li>
                        <li>Target tuntas SHM 100% dalam 2 tahun.</li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 text-[11px] text-gray-500 font-medium">Fase lanjutan penuntasan legalitas.</div>
        </div>

        <!-- Prioritas III -->
        <div class="p-5 rounded-xl bg-white border border-emerald-200 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        PRIORITAS III
                    </span>
                    <span class="text-2xl font-extrabold text-emerald-600 font-mono">
                        {{ count($clustering['prioritas_3'] ?? []) }}
                    </span>
                </div>
                <h3 class="text-base font-bold text-gray-900 mt-3">Pemeliharaan & Monitoring</h3>
                <p class="text-xs text-gray-600 mt-1">Capaian sertifikasi tinggi dengan skor urgensi komposit &lt; 30 poin.</p>

                <div class="mt-4 pt-3 border-t border-gray-100 space-y-2">
                    <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Arah Kebijakan ATR/BPN:</div>
                    <ul class="text-xs text-gray-700 space-y-1.5 list-disc list-inside">
                        <li>Penerbitan sisa sertifikat silsilah waris.</li>
                        <li>Pemberdayaan tanah masyarakat (Access Reform).</li>
                        <li>Integrasi sertifikat tanah dengan perbankan usaha.</li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 text-[11px] text-gray-500 font-medium">Penguatan ekonomi dan permodalan tani.</div>
        </div>

    </div>

    <!-- Detailed Settlement Table per Cluster -->
    <div class="space-y-6">
        
        <!-- Table Prioritas 1 -->
        <div class="bg-white border border-rose-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 bg-rose-50 border-b border-rose-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <h3 class="text-sm font-bold text-rose-950">Daftar Kawasan Prioritas I (Mendesak / Intervensi Khusus)</h3>
                </div>
                <span class="text-xs font-mono font-bold text-rose-800">{{ count($clustering['prioritas_1'] ?? []) }} Kawasan Teridentifikasi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-slate-100 border-b-2 border-slate-300 text-[11px] uppercase font-bold text-slate-800 tracking-wider divide-x divide-slate-200">
                        <tr>
                            <th class="py-3 px-3.5 w-10 text-center">No</th>
                            <th class="py-3 px-3.5">Nama Kawasan & Lokasi</th>
                            <th class="py-3 px-3.5">Kabupaten</th>
                            <th class="py-3 px-3.5 text-center">Total KK</th>
                            <th class="py-3 px-3.5 text-center">Belum SHM (KK / %)</th>
                            <th class="py-3 px-3.5 text-center">Luas Belum SHM</th>
                            <th class="py-3 px-3.5 text-center">Skor SAW</th>
                            <th class="py-3 px-3.5">Rekomendasi Tindakan Spasial</th>
                            <th class="py-3 px-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 divide-x divide-slate-200">
                        @forelse ($clustering['prioritas_1'] ?? [] as $index => $k)
                            <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-rose-50/40 transition-colors">
                                <td class="py-3 px-3.5 text-center font-mono text-gray-500 font-semibold">{{ $index + 1 }}</td>
                                <td class="py-3 px-3.5">
                                    <div class="font-bold text-gray-900">{{ $k['nama'] }}</div>
                                    <div class="text-[11px] text-gray-500">Desa {{ $k['desa'] }}, Kec. {{ $k['kecamatan'] }}</div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-700 font-medium">{{ $k['kabupaten'] }}</td>
                                <td class="py-3 px-3.5 text-center font-mono font-semibold text-gray-800">{{ number_format($k['jumlah_kk']) }} KK</td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="font-mono font-bold text-rose-600">{{ number_format($k['kk_non_shm']) }} KK</span>
                                    <span class="text-[11px] text-gray-500 block">({{ $k['persentase_non_shm'] }}% belum)</span>
                                </td>
                                <td class="py-3 px-3.5 text-center font-mono text-gray-700">
                                    {{ number_format($k['luas_non_shm_ha'], 1) }} Ha
                                </td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono">
                                        {{ $k['skor_urgensi'] }}
                                    </span>
                                    <div class="text-[9px] text-gray-500 font-mono mt-0.5" title="Komponen C1(40%) + C2(35%) + C3(25%)">
                                        {{ $k['score_persen'] ?? '-' }}|{{ $k['score_kk'] ?? '-' }}|{{ $k['score_luas'] ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-600 text-[11px] leading-relaxed max-w-xs">{{ $k['rekomendasi'] ?? 'Akselerasi PTSL Terpadu khusus transmigrasi dan tapal batas HPL.' }}</td>
                                <td class="py-3 px-3.5 text-center">
                                    <a href="{{ route('admin.kawasan.spasial', $k['id']) }}" class="px-2.5 py-1 rounded bg-blue-50 hover:bg-blue-100 text-blue-900 font-bold text-[11px] border border-blue-200 transition-colors inline-flex items-center gap-1">
                                        <span>Peta</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-500 text-xs">Tidak ada kawasan pada kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Prioritas 2 -->
        <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 bg-amber-50 border-b border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h3 class="text-sm font-bold text-amber-950">Daftar Kawasan Prioritas II (Penanganan Menengah / Terjadwal)</h3>
                </div>
                <span class="text-xs font-mono font-bold text-amber-800">{{ count($clustering['prioritas_2'] ?? []) }} Kawasan Teridentifikasi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-slate-100 border-b-2 border-slate-300 text-[11px] uppercase font-bold text-slate-800 tracking-wider divide-x divide-slate-200">
                        <tr>
                            <th class="py-3 px-3.5 w-10 text-center">No</th>
                            <th class="py-3 px-3.5">Nama Kawasan & Lokasi</th>
                            <th class="py-3 px-3.5">Kabupaten</th>
                            <th class="py-3 px-3.5 text-center">Total KK</th>
                            <th class="py-3 px-3.5 text-center">Belum SHM (KK / %)</th>
                            <th class="py-3 px-3.5 text-center">Luas Belum SHM</th>
                            <th class="py-3 px-3.5 text-center">Skor SAW</th>
                            <th class="py-3 px-3.5">Rekomendasi Tindakan Spasial</th>
                            <th class="py-3 px-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 divide-x divide-slate-200">
                        @forelse ($clustering['prioritas_2'] ?? [] as $index => $k)
                            <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-amber-50/40 transition-colors">
                                <td class="py-3 px-3.5 text-center font-mono text-gray-500 font-semibold">{{ $index + 1 }}</td>
                                <td class="py-3 px-3.5">
                                    <div class="font-bold text-gray-900">{{ $k['nama'] }}</div>
                                    <div class="text-[11px] text-gray-500">Desa {{ $k['desa'] }}, Kec. {{ $k['kecamatan'] }}</div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-700 font-medium">{{ $k['kabupaten'] }}</td>
                                <td class="py-3 px-3.5 text-center font-mono font-semibold text-gray-800">{{ number_format($k['jumlah_kk']) }} KK</td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="font-mono font-bold text-amber-600">{{ number_format($k['kk_non_shm']) }} KK</span>
                                    <span class="text-[11px] text-gray-500 block">({{ $k['persentase_non_shm'] }}% belum)</span>
                                </td>
                                <td class="py-3 px-3.5 text-center font-mono text-gray-700">
                                    {{ number_format($k['luas_non_shm_ha'], 1) }} Ha
                                </td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 font-mono">
                                        {{ $k['skor_urgensi'] }}
                                    </span>
                                    <div class="text-[9px] text-gray-500 font-mono mt-0.5" title="Komponen C1(40%) + C2(35%) + C3(25%)">
                                        {{ $k['score_persen'] ?? '-' }}|{{ $k['score_kk'] ?? '-' }}|{{ $k['score_luas'] ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-600 text-[11px] leading-relaxed max-w-xs">{{ $k['rekomendasi'] ?? 'Pemberkasan Lahan Usaha II (LU-II) dan verifikasi data fisik pemegang hak.' }}</td>
                                <td class="py-3 px-3.5 text-center">
                                    <a href="{{ route('admin.kawasan.spasial', $k['id']) }}" class="px-2.5 py-1 rounded bg-blue-50 hover:bg-blue-100 text-blue-900 font-bold text-[11px] border border-blue-200 transition-colors inline-flex items-center gap-1">
                                        <span>Peta</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-500 text-xs">Tidak ada kawasan pada kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table Prioritas 3 -->
        <div class="bg-white border border-emerald-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 bg-emerald-50 border-b border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="text-sm font-bold text-emerald-950">Daftar Kawasan Prioritas III (Pemeliharaan & Monitoring)</h3>
                </div>
                <span class="text-xs font-mono font-bold text-emerald-800">{{ count($clustering['prioritas_3'] ?? []) }} Kawasan Teridentifikasi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-slate-100 border-b-2 border-slate-300 text-[11px] uppercase font-bold text-slate-800 tracking-wider divide-x divide-slate-200">
                        <tr>
                            <th class="py-3 px-3.5 w-10 text-center">No</th>
                            <th class="py-3 px-3.5">Nama Kawasan & Lokasi</th>
                            <th class="py-3 px-3.5">Kabupaten</th>
                            <th class="py-3 px-3.5 text-center">Total KK</th>
                            <th class="py-3 px-3.5 text-center">Belum SHM (KK / %)</th>
                            <th class="py-3 px-3.5 text-center">Luas Belum SHM</th>
                            <th class="py-3 px-3.5 text-center">Skor SAW</th>
                            <th class="py-3 px-3.5">Rekomendasi Tindakan Spasial</th>
                            <th class="py-3 px-3.5 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 divide-x divide-slate-200">
                        @forelse ($clustering['prioritas_3'] ?? [] as $index => $k)
                            <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-emerald-50/40 transition-colors">
                                <td class="py-3 px-3.5 text-center font-mono text-gray-500 font-semibold">{{ $index + 1 }}</td>
                                <td class="py-3 px-3.5">
                                    <div class="font-bold text-gray-900">{{ $k['nama'] }}</div>
                                    <div class="text-[11px] text-gray-500">Desa {{ $k['desa'] }}, Kec. {{ $k['kecamatan'] }}</div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-700 font-medium">{{ $k['kabupaten'] }}</td>
                                <td class="py-3 px-3.5 text-center font-mono font-semibold text-gray-800">{{ number_format($k['jumlah_kk']) }} KK</td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="font-mono font-bold text-emerald-600">{{ number_format($k['kk_non_shm']) }} KK</span>
                                    <span class="text-[11px] text-gray-500 block">({{ $k['persentase_non_shm'] }}% belum)</span>
                                </td>
                                <td class="py-3 px-3.5 text-center font-mono text-gray-700">
                                    {{ number_format($k['luas_non_shm_ha'], 1) }} Ha
                                </td>
                                <td class="py-3 px-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono">
                                        {{ $k['skor_urgensi'] }}
                                    </span>
                                    <div class="text-[9px] text-gray-500 font-mono mt-0.5" title="Komponen C1(40%) + C2(35%) + C3(25%)">
                                        {{ $k['score_persen'] ?? '-' }}|{{ $k['score_kk'] ?? '-' }}|{{ $k['score_luas'] ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3.5 text-gray-600 text-[11px] leading-relaxed max-w-xs">{{ $k['rekomendasi'] ?? 'Pemeliharaan tapal batas dan fasilitasi peningkatan sertifikat elektronik.' }}</td>
                                <td class="py-3 px-3.5 text-center">
                                    <a href="{{ route('admin.kawasan.spasial', $k['id']) }}" class="px-2.5 py-1 rounded bg-blue-50 hover:bg-blue-100 text-blue-900 font-bold text-[11px] border border-blue-200 transition-colors inline-flex items-center gap-1">
                                        <span>Peta</span>
                                        <span>&rarr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-500 text-xs">Tidak ada kawasan pada kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
