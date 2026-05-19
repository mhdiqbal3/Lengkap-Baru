-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2026 at 07:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usn`
--

-- --------------------------------------------------------

--
-- Table structure for table `agendas`
--

CREATE TABLE `agendas` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publikasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agendas`
--

INSERT INTO `agendas` (`id`, `judul`, `slug`, `penulis`, `konten`, `thumbnail`, `tanggal`, `status`, `created_at`, `updated_at`) VALUES
(11, 'Pelatihan Satuan Tugas Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi', 'pelatihan-satuan-tugas-pencegahan-dan-penanganan-kekerasan-di-lingkungan-perguruan-tinggi-1779069001', 'Admin', '<p>tes</p>', 'assets/agenda/1779069001_images.jpg', '2026-05-18', 'publikasi', '2026-05-17 17:50:01', '2026-05-17 17:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `arsips`
--

CREATE TABLE `arsips` (
  `id` bigint NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `jenis_kegiatan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `status_publikasi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `arsips`
--

INSERT INTO `arsips` (`id`, `judul_kegiatan`, `jenis_kegiatan`, `tanggal`, `lokasi`, `status_publikasi`, `deskripsi`, `dokumentasi`, `created_at`, `updated_at`) VALUES
(4, 'Poster Kekerasan', 'rilis_poster', '2026-04-17', 'Auditorium', 'poster', 'Tindakan Kekerasan adalah keji', 'assets/kegiatan/1776412194_poster.jpg', '2026-04-16 23:49:54', '2026-04-16 23:49:54'),
(5, 'Seminar Anti Kekerasan', 'seminar', '2026-04-17', 'Auditorium', 'sosialisasi', 'Sebagai Bentuk Ketegasan Satuan Tugas Dalam Memberantas Kekerasan Seksual Di kampus', 'assets/kegiatan/1776412310_foto.png', '2026-04-16 23:51:50', '2026-04-16 23:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` bigint UNSIGNED NOT NULL,
  `page_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konten_halamans`
--

CREATE TABLE `konten_halamans` (
  `id` bigint UNSIGNED NOT NULL,
  `halaman` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `konten_halamans`
--

INSERT INTO `konten_halamans` (`id`, `halaman`, `konten`, `created_at`, `updated_at`) VALUES
(1, 'pencegahan', '{\"hero_badge\":\"LINGKUNGAN KAMPUS AMAN\",\"hero_title\":\"Mencegah Lebih Baik\\r\\nDaripada Menangani.\",\"hero_desc\":\"Satgas PPKPT USN Kolaka berkomitmen penuh untuk menghapuskan segala bentuk Kekerasan, Perundungan, dan Intoleransi melalui edukasi, kampanye, dan kebijakan yang berpihak pada korban.\",\"hero_btn\":\"Lihat Peran\",\"langkah_title\":\"Langkah Pencegahan Satgas PPKPT\",\"langkah_desc\":\"Upaya sistematis yang kami lakukan berdasarkan Permendikbudristek No. 55 Tahun 2024.\",\"l_1_title\":\"Edukasi & Sosialisasi\",\"l_1_desc\":\"Penyisipan materi anti kekerasan seksual pada Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) dan seminar berkala.\",\"l_2_title\":\"Pakta Integritas\",\"l_2_desc\":\"Mewajibkan seluruh sivitas akademika (Mahasiswa, Dosen, Tendik) menandatangani pakta integritas penolakan PPKPT.\",\"l_3_title\":\"Kampanye Publik\",\"l_3_desc\":\"Pemasangan poster edukasi, rambu kawasan aman, serta kampanye di media sosial kampus tentang batas-batas interaksi.\",\"peran_title\":\"Apa Peran Anda di Kampus?\",\"p_mhs_title\":\"Mahasiswa\",\"p_mhs_desc\":\"- Perbanyak diskusi positif tentang HAM.\\r\\n- Ikuti sosialisasi anti kekerasan.\\r\\n- Cari tahu unit PPKS di kampus.\\r\\n- Terapkan relasi yang sehat.\",\"p_dsn_title\":\"Dosen & Tendik\",\"p_dsn_desc\":\"- Perbanyak keterlibatan mahasiswa.\\r\\n- Perbanyak sosialisasi & pelatihan.\\r\\n- Perkenalkan layanan unit PPKS.\\r\\n- Terapkan relasi sehat dan setara.\",\"prinsip_title\":\"Prinsip Pengelola Perguruan Tinggi\",\"prinsip_item_titles\":[\"1. Kepentingan Terbaik\",\"2. Keadilan & Kesetaraan Gender\",\"3. Akuntabilitas & Independen\",\"4. Jaminan Ketidakberulangan\"],\"prinsip_item_descs\":[\"Menyediakan infrastruktur dan mekanisme pengaduan yang aman di kampus.\",\"Menyediakan pemulihan untuk korban kekerasan dan keadilan pelaporan.\",\"Transparansi program dan bertindak secara profesional tanpa konflik kepentingan.\",\"Memberikan sanksi tegas kepada pelaku dan meningkatkan keamanan kampus.\"],\"tindakan_title\":\"Tindakan Sebagai Individu (Bystander)\",\"tindakan_item_titles\":[\"1. Pahami Konsep \\\"Consent\\\" (Persetujuan)\",\"2. Jadilah \\\"Bystander\\\" yang Aktif\"],\"tindakan_item_descs\":[\"Segala bentuk aktivitas tanpa persetujuan yang jelas dikategorikan sebagai pelecehan.\",\"Jika melihat perilaku kekerasan, alihkan perhatian pelaku atau laporkan ke Satgas PPKPT.\"]}', '2026-04-23 22:41:14', '2026-05-06 03:49:35'),
(2, 'penanganan', '{\"hero_badge\":\"PROSEDUR RESMI\",\"hero_title\":\"Mendampingi Korban,\\r\\nMenegakkan Keadilan.\",\"hero_desc\":\"Satgas PPKPT bertugas memproses setiap laporan kekerasan secara objektif, rahasia, dan independen. Anda tidak sendirian, kami siap mendengar dan menindaklanjuti laporan Anda.\",\"hero_btn\":\"Buat Laporan Sekarang\",\"prinsip_title_main\":\"Prinsip Penanganan Kami\",\"prinsip_titles\":[\"Berpihak pada Korban\",\"Kerahasiaan Identitas\",\"Keamanan & Perlindungan\"],\"prinsip_descs\":[\"Semua proses penanganan mengutamakan kepentingan, kebutuhan, dan kenyamanan korban.\",\"Identitas semua pihak yang terlibat, terutama korban dan pelaku, dijaga ketat dari publikasi.\",\"Memastikan korban aman dari ancaman, intimidasi, maupun serangan balik dari pihak pelaku.\"],\"alur_title_main\":\"Alur Penanganan Laporan\",\"alur_titles\":[\"1. Penerimaan Laporan\",\"2. Pemeriksaan & Verifikasi\",\"3. Kesimpulan & Rekomendasi\",\"4. Pemulihan Korban\"],\"alur_descs\":[\"Satgas menerima laporan melalui website, WhatsApp, atau pelaporan langsung dengan menjamin kerahasiaan identitas.\",\"Satgas melakukan penggalian informasi dari pelapor, korban, saksi, dan terlapor secara terpisah dan aman.\",\"Satgas menyusun kesimpulan dan memberikan rekomendasi sanksi kepada Pimpinan Perguruan Tinggi.\",\"Memberikan layanan bantuan hukum jika diperlukan oleh korban.\"]}', '2026-04-23 22:57:17', '2026-05-14 17:22:22'),
(3, 'kontak', '{\"hero_badge\":\"HUBUNGI KAMI\",\"hero_title\":\"Kami Siap Mendengar\\r\\ndan Melindungi Anda.\",\"hero_desc\":\"Jangan ragu untuk menghubungi Satgas PPKPT USN Kolaka. Kami menjamin kerahasiaan identitas dan laporan Anda. Berikut adalah struktur keanggotaan dan kontak resmi kami.\",\"wa_title\":\"Hotline PPKPT\",\"wa_nomor\":\"0852-4218-4750\",\"wa_desc\":\"Aktif pada jam kerja (08:00 - 16:00 WITA)\",\"email_title\":\"Email Pengaduan\",\"email_alamat\":\"satgas_ppks@usn.ac.id\",\"email_desc\":\"Kami membalas dalam waktu 1x24 Jam\",\"alamat_title\":\"Ruang Satgas\",\"alamat_singkat\":\"Biro Akademik & Kemahasiswaan\",\"alamat_desc\":\"Universitas Sembilanbelas November Kolaka\",\"struktur_title\":\"Struktur Organisasi Satgas PPKPT USN Kolaka\",\"org_pengarah_nama\":\"Prof Dr. Nur Ihsan Hi, S.Pd.,M.Hum\",\"org_pengarah_jab\":\"Rektor\",\"org_pj_nama\":\"Qammaddin, S.Kom., M.Kom, CITSM, ECIH\",\"org_pj_jab\":\"Wakil Rektor III\",\"org_ketua_nama\":\"Muhamad Aksan Akbar, S.H., M.H\",\"org_sek_nama\":\"Irwan, S.Pi\",\"div1_nama\":\"Divisi Pencegahan & Edukasi\",\"div1_koor\":\"Dr. Grace Tedy Tulak, S.Kep.,Ns.,M.Kep\",\"div1_anggota\":\"Dr. Sarmadan, S.Pd., M.Pd.\\r\\nSaleh, S. Ag., M.A.\",\"div2_nama\":\"Divisi Informasi & Komunikasi\",\"div2_koor\":\"Hj. Nuraidah Tayeb, S.Pd., M.M.Pd\",\"div2_anggota\":\"Arman Sagita, S.Kep., Ns.\\r\\nAriel Bezalel Santoso\\r\\nAndi Lena Patma Dewi\",\"div3_nama\":\"Divisi Penanganan & Pemulihan\",\"div3_koor\":\"Ns. Heriviyatno Siagian, S.Kep., M.N\",\"div3_anggota\":\"Anis Ribcalia Septiana, S.Sos.,M.Si\\r\\nTukatman, S.Kep.Ns.M.Kep\\r\\nMariany, S.St.,M.Keb\"}', '2026-04-23 23:11:28', '2026-05-06 04:05:30'),
(4, 'tentang', '{\"hero_badge\":\"PROFIL SATGAS PPKPT\",\"hero_title\":\"Mewujudkan Kampus yang\\r\\nAman, Setara, dan Inklusif.\",\"hero_desc\":\"Satuan Tugas Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi (Satgas PPKPT) Universitas Sembilanbelas November Kolaka hadir sebagai garda terdepan pelindung sivitas akademika.\",\"latar_title\":\"Latar Belakang\",\"latar_desc\":\"Pembentukan Satgas PPKPT USN Kolaka merupakan wujud komitmen nyata universitas dalam merespons dan mengimplementasikan Permendikbudristek Nomor 55 Tahun 2024 tentang Pencegahan dan Penanganan Kekerasan di Lingkungan Perguruan Tinggi.\\r\\n\\r\\nKami menyadari bahwa perguruan tinggi harus menjadi ruang yang aman bagi penyemaian ilmu pengetahuan. Tidak boleh ada ruang bagi tindakan kekerasan, perundungan, maupun intoleransi. Satgas ini beranggotakan unsur pendidik, tenaga kependidikan, dan mahasiswa yang telah lulus uji seleksi dan pelatihan khusus.\\r\\n\\r\\nKami hadir tidak hanya untuk menangani laporan, tetapi juga berfokus pada edukasi, kampanye pencegahan, dan pemulihan korban dengan prinsip berperspektif pada korban.\",\"latar_img_cap\":\"Kampus USN Kolaka yang aman dan nyaman.\",\"visi_badge\":\"Pandangan Ke Depan\",\"visi_title\":\"Visi Kami\",\"visi_desc\":\"Mewujudkan lingkungan Universitas Sembilanbelas November Kolaka yang aman, setara, inklusif, dan terbebas dari segala bentuk kekerasan seksual.\",\"misi_badge\":\"Langkah Nyata\",\"misi_title\":\"Misi Utama\",\"misi_items\":[\"Menyelenggarakan program edukasi dan sosialisasi pencegahan kekerasan seksual secara berkala.\",\"Menyediakan layanan pengaduan yang mudah diakses, responsif, dan terjamin kerahasiaannya.\",\"Memberikan pendampingan psikologis, hukum, dan akademik bagi korban kekerasan.\",\"Menindaklanjuti laporan dengan adil dan merekomendasikan sanksi tegas bagi pelaku.\"],\"nilai_title_main\":\"Nilai-Nilai Dasar Kami\",\"nilai_titles\":[\"Kerahasiaan\",\"Empati\",\"Keadilan\",\"Inklusif\"],\"nilai_descs\":[\"Kami menjamin 100% privasi dan identitas pelapor serta korban dalam setiap penanganan kasus.\",\"Setiap tindakan selalu menggunakan perspektif korban (victim-centered) dan menghindari victim blaming.\",\"Investigasi dilakukan secara objektif, proporsional, serta bebas dari konflik kepentingan.\",\"Terbuka untuk semua golongan, setara gender, dan memastikan aksesibilitas bagi penyandang disabilitas.\"],\"latar_img_url\":\"assets\\/image\\/foto.PNG\"}', '2026-04-23 23:17:03', '2026-05-06 03:58:55'),
(5, 'dashboard', '{\"carousel_title\":\"Bersama Wujudkan Kampus Aman\",\"carousel_desc\":\"Satgas PPKPT hadir untuk memberikan perlindungan, pendampingan, dan keadilan bagi seluruh civitas akademika.\",\"bentuk_title\":\"Kenali Bentuk Kekerasan\",\"bentuk_item_titles\":[\"Kekerasan Seksual\",\"Kekerasan Fisik\",\"Kekerasan Psikis\",\"Perundungan\",\"Diskriminasi dan Intoleransi\"],\"bentuk_item_descs\":[\"Termasuk pelecehan verbal, fisik, hingga pemaksaan melalui media digital atau intimidasi.\",\"Tindakan kontak fisik yang menyakiti atau membahayakan nyawa orang lain secara sengaja.\",\"Ejekan, pengucilan, atau ancaman yang merusak kesehatan mental dan rasa percaya diri seseorang.\",\"tindakan mengganggu, menyakiti, mengejek, atau menindas seseorang secara sengaja dan berulang-ulang.\",\"Diskriminasi terjadi ketika seseorang diperlakukan tidak adil karena perbedaan suku, agama, ras, gender, atau latar belakang tertentu, sedangkan intoleransi adalah sikap tidak menghormati pendapat, keyakinan, atau budaya orang lain.\"],\"hak_title\":\"Hak Anda Sebagai Pelapor\\/Korban\",\"hak_items\":[\"Hak atas perlindungan identitas dan kerahasiaan informasi.\",\"Hak atas pendampingan psikologis, hukum, dan medis.\",\"Hak untuk mendapatkan informasi perkembangan kasus secara rutin.\",\"Hak atas rasa aman dan bebas dari ancaman pihak manapun.\"],\"kontak_title\":\"Kontak Bantuan & Darurat\",\"kontak_wa\":\"0852-4218-4750\",\"kontak_email\":\"satgas_ppks@usn.ac.id\",\"alur_title\":\"Alur Penanganan Laporan\",\"alur_desc\":\"Langkah nyata kami untuk menjaga keamanan Anda.\",\"alur_item_titles\":[\"Buat Laporan\",\"Verifikasi\",\"Investigasi\",\"Pemulihan\"],\"alur_item_descs\":[\"Isi form pengaduan\",\"Satgas memeriksa laporan\",\"Proses pencarian fakta\",\"Tindak lanjut & pendampingan\"]}', '2026-05-03 17:37:08', '2026-05-11 16:34:22'),
(6, 'peraturan', '{\"peraturan_items\":[{\"nomor\":\"30\",\"tahun\":\"Permendikbudristek 2021\",\"judul\":\"Pencegahan dan Penanganan Kekerasan Seksual (PPKS)\",\"deskripsi\":\"Menjamin hak warga kampus atas pendidikan yang aman, penanganan kasus berperspektif korban dan mengutamakan kerahasiaan.\",\"file_url\":\"assets\\/aturan\\/TAHUN 2021.pdf\"},{\"nomor\":\"17\",\"tahun\":\"Permendikbudristek Tahun 2022\",\"judul\":\"Pedoman Lingkungan Inklusif dan Aman\",\"deskripsi\":\"Mengatur komitmen institusi dalam menyelenggarakan pendidikan yang bebas kekerasan, mendorong tindakan proaktif.\",\"file_url\":\"assets\\/aturan\\/TAHUN 2022.pdf\"},{\"nomor\":\"55\",\"tahun\":\"permendikbudristek Tahun 2024\",\"judul\":\"Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi\",\"deskripsi\":\"Peraturan Pembaruan\",\"file_url\":\"assets\\/aturan\\/1777997928_1777997748019_TAHUN2024.pdf\"}]}', '2026-05-05 08:15:37', '2026-05-05 08:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `kode_tiket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul_lapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kasus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_korban` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp_korban` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_korban` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_terlapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `disabilitas` enum('ya','tidak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `lokasi_kejadian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_video` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu Verifikasi','Sedang Diproses','Selesai','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu Verifikasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `user_id`, `kode_tiket`, `judul_lapor`, `jenis_kasus`, `nama_korban`, `no_hp_korban`, `status_korban`, `status_terlapor`, `jenis_kelamin`, `disabilitas`, `tanggal_kejadian`, `lokasi_kejadian`, `deskripsi`, `link_video`, `bukti`, `status`, `created_at`, `updated_at`) VALUES
(43, 16, 'PPKS-001', 'Pelecehan Di Ruang Kelas', 'Kekerasan Seksual', 'amira', '081111111125', 'mahasiswa', 'dosen', 'P', 'tidak', '2026-05-19', 'Rusun', 'test', '', 'assets/bukti/1779157257_1469411106-hero11.jpg', 'Sedang Diproses', '2026-05-18 18:20:57', '2026-05-18 18:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_03_18_140314_create_laporans_table', 1),
(6, '2026_03_18_140331_create_arsips_table', 1),
(7, '2026_03_18_140341_create_notifications_table', 1),
(8, '2026_04_23_124147_create_contents_table', 2),
(9, '2026_04_24_054753_create_halamen_table', 3),
(10, '2026_04_24_060230_create_konten_halamen_table', 4),
(11, '2026_05_06_122033_create_agendas_table', 5),
(12, '2026_05_07_132134_add_penulis_ke_tabel_agendas', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `url`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-002 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-17 20:18:07', '2026-04-18 04:03:08'),
(2, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-17 20:18:15', '2026-04-17 20:18:25'),
(3, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-17 20:25:54', '2026-04-17 20:32:19'),
(4, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Selesai.', 'http://localhost:8000/cek-status', 1, '2026-04-17 20:28:33', '2026-04-17 20:28:48'),
(5, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-004 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-17 20:30:34', '2026-04-17 20:30:46'),
(6, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-005 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-17 21:28:26', '2026-04-17 21:28:50'),
(7, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Selesai.', 'http://localhost:8000/cek-status', 1, '2026-04-17 21:57:17', '2026-04-18 04:47:16'),
(8, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Ditolak.', 'http://localhost:8000/cek-status', 1, '2026-04-17 21:57:27', '2026-04-18 04:46:56'),
(9, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-005 kini berstatus: Menunggu Verifikasi.', 'http://localhost:8000/cek-status', 1, '2026-04-17 21:57:38', '2026-04-18 04:02:57'),
(10, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-006 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-18 04:06:59', '2026-04-18 04:13:59'),
(11, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-006 kini berstatus: Selesai.', 'http://localhost:8000/cek-status', 1, '2026-04-18 04:17:49', '2026-04-20 19:37:18'),
(12, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Selesai.', 'http://localhost:8000/cek-status', 1, '2026-04-18 04:56:12', '2026-04-18 04:56:21'),
(14, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Ditolak.', 'http://localhost:8000/cek-status', 1, '2026-04-21 04:48:58', '2026-04-22 22:23:17'),
(15, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-008 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-21 04:59:58', '2026-04-22 22:23:11'),
(16, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-005 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-21 05:11:48', '2026-05-04 15:59:47'),
(17, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-008 kini berstatus: Menunggu Verifikasi.', 'http://localhost:8000/cek-status', 1, '2026-04-21 05:12:21', '2026-04-22 22:23:03'),
(18, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-009 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-21 06:49:21', '2026-04-22 22:22:45'),
(19, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-008 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-21 06:49:36', '2026-04-22 22:22:54'),
(21, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-002 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-04-27 06:07:43', '2026-04-27 06:07:55'),
(22, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-04 16:26:28', '2026-05-12 05:44:24'),
(24, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-003 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-04 22:05:04', '2026-05-04 22:08:00'),
(25, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-04 22:05:45', '2026-05-12 05:44:19'),
(26, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-004 kini berstatus: Selesai.', 'http://localhost:8000/cek-status', 1, '2026-05-04 22:40:17', '2026-05-12 05:44:14'),
(27, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-005 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-05 23:13:11', '2026-05-05 23:13:51'),
(28, 1, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-006 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-11 05:57:56', '2026-05-12 04:41:59'),
(29, 2, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-15 04:38:44', '2026-05-15 05:04:09'),
(32, 16, 'Status Laporan Diperbarui', 'Laporan Anda dengan kode tiket PPKS-001 kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-05-18 18:24:27', '2026-05-18 18:25:57');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('mohdiiqball03@gmail.com', '$2y$12$R2xSESzWpodw4EMloN.Dz.WlCf2/g6WgO6sVyoyCqmBaOY4nVNhI6', '2026-05-12 16:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user','satgas') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_plain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `no_hp`, `role`, `foto`, `email_verified_at`, `password`, `password_plain`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'satgasppks@usn.ac.id', '081200001111', 'admin', NULL, NULL, '$2y$12$4neUuK.pEyyHvZ1MxtaTduG1KXG.2LL2N4E8REaKKvQuAtEv5aSnS', NULL, NULL, '2026-03-18 06:19:27', '2026-05-17 18:23:10'),
(2, 'Pelapor', 'mahasiswa1', 'pelapor@gmail.com', '085200002222', 'user', NULL, NULL, '$2y$12$KJLMGFynKXj.KOrgCbMutuM2DSIotFHiCCAOCm8qE3E.BZJ8YdlPq', NULL, NULL, '2026-03-18 06:19:27', '2026-05-14 17:24:27'),
(11, 'satgas 1', 'satgas1', 'satgas1@gmail.com', NULL, 'satgas', NULL, NULL, '$2y$12$nBrwIPhayiMDyrU4NhF6weCVJp/OOlBwOZNWTMSV4EbWPZG8Fp8mS', 'satgas1', NULL, '2026-05-11 08:01:01', '2026-05-11 16:28:14'),
(12, 'satgas 2', 'satgas2', 'satgas2@gmail.com', NULL, 'satgas', NULL, NULL, '$2y$12$ByRoA78AZpD3ag4m.wN.8.X.fTVhfHZwcO78NAKJe5Hhnk5ZX5J7O', 'satgas2', NULL, '2026-05-11 16:28:49', '2026-05-11 16:28:49'),
(16, 'Mahasiswa', 'pelapor1', 'pelapor1@gmail.com', '081111111111', 'user', NULL, NULL, '$2y$12$SGv4s4CcklkvZkP59tRqO.soAlgHYQm8Td.f0/Uc4MnieI6zIg85G', NULL, NULL, '2026-05-18 18:18:04', '2026-05-18 18:18:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agendas`
--
ALTER TABLE `agendas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agendas_slug_unique` (`slug`);

--
-- Indexes for table `arsips`
--
ALTER TABLE `arsips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contents_page_key_unique` (`page_key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `konten_halamans`
--
ALTER TABLE `konten_halamans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konten_halamans_halaman_unique` (`halaman`);

--
-- Indexes for table `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `laporans_kode_tiket_unique` (`kode_tiket`),
  ADD KEY `laporans_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agendas`
--
ALTER TABLE `agendas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `arsips`
--
ALTER TABLE `arsips`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konten_halamans`
--
ALTER TABLE `konten_halamans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporans`
--
ALTER TABLE `laporans`
  ADD CONSTRAINT `laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
