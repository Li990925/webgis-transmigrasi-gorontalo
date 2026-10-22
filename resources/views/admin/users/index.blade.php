@extends('admin.layout')

@section('title', 'Kelola Akun Aparatur')
@section('header-title', 'Manajemen Akun Aparatur & Hak Akses')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                <span>Daftar Akun Aparatur & Pengguna Sistem</span>
            </h1>
            <p class="text-xs text-gray-500 mt-0.5">Kelola data aparatur, penetapan hak akses peran, dan wilayah penugasan di 4 kabupaten target.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Tambah Aparatur Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
    @if(session('success'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-base font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-700 text-base font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- 4 Metrik Ringkasan Akun -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center text-[#1a73e8] shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Aparatur</div>
                <div class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $stats['total'] }} Akun</div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-lg bg-[#081e36] text-yellow-400 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Super Admin</div>
                <div class="text-xl font-extrabold text-blue-900 mt-0.5">{{ $stats['super_admin'] }} Orang</div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Operator Kabupaten</div>
                <div class="text-xl font-extrabold text-amber-900 mt-0.5">{{ $stats['operator'] }} Wilayah</div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Eksekutif / Pimpinan</div>
                <div class="text-xl font-extrabold text-sky-900 mt-0.5">{{ $stats['eksekutif'] }} Pejabat</div>
            </div>
        </div>
    </div>

    <!-- Filter & Toolbar Pencarian -->
    <div class="p-4 rounded-xl bg-white border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            
            <!-- Search Text -->
            <div class="sm:col-span-7 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama aparatur, email kedinasan, jabatan, atau kabupaten..."
                    class="w-full pl-9 pr-3 py-2 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
            </div>

            <!-- Filter Peran -->
            <div class="sm:col-span-3">
                <select name="role" class="w-full py-2 px-3 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                    <option value="">Semua Peran Otoritas</option>
                    <option value="super_admin" {{ $selectedRole === 'super_admin' ? 'selected' : '' }}>Super Admin Provinsi</option>
                    <option value="operator_kabupaten" {{ $selectedRole === 'operator_kabupaten' ? 'selected' : '' }}>Operator Kabupaten</option>
                    <option value="eksekutif" {{ $selectedRole === 'eksekutif' ? 'selected' : '' }}>Eksekutif (Read-Only)</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition-colors cursor-pointer">
                    Cari
                </button>
                <a href="{{ route('admin.users.index') }}" class="py-2 px-3 rounded-xl bg-white hover:bg-gray-100 text-gray-600 hover:text-gray-900 text-xs font-semibold border border-gray-300 shadow-sm transition-colors text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Data Aparatur -->
    <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-100 border-b-2 border-slate-300 text-slate-800 text-[11px] font-bold uppercase tracking-wider divide-x divide-slate-200">
                    <tr>
                        <th class="py-3 px-3.5">Aparatur / Pegawai</th>
                        <th class="py-3 px-3.5">Email Kedinasan</th>
                        <th class="py-3 px-3.5">Peran (Role)</th>
                        <th class="py-3 px-3.5">Wilayah Otoritas</th>
                        <th class="py-3 px-3.5">Terdaftar</th>
                        <th class="py-3 px-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 divide-x divide-slate-200 text-xs text-gray-700">
                    @forelse($users as $user)
                        <tr class="odd:bg-white even:bg-slate-50/70 hover:bg-blue-50/40 transition-colors {{ Auth::id() === $user->id ? 'bg-amber-50/50' : '' }}">
                            <td class="py-3 px-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#081e36] text-yellow-400 font-extrabold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if(Auth::id() === $user->id)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-yellow-200 text-yellow-900 border border-yellow-300">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-gray-500">{{ $user->jabatan ?: 'Staf Pelaksana' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3.5 font-mono text-[11px] text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="py-3 px-3.5">
                                @if($user->role === 'super_admin')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-900 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#1a73e8]"></span>
                                        Super Admin
                                    </span>
                                @elseif($user->role === 'operator_kabupaten')
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-900 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                        Operator Kabupaten
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 text-sky-900 border border-sky-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                                        Eksekutif (Read-Only)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5">
                                @if($user->kabupaten)
                                    <span class="font-semibold text-gray-900 flex items-center gap-1 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        {{ $user->kabupaten }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-gray-500 italic">Provinsi Gorontalo (Semua Wilayah)</span>
                                @endif
                            </td>
                            <td class="py-3 px-3.5 font-mono text-[11px] text-gray-500">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3 px-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="p-1.5 rounded bg-slate-100 hover:bg-blue-100 text-slate-700 hover:text-blue-900 border border-slate-300 transition-colors cursor-pointer"
                                        title="Ubah Data & Reset Password">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    @if(Auth::id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun aparatur \'{{ $user->name }}\'? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded bg-slate-100 hover:bg-rose-100 text-slate-700 hover:text-rose-700 border border-slate-300 transition-colors cursor-pointer"
                                                title="Hapus Akun Aparatur">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-xs font-semibold text-gray-600">Tidak ada data aparatur yang cocok dengan pencarian.</p>
                                <p class="text-[11px] text-gray-400 mt-1">Coba sesuaikan kata kunci pencarian atau bersihkan filter peran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
