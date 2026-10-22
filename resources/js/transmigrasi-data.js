/**
 * Data Spasial & Informasi Kawasan Transmigrasi Provinsi Gorontalo
 * Sistem Koordinat: WGS84 (EPSG:4326)
 */

export const transmigrasiData = [
    {
        id: "ktm-pawonsari",
        nama: "KTM Pawonsari (Wonosari)",
        tipe: "KTM",
        tipeLengkap: "Kawasan Transmigrasi Mandiri",
        kabupaten: "Boalemo",
        kecamatan: "Wonosari",
        desa: "Sari Tani, Sukamaju, Harapan",
        tahunPenempatan: 2008,
        luasHa: 4250,
        jumlahKK: 680,
        jumlahJiwa: 2720,
        asalTransmigran: "Jawa Timur, Jawa Tengah, D.I. Yogyakarta, TPS (Lokal)",
        komoditasUnggulan: ["Jagung Hibrida", "Tebu", "Kelapa Sawit", "Sapi Potong"],
        statusPerkembangan: "Mandiri Berdaya Saing",
        skorKemandirian: 88,
        statusWarna: "#10b981", // emerald
        fasilitas: [
            { nama: "Puskesmas Pembantu Wonosari", icon: "activity", status: "Aktif Baik" },
            { nama: "SDN 04 Wonosari & SMP Satap", icon: "book-open", status: "Lengkap" },
            { nama: "Silo Pengering & Gudang Jagung", icon: "warehouse", status: "Operasional" },
            { nama: "Jaringan Listrik PLN 100%", icon: "zap", status: "Teraliri" },
            { nama: "Jalan Usaha Tani Hotmix & Rabat", icon: "milestone", status: "85% Teraspal" },
            { nama: "Koperasi Unit Desa (KUD) Pawonsari", icon: "shopping-bag", status: "Aktif" }
        ],
        deskripsi: "Kawasan Transmigrasi Mandiri (KTM) Pawonsari merupakan model kawasan transmigrasi percontohan terpadu di Provinsi Gorontalo yang berfokus pada sentra agribisnis jagung dan industri gula tebu.",
        center: [0.6720, 122.3750],
        polygon: [
            [0.6920, 122.3550],
            [0.6950, 122.3950],
            [0.6650, 122.4080],
            [0.6500, 122.3780],
            [0.6550, 122.3500],
            [0.6920, 122.3550]
        ]
    },
    {
        id: "upt-pangea",
        nama: "UPT Pangea (Tilamuta)",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman Transmigrasi",
        kabupaten: "Boalemo",
        kecamatan: "Tilamuta",
        desa: "Pangea Baru",
        tahunPenempatan: 2012,
        luasHa: 2150,
        jumlahKK: 350,
        jumlahJiwa: 1400,
        asalTransmigran: "Jawa Barat, Jawa Tengah, Warga Lokal Boalemo",
        komoditasUnggulan: ["Jagung", "Kelapa Dalam", "Padi Ladang"],
        statusPerkembangan: "Berkembang",
        skorKemandirian: 74,
        statusWarna: "#0284c7", // sky
        fasilitas: [
            { nama: "Poskesdes Pangea", icon: "activity", status: "Aktif" },
            { nama: "SD Negeri Permukiman Trans", icon: "book-open", status: "Aktif" },
            { nama: "Balai Pertemuan Serbaguna", icon: "home", status: "Baik" },
            { nama: "Jaringan Pamsimas Air Bersih", icon: "droplets", status: "Tersedia" }
        ],
        deskripsi: "Permukiman transmigrasi yang berorientasi pada ketahanan pangan dan perkebunan kelapa terintegrasi dengan aksesibilitas langsung ke ibukota kabupaten di Tilamuta.",
        center: [0.5580, 122.4120],
        polygon: [
            [0.5720, 122.3980],
            [0.5750, 122.4280],
            [0.5450, 122.4300],
            [0.5400, 122.4020],
            [0.5720, 122.3980]
        ]
    },
    {
        id: "ktm-taluditi",
        nama: "KTM Taluditi / Malango",
        tipe: "KTM",
        tipeLengkap: "Kawasan Transmigrasi Mandiri",
        kabupaten: "Pohuwato",
        kecamatan: "Taluditi",
        desa: "Panca Karsa, Tirto Asri, Makarti",
        tahunPenempatan: 2009,
        luasHa: 5600,
        jumlahKK: 820,
        jumlahJiwa: 3280,
        asalTransmigran: "Jawa Tengah, Jawa Timur, D.I. Yogyakarta, NTB, TPS",
        komoditasUnggulan: ["Kakao Fermentasi", "Jagung", "Minyak Nilam", "Kelapa"],
        statusPerkembangan: "Mandiri",
        skorKemandirian: 84,
        statusWarna: "#10b981",
        fasilitas: [
            { nama: "Terminal Agrobisnis Taluditi", icon: "truck", status: "Beroperasi" },
            { nama: "Puskesmas Perawatan Taluditi", icon: "activity", status: "24 Jam" },
            { nama: "Sentra Pengolahan Kakao & Nilam", icon: "cpu", status: "Ekspor/Lokal" },
            { nama: "Solar Home System & Jaringan PLN", icon: "zap", status: "Stabil" },
            { nama: "Pasar Mingguan Transmigran", icon: "shopping-bag", status: "Ramai" }
        ],
        deskripsi: "Sentra perkebunan kakao dan nilam terbesar di kawasan barat Gorontalo, dengan komitmen agrikultur ramah lingkungan dan kemandirian ekonomi tinggi.",
        center: [0.6850, 121.6820],
        polygon: [
            [0.7100, 121.6500],
            [0.7150, 121.7100],
            [0.6600, 121.7200],
            [0.6550, 121.6600],
            [0.7100, 121.6500]
        ]
    },
    {
        id: "upt-popayato",
        nama: "UPT Popayato Barat",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman",
        kabupaten: "Pohuwato",
        kecamatan: "Popayato Barat",
        desa: "Butungale",
        tahunPenempatan: 2017,
        luasHa: 1800,
        jumlahKK: 275,
        jumlahJiwa: 1100,
        asalTransmigran: "Jawa Tengah, Sulawesi Selatan, TPS Lokal",
        komoditasUnggulan: ["Kelapa Sawit", "Jagung", "Pisang Abaka"],
        statusPerkembangan: "Tahap Pembinaan Lanjutan",
        skorKemandirian: 69,
        statusWarna: "#f59e0b", // amber
        fasilitas: [
            { nama: "Pamsimas Mata Air Pegunungan", icon: "droplets", status: "Berfungsi" },
            { nama: "Sekolah Dasar Terpadu", icon: "book-open", status: "Aktif" },
            { nama: "Kantor Pembina Transmigrasi", icon: "briefcase", status: "Aktif" }
        ],
        deskripsi: "Kawasan strategis perbatasan antara Provinsi Gorontalo dan Sulawesi Tengah, berperan sebagai benteng ketahanan pangan dan pusat pertumbuhan baru.",
        center: [0.5230, 121.3650],
        polygon: [
            [0.5400, 121.3500],
            [0.5420, 121.3800],
            [0.5100, 121.3850],
            [0.5050, 121.3520],
            [0.5400, 121.3500]
        ]
    },
    {
        id: "upt-motilango",
        nama: "UPT Motilango (Tibawa)",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman Transmigrasi",
        kabupaten: "Gorontalo",
        kecamatan: "Tibawa",
        desa: "Motilango",
        tahunPenempatan: 2014,
        luasHa: 1650,
        jumlahKK: 290,
        jumlahJiwa: 1160,
        asalTransmigran: "Jawa Timur, Jawa Tengah, Penduduk Lokal",
        komoditasUnggulan: ["Padi Sawah Irigasi", "Cabai Rawit", "Tomat", "Semangka"],
        statusPerkembangan: "Mandiri",
        skorKemandirian: 82,
        statusWarna: "#10b981",
        fasilitas: [
            { nama: "Jaringan Irigasi Bendungan", icon: "shuffle", status: "Mengalir Baik" },
            { nama: "Lumbung Pangan & Dryer Padi", icon: "warehouse", status: "Kapasitas 100 Ton" },
            { nama: "Balai Pertemuan Tani", icon: "users", status: "Aktif" },
            { nama: "Akses Dekat Bandara Jalaluddin (15 Menit)", icon: "navigation", status: "Sangat Strategis" }
        ],
        deskripsi: "Permukiman agropolitan produktif yang menyuplai kebutuhan beras dan hortikultura segar untuk kawasan aglomerasi Kota Gorontalo dan sekitarnya.",
        center: [0.6350, 122.8450],
        polygon: [
            [0.6500, 122.8300],
            [0.6520, 122.8650],
            [0.6200, 122.8680],
            [0.6180, 122.8320],
            [0.6500, 122.8300]
        ]
    },
    {
        id: "upt-pulubala",
        nama: "UPT Pulubala (Bongomeme)",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman Mandiri",
        kabupaten: "Gorontalo",
        kecamatan: "Pulubala",
        desa: "Toyidito, Mulyonegoro",
        tahunPenempatan: 2011,
        luasHa: 2400,
        jumlahKK: 420,
        jumlahJiwa: 1680,
        asalTransmigran: "D.I. Yogyakarta, Jawa Tengah, Warga Eks-Bencana",
        komoditasUnggulan: ["Gula Aren Semut", "Jagung", "Ternak Kambing PE"],
        statusPerkembangan: "Mandiri",
        skorKemandirian: 85,
        statusWarna: "#10b981",
        fasilitas: [
            { nama: "Sentra Olahan Gula Aren Higienis", icon: "box", status: "Sertifikasi Halal & BPOM" },
            { nama: "SDN 2 Pulubala", icon: "book-open", status: "Akreditasi A" },
            { nama: "Puskesmas Pembantu", icon: "activity", status: "Layanan Lengkap" }
        ],
        deskripsi: "Sentra produksi olahan gula aren semut berkualitas ekspor yang dikembangkan oleh kelompok tani transmigran dengan integrasi ternak perah.",
        center: [0.6800, 122.7520],
        polygon: [
            [0.6950, 122.7350],
            [0.7000, 122.7750],
            [0.6650, 122.7780],
            [0.6620, 122.7380],
            [0.6950, 122.7350]
        ]
    },
    {
        id: "upt-tolinggula",
        nama: "UPT Tolinggula (Cempaka Putih)",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman Pesisir & Perbatasan",
        kabupaten: "Gorontalo Utara",
        kecamatan: "Tolinggula",
        desa: "Cempaka Putih",
        tahunPenempatan: 2016,
        luasHa: 1950,
        jumlahKK: 310,
        jumlahJiwa: 1240,
        asalTransmigran: "Jawa Timur, Sulawesi Selatan, TPS Lokal",
        komoditasUnggulan: ["Cengkeh", "Kelapa Kopra", "Perikanan Tangkap", "Jagung"],
        statusPerkembangan: "Berkembang",
        skorKemandirian: 72,
        statusWarna: "#0284c7",
        fasilitas: [
            { nama: "Jalan Hotmix Penghubung Trans-Sulawesi", icon: "milestone", status: "Kondisi Prima" },
            { nama: "Puskesmas Tolinggula", icon: "activity", status: "Siaga 24 Jam" },
            { nama: "Menara BTS Seluler 4G LTE", icon: "radio", status: "Sinyal Kuat" }
        ],
        deskripsi: "Daerah transmigrasi berkarakter maritim-agraris di pesisir utara Gorontalo yang berbatasan langsung dengan Laut Sulawesi.",
        center: [0.9850, 122.2540],
        polygon: [
            [1.0020, 122.2350],
            [1.0050, 122.2750],
            [0.9700, 122.2780],
            [0.9680, 122.2400],
            [1.0020, 122.2350]
        ]
    },
    {
        id: "upt-atinggola",
        nama: "UPT Atinggola (Pinontoyonga)",
        tipe: "SP",
        tipeLengkap: "Satuan Permukiman",
        kabupaten: "Gorontalo Utara",
        kecamatan: "Atinggola",
        desa: "Pinontoyonga Baru",
        tahunPenempatan: 2018,
        luasHa: 1420,
        jumlahKK: 220,
        jumlahJiwa: 880,
        asalTransmigran: "Jawa Tengah, Lampung, Warga Pesisir Gorut",
        komoditasUnggulan: ["Padi Ladang", "Pisang Gapi", "Kayu Sengon"],
        statusPerkembangan: "Tahap Pembinaan",
        skorKemandirian: 66,
        statusWarna: "#f59e0b",
        fasilitas: [
            { nama: "Embung Penampung Air Baku", icon: "droplet", status: "Fungsional" },
            { nama: "Gedung Balai Dusun", icon: "home", status: "Baru Direnovasi" },
            { nama: "Posyandu Balita & Lansia", icon: "heart", status: "Rutin Bulanan" }
        ],
        deskripsi: "Sentra hortikultura pisang dan pertanian tanaman pangan perbukitan dengan sistem konservasi tanah dan agroforestri modern.",
        center: [0.9120, 123.2100],
        polygon: [
            [0.9250, 123.1950],
            [0.9300, 123.2280],
            [0.8980, 123.2300],
            [0.8950, 123.1980],
            [0.9250, 123.1950]
        ]
    },
    {
        id: "upt-pinogu",
        nama: "UPT Suwawa Timur / Pinogu",
        tipe: "SP-K",
        tipeLengkap: "Kawasan Khusus Komoditas Organik",
        kabupaten: "Bone Bolango",
        kecamatan: "Suwawa Timur",
        desa: "Pinogu Indah",
        tahunPenempatan: 2013,
        luasHa: 1850,
        jumlahKK: 260,
        jumlahJiwa: 1040,
        asalTransmigran: "Jawa Tengah, D.I. Yogyakarta, TPS Suwawa",
        komoditasUnggulan: ["Kopi Organik Pinogu", "Kakao Specialty", "Vanili"],
        statusPerkembangan: "Mandiri Berkelanjutan",
        skorKemandirian: 86,
        statusWarna: "#10b981",
        fasilitas: [
            { nama: "Unit Pengolahan & Roasting Kopi Pinogu", icon: "coffee", status: "Sertifikasi Organik" },
            { nama: "PLTMH Mikrohidro Tenaga Air", icon: "zap", status: "Mandiri Energi Ramah Lingkungan" },
            { nama: "Klinik Pratama Desa", icon: "activity", status: "Tersedia Bidan & Perawat" }
        ],
        deskripsi: "Kawasan dataran tinggi beriklim sejuk, rumah bagi varietas legendaris 'Kopi Robusta & Liberika Pinogu' yang tersertifikasi Indikasi Geografis (IG) Nasional.",
        center: [0.5820, 123.3250],
        polygon: [
            [0.5980, 123.3080],
            [0.6020, 123.3420],
            [0.5650, 123.3450],
            [0.5620, 123.3100],
            [0.5980, 123.3080]
        ]
    }
];

export const kabupatenSummary = [
    { nama: "Boalemo", kawasanCount: 2, totalKK: 1030, totalJiwa: 4120, totalLuasHa: 6400, color: "#10b981" },
    { nama: "Pohuwato", kawasanCount: 2, totalKK: 1095, totalJiwa: 4380, totalLuasHa: 7400, color: "#06b6d4" },
    { nama: "Gorontalo", kawasanCount: 2, totalKK: 710, totalJiwa: 2840, totalLuasHa: 4050, color: "#3b82f6" },
    { nama: "Gorontalo Utara", kawasanCount: 2, totalKK: 530, totalJiwa: 2120, totalLuasHa: 3370, color: "#8b5cf6" },
    { nama: "Bone Bolango", kawasanCount: 1, totalKK: 260, totalJiwa: 1040, totalLuasHa: 1850, color: "#f59e0b" }
];
