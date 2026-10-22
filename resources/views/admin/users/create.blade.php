@extends('admin.layout')

@section('title', 'Tambah Akun Aparatur')
@section('header-title', 'Pendaftaran Akun Aparatur Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header & Navigasi Kembali -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Pendaftaran Akun Aparatur Baru</h1>
            <p class="text-xs text-gray-500 mt-0.5">Daftarkan akun operator dinas kabupaten, aparatur provinsi, atau pimpinan eksekutif.</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
            <div class="font-bold flex items-center gap-2 mb-1.5">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Terdapat beberapa kesalahan dalam formulir pendaftaran:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 ml-1 text-[11px] text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-6">
        @csrf

        <!-- BAGIAN 1: IDENTITAS & PROFIL APARATUR -->
        <div>
            <h2 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-700"></span>
                <span>1. Profil & Identitas Pegawai Kedinasan</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Nama Lengkap & Gelar / NIP <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ir. Budi Santoso, M.Si / NIP 198501..."
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Alamat Email Kedinasan <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="aparatur@gorontalo.go.id"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all font-mono">
                    <span class="text-[10px] text-gray-400 mt-1 block">Email ini digunakan sebagai akun login ke dalam portal WebGIS.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Jabatan / Unit Kerja
                    </label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Analis SIG Bidang Transmigrasi Disnakertrans"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: HAK AKSES & PENUGASAN WILAYAH -->
        <div class="pt-5 border-t border-gray-200">
            <h2 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                <span>2. Hak Akses & Penugasan Wilayah</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Peran Sistem (Role) <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" id="user-role-select" required onchange="toggleKabupatenField(this.value)"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                        <option value="">Pilih Peran Aparatur...</option>
                        <option value="operator_kabupaten" {{ old('role') === 'operator_kabupaten' ? 'selected' : '' }}>
                            Operator Kabupaten
                        </option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>
                            Super Admin Provinsi (Akses Seluruh Wilayah)
                        </option>
                        <option value="eksekutif" {{ old('role') === 'eksekutif' ? 'selected' : '' }}>
                            Eksekutif / Pimpinan (Akses Pemantauan)
                        </option>
                    </select>
                </div>

                <div id="kabupaten-container">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Wilayah Kabupaten Penugasan <span id="kabupaten-required-star" class="text-rose-500">*</span>
                    </label>
                    <select name="kabupaten" id="kabupaten-select"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                        <option value="">Pilih Kabupaten Penugasan...</option>
                        @foreach($kabupatenList as $kab)
                            <option value="{{ $kab }}" {{ old('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                        @endforeach
                    </select>
                    <p id="kabupaten-help" class="text-[10px] text-gray-400 mt-1">
                        Pilih wilayah kabupaten tugas penugasan operator.
                    </p>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: KATA SANDI / KREDENSIAL KEAMANAN -->
        <div class="pt-5 border-t border-gray-200">
            <h2 class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-purple-700"></span>
                <span>3. Kredensial Kata Sandi</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">
                        Kata Sandi Awal <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter..."
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-show-password', 'eye-hide-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-purple-900 focus:outline-none cursor-pointer" title="Tampilkan / Sembunyikan Kata Sandi">
                            <svg id="eye-show-password" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg id="eye-hide-password" class="w-4 h-4 hidden text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi di atas..."
                            class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-gray-50 border border-gray-300 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:bg-white transition-all">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-show-confirm', 'eye-hide-confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-purple-900 focus:outline-none cursor-pointer" title="Tampilkan / Sembunyikan Kata Sandi">
                            <svg id="eye-show-confirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg id="eye-hide-confirm" class="w-4 h-4 hidden text-purple-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Form -->
        <div class="pt-6 border-t border-gray-200 flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}"
                class="px-4 py-2.5 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-300 transition-colors cursor-pointer">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-purple-950 text-xs font-bold shadow-md transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-purple-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Akun Aparatur</span>
            </button>
        </div>
    </form>
</div>

<script>
    function toggleKabupatenField(role) {
        const select = document.getElementById('kabupaten-select');
        const star = document.getElementById('kabupaten-required-star');
        const help = document.getElementById('kabupaten-help');

        if (role === 'operator_kabupaten') {
            select.disabled = false;
            select.required = true;
            star.classList.remove('hidden');
            help.textContent = 'Pilih wilayah kabupaten tugas penugasan operator.';
        } else {
            select.disabled = true;
            select.required = false;
            select.value = '';
            star.classList.add('hidden');
            help.textContent = 'Peran Provinsi / Eksekutif memiliki cakupan seluruh wilayah Provinsi Gorontalo.';
        }
    }

    // Inisialisasi awal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.getElementById('user-role-select');
        if (roleSelect) {
            toggleKabupatenField(roleSelect.value);
        }
    });

    function togglePasswordVisibility(inputId, showIconId, hideIconId) {
        const input = document.getElementById(inputId);
        const showIcon = document.getElementById(showIconId);
        const hideIcon = document.getElementById(hideIconId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            if (showIcon) showIcon.classList.add('hidden');
            if (hideIcon) hideIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            if (showIcon) showIcon.classList.remove('hidden');
            if (hideIcon) hideIcon.classList.add('hidden');
        }
    }
</script>
@endsection
