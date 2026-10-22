@extends('admin.layout')

@section('title', 'Log Aktivitas Sistem')
@section('header-title', 'Log Aktivitas & Riwayat Perubahan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Rekam Jejak & Log Aktivitas Sistem</h1>
            <p class="text-xs text-gray-500 mt-0.5">Catatan riwayat dari setiap penambahan data, pembaruan atribut, dan perubahan spasial.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-purple-100 text-purple-900 text-xs font-semibold border border-purple-200 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span>Standar Kepatuhan Pemerintahan</span>
            </span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.audit-logs') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Search Text -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna, deskripsi, kawasan..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
            </div>

            <!-- Filter Action -->
            <div class="sm:col-span-3">
                <select name="action" class="w-full py-2 px-3 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                    <option value="all">Semua Tipe Aksi</option>
                    @foreach(['CREATE', 'UPDATE', 'DELETE', 'GEOMETRY_EDIT', 'LOGIN', 'LOGOUT', 'ROLE_SWITCH', 'IMPORT', 'EXPORT'] as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kabupaten -->
            <div class="sm:col-span-2">
                <select name="kabupaten" class="w-full py-2 px-3 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                    <option value="all">Semua Wilayah</option>
                    @foreach(['Kabupaten Boalemo', 'Kabupaten Pohuwato', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara'] as $kab)
                        <option value="{{ $kab }}" {{ request('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-purple-950 text-xs font-bold shadow-sm transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.audit-logs') }}" class="py-2 px-3 rounded-xl bg-white hover:bg-gray-100 text-gray-600 hover:text-gray-900 text-xs font-semibold border border-gray-300 shadow-sm transition-colors">
                    Reset
                </a>
            </div>

        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700">
                <thead class="bg-slate-100 border-b-2 border-slate-300 text-[11px] uppercase font-bold tracking-wider text-slate-800 divide-x divide-slate-200">
                    <tr>
                        <th class="py-3 px-3.5 w-36">Waktu (WITA)</th>
                        <th class="py-3 px-3.5">Pengguna / Operator</th>
                        <th class="py-3 px-3.5 text-center">Tipe Aksi</th>
                        <th class="py-3 px-3.5">Deskripsi Aktivitas</th>
                        <th class="py-3 px-3.5">Wilayah Terkait</th>
                        <th class="py-3 px-3.5 text-center w-24">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 divide-x divide-slate-200 font-mono">
                    @forelse ($logs as $log)
                        <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-purple-50/40 transition-colors">
                            <!-- Timestamp -->
                            <td class="py-3 px-3.5 whitespace-nowrap text-gray-600 font-mono text-[11px]">
                                <div class="font-bold text-gray-800">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-gray-400 text-[10px]">{{ $log->created_at->format('H:i:s') }} WITA</div>
                            </td>

                            <!-- User Info -->
                            <td class="py-3 px-3.5 font-sans">
                                <div class="font-bold text-gray-900 text-xs">{{ $log->user_name }}</div>
                                <div class="text-[10px] text-gray-500 font-mono">{{ $log->user_role }}</div>
                            </td>

                            <!-- Action Badge -->
                            <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                    @if($log->action === 'CREATE') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif($log->action === 'UPDATE') bg-purple-50 text-purple-900 border border-purple-200
                                    @elseif($log->action === 'GEOMETRY_EDIT') bg-indigo-50 text-indigo-800 border border-indigo-200
                                    @elseif($log->action === 'DELETE') bg-rose-50 text-rose-800 border border-rose-200
                                    @elseif($log->action === 'ROLE_SWITCH') bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif($log->action === 'LOGIN') bg-yellow-50 text-yellow-900 border border-yellow-200
                                    @else bg-gray-100 text-gray-700 border border-gray-200 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="py-3 px-3.5 font-sans text-xs text-gray-800">
                                {{ $log->description }}
                            </td>

                            <!-- Kabupaten -->
                            <td class="py-3 px-3.5 font-sans whitespace-nowrap">
                                @if($log->kabupaten)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-800 border border-slate-300">
                                        {{ str_replace('Kabupaten ', '', $log->kabupaten) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-[10px]">-</span>
                                @endif
                            </td>

                            <!-- View Payload Modal Button -->
                            <td class="py-3 px-3.5 text-center whitespace-nowrap font-sans">
                                @if(!empty($log->old_values) || !empty($log->new_values))
                                    <button onclick="showPayloadModal({{ $log->id }}, '{{ $log->action }}', {{ json_encode($log->old_values) }}, {{ json_encode($log->new_values) }})"
                                        class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-800 text-[11px] font-semibold border border-slate-300 shadow-xs transition-colors cursor-pointer">
                                        Payload
                                    </button>
                                @else
                                    <span class="text-gray-400 text-[10px]">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500 font-sans">
                                Belum ada log audit yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/60">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Inspect Payload Diff -->
<div id="payload-modal" class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white border border-gray-200 rounded-xl max-w-2xl w-full p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                <span id="modal-action-badge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 text-purple-900"></span>
                <span>Detail Perubahan Data (Audit Payload)</span>
            </h3>
            <button onclick="document.getElementById('payload-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider mb-1.5">Nilai Sebelum (Old Values):</div>
                <pre id="modal-old-values" class="p-3 rounded-xl bg-amber-50/50 border border-amber-200 text-gray-800 font-mono text-[10px] overflow-auto max-h-60 leading-relaxed"></pre>
            </div>
            <div>
                <div class="text-[11px] font-bold text-purple-900 uppercase tracking-wider mb-1.5">Nilai Sesudah (New Values):</div>
                <pre id="modal-new-values" class="p-3 rounded-xl bg-purple-50/50 border border-purple-200 text-gray-800 font-mono text-[10px] overflow-auto max-h-60 leading-relaxed"></pre>
            </div>
        </div>

        <div class="pt-2 flex justify-end">
            <button onclick="document.getElementById('payload-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold border border-gray-300 shadow-sm transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showPayloadModal(id, action, oldVals, newVals) {
    document.getElementById('modal-action-badge').textContent = action;
    document.getElementById('modal-old-values').textContent = oldVals ? JSON.stringify(oldVals, null, 2) : '(Kosong / Data Baru)';
    document.getElementById('modal-new-values').textContent = newVals ? JSON.stringify(newVals, null, 2) : '(Dihapus / Tidak Ada)';
    document.getElementById('payload-modal').classList.remove('hidden');
}
</script>
@endsection
