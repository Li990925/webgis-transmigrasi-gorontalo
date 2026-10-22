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
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 85px;
            padding-bottom: 14px;
        }

        .kop-logo {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 82px;
            height: 82px;
            object-fit: contain;
        }

        .kop-text {
            width: 100%;
            text-align: center;
            padding: 0 90px;
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

        @media (max-width: 640px) {
            .kop-surat {
                flex-direction: column;
                min-height: auto;
                text-align: center;
                gap: 12px;
            }
            .kop-logo {
                position: static;
                transform: none;
                margin: 0 auto;
            }
            .kop-text {
                padding: 0;
            }
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
            margin-top: 24px;
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

        /* Editable Signature Elements */
        .editable-field {
            cursor: text;
            transition: all 0.2s ease;
            position: relative;
            border-radius: 4px;
            padding: 1px 4px;
            display: inline-block;
            min-width: 40px;
        }

        .editable-field:hover {
            background-color: rgba(59, 130, 246, 0.08);
            outline: 1px dashed #3b82f6;
        }

        .editable-field:focus {
            background-color: #eff6ff;
            outline: 2px solid #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .sig-edit-hint {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 8px 14px;
            margin-top: 32px;
            margin-bottom: 8px;
            font-size: 8pt;
            color: #1e40af;
        }

        .btn-text-action {
            background: #ffffff;
            border: 1px solid #93c5fd;
            color: #1d4ed8;
            font-size: 8pt;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-text-action:hover {
            background: #dbeafe;
        }
        .btn-text-action.text-danger {
            color: #b91c1c;
            border-color: #fca5a5;
        }
        .btn-text-action.text-danger:hover {
            background: #fee2e2;
        }

        /* Signature Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 12px;
            max-width: 680px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(-8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-size: 11pt;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #64748b;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.15s;
        }
        .modal-close-btn:hover {
            color: #0f172a;
            background: #e2e8f0;
        }

        .modal-body {
            padding: 20px;
            font-size: 9.5pt;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 640px) {
            .modal-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-section-title {
            font-size: 9pt;
            font-weight: 700;
            color: #1e40af;
            background: #eff6ff;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group label {
            display: block;
            font-size: 8.5pt;
            font-weight: 600;
            color: #334155;
            margin-bottom: 4px;
        }

        .form-control {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 9pt;
            font-family: inherit;
            color: #0f172a;
            box-sizing: border-box;
            transition: all 0.15s;
        }
        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0 0 12px 12px;
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

            .no-print,
            .sig-edit-hint,
            .modal-overlay {
                display: none !important;
            }

            .editable-field,
            .editable-field:hover,
            .editable-field:focus {
                background: transparent !important;
                outline: none !important;
                border: none !important;
                box-shadow: none !important;
                cursor: default !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .kop-surat {
                min-height: 80px;
                padding-bottom: 8px;
            }

            .kop-logo {
                width: 76px;
                height: 76px;
            }

            .kop-text {
                padding: 0 84px;
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
        <div style="display:flex; gap:10px; align-items:center;">
            <button type="button" onclick="openSignatureModal()" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Edit Penandatangan
            </button>
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

        <!-- Helper banner on screen (hidden during print) -->
        <div class="sig-edit-hint no-print">
            <div style="display:flex; align-items:center; gap:8px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#2563eb;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span><strong>Bagian Pengesahan Dapat Diedit:</strong> Klik langsung pada teks nama, NIP, jabatan, atau tanggal di bawah untuk mengedit sebelum mencetak, atau gunakan form dialog.</span>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="button" onclick="openSignatureModal()" class="btn-text-action">Buka Form Edit</button>
                <button type="button" onclick="resetSignatures()" class="btn-text-action text-danger" title="Kembalikan ke data default">Reset Default</button>
            </div>
        </div>

        <!-- Blok Pengesahan Tanda Tangan -->
        <div class="signature-container">
            <div class="sig-block">
                <div class="editable-field" id="sig1_label" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">Mengetahui,</div>
                <div class="editable-field" id="sig1_jabatan" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit" style="font-weight:600; margin-bottom:4px;">Kepala Dinas Tenaga Kerja, ESDM dan Transmigrasi<br>Provinsi Gorontalo</div>
                <div class="sig-space"></div>
                <div class="sig-name editable-field" id="sig1_nama" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">Dr. H. Rusli Habibie, M.Si</div>
                <div class="sig-nip editable-field" id="sig1_nip" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">NIP. 19680512 199303 1 004</div>
            </div>

            <div class="sig-block">
                <div class="editable-field" id="sig2_date" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">Gorontalo, {{ $tanggalCetak }}</div>
                <div class="editable-field" id="sig2_jabatan" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit" style="font-weight:600; margin-bottom:4px;">Kepala Bidang Pengembangan Kawasan Transmigrasi<br>Dinas Nakertrans Provinsi Gorontalo</div>
                <div class="sig-space"></div>
                <div class="sig-name editable-field" id="sig2_nama" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">Ir. H. Marwan Monoarfa, M.M</div>
                <div class="sig-nip editable-field" id="sig2_nip" contenteditable="true" spellcheck="false" title="Klik langsung untuk mengedit">NIP. 19740918 200003 1 002</div>
            </div>
        </div>

    </div>

    <!-- Modal Dialog Edit Penandatangan -->
    <div id="signatureModal" class="modal-overlay" onclick="if(event.target===this) closeSignatureModal()">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#2563eb;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Edit Data Pengesahan & Penandatangan
                </div>
                <button type="button" class="modal-close-btn" onclick="closeSignatureModal()" title="Tutup">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-grid">
                    <!-- Kolom Kiri: Pihak Mengetahui -->
                    <div>
                        <div class="form-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Pihak Mengetahui (Kiri)
                        </div>
                        <div class="form-group">
                            <label for="modal_sig1_label">Keterangan / Frasa Pembuka</label>
                            <input type="text" id="modal_sig1_label" class="form-control" placeholder="Mengetahui,">
                        </div>
                        <div class="form-group">
                            <label for="modal_sig1_jabatan">Jabatan & Instansi</label>
                            <textarea id="modal_sig1_jabatan" rows="3" class="form-control" placeholder="Kepala Dinas Tenaga Kerja, ESDM dan Transmigrasi&#10;Provinsi Gorontalo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="modal_sig1_nama">Nama Lengkap & Gelar</label>
                            <input type="text" id="modal_sig1_nama" class="form-control" placeholder="Dr. H. Rusli Habibie, M.Si">
                        </div>
                        <div class="form-group">
                            <label for="modal_sig1_nip">NIP Pejabat</label>
                            <input type="text" id="modal_sig1_nip" class="form-control" placeholder="NIP. 19680512 199303 1 004">
                        </div>
                    </div>

                    <!-- Kolom Kanan: Pihak Penandatangan -->
                    <div>
                        <div class="form-section-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Pihak Penandatangan (Kanan)
                        </div>
                        <div class="form-group">
                            <label for="modal_sig2_date">Tempat & Tanggal Dokumen</label>
                            <input type="text" id="modal_sig2_date" class="form-control" placeholder="Gorontalo, 22 Oktober 2026">
                        </div>
                        <div class="form-group">
                            <label for="modal_sig2_jabatan">Jabatan & Instansi</label>
                            <textarea id="modal_sig2_jabatan" rows="3" class="form-control" placeholder="Kepala Bidang Pengembangan Kawasan Transmigrasi&#10;Dinas Nakertrans Provinsi Gorontalo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="modal_sig2_nama">Nama Lengkap & Gelar</label>
                            <input type="text" id="modal_sig2_nama" class="form-control" placeholder="Ir. H. Marwan Monoarfa, M.M">
                        </div>
                        <div class="form-group">
                            <label for="modal_sig2_nip">NIP Pejabat</label>
                            <input type="text" id="modal_sig2_nip" class="form-control" placeholder="NIP. 19740918 200003 1 002">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="resetSignatures()" class="btn-text-action text-danger" style="padding:6px 12px; font-size:9pt;">
                    Reset ke Default
                </button>
                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="closeSignatureModal()" class="btn" style="background:#e2e8f0; color:#334155;">
                        Batal
                    </button>
                    <button type="button" onclick="saveSignaturesFromModal()" class="btn btn-primary" style="background:#2563eb; color:#ffffff;">
                        Simpan & Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const STORAGE_KEY = 'webgis_transmigrasi_laporan_signatures_v1';
        const defaultSignatures = {
            sig1_label: 'Mengetahui,',
            sig1_jabatan: 'Kepala Dinas Tenaga Kerja, ESDM dan Transmigrasi<br>Provinsi Gorontalo',
            sig1_nama: 'Dr. H. Rusli Habibie, M.Si',
            sig1_nip: 'NIP. 19680512 199303 1 004',
            sig2_date: 'Gorontalo, {{ $tanggalCetak }}',
            sig2_jabatan: 'Kepala Bidang Pengembangan Kawasan Transmigrasi<br>Dinas Nakertrans Provinsi Gorontalo',
            sig2_nama: 'Ir. H. Marwan Monoarfa, M.M',
            sig2_nip: 'NIP. 19740918 200003 1 002'
        };

        function loadSignatures() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved) {
                    const data = JSON.parse(saved);
                    if (data.sig1_label) document.getElementById('sig1_label').innerText = data.sig1_label;
                    if (data.sig1_jabatan) document.getElementById('sig1_jabatan').innerHTML = data.sig1_jabatan;
                    if (data.sig1_nama) document.getElementById('sig1_nama').innerText = data.sig1_nama;
                    if (data.sig1_nip) document.getElementById('sig1_nip').innerText = data.sig1_nip;
                    if (data.sig2_date) document.getElementById('sig2_date').innerText = data.sig2_date;
                    if (data.sig2_jabatan) document.getElementById('sig2_jabatan').innerHTML = data.sig2_jabatan;
                    if (data.sig2_nama) document.getElementById('sig2_nama').innerText = data.sig2_nama;
                    if (data.sig2_nip) document.getElementById('sig2_nip').innerText = data.sig2_nip;
                }
            } catch (e) {
                console.error('Gagal memuat tanda tangan tersimpan:', e);
            }
        }

        function saveToStorage() {
            try {
                const data = {
                    sig1_label: document.getElementById('sig1_label').innerText.trim(),
                    sig1_jabatan: document.getElementById('sig1_jabatan').innerHTML.trim(),
                    sig1_nama: document.getElementById('sig1_nama').innerText.trim(),
                    sig1_nip: document.getElementById('sig1_nip').innerText.trim(),
                    sig2_date: document.getElementById('sig2_date').innerText.trim(),
                    sig2_jabatan: document.getElementById('sig2_jabatan').innerHTML.trim(),
                    sig2_nama: document.getElementById('sig2_nama').innerText.trim(),
                    sig2_nip: document.getElementById('sig2_nip').innerText.trim()
                };
                localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            } catch (e) {
                console.error('Gagal menyimpan tanda tangan:', e);
            }
        }

        function openSignatureModal() {
            document.getElementById('modal_sig1_label').value = document.getElementById('sig1_label').innerText.trim();
            document.getElementById('modal_sig1_jabatan').value = document.getElementById('sig1_jabatan').innerHTML
                .replace(/<br\s*[\/]?>/gi, '\n')
                .replace(/<\/?[^>]+(>|$)/g, '')
                .trim();
            document.getElementById('modal_sig1_nama').value = document.getElementById('sig1_nama').innerText.trim();
            document.getElementById('modal_sig1_nip').value = document.getElementById('sig1_nip').innerText.trim();

            document.getElementById('modal_sig2_date').value = document.getElementById('sig2_date').innerText.trim();
            document.getElementById('modal_sig2_jabatan').value = document.getElementById('sig2_jabatan').innerHTML
                .replace(/<br\s*[\/]?>/gi, '\n')
                .replace(/<\/?[^>]+(>|$)/g, '')
                .trim();
            document.getElementById('modal_sig2_nama').value = document.getElementById('sig2_nama').innerText.trim();
            document.getElementById('modal_sig2_nip').value = document.getElementById('sig2_nip').innerText.trim();

            document.getElementById('signatureModal').classList.add('active');
        }

        function closeSignatureModal() {
            document.getElementById('signatureModal').classList.remove('active');
        }

        function saveSignaturesFromModal() {
            document.getElementById('sig1_label').innerText = document.getElementById('modal_sig1_label').value.trim();
            document.getElementById('sig1_jabatan').innerHTML = document.getElementById('modal_sig1_jabatan').value.trim().replace(/\n/g, '<br>');
            document.getElementById('sig1_nama').innerText = document.getElementById('modal_sig1_nama').value.trim();
            document.getElementById('sig1_nip').innerText = document.getElementById('modal_sig1_nip').value.trim();

            document.getElementById('sig2_date').innerText = document.getElementById('modal_sig2_date').value.trim();
            document.getElementById('sig2_jabatan').innerHTML = document.getElementById('modal_sig2_jabatan').value.trim().replace(/\n/g, '<br>');
            document.getElementById('sig2_nama').innerText = document.getElementById('modal_sig2_nama').value.trim();
            document.getElementById('sig2_nip').innerText = document.getElementById('modal_sig2_nip').value.trim();

            saveToStorage();
            closeSignatureModal();
        }

        function resetSignatures() {
            if (confirm('Kembalikan data pengesahan tanda tangan ke pejabat default?')) {
                localStorage.removeItem(STORAGE_KEY);
                document.getElementById('sig1_label').innerText = defaultSignatures.sig1_label;
                document.getElementById('sig1_jabatan').innerHTML = defaultSignatures.sig1_jabatan;
                document.getElementById('sig1_nama').innerText = defaultSignatures.sig1_nama;
                document.getElementById('sig1_nip').innerText = defaultSignatures.sig1_nip;

                document.getElementById('sig2_date').innerText = defaultSignatures.sig2_date;
                document.getElementById('sig2_jabatan').innerHTML = defaultSignatures.sig2_jabatan;
                document.getElementById('sig2_nama').innerText = defaultSignatures.sig2_nama;
                document.getElementById('sig2_nip').innerText = defaultSignatures.sig2_nip;

                closeSignatureModal();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadSignatures();

            document.querySelectorAll('.editable-field').forEach(function(field) {
                field.addEventListener('input', saveToStorage);
                field.addEventListener('blur', saveToStorage);
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSignatureModal();
            }
        });
    </script>
</body>
</html>
