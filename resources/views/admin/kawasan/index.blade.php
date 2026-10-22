@extends('admin.layout')

@section('title', 'Manajemen Data Kawasan')
@section('header-title', 'Master Data Kawasan Transmigrasi')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Data Spasial & Tabular Kawasan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data pemukiman transmigrasi, koordinat PostGIS, dan legalitas sertifikasi tanah.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(!$user->isEksekutif())
                <a href="{{ route('admin.kawasan.create') }}" class="px-3.5 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Kawasan</span>
                </a>

                <button onclick="document.getElementById('import-modal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-[#1a73e8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span>Impor Excel</span>
                </button>
            @endif

            <a href="{{ route('data.export') }}?format=xlsx" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-[#1a73e8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Ekspor XLSX</span>
            </a>

            <a href="{{ route('data.template') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Template</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar (Light Mode) -->
    <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.kawasan.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Search Text -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kawasan, desa, kecamatan..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:border-transparent">
            </div>

            <!-- Filter Kabupaten -->
            <div class="sm:col-span-3">
                <select name="kabupaten" class="w-full py-2 px-3 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <option value="all">Semua Kabupaten</option>
                    @foreach(['Kabupaten Boalemo', 'Kabupaten Pohuwato', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara'] as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status SHM -->
            <div class="sm:col-span-2">
                <select name="status_shm" class="w-full py-2 px-3 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <option value="">Status SHM (Semua)</option>
                    <option value="tinggi" {{ request('status_shm') === 'tinggi' ? 'selected' : '' }}>&ge; 75% (Tinggi)</option>
                    <option value="sedang" {{ request('status_shm') === 'sedang' ? 'selected' : '' }}>50% - 74% (Sedang)</option>
                    <option value="rendah" {{ request('status_shm') === 'rendah' ? 'selected' : '' }}>&lt; 50% (Rendah)</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-[#1a73e8] hover:bg-blue-600 text-white text-xs font-bold transition-colors shadow-xs cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('admin.kawasan.index') }}" class="py-2 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium border border-gray-300 transition-colors">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Data Table Container (Light Mode) -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-slate-100 border-b-2 border-slate-300 text-[11px] uppercase font-bold tracking-wider text-slate-800 divide-x divide-slate-200">
                    <tr>
                        <th class="py-3 px-3.5 w-12 text-center">No</th>
                        <th class="py-3 px-3.5">Nama Kawasan & Lokasi</th>
                        <th class="py-3 px-3.5">Wilayah Kabupaten</th>
                        <th class="py-3 px-3.5 text-center">Demografi</th>
                        <th class="py-3 px-3.5 text-center">Progres Sertifikasi (SHM)</th>
                        <th class="py-3 px-3.5 text-right">Luas (Ha)</th>
                        <th class="py-3 px-3.5 text-center">Status</th>
                        <th class="py-3 px-3.5 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 divide-x divide-slate-200">
                    @forelse ($kawasanList as $index => $item)
                        @php
                            $canManage = $user->isSuperAdmin() || $user->canManageKabupaten($item->kabupaten);
                        @endphp
                        <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-amber-50/60 transition-colors">
                            <td class="py-3 px-3.5 text-center font-mono text-gray-500 font-semibold">
                                {{ $kawasanList->firstItem() + $index }}
                            </td>
                            
                            <td class="py-3 px-3.5">
                                <div class="font-bold text-gray-900 text-sm flex items-center gap-2">
                                    <span>{{ $item->nama }}</span>
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ $item->tipe }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    Desa {{ $item->desa }}, Kec. {{ $item->kecamatan }} &bull; Thn {{ $item->tahun_penempatan }}
                                </div>
                                @if(!empty($item->program_pemberdayaan) && count($item->program_pemberdayaan) > 0)
                                    <div class="flex items-center gap-1 mt-1.5 flex-wrap">
                                        @foreach(array_slice($item->program_pemberdayaan, 0, 2) as $prog)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] bg-teal-50 text-teal-800 border border-teal-200 font-medium">
                                                {{ $prog }}
                                            </span>
                                        @endforeach
                                        @if(count($item->program_pemberdayaan) > 2)
                                            <span class="text-[9px] text-teal-700 font-bold">+{{ count($item->program_pemberdayaan) - 2 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 px-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-800 border border-slate-300">
                                    {{ str_replace('Kabupaten ', '', $item->kabupaten) }}
                                </span>
                            </td>

                            <td class="py-3 px-3.5 text-center">
                                <div class="font-bold text-gray-900 font-mono">{{ number_format($item->jumlah_kk) }} KK</div>
                                <div class="text-[10px] text-gray-500">{{ number_format($item->jumlah_jiwa) }} Jiwa</div>
                            </td>

                            <td class="py-3 px-3.5 text-center">
                                <div class="flex items-center justify-between text-[11px] mb-1">
                                    <span class="text-blue-900 font-bold">{{ $item->kk_shm }} SHM</span>
                                    <span class="text-yellow-800 font-semibold">{{ $item->kk_non_shm }} Non</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden flex">
                                    <div class="h-full bg-[#1a73e8]" style="width: {{ $item->persentase_shm }}%"></div>
                                    <div class="h-full bg-yellow-500" style="width: {{ 100 - $item->persentase_shm }}%"></div>
                                </div>
                                <div class="text-[10px] font-bold mt-1 {{ $item->persentase_shm >= 75 ? 'text-blue-900' : ($item->persentase_shm >= 50 ? 'text-yellow-700' : 'text-rose-700') }}">
                                    {{ $item->persentase_shm }}% Selesai
                                </div>
                                @if($item->target_bidang_shm)
                                    <div class="text-[9px] text-gray-500 font-mono mt-0.5">
                                        {{ $item->bidang_shm_terbit ?? $item->kk_shm }}/{{ $item->target_bidang_shm }} Bidang
                                    </div>
                                @endif
                                @if($item->tahapan_shm)
                                    <div class="text-[9px] font-semibold text-indigo-700 truncate max-w-[130px] mx-auto mt-0.5" title="{{ $item->tahapan_shm }}">
                                        {{ $item->tahapan_shm }}
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 px-3.5 text-right font-mono text-gray-900">
                                <div class="font-bold">{{ number_format($item->luas_ha, 0) }} Ha</div>
                                @if($item->luas_lahan_pekarangan_ha || $item->luas_lahan_usaha_1_ha || $item->luas_lahan_usaha_2_ha)
                                    <div class="text-[9px] text-gray-500 font-sans" title="LP: {{ $item->luas_lahan_pekarangan_ha ?? 0 }} Ha | LU I: {{ $item->luas_lahan_usaha_1_ha ?? 0 }} Ha | LU II: {{ $item->luas_lahan_usaha_2_ha ?? 0 }} Ha">
                                        LP: {{ number_format($item->luas_lahan_pekarangan_ha, 0) }} &bull; LU: {{ number_format(($item->luas_lahan_usaha_1_ha ?? 0) + ($item->luas_lahan_usaha_2_ha ?? 0), 0) }}
                                    </div>
                                @endif
                            </td>

                            <td class="py-3 px-3.5 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border
                                    @if($item->status_perkembangan === 'Mandiri') bg-emerald-50 text-emerald-800 border-emerald-200
                                    @elseif($item->status_perkembangan === 'Berkembang') bg-yellow-50 text-yellow-800 border-yellow-200
                                    @else bg-rose-50 text-rose-800 border-rose-200 @endif">
                                    {{ $item->status_perkembangan }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    
                                    <!-- Editor Spasial Button -->
                                    <a href="{{ route('admin.kawasan.spasial', $item->id) }}" title="Editor Spasial Poligon"
                                        class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-900 border border-blue-200 transition-colors shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    </a>

                                    @if($canManage && !$user->isEksekutif())
                                        <!-- Edit Data Tabular -->
                                        <a href="{{ route('admin.kawasan.edit', $item->id) }}" title="Edit Data Tabular"
                                            class="p-1.5 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-800 border border-yellow-200 transition-colors shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Hapus Data -->
                                        <form action="{{ route('admin.kawasan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kawasan {{ $item->nama }}? Tindakan ini akan dicatat di log audit.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Kawasan"
                                                class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors shadow-2xs cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">Read-only</span>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-gray-500">
                                <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="text-sm font-semibold text-gray-700">Tidak ada data kawasan yang cocok.</div>
                                <div class="text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci atau filter pencarian Anda.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($kawasanList->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                {{ $kawasanList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Impor Data Massal (Light Mode) -->
<div id="import-modal" class="fixed inset-0 z-50 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white border border-gray-200 rounded-xl max-w-lg w-full p-6 shadow-2xl space-y-4 text-gray-800">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#1a73e8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Impor Data Massal (Excel / CSV)
            </h3>
            <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <p class="text-xs text-gray-600">
            Unggah file lembar kerja XLSX atau CSV data pemukiman transmigrasi. Sistem akan memvalidasi kolom dan membuat entri audit trail secara otomatis.
        </p>

        <form id="form-import-excel" action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Pilih File (.xlsx / .csv)</label>
                <input type="file" name="file" required accept=".xlsx,.csv"
                    class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#081e36] file:text-white hover:file:bg-[#0d2a4a] cursor-pointer bg-gray-50 p-2 rounded-xl border border-gray-300">
            </div>

            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-[11px] text-blue-900 flex items-center justify-between">
                <span>Belum memiliki format data?</span>
                <a href="{{ route('data.template') }}" class="text-blue-900 font-bold hover:underline">Unduh Template XLSX</a>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 border border-gray-300 cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="btn-submit-import" class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm flex items-center gap-1.5 cursor-pointer">
                    <span>Mulai Proses Impor</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
