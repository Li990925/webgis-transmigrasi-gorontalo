<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Kawasan Transmigrasi Provinsi Gorontalo</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Source+Serif+4:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4c1d95;
            --primary-dark: #3b0764;
            --accent: #eab308;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #0f172a;
            background-color: #f8fafc;
            line-height: 1.45;
            font-size: 11pt;
            padding: 24px;
        }

        .paper-sheet {
            max-width: 1080px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 48px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Toolbar Top */
        .toolbar {
            max-width: 1080px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #4c1d95;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: #eab308;
            color: #3b0764;
            font-weight: 700;
        }
        .btn-primary:hover {
            background: #facc15;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Kop Surat Resmi */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 24px;
            padding-bottom: 14px;
            position: relative;
        }

        .kop-logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            border-radius: 8px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-pemprov {
            font-size: 14pt;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-dinas {
            font-size: 16pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: var(--primary);
            text-transform: uppercase;
            margin: 2px 0;
        }

        .kop-alamat {
            font-size: 9.5pt;
            color: var(--slate-600);
            line-height: 1.35;
        }

        .kop-divider {
            border: 0;
            border-top: 3px double #0f172a;
            margin: 8px 0 24px 0;
        }

        /* Header Laporan */
        .report-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .report-title {
            font-size: 13pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .report-subtitle {
            font-size: 10pt;
            color: var(--slate-600);
            font-weight: 500;
        }

        /* KPI Executive Grid */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .kpi-card {
            border: 1px solid var(--slate-200);
            border-radius: 6px;
            padding: 10px 14px;
            background: #f8fafc;
        }

        .kpi-label {
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--slate-600);
            font-weight: 600;
        }

        .kpi-value {
            font-size: 16pt;
            font-weight: 800;
            color: var(--primary);
            margin: 2px 0;
        }

        .kpi-sub {
            font-size: 8pt;
            color: #64748b;
        }

        /* Section Headings */
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
            margin: 20px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 1.5px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f1f5f9;
            color: #0f172a;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .badge-shm {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 7.5pt;
        }
        .badge-high { background: #dcfce7; color: #166534; }
        .badge-med { background: #fef3c7; color: #92400e; }
        .badge-low { background: #fee2e2; color: #991b1b; }

        /* Clustering Section */
        .cluster-box {
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--primary);
            border-radius: 4px;
            padding: 10px 14px;
            margin-bottom: 10px;
            background: #ffffff;
            font-size: 9pt;
        }
        .cluster-box.p1 { border-left-color: #ef4444; }
        .cluster-box.p2 { border-left-color: #f59e0b; }
        .cluster-box.p3 { border-left-color: #10b981; }

        /* Signature Block */
        .signature-container {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            page-break-inside: avoid;
        }

        .sig-block {
            text-align: center;
            font-size: 9.5pt;
        }

        .sig-space {
            height: 70px;
        }

        .sig-name {
            font-weight: 700;
            text-decoration: underline;
            color: #0f172a;
        }

        .sig-nip {
            font-size: 8.5pt;
            color: var(--slate-600);
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
                font-size: 10pt;
            }

            .paper-sheet {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            @page {
                size: A4 portrait;
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (Hidden during print) -->
    <div class="toolbar no-print">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ Auth::check() ? route('admin.dashboard') : route('beranda') }}" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali ke Panel Admin
            </a>
            <span style="font-size:13px; color:#cbd5e1;">Format Resmi Cetak Dokumen / Simpan PDF</span>
        </div>
        <div style="display:flex; gap:10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak / Simpan PDF (Ctrl+P)
            </button>
        </div>
    </div>

    <!-- Official Paper Sheet -->
    <div class="paper-sheet">

        <!-- Kop Surat Resmi Pemerintahan -->
        <div class="kop-surat">
            <img src="{{ asset('images/logo.png') }}" alt="Lambang Daerah Provinsi Gorontalo" class="kop-logo" onerror="this.style.display='none'">
            <div class="kop-text">
                <div class="kop-pemprov">Pemerintah Provinsi Gorontalo</div>
                <div class="kop-dinas">Dinas Tenaga Kerja, ESDM dan Transmigrasi</div>
                <div class="kop-alamat">
                    Kompleks Perkantoran Blok D, Jl. Sapta Marga, Kota Gorontalo, Gorontalo 96115<br>
                    Pos-el: disnakertrans@gorontaloprov.go.id | Laman Resmi: webgis-transmigrasi.gorontaloprov.go.id
                </div>
            </div>
            <img src="{{ asset('images/logo.png') }}" alt="Lambang Daerah Provinsi Gorontalo" class="kop-logo" onerror="this.style.display='none'">
        </div>

        <hr class="kop-divider">

        <!-- Header Laporan -->
        <div class="report-header">
            <div class="report-title">
                Laporan Eksekutif Perkembangan dan Legalitas Hak Milik (SHM)<br>
                Kawasan Transmigrasi Provinsi Gorontalo
            </div>
            <div class="report-subtitle">
                Cakupan Wilayah: {{ $kabupatenFilter && $kabupatenFilter !== 'all' ? $kabupatenFilter : 'Seluruh Wilayah (Kabupaten Boalemo, Pohuwato, Gorontalo, Gorontalo Utara)' }} | Data per {{ $tanggalCetak }}
            </div>
        </div>

        <!-- KPI Ringkasan Provinsi -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-label">Total Permukiman</div>
                <div class="kpi-value">{{ $totalKawasan }}</div>
                <div class="kpi-sub">KTM, UPT & SP Binaan</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Total Penduduk</div>
                <div class="kpi-value">{{ number_format($totalKk) }} <span style="font-size:10pt; font-weight:600;">KK</span></div>
                <div class="kpi-sub">{{ number_format($totalJiwa) }} Jiwa Transmigran</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Realisasi SHM</div>
                <div class="kpi-value" style="color:#059669;">{{ $persentaseShm }}%</div>
                <div class="kpi-sub">{{ number_format($totalKkShm) }} KK Sudah Bersertifikat</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-label">Backlog Sertifikasi</div>
                <div class="kpi-value" style="color:#d97706;">{{ number_format($totalKkNonShm) }} <span style="font-size:10pt; font-weight:600;">KK</span></div>
                <div class="kpi-sub">{{ number_format($totalLuasNonShmHa, 1) }} Ha Belum Bersertifikat</div>
            </div>
        </div>

        <!-- Tabel Detail Permukiman -->
        <div class="section-title">
            <span>I. DATA TABULAR PERMUKIMAN DAN KEPEMILIKAN SERTIFIKAT (SHM)</span>
            <span style="font-size:8.5pt; font-weight:500; color:#64748b;">(Satuan: KK & Hektar)</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 25px;" class="text-center">No</th>
                    <th>Nama Kawasan</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan / Desa</th>
                    <th class="text-center">Tahun</th>
                    <th class="text-right">Jml KK</th>
                    <th class="text-right">KK SHM</th>
                    <th class="text-right">Belum SHM</th>
                    <th class="text-center">% SHM</th>
                    <th class="text-right">Luas (Ha)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settlements as $index => $item)
                    @php
                        $badgeClass = $item->persentase_shm >= 75 ? 'badge-high' : ($item->persentase_shm >= 50 ? 'badge-med' : 'badge-low');
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $item->nama }}</strong> ({{ $item->tipe }})</td>
                        <td>{{ str_replace('Kabupaten ', '', $item->kabupaten) }}</td>
                        <td>{{ $item->kecamatan }} - {{ Str::limit($item->desa, 20) }}</td>
                        <td class="text-center">{{ $item->tahun_penempatan }}</td>
                        <td class="text-right">{{ number_format($item->jumlah_kk) }}</td>
                        <td class="text-right" style="color:#166534; font-weight:600;">{{ number_format($item->kk_shm) }}</td>
                        <td class="text-right" style="color:#991b1b; font-weight:600;">{{ number_format($item->kk_non_shm) }}</td>
                        <td class="text-center">
                            <span class="badge-shm {{ $badgeClass }}">{{ $item->persentase_shm }}%</span>
                        </td>
                        <td class="text-right">{{ number_format($item->luas_ha, 1) }}</td>
                        <td>{{ $item->status_perkembangan }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#f1f5f9; font-weight:700;">
                    <td colspan="5" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right">{{ number_format($totalKk) }}</td>
                    <td class="text-right" style="color:#166534;">{{ number_format($totalKkShm) }}</td>
                    <td class="text-right" style="color:#991b1b;">{{ number_format($totalKkNonShm) }}</td>
                    <td class="text-center"><span class="badge-shm badge-high">{{ $persentaseShm }}%</span></td>
                    <td class="text-right">{{ number_format($totalLuasHa, 1) }}</td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>

        <!-- Rekomendasi Klaster Spasial Prioritas ATR/BPN -->
        <div class="section-title">
            <span>II. HASIL KLASTERISASI SPASIAL & REKOMENDASI INTERVENSI KE KEMENTERIAN ATR/BPN</span>
        </div>

        <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:6px; padding:7px 12px; margin-bottom:12px; font-size:8pt; color:#334155; line-height:1.4;">
            <strong>Metode Penilaian:</strong> Simple Additive Weighting (SAW) Multi-Kriteria berbasis pembobotan terukur: 
            <strong>Kesenjangan Non-SHM (Bobot 40%)</strong>, <strong>Volume KK Terdampak (Bobot 35%)</strong>, dan <strong>Luas Lahan Belum Bersertifikat (Bobot 25%)</strong>.
        </div>

        @foreach ($clustering['clusters'] as $cKey => $cluster)
            <div class="cluster-box {{ $cKey == 'prioritas_1' ? 'p1' : ($cKey == 'prioritas_2' ? 'p2' : 'p3') }}">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                    <strong style="color: {{ $cluster['badge_color'] }}; font-size:9.5pt;">{{ $cluster['label'] }}</strong>
                    <span style="font-size:8pt; font-weight:600; color:#475569;">
                        {{ $cluster['total_kawasan'] }} Kawasan | {{ number_format($cluster['total_kk_non_shm']) }} KK Non-SHM | {{ number_format($cluster['total_luas_non_shm_ha'], 1) }} Ha
                    </span>
                </div>
                <div style="font-size:8.5pt; color:#334155; line-height:1.4;">
                    <strong>Rekomendasi Aksi:</strong> {{ $cluster['rekomendasi'] }}
                </div>
                <div style="font-size:8pt; color:#64748b; margin-top:4px;">
                    <strong>Wilayah Tercakup:</strong> 
                    @foreach ($cluster['items'] as $it)
                        {{ $it['nama'] }} ({{ str_replace('Kabupaten ', '', $it['kabupaten']) }}){{ ! $loop->last ? ', ' : '.' }}
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Blok Pengesahan Tanda Tangan -->
        <div class="signature-container">
            <div class="sig-block">
                <div>Mengetahui,</div>
                <div style="font-weight:600; margin-bottom:4px;">Kepala Dinas Tenaga Kerja, ESDM dan Transmigrasi<br>Provinsi Gorontalo</div>
                <div class="sig-space"></div>
                <div class="sig-name">Dr. H. Rusli Habibie, M.Si</div>
                <div class="sig-nip">NIP. 19680512 199303 1 004</div>
            </div>

            <div class="sig-block">
                <div>Gorontalo, {{ $tanggalCetak }}</div>
                <div style="font-weight:600; margin-bottom:4px;">Kepala Bidang Pengembangan Kawasan Transmigrasi<br>Dinas Nakertrans Provinsi Gorontalo</div>
                <div class="sig-space"></div>
                <div class="sig-name">Ir. H. Marwan Monoarfa, M.M</div>
                <div class="sig-nip">NIP. 19740918 200003 1 002</div>
            </div>
        </div>

    </div>

</body>
</html>
