@extends('admin.layout')

@section('title', 'Tambah Kawasan Transmigrasi')
@section('header-title', 'Tambah Data Kawasan Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Formulir Pendaftaran Kawasan Baru</h1>
            <p class="text-xs text-gray-500 mt-0.5">Input data demografi, capaian sertifikasi hak milik, dan koordinat spasial PostGIS.</p>
        </div>
        <a href="{{ route('admin.kawasan.index') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-colors flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1.5">Terjadi kesalahan pengisian formulir:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kawasan.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Identitas & Lokasi Administratif -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">1</span>
                Identitas Kawasan & Administrasi Wilayah
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Kawasan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama') }}" placeholder="Contoh: UPT Ayula Baru"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Tipe Kawasan <span class="text-rose-500">*</span></label>
                    <select name="tipe" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        <option value="UPT" {{ old('tipe') === 'UPT' ? 'selected' : '' }}>UPT (Unit Pemukiman Transmigrasi)</option>
                        <option value="KTM" {{ old('tipe') === 'KTM' ? 'selected' : '' }}>KTM (Kota Terpadu Mandiri)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kabupaten <span class="text-rose-500">*</span></label>
                    <select name="kabupaten" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        @if ($user->isSuperAdmin())
                            @foreach(['Kabupaten Boalemo', 'Kabupaten Pohuwato', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara'] as $kab)
                                <option value="{{ $kab }}" {{ old('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        @else
                            <option value="{{ $user->kabupaten }}" selected>{{ $user->kabupaten }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kecamatan" required value="{{ old('kecamatan') }}" placeholder="Contoh: Tilamuta"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Desa <span class="text-rose-500">*</span></label>
                    <input type="text" name="desa" required value="{{ old('desa') }}" placeholder="Contoh: Ayula"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Tahun Penempatan <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_penempatan" required value="{{ old('tahun_penempatan', date('Y')) }}" min="1980" max="2030"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>
            </div>
        </div>

        <!-- Section 2: Demografi & Legalitas Hak Milik -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-yellow-100 text-yellow-800 flex items-center justify-center text-xs font-extrabold">2</span>
                Demografi Penduduk & Sertifikasi Tanah (SHM)
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah KK <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_kk" id="input_kk" required value="{{ old('jumlah_kk', 0) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah Jiwa <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_jiwa" required value="{{ old('jumlah_jiwa', 0) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">KK Sudah SHM <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_shm" id="input_shm" required value="{{ old('kk_shm', 0) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">KK Belum SHM (Non-SHM) <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_non_shm" id="input_non_shm" required value="{{ old('kk_non_shm', 0) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Luas Kawasan (Hektar) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="luas_ha" required value="{{ old('luas_ha', 0) }}" min="0.1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Status Perkembangan <span class="text-rose-500">*</span></label>
                    <select name="status_perkembangan" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        <option value="Pembinaan" {{ old('status_perkembangan') === 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                        <option value="Berkembang" {{ old('status_perkembangan') === 'Berkembang' ? 'selected' : '' }}>Berkembang</option>
                        <option value="Mandiri" {{ old('status_perkembangan') === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Skor Kemandirian (0 - 100)</label>
                    <input type="number" name="skor_kemandirian" value="{{ old('skor_kemandirian', 70) }}" min="0" max="100"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Komoditas Unggulan</label>
                    <input type="text" name="komoditas_unggulan" value="{{ old('komoditas_unggulan') }}" placeholder="Pisang, Jagung, Kelapa"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>
            </div>
        </div>

        <!-- Section 3: Rincian Alokasi Lahan Transmigrasi (LU I, LU II & Pekarangan) -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <div class="border-b border-gray-200 pb-3">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-extrabold">3</span>
                    Rincian Alokasi Lahan Transmigrasi (Hektar)
                </h2>
                <p class="text-[11px] text-gray-500 mt-1">Pembagian hak lahan warga transmigrasi (Lahan Pekarangan, Lahan Usaha I, dan Lahan Usaha II) sesuai SK Hak Pengelolaan Lahan (HPL).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Lahan Pekarangan (LP) (Ha)</label>
                    <input type="number" step="0.01" name="luas_lahan_pekarangan_ha" id="input_lp_ha" value="{{ old('luas_lahan_pekarangan_ha', 0) }}" min="0" placeholder="Contoh: 125.00"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-gray-400 mt-1 block">Tapak pemukiman & pekarangan keluarga.</span>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Lahan Usaha I (LU I) (Ha)</label>
                    <input type="number" step="0.01" name="luas_lahan_usaha_1_ha" id="input_lu1_ha" value="{{ old('luas_lahan_usaha_1_ha', 0) }}" min="0" placeholder="Contoh: 350.50"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-gray-400 mt-1 block">Lahan pertanian/tanaman pangan utama.</span>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Lahan Usaha II (LU II) (Ha)</label>
                    <input type="number" step="0.01" name="luas_lahan_usaha_2_ha" id="input_lu2_ha" value="{{ old('luas_lahan_usaha_2_ha', 0) }}" min="0" placeholder="Contoh: 240.00"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-gray-400 mt-1 block">Lahan perkebunan/pengembangan lanjutan.</span>
                </div>
            </div>
        </div>

        <!-- Section 4: Target & Tahapan Sertifikasi Bidang Tanah (ATR/BPN) -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <div class="border-b border-gray-200 pb-3">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center text-xs font-extrabold">4</span>
                    Target & Tahapan Sertifikasi Bidang Hak Milik (ATR/BPN)
                </h2>
                <p class="text-[11px] text-gray-500 mt-1">Pemantauan target sertifikasi bidang tanah dan progres tahapan legalitas di Kantor Pertanahan ATR/BPN.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Target Total Bidang SHM</label>
                    <input type="number" name="target_bidang_shm" id="input_target_bidang" value="{{ old('target_bidang_shm', 0) }}" min="0" placeholder="Contoh: 4150"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-gray-400 mt-1 block">Total kuota bidang target sertifikasi.</span>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Realisasi SHM Terbit (Bidang)</label>
                    <input type="number" name="bidang_shm_terbit" id="input_bidang_terbit" value="{{ old('bidang_shm_terbit', 0) }}" min="0" placeholder="Contoh: 2864"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">Sertifikat telah terbit resmi dari BPN.</span>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Bidang SHM Masih Proses</label>
                    <input type="number" name="bidang_shm_proses" id="input_bidang_proses" value="{{ old('bidang_shm_proses', 0) }}" min="0" placeholder="Contoh: 1286"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-amber-600 font-semibold mt-1 block">Dalam proses pengukuran / sidang pertanahan.</span>
                </div>
            </div>

            <div class="text-xs pt-1">
                <label class="block font-bold text-gray-700 mb-1">Tahapan Proses / Kendala Sertifikasi Saat Ini</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <select name="tahapan_shm" id="select_tahapan_shm" class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        <option value="">-- Pilih Tahapan ATR/BPN atau Masukkan Kustom --</option>
                        @php
                            $tahapanOptions = [
                                'Tahap Pengukuran & Sidang PBT',
                                'Tahap Inventarisasi Subjek & Objek',
                                'Tahap Penerbitan SK Hak Milik (BPN)',
                                'Kendala Tumpang Tindih Kawasan Hutan',
                                'Selesai Sertifikasi 100%'
                            ];
                        @endphp
                        @foreach($tahapanOptions as $opt)
                            <option value="{{ $opt }}" {{ old('tahapan_shm') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="tahapan_shm_custom" id="input_tahapan_custom" placeholder="Atau ketik keterangan tahapan/kendala spesifik..."
                        value="{{ old('tahapan_shm_custom') }}"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>
                <span class="text-[10px] text-gray-400 mt-1 block">Catatan status tahapan ini akan muncul pada kartu indikator sertifikasi eksekutif dashboard.</span>
            </div>
        </div>

        <!-- Section 5: Program Pemberdayaan Ekonomi Transmigrasi -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <div class="border-b border-gray-200 pb-3">
                <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-extrabold">5</span>
                    Program Pemberdayaan Ekonomi & Bantuan Usaha
                </h2>
                <p class="text-[11px] text-gray-500 mt-1">Intervensi stimulan usaha tani, peningkatan kompetensi transmigran, dan penguatan lembaga ekonomi desa.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah Kegiatan Pemberdayaan</label>
                    <input type="number" name="jumlah_kegiatan_pemberdayaan" id="input_kegiatan_pemberdayaan" value="{{ old('jumlah_kegiatan_pemberdayaan', 0) }}" min="0" placeholder="Contoh: 24"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    <span class="text-[10px] text-gray-400 mt-1 block">Akumulasi paket pelatihan/bantuan yang disalurkan.</span>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-bold text-gray-700 mb-1.5">Jenis Program Pemberdayaan (Centang yang sesuai)</label>
                    @php
                        $defaultPrograms = [
                            'Bimtek Budidaya & Pasca Panen',
                            'Bantuan Saprotan & Alsintan',
                            'Kelompok Usaha Bersama (KUBE)',
                            'Pelatihan Vokasi & Keterampilan',
                            'Kemitraan Usaha & Akses Pasar'
                        ];
                        $oldPrograms = is_array(old('program_pemberdayaan')) ? old('program_pemberdayaan') : [];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($defaultPrograms as $prog)
                            <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 cursor-pointer text-[11px] transition-colors">
                                <input type="checkbox" name="program_pemberdayaan[]" value="{{ $prog }}"
                                    {{ in_array($prog, $oldPrograms) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded text-[#1a73e8] focus:ring-[#1a73e8] border-gray-300">
                                <span class="text-gray-800 font-medium">{{ $prog }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-xs pt-1">
                <label class="block font-bold text-gray-700 mb-1">Program Tambahan Lainnya (Opsional)</label>
                <input type="text" name="program_pemberdayaan_custom" value="{{ old('program_pemberdayaan_custom') }}" placeholder="Contoh: Pelatihan Digital Marketing UMKM, Bantuan Ternak Sapi (pisahkan koma jika lebih dari satu)"
                    class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
            </div>
        </div>

        <!-- Section 6: Koordinat Spasial PostGIS -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">6</span>
                Koordinat Titik Spasial (PostGIS Point SRID 4326)
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4 space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Latitude (Lintang) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="latitude" id="input_latitude" required value="{{ old('latitude', '0.550000') }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Longitude (Bujur) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="longitude" id="input_longitude" required value="{{ old('longitude', '122.350000') }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    </div>

                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-[11px] text-blue-900">
                        <span class="font-bold">Tips:</span> Anda dapat mengklik langsung di atas peta di sebelah kanan untuk menentukan koordinat pusat titik secara presisi. Poligon batas dapat diedit lebih lanjut melalui menu <span class="font-bold underline">Editor Spasial</span> setelah disimpan.
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div id="picker-map" class="w-full h-64 rounded-xl border border-gray-300 overflow-hidden relative z-0"></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.kawasan.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold border border-gray-300 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Kawasan Baru</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('input_latitude');
    const lngInput = document.getElementById('input_longitude');
    
    // Auto sync KK Non-SHM
    const kkInput = document.getElementById('input_kk');
    const shmInput = document.getElementById('input_shm');
    const nonShmInput = document.getElementById('input_non_shm');

    function syncShm() {
        const total = parseInt(kkInput.value) || 0;
        const shm = parseInt(shmInput.value) || 0;
        if (total > 0 && shm <= total) {
            nonShmInput.value = total - shm;
        }

        // Suggest target bidang if empty
        if (targetBidangInput && (!targetBidangInput.value || parseInt(targetBidangInput.value) === 0)) {
            targetBidangInput.value = total;
            syncBidang();
        }
        if (bidangTerbitInput && (!bidangTerbitInput.value || parseInt(bidangTerbitInput.value) === 0)) {
            bidangTerbitInput.value = shm;
            syncBidang();
        }
    }
    kkInput?.addEventListener('input', syncShm);
    shmInput?.addEventListener('input', syncShm);

    // Auto sync Bidang SHM (Target - Terbit = Proses)
    const targetBidangInput = document.getElementById('input_target_bidang');
    const bidangTerbitInput = document.getElementById('input_bidang_terbit');
    const bidangProsesInput = document.getElementById('input_bidang_proses');

    function syncBidang() {
        if (!targetBidangInput || !bidangTerbitInput || !bidangProsesInput) return;
        const target = parseInt(targetBidangInput.value) || 0;
        const terbit = parseInt(bidangTerbitInput.value) || 0;
        if (target >= terbit) {
            bidangProsesInput.value = target - terbit;
        }
    }
    targetBidangInput?.addEventListener('input', syncBidang);
    bidangTerbitInput?.addEventListener('input', syncBidang);

    // Tahapan ATR/BPN select vs custom input sync
    const selectTahapan = document.getElementById('select_tahapan_shm');
    const inputTahapanCustom = document.getElementById('input_tahapan_custom');
    selectTahapan?.addEventListener('change', function() {
        if (this.value) {
            inputTahapanCustom.value = '';
        }
    });
    inputTahapanCustom?.addEventListener('input', function() {
        if (this.value) {
            selectTahapan.value = '';
        }
    });

    // Auto count selected pemberdayaan programs if kegiatan is 0
    const progCheckboxes = document.querySelectorAll('input[name="program_pemberdayaan[]"]');
    const kegiatanInput = document.getElementById('input_kegiatan_pemberdayaan');
    progCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (kegiatanInput && (parseInt(kegiatanInput.value) || 0) === 0) {
                const count = document.querySelectorAll('input[name="program_pemberdayaan[]"]:checked').length;
                if (count > 0) {
                    kegiatanInput.value = count;
                }
            }
        });
    });

    // Mini Map Picker
    const initLat = parseFloat(latInput.value) || 0.65;
    const initLng = parseFloat(lngInput.value) || 122.50;

    const map = L.map('picker-map').setView([initLat, initLng], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(6);
        lngInput.value = pos.lng.toFixed(6);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(6);
        lngInput.value = e.latlng.lng.toFixed(6);
    });

    latInput.addEventListener('change', function() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    });

    lngInput.addEventListener('change', function() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    });
});
</script>
@endsection
