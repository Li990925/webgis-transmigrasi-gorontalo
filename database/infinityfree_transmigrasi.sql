-- WebGIS Transmigrasi Gorontalo MySQL Export for InfinityFree / Shared Hosting
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, "0001_01_01_000000_create_users_table", 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, "0001_01_01_000001_create_cache_table", 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, "0001_01_01_000002_create_jobs_table", 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, "2026_10_21_144602_create_kawasan_transmigrasis_table", 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, "2026_10_21_153001_create_audit_logs_table", 1);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT "operator_kabupaten",
  `kabupaten` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (1, "Super Admin Provinsi", "admin@gorontalo.go.id", "$2y$12$KXnRCg/BFqsPLNukZGaMB.Rk.ah9pRDCdB/pt4e.1FLFRIOxozfum", "super_admin", NULL, "Kepala Dinas Tenaga Kerja, ESDM & Transmigrasi Prov. Gorontalo", "2026-10-21 15:33:22", "2026-10-21 17:58:16");
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (21, "Kabupaten Boalemo", "operator.boalemo@gorontalo.go.id", "$2y$12$mZvGqr21VQxSrsGmzxMj4.CopfHeXdyLe.KAFx2a38AbT2/.3J27K", "operator_kabupaten", "Kabupaten Boalemo", "Pranata SIG Bidang Transmigrasi Disnakertrans Boalemo", "2026-10-21 17:38:40", "2026-10-21 17:58:16");
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (22, "Kabupaten Pohuwato", "operator.pohuwato@gorontalo.go.id", "$2y$12$ehkidj5OTAmf0V8LKI24d.OC3ZhKHfuESvzY90yPBaAa7HmUQ1/JO", "operator_kabupaten", "Kabupaten Pohuwato", "Staf Tata Ruang & Pertanahan Kawasan Transmigrasi Pohuwato", "2026-10-21 17:38:40", "2026-10-21 17:58:16");
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (4, "Kabupaten Gorontalo", "operator.gorontalo@gorontalo.go.id", "$2y$12$Zk/kM34G4UTa6WRopD4/ZeUKYjzbIAIDkExyQB6uY7jascKFZX7JW", "operator_kabupaten", "Kabupaten Gorontalo", "Analis Data Transmigrasi Disnakertrans Kab. Gorontalo", "2026-10-21 15:33:22", "2026-10-21 17:58:16");
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (5, "Kabupaten Gorontalo Utara", "operator.gorut@gorontalo.go.id", "$2y$12$Z8mgDkTFEIrtwXatdQe33.15Rw.8bX66mfi/jtM.OLO9Zei2uf0nm", "operator_kabupaten", "Kabupaten Gorontalo Utara", "Operator GIS & Wilayah Transmigrasi Disnakertrans Gorut", "2026-10-21 15:33:22", "2026-10-21 17:58:16");
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `kabupaten`, `jabatan`, `created_at`, `updated_at`) VALUES (6, "Eksekutif Pemprov Gorontalo", "eksekutif@gorontalo.go.id", "$2y$12$FuyTmjNOlElFQdEWjkv8/ejushJXKDr/m9tjW.W3p.HXiarKmK4Ou", "eksekutif", NULL, "Bappeda Litbang & Tim Koordinasi Penataan Ruang Daerah Prov. Gorontalo", "2026-10-21 15:33:22", "2026-10-21 17:58:16");

DROP TABLE IF EXISTS `kawasan_transmigrasis`;
CREATE TABLE `kawasan_transmigrasis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `kabupaten` varchar(255) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `desa` varchar(255) NOT NULL,
  `tahun_penempatan` smallint(5) unsigned NOT NULL,
  `jumlah_kk` int(10) unsigned NOT NULL,
  `jumlah_jiwa` int(10) unsigned NOT NULL,
  `kk_shm` int(10) unsigned NOT NULL,
  `kk_non_shm` int(10) unsigned NOT NULL,
  `persentase_shm` decimal(5,2) NOT NULL DEFAULT 0.00,
  `luas_ha` decimal(10,2) NOT NULL,
  `luas_shm_ha` decimal(10,2) NOT NULL DEFAULT 0.00,
  `luas_non_shm_ha` decimal(10,2) NOT NULL DEFAULT 0.00,
  `komoditas_unggulan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `asal_transmigran` varchar(255) DEFAULT NULL,
  `sarana_prasarana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status_perkembangan` varchar(255) NOT NULL DEFAULT "Berkembang",
  `skor_kemandirian` tinyint(3) unsigned NOT NULL DEFAULT 70,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `polygon_geojson` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `geom_point` point DEFAULT NULL,
  `geom_polygon` polygon DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kawasan_transmigrasis_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (100, "upt-banyumas-kabupaten-boalemo", "UPT Banyumas", "UPT", "Kabupaten Boalemo", "tilamuta", "banyu", 2026, 20, 200, 170, 30, 850, 20000, 170000, -150000, "[\"jagung\"]", NULL, NULL, "Berkembang", 80, 0.71375, 122.331395, "[[0.72875,122.316395],[0.72875,122.346395],[0.69875,122.346395],[0.69875,122.316395],[0.72875,122.316395]]", NULL, "2026-10-21 19:35:08", "2026-10-22 02:04:50");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (7, "upt-motilango-tibawa", "UPT Motilango (Tibawa)", "UPT", "Kabupaten Gorontalo", "Tibawa", "Motilango", 2014, 290, 1160, 250, 40, 86.21, 1650, 1422.5, 227.5, "[\"Padi Sawah Irigasi Teknis\",\"Cabai Rawit Malita\",\"Tomat\",\"Semangka\"]", "Jawa Timur (Banyuwangi, Lumajang), Jawa Tengah (Pati), Warga Sekitar Danau Limboto", "{\"kesehatan\":\"Puskesmas Pembantu Tibawa & Bidan Siaga\",\"pendidikan\":\"SDN 7 Tibawa & SMP Negeri Terbuka\",\"air_bersih\":\"Irigasi Tersier Bendungan & Air Bersih PAM\",\"listrik\":\"Listrik PLN 100% Handal\",\"akses_jalan\":\"Jalan Aspal Dekat Akses Bandara Jalaluddin (15 Menit)\",\"ekonomi\":\"Lumbung Pangan Desa, Dryer Padi 5 Ton\\/Hari, KUD Berkah\"}", "Mandiri", 84, 0.635, 122.845, "[[0.65,122.83],[0.652,122.865],[0.62,122.868],[0.618,122.832],[0.65,122.83]]", "Sentra hortikultura dan padi unggulan Kabupaten Gorontalo yang sangat produktif karena memiliki saluran irigasi teknis dan kedekatan akses logistik bandara.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (4, "ktm-malango-taluditi", "KTM Malango / Taluditi", "KTM", "Kabupaten Pohuwato", "Taluditi", "Panca Karsa, Tirto Asri, Makarti Jaya, Kalimas", 2009, 820, 3280, 680, 140, 82.93, 5600, 4644, 956, "[\"Kakao Fermentasi\",\"Minyak Atsiri Nilam\",\"Jagung\",\"Kelapa Dalam\"]", "Jawa Tengah (Klaten, Boyolali), Jawa Timur (Blitar, Malang), NTB (Lombok), TPS", "{\"kesehatan\":\"Puskesmas Perawatan Taluditi Siaga 24 Jam & Posyandu\",\"pendidikan\":\"3 Unit SD Negeri, SMP Negeri 1 Taluditi, SMA Negeri 1\",\"air_bersih\":\"Instalasi Pengolahan Air Minum (IPA) & Jaringan Pamsimas\",\"listrik\":\"Jaringan Transmisi Listrik PLN 100%\",\"akses_jalan\":\"Jalan Poros Aspal Hotmix Penghubung Trans Marisa\",\"ekonomi\":\"Terminal Agrobisnis, Unit Fermentasi Kakao, Pasar Desa\"}", "Mandiri", 85, 0.685, 121.682, "[[0.71,121.65],[0.715,121.71],[0.66,121.72],[0.655,121.66],[0.71,121.65]]", "Pusat kawasan transmigrasi perkebunan kakao dan nilam terbesar di Pohuwato dengan sertifikasi tanah Lahan Pekarangan (LP) dan Lahan Usaha (LU) yang hampir tuntas.", "2026-10-21 15:33:22", "2026-10-21 17:38:40");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, "ktm-pawonsari-wonosari", "KTM Pawonsari (Wonosari)", "KTM", "Kabupaten Boalemo", "Wonosari", "Sari Tani, Sukamaju, Harapan Tani", 2008, 680, 2720, 635, 45, 93.38, 4250, 3968.65, 281.35, "[\"Jagung Hibrida\",\"Tebu Industri\",\"Kelapa Sawit\",\"Peternakan Sapi\"]", "Jawa Timur (Kediri, Blitar), Jawa Tengah, DIY, TPS (Warga Lokal Boalemo)", "{\"kesehatan\":\"Puskesmas Pembantu Wonosari & 3 Poskesdes\",\"pendidikan\":\"SDN 04 Wonosari, SMP Negeri Satap 1, PAUD Melati\",\"air_bersih\":\"Jaringan SPAM Pamsimas & 4 Reservoir Gravitasi\",\"listrik\":\"Jaringan PLN 100% 24 Jam\",\"akses_jalan\":\"Jalan Poros Hotmix Aspal & Rabat Beton Usaha Tani\",\"ekonomi\":\"Gudang Silo Pengering Jagung, KUD Pawonsari, Pasar Mingguan\"}", "Mandiri Berdaya Saing", 88, 0.672, 122.375, "[[0.692,122.355],[0.695,122.395],[0.665,122.408],[0.65,122.378],[0.655,122.35],[0.692,122.355]]", "Kawasan Transmigrasi Mandiri (KTM) Pawonsari merupakan pusat pertumbuhan agribisnis jagung dan tebu percontohan di Kabupaten Boalemo dengan tingkat kepemilikan Sertifikat Hak Milik (SHM) yang sangat tinggi.", "2026-10-21 15:33:22", "2026-10-21 23:15:44");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (10, "upt-atinggola-pinontoyonga", "UPT Atinggola (Pinontoyonga)", "UPT", "Kabupaten Gorontalo Utara", "Atinggola", "Pinontoyonga Baru", 2018, 220, 880, 95, 125, 43.18, 1420, 613.2, 806.8, "[\"Padi Ladang\",\"Pisang Gapi Gorut\",\"Kayu Sengon Agroforestri\",\"Jagung\"]", "Jawa Tengah (Purworejo, Cilacap), Lampung, Warga Pesisir Gorut", "{\"kesehatan\":\"Poskesdes Pinontoyonga & Bidan Desa\",\"pendidikan\":\"SD Filial Atinggola\",\"air_bersih\":\"Embung Air Baku Pedesaan & Sumur Gali\",\"listrik\":\"PLN Masuk Desa\",\"akses_jalan\":\"Jalan Tani Perkerasan Batu Pecah\",\"ekonomi\":\"Sentra Pengepul Pisang Gapi & Kayu Rakyat\"}", "Tahap Pembinaan", 66, 0.912, 123.21, "[[0.925,123.195],[0.93,123.228],[0.898,123.23],[0.895,123.198],[0.925,123.195]]", "Permukiman penempatan baru di kawasan perbatasan Gorontalo Utara dengan Sulawesi Utara. Penerbitan sertifikasi hak milik sedang gencar disinergikan dengan Program PTSL Transmigrasi.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, "upt-pangea-tilamuta", "UPT Pangea (Tilamuta)", "UPT", "Kabupaten Boalemo", "Tilamuta", "Pangea Baru", 2012, 350, 1400, 250, 100, 71.43, 2150, 1535.75, 614.25, "[\"Jagung\",\"Kelapa Dalam\",\"Padi Gogo Ladang\",\"Pisang\"]", "Jawa Barat (Cianjur, Garut), Jawa Tengah (Banyumas), TPS Boalemo", "{\"kesehatan\":\"Poskesdes Pangea & Kunjungan Dokter Puskesmas\",\"pendidikan\":\"SD Negeri Pangea & Madrasah Diniyah\",\"air_bersih\":\"Mata Air Pegunungan & Sumur Bor\",\"listrik\":\"Jaringan PLN Masuk Desa\",\"akses_jalan\":\"Jalan Perkerasan Sirtu & Beton Lingkungan\",\"ekonomi\":\"Kelompok Usaha Bersama (KUBE) & Pengolahan Kopra\"}", "Berkembang", 74, 0.558, 122.412, "[[0.572,122.398],[0.575,122.428],[0.545,122.43],[0.54,122.402],[0.572,122.398]]", "Satuan permukiman produktif dengan lahan usaha yang sedang dalam akselerasi penerbitan sisa sertifikat hak milik oleh Kantor Pertanahan ATR/BPN Boalemo.", "2026-10-21 15:33:22", "2026-10-21 23:26:19");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (5, "upt-popayato-barat", "UPT Popayato Barat", "UPT", "Kabupaten Pohuwato", "Popayato Barat", "Butungale & Dudewulo", 2017, 275, 1100, 120, 155, 43.64, 1800, 785.5, 1014.5, "[\"Kelapa Sawit Rakyat\",\"Jagung\",\"Pisang Abaka\"]", "Jawa Tengah (Grobogan), Sulawesi Selatan (Bone, Sinjai), TPS Lokal", "{\"kesehatan\":\"Poskesdes Butungale & Ambulans Desa\",\"pendidikan\":\"SD Terpadu Popayato & PAUD\",\"air_bersih\":\"Pamsimas Gravitasi Gunung & Sumur Bor\",\"listrik\":\"Jaringan PLN Sub-Gardu Popayato\",\"akses_jalan\":\"Jalan Cor Beton Lingkungan & Jembatan Baja\",\"ekonomi\":\"Kemitraan Plasma Pabrik Kelapa Sawit\"}", "Tahap Pembinaan", 69, 0.523, 121.365, "[[0.54,121.35],[0.542,121.38],[0.51,121.385],[0.505,121.352],[0.54,121.35]]", "Kawasan perbatasan barat Gorontalo yang strategis, 120 KK telah menerima sertifikat SHM, sedangkan 155 KK sisa Lahan Usaha II sedang diproses bersama Kanwil BPN.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (6, "sp-lemito-wanggarasi", "SP Lemito / Wanggarasi", "SP", "Kabupaten Pohuwato", "Lemito", "Kenari Jaya", 2013, 240, 960, 195, 45, 81.25, 1550, 1259.4, 290.6, "[\"Jagung\",\"Padi Sawah Pasang Surut\",\"Ikan Air Tawar\"]", "Jawa Timur (Tulungagung), DIY (Bantul), Warga Eks-Bencana", "{\"kesehatan\":\"Polindes Kenari Jaya\",\"pendidikan\":\"SD Negeri 2 Lemito\",\"air_bersih\":\"Saluran Pipa Air Minum Desa\",\"listrik\":\"PLN 900VA \\/ 1300VA\",\"akses_jalan\":\"Jalan Tani Teraspal 70%\",\"ekonomi\":\"Penggilingan Padi Kelompok Tani\"}", "Berkembang Menuju Mandiri", 78, 0.545, 121.52, "[[0.56,121.505],[0.562,121.54],[0.53,121.542],[0.528,121.508],[0.56,121.505]]", "Sentra tanaman pangan padi dan jagung terpadu dengan capaian sertifikasi tanah yang tinggi mencapai 81,25%.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (9, "upt-tolinggula-cempaka-putih", "UPT Tolinggula (Cempaka Putih)", "UPT", "Kabupaten Gorontalo Utara", "Tolinggula", "Cempaka Putih", 2016, 310, 1240, 180, 130, 58.06, 1950, 1132.2, 817.8, "[\"Cengkeh\",\"Kelapa Kopra\",\"Perikanan Tangkap Laut\",\"Jagung\"]", "Jawa Timur (Pacitan, Ponorogo), Sulawesi Selatan (Luwu), TPS Pesisir Gorut", "{\"kesehatan\":\"Puskesmas Perawatan Tolinggula\",\"pendidikan\":\"SD Negeri Cempaka Putih, SMP Negeri Tolinggula\",\"air_bersih\":\"Jaringan Air Bersih Sumber Air Gravitasi\",\"listrik\":\"Jaringan PLN Kabel Udara & Sinyal 4G\",\"akses_jalan\":\"Jalan Jalur Trans Sulawesi Lintas Utara\",\"ekonomi\":\"Dermaga Perahu Nelayan, Koperasi Simpan Pinjam Tani\"}", "Berkembang", 73, 0.985, 122.254, "[[1.002,122.235],[1.005,122.275],[0.97,122.278],[0.968,122.24],[1.002,122.235]]", "Kawasan transmigrasi pesisir utara berbatasan dengan Laut Sulawesi. 180 KK sudah menerima SHM lahan pekarangan dan usaha I, sedangkan 130 KK lahan usaha II sedang tahapan pengukuran BPN.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (8, "upt-pulubala-bongomeme", "UPT Pulubala (Mulyonegoro)", "UPT", "Kabupaten Gorontalo", "Pulubala", "Toyidito & Mulyonegoro", 2011, 420, 1680, 380, 40, 90.48, 2400, 2171.5, 228.5, "[\"Gula Aren Semut Organik\",\"Jagung\",\"Peternakan Kambing Peranakan Etawa\"]", "D.I. Yogyakarta (Gunungkidul, Kulonprogo), Jawa Tengah (Wonogiri), TPS Lokal", "{\"kesehatan\":\"Pustu Pulubala, Posyandu Terintegrasi\",\"pendidikan\":\"SD Negeri 2 Pulubala (Akreditasi A) & SMP Negeri 4\",\"air_bersih\":\"Pipa SPAM Pedesaan & Sumber Mata Air Pegunungan\",\"listrik\":\"Jaringan Listrik PLN & Lampu Penerangan Jalan Umum\",\"akses_jalan\":\"Jalan Poros Penghubung Limboto-Paguyaman\",\"ekonomi\":\"Sentra Pengolahan Gula Aren Bersertifikat Halal & Ekspor\"}", "Mandiri Berkelanjutan", 87, 0.68, 122.752, "[[0.695,122.735],[0.7,122.775],[0.665,122.778],[0.662,122.738],[0.695,122.735]]", "Model keberhasilan transmigrasi berbasis komoditas khas lokal aren semut dan peternakan dengan persentase sertifikasi SHM tertinggi mencapai 90,48%.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (38, "upt-wonosari-baru", "UPT Wonosari Baru", "UPT", "Kabupaten Boalemo", "Wonosari", "Desa Sukamakmur", 2015, 300, 1200, 210, 90, 70, 1850.5, 1295.3, 555.2, "[\"Jagung\",\"Padi Sawah\",\"Kelapa Dalam\"]", "Jawa Timur, TPS Boalemo", NULL, "Berkembang", 75, 0.665, 122.38, NULL, "Kawasan permukiman produktif sentra jagung.", "2026-10-21 16:16:28", "2026-10-21 16:16:28");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (39, "sp-ayula-sejahtera", "SP Ayula Sejahtera", "SP", "Kabupaten Gorontalo", "Boliyohuto", "Desa Ayula", 2018, 200, 800, 140, 60, 70, 1200, 840, 360, "[\"Cokelat\",\"Jagung\",\"Sapi\"]", "Jawa Tengah, Lokal Gorontalo", NULL, "Berkembang", 72, 0.64, 122.68, NULL, "Permukiman transmigrasi mandiri dengan peternakan sapi.", "2026-10-21 16:16:28", "2026-10-21 16:16:28");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, "sp-dimito-wonosari", "SP Dimito (Wonosari)", "SP", "Kabupaten Boalemo", "Wonosari", "Dimito Jaya", 2015, 210, 840, 115, 95, 54.76, 1400, 766.6, 633.4, "[\"Jagung\",\"Kacang Tanah\",\"Peternakan Kambing\"]", "Jawa Timur, DIY, Penduduk Sekitar Hutan", "{\"kesehatan\":\"Polindes & Bidan Desa Standby\",\"pendidikan\":\"SD Filial & PAUD Tunas Bangsa\",\"air_bersih\":\"Bak Penampung Air Hujan & Pamsimas\",\"listrik\":\"PLN Pascabayar & Solar Home System\",\"akses_jalan\":\"Jalan Usaha Tani Tembus Jalan Provinsi\",\"ekonomi\":\"Lumbung Pakan & Koperasi Tani\"}", "Tahap Pembinaan Lanjutan", 68, 0.71, 122.32, "[[0.725,122.305],[0.728,122.338],[0.695,122.34],[0.692,122.308],[0.725,122.305]]", "Kawasan penyangga KTM Pawonsari yang fokus pada integrasi tanaman pangan dan peternakan dengan program sertifikasi redistribusi tanah BPN.", "2026-10-21 15:33:22", "2026-10-21 17:38:40");
INSERT INTO `kawasan_transmigrasis` (`id`, `slug`, `nama`, `tipe`, `kabupaten`, `kecamatan`, `desa`, `tahun_penempatan`, `jumlah_kk`, `jumlah_jiwa`, `kk_shm`, `kk_non_shm`, `persentase_shm`, `luas_ha`, `luas_shm_ha`, `luas_non_shm_ha`, `komoditas_unggulan`, `asal_transmigran`, `sarana_prasarana`, `status_perkembangan`, `skor_kemandirian`, `latitude`, `longitude`, `polygon_geojson`, `deskripsi`, `created_at`, `updated_at`) VALUES (11, "sp-sumalata-timur", "SP Sumalata Timur", "SP", "Kabupaten Gorontalo Utara", "Sumalata Timur", "Bulan Jaya", 2015, 230, 920, 155, 75, 67.39, 1520, 1024.3, 495.7, "[\"Jagung Hibrida\",\"Kelapa Dalam\",\"Kakao\"]", "Jawa Timur (Trenggalek), Jawa Barat (Subang), TPS Lokal", "{\"kesehatan\":\"Posyandu Terpadu & Poskesdes\",\"pendidikan\":\"SD Negeri Bulan Jaya\",\"air_bersih\":\"Mata Air Gravitasi Hutan Lindung\",\"listrik\":\"Listrik PLN 100%\",\"akses_jalan\":\"Jalan Lingkungan Rabat Beton\",\"ekonomi\":\"Koperasi Tani & Pengeringan Jagung\"}", "Berkembang", 71, 0.935, 122.62, "[[0.95,122.605],[0.952,122.638],[0.92,122.64],[0.918,122.608],[0.95,122.605]]", "Kawasan agribisnis jagung di perbukitan Sumalata Timur dengan kemajuan progres sertifikasi hak milik tanah pekarangan dan usaha.", "2026-10-21 15:33:22", "2026-10-21 15:33:22");

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `auditable_id` bigint(20) unsigned DEFAULT NULL,
  `kawasan_nama` varchar(255) DEFAULT NULL,
  `kabupaten` varchar(255) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  KEY `audit_logs_kabupaten_created_at_index` (`kabupaten`,`created_at`),
  KEY `audit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
