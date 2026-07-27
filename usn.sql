-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 25, 2026 at 09:28 AM
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
(13, 'Pelatihan Satuan Tugas Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi', 'pelatihan-satuan-tugas-pencegahan-dan-penanganan-kekerasan-di-lingkungan-perguruan-tinggi-1779198628', 'Admin', '<p data-path-to-node=\"3\"> Dalam komitmennya untuk menghadirkan lingkungan kampus yang aman dan berintegritas, perwakilan dari <b data-path-to-node=\"3\" data-index-in-node=\"152\">Satuan Tugas Pencegahan dan Penanganan Kekerasan di Lingkungan Perguruan Tinggi (Satgas PPKPT) Universitas Sembilanbelas November (USN) Kolaka</b> telah secara aktif berpartisipasi dalam agenda \"Pelatihan Nasional Satgas PPKS\".</p><p data-path-to-node=\"4\">Kegiatan ini berlangsung selama dua hari, dari tanggal 23 hingga 24 Oktober 2023, bertempat di salah satu hotel di Surabaya. Sebagaimana terdokumentasikan dalam gambar di atas, delapan orang perwakilan dari USN Kolaka tampil kompak dan bersemangat di depan spanduk latar belakang kegiatan. Spanduk tersebut memuat tajuk besar \"<b data-path-to-node=\"4\" data-index-in-node=\"327\">PELATIHAN NASIONAL SATGAS PPKS</b>\" dengan sub-tema penting: \"<b data-path-to-node=\"4\" data-index-in-node=\"385\">Terampil dalam Pencegahan dan Penanganan Kasus Kekerasan Seksual di Perguruan Tinggi</b>\".</p><p data-path-to-node=\"5\">Dalam foto bersama tersebut, para peserta yang terdiri dari pria dan wanita terlihat mengenakan pakaian formal, termasuk batik dan pakaian dinas, dengan beberapa di antaranya menyematkan kartu tanda pengenal. Menariknya, sebagian besar peserta melakukan pose menyilangkan lengan di depan dada. Pose ini bukan sekadar gaya foto, melainkan secara simbolis menegaskan sikap tegas \"Stop!\" dan penolakan keras terhadap segala bentuk kekerasan, terutama kekerasan seksual di lingkungan kampus.</p><p data-path-to-node=\"6\">Keikutsertaan Satgas PPKPT USN Kolaka dalam pelatihan berskala nasional ini menunjukkan keseriusan pihak universitas dalam mengimplementasikan mandat Permendikbudristek No. 30 Tahun 2021 tentang PPKS. Tujuan utama dari keikutsertaan ini adalah untuk meningkatkan kompetensi, wawasan, dan keterampilan satgas dalam berbagai aspek, mulai dari edukasi pencegahan secara masif hingga prosedur penanganan kasus yang tepat, sensitif gender, dan berpihak pada korban.</p><p data-path-to-node=\"7\">Langkah ini diharapkan akan memperkuat peran Satgas PPKPT USN Kolaka dalam menjalankan tugasnya, memastikan kampus USN Kolaka menjadi ruang belajar yang inklusif, aman, dan bebas dari rasa takut akan kekerasan bagi seluruh sivitas akademika.</p>', 'assets/agenda/1779198628_foto.png', '2026-05-18', 'publikasi', '2026-05-19 05:50:28', '2026-05-19 05:51:55');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `arsips`
--

INSERT INTO `arsips` (`id`, `judul_kegiatan`, `jenis_kegiatan`, `tanggal`, `lokasi`, `status_publikasi`, `deskripsi`, `dokumentasi`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Seminar Anti Kekerasan', 'Rapat Koordinasi', '2026-06-18', 'Surabaya', 'internal', 'Partisipasi perwakilan Universitas Sembilanbelas November Kolaka dalam kegiatan Pelatihan Nasional Satuan Tugas Pencegahan dan Penanganan Kekerasan Seksual (Satgas PPKS) yang diselenggarakan di Surabaya pada tanggal 23–24 Oktober.', 'assets/kegiatan/1781793905_foto.png', '2026-06-18 06:45:05', '2026-06-18 07:00:44', 1);

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
(2, 'penanganan', '{\"hero_badge\":\"PROSEDUR RESMI\",\"hero_title\":\"Mendampingi Korban,\\r\\nMenegakkan Keadilan.\",\"hero_desc\":\"Satgas PPKPT bertugas memproses setiap laporan kekerasan secara objektif, rahasia, dan independen. Anda tidak sendirian, kami siap mendengar dan menindaklanjuti laporan Anda.\",\"hero_btn\":\"Buat Laporan Sekarang\",\"prinsip_title_main\":\"Prinsip Penanganan Kami\",\"prinsip_titles\":[\"Berpihak pada Korban\",\"Kerahasiaan Identitas\",\"Keamanan & Perlindungan\"],\"prinsip_descs\":[\"Semua proses penanganan mengutamakan kepentingan, kebutuhan, dan kenyamanan korban.\",\"Identitas semua pihak yang terlibat, terutama korban dan pelaku, dijaga ketat dari publikasi.\",\"Memastikan korban aman dari ancaman, intimidasi, maupun serangan balik dari pihak pelaku.\"],\"alur_title_main\":\"Standar Operasional Prosedur Penanganan\",\"alur_titles\":[\"1. Penerimaan Laporan\",\"2. Pemeriksaan, Klrifikasi & Verifikasi\",\"3. Kesimpulan & Rekomendasi\",\"4. Pemulihan Korban\"],\"alur_descs\":[\"Satgas menerima laporan melalui website, WhatsApp, atau pelaporan langsung dengan menjamin kerahasiaan identitas.\",\"Satgas melakukan penggalian informasi dari pelapor, korban, saksi, dan terlapor secara terpisah dan aman.\",\"Satgas menyusun kesimpulan dan memberikan rekomendasi sanksi kepada Pimpinan Perguruan Tinggi.\",\"Memberikan layanan bantuan hukum jika diperlukan oleh korban.\"]}', '2026-04-23 22:57:17', '2026-07-20 12:36:26'),
(3, 'kontak', '{\"hero_badge\":\"HUBUNGI KAMI\",\"hero_title\":\"Kami Siap Mendengar\\r\\ndan Melindungi Anda.\",\"hero_desc\":\"Jangan ragu untuk menghubungi Satgas PPKPT USN Kolaka. Kami menjamin kerahasiaan identitas dan laporan Anda. Berikut adalah struktur keanggotaan dan kontak resmi kami.\",\"wa_title\":\"Hotline PPKPT\",\"wa_nomor\":\"0852-4218-4750\",\"wa_desc\":\"Aktif pada jam kerja (08:00 - 16:00 WITA)\",\"email_title\":\"Email Pengaduan\",\"email_alamat\":\"satgas_ppks@usn.ac.id\",\"email_desc\":\"Kami membalas dalam waktu 1x24 Jam\",\"alamat_title\":\"Ruang Satgas\",\"alamat_singkat\":\"Biro Akademik & Kemahasiswaan\",\"alamat_desc\":\"Universitas Sembilanbelas November Kolaka\",\"struktur_title\":\"Struktur Organisasi Satgas PPKPT USN Kolaka\",\"org_pengarah_nama\":\"Prof Dr. Nur Ihsan Hi, S.Pd.,M.Hum\",\"org_pengarah_jab\":\"Rektor\",\"org_pj_nama\":\"Qammaddin, S.Kom., M.Kom, CITSM, ECIH\",\"org_pj_jab\":\"Wakil Rektor III\",\"org_ketua_nama\":\"Muhamad Aksan Akbar, S.H., M.H\",\"org_sek_nama\":\"Irwan, S.Pi\",\"div1_nama\":\"Divisi Pencegahan & Edukasi\",\"div1_koor\":\"Dr. Grace Tedy Tulak, S.Kep.,Ns.,M.Kep\",\"div1_anggota\":\"Dr. Sarmadan, S.Pd., M.Pd.\\r\\nSaleh, S. Ag., M.A.\",\"div2_nama\":\"Divisi Informasi & Komunikasi\",\"div2_koor\":\"Hj. Nuraidah Tayeb, S.Pd., M.M.Pd\",\"div2_anggota\":\"Arman Sagita, S.Kep., Ns.\\r\\nAriel Bezalel Santoso\\r\\nAndi Lena Patma Dewi\",\"div3_nama\":\"Divisi Penanganan & Pemulihan\",\"div3_koor\":\"Ns. Heriviyatno Siagian, S.Kep., M.N\",\"div3_anggota\":\"Anis Ribcalia Septiana, S.Sos.,M.Si\\r\\nTukatman, S.Kep.Ns.M.Kep\\r\\nMariany, S.St.,M.Keb\"}', '2026-04-23 23:11:28', '2026-05-06 04:05:30'),
(4, 'tentang', '{\"hero_badge\":\"PROFIL SATGAS PPKPT\",\"hero_title\":\"Mewujudkan Kampus yang\\r\\nAman, Setara, dan Inklusif.\",\"hero_desc\":\"Satuan Tugas Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi (Satgas PPKPT) Universitas Sembilanbelas November Kolaka hadir sebagai garda terdepan pelindung sivitas akademika.\",\"latar_title\":\"Latar Belakang\",\"latar_desc\":\"Pembentukan Satgas PPKPT USN Kolaka merupakan wujud komitmen nyata universitas dalam merespons dan mengimplementasikan Permendikbudristek Nomor 55 Tahun 2024 tentang Pencegahan dan Penanganan Kekerasan di Lingkungan Perguruan Tinggi.\\r\\n\\r\\nKami menyadari bahwa perguruan tinggi harus menjadi ruang yang aman bagi penyemaian ilmu pengetahuan. Tidak boleh ada ruang bagi tindakan kekerasan, perundungan, maupun intoleransi. Satgas ini beranggotakan unsur pendidik, tenaga kependidikan, dan mahasiswa yang telah lulus uji seleksi dan pelatihan khusus.\\r\\n\\r\\nKami hadir tidak hanya untuk menangani laporan, tetapi juga berfokus pada edukasi, kampanye pencegahan, dan pemulihan korban dengan prinsip berperspektif pada korban.\",\"latar_img_cap\":\"Kampus USN Kolaka yang aman dan nyaman.\",\"visi_badge\":\"Pandangan Ke Depan\",\"visi_title\":\"Visi Kami\",\"visi_desc\":\"Mewujudkan lingkungan Universitas Sembilanbelas November Kolaka yang aman, setara, inklusif, dan terbebas dari segala bentuk kekerasan seksual.\",\"misi_badge\":\"Langkah Nyata\",\"misi_title\":\"Misi Utama\",\"misi_items\":[\"Menyelenggarakan program edukasi dan sosialisasi pencegahan kekerasan seksual secara berkala.\",\"Menyediakan layanan pengaduan yang mudah diakses, responsif, dan terjamin kerahasiaannya.\",\"Memberikan pendampingan psikologis, hukum, dan akademik bagi korban kekerasan.\",\"Menindaklanjuti laporan dengan adil dan merekomendasikan sanksi tegas bagi pelaku.\"],\"nilai_title_main\":\"Nilai-Nilai Dasar Kami\",\"nilai_titles\":[\"Kerahasiaan\",\"Empati\",\"Keadilan\",\"Inklusif\"],\"nilai_descs\":[\"Kami menjamin 100% privasi dan identitas pelapor serta korban dalam setiap penanganan kasus.\",\"Setiap tindakan selalu menggunakan perspektif korban (victim-centered) dan menghindari victim blaming.\",\"Investigasi dilakukan secara objektif, proporsional, serta bebas dari konflik kepentingan.\",\"Terbuka untuk semua golongan, setara gender, dan memastikan aksesibilitas bagi penyandang disabilitas.\"],\"latar_img_url\":\"assets\\/image\\/foto.PNG\"}', '2026-04-23 23:17:03', '2026-05-06 03:58:55'),
(5, 'dashboard', '{\"carousel_title\":\"Bersama Wujudkan Kampus Aman\",\"carousel_desc\":\"Satgas PPKPT hadir untuk memberikan perlindungan, pendampingan, dan keadilan bagi seluruh civitas akademika.\",\"bentuk_title\":\"Kenali Bentuk Kekerasan\",\"bentuk_item_titles\":[\"Kekerasan Seksual\",\"Kekerasan Fisik\",\"Kekerasan Psikis\",\"Perundungan\",\"Diskriminasi dan Intoleransi\",\"Kebijakan Unsur Kekerasan\"],\"bentuk_item_descs\":[\"Termasuk pelecehan verbal, fisik, hingga pemaksaan melalui media digital atau intimidasi.\",\"Tindakan kontak fisik yang menyakiti atau membahayakan nyawa orang lain secara sengaja.\",\"Ejekan, pengucilan, atau ancaman yang merusak kesehatan mental dan rasa percaya diri seseorang.\",\"tindakan mengganggu, menyakiti, mengejek, atau menindas seseorang secara sengaja dan berulang-ulang.\",\"Diskriminasi terjadi ketika seseorang diperlakukan tidak adil karena perbedaan suku, agama, ras, gender, atau latar belakang tertentu, sedangkan intoleransi adalah sikap tidak menghormati pendapat, keyakinan, atau budaya orang lain.\",\"PEraturan aneh\"],\"hak_title\":\"Hak Anda Sebagai Pelapor\\/Korban\",\"hak_items\":[\"Hak atas perlindungan identitas dan kerahasiaan informasi.\",\"Hak atas pendampingan psikologis, hukum, dan medis.\",\"Hak untuk mendapatkan informasi perkembangan kasus secara rutin.\",\"Hak atas rasa aman dan bebas dari ancaman pihak manapun.\"],\"kontak_title\":\"Kontak Bantuan & Darurat\",\"kontak_wa\":\"0852-4218-4750\",\"kontak_email\":\"satgas_ppks@usn.ac.id\",\"alur_title\":\"Alur Penanganan Laporan\",\"alur_desc\":\"Langkah nyata kami untuk menjaga keamanan Anda.\",\"alur_item_titles\":[\"Buat Laporan\",\"Verifikasi\",\"Investigasi\",\"Pemulihan\"],\"alur_item_descs\":[\"Isi form pengaduan\",\"Satgas memeriksa laporan\",\"Proses pencarian fakta\",\"Tindak lanjut & pendampingan\"]}', '2026-05-03 17:37:08', '2026-07-20 02:58:14'),
(6, 'peraturan', '{\"peraturan_items\":[{\"nomor\":\"30\",\"tahun\":\"Permendikbudristek 2021\",\"judul\":\"Pencegahan dan Penanganan Kekerasan Seksual (PPKS)\",\"deskripsi\":\"Menjamin hak warga kampus atas pendidikan yang aman, penanganan kasus berperspektif korban dan mengutamakan kerahasiaan.\",\"file_url\":\"assets\\/aturan\\/TAHUN 2021.pdf\"},{\"nomor\":\"17\",\"tahun\":\"Permendikbudristek Tahun 2022\",\"judul\":\"Pedoman Lingkungan Inklusif dan Aman\",\"deskripsi\":\"Mengatur komitmen institusi dalam menyelenggarakan pendidikan yang bebas kekerasan, mendorong tindakan proaktif.\",\"file_url\":\"assets\\/aturan\\/TAHUN 2022.pdf\"},{\"nomor\":\"55\",\"tahun\":\"permendikbudristek Tahun 2024\",\"judul\":\"Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi\",\"deskripsi\":\"Peraturan Pembaruan\",\"file_url\":\"assets\\/aturan\\/1780579970_2_TAHUN2024.pdf\"}]}', '2026-05-05 08:15:37', '2026-06-04 05:32:50'),
(7, 'pengaturan_surat', '{\"ttd_url\":\"assets\\/image\\/surat\\/ttd_admin_1780926079.jpg\",\"nama_ketua\":\"Muhamad Aksan Akbar, S.H., M.H\",\"nip_ketua\":\"0123456789\"}', '2026-06-08 04:40:45', '2026-06-08 05:41:19');

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
  `status_korban_lainnya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_terlapor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_terlapor_lainnya` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `disabilitas` enum('ya','tidak') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `lokasi_kejadian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saksi` json DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_video` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bukti` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Menunggu Verifikasi','Sedang Diproses','Selesai','Ditolak') COLLATE utf8mb4_unicode_ci DEFAULT 'Menunggu Verifikasi',
  `diproses_at` timestamp NULL DEFAULT NULL,
  `selesai_at` timestamp NULL DEFAULT NULL,
  `akumulasi_waktu` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporans`
--

INSERT INTO `laporans` (`id`, `user_id`, `kode_tiket`, `judul_lapor`, `jenis_kasus`, `nama_korban`, `no_hp_korban`, `status_korban`, `status_korban_lainnya`, `status_terlapor`, `status_terlapor_lainnya`, `jenis_kelamin`, `disabilitas`, `tanggal_kejadian`, `lokasi_kejadian`, `saksi`, `deskripsi`, `link_video`, `bukti`, `status`, `diproses_at`, `selesai_at`, `akumulasi_waktu`, `created_at`, `updated_at`) VALUES
(78, 1, 'KZEQ4SFD', 'Pelecehan Verbal', 'Kekerasan Fisik', 'Mohd Iqbal', '08123456789', 'mahasiswa', NULL, 'mahasiswa', NULL, 'L', 'tidak', '2026-07-21', 'Fakultas', NULL, 'Mengeluarkan kata kata tidak baik', '', NULL, 'Sedang Diproses', NULL, NULL, 0, '2026-07-21 11:34:13', '2026-07-21 11:35:35'),
(79, 1, 'VLPPZ5YS', 'Percobaan Pemerkosaan', 'Kekerasan Seksual', 'andira', '08123456789', 'mahasiswa', NULL, 'dosen', NULL, 'P', 'tidak', '2026-07-24', 'Fakultas', NULL, 'perccobaan perkosaan', '', NULL, 'Sedang Diproses', NULL, NULL, 0, '2026-07-24 11:42:40', '2026-07-24 11:44:33'),
(80, 1, 'QYKIM9P7', 'Bullying', 'Kekerasan Fisik', 'Mohd Iqbal', '08123456789', 'mahasiswa', NULL, 'mahasiswa', NULL, 'L', 'tidak', '2026-07-24', 'Fakultas', NULL, 'ddfdfdfd', '', NULL, 'Sedang Diproses', NULL, NULL, 0, '2026-07-24 12:16:05', '2026-07-24 12:16:43');

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
(12, '2026_05_07_132134_add_penulis_ke_tabel_agendas', 6),
(13, '2026_05_11_123944_add_new_fields_to_laporans_table', 7),
(14, '2026_05_11_152708_add_password_plain_to_users_table', 7),
(15, '2026_06_18_093438_add_saksi_and_status_lainnya_to_laporans_table', 7),
(16, '2026_06_18_140820_add_status_terlapor_lainnya_to_laporans_table', 8),
(17, '2026_06_18_144033_add_user_id_to_arsips_table', 9),
(18, '2026_07_16_105223_add_handling_timestamps_to_laporans_table', 10),
(19, '2026_07_16_110956_add_akumulasi_waktu_to_laporans_table', 11),
(20, '2026_07_16_120651_create_riwayat_laporans_table', 12),
(25, '2026_07_17_141711_alter_status_enum_in_laporans_table', 15);

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
(91, 20, 'Keluhan Baru: 1AHELF8J', 'Terdapat keluhan baru terkait laporan ini.', 'http://localhost:8000/admin/laporan/70', 0, '2026-07-17 07:15:56', '2026-07-17 07:15:56'),
(115, 1, 'KZEQ4SFD Diperbarui', 'Laporan Anda kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 1, '2026-07-21 11:35:35', '2026-07-21 11:36:50'),
(116, 1, 'KZEQ4SFD Diperbarui', 'Laporan Anda kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 0, '2026-07-24 11:23:02', '2026-07-24 11:23:02'),
(117, 1, 'VLPPZ5YS Diperbarui', 'Laporan Anda kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 0, '2026-07-24 11:44:33', '2026-07-24 11:44:33'),
(118, 1, 'QYKIM9P7 Diperbarui', 'Laporan Anda kini berstatus: Sedang Diproses.', 'http://localhost:8000/cek-status', 0, '2026-07-24 12:16:43', '2026-07-24 12:16:43');

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
-- Table structure for table `riwayat_laporans`
--

CREATE TABLE `riwayat_laporans` (
  `id` bigint UNSIGNED NOT NULL,
  `laporan_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_laporans`
--

INSERT INTO `riwayat_laporans` (`id`, `laporan_id`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(67, 78, 'Menunggu Verifikasi', 'Laporan diterima sistem.', '2026-07-21 11:34:13', '2026-07-21 11:34:13'),
(68, 78, 'Sedang Diproses', 'Laporan telah diverifikasi dan akan ditindaklanjuti segera.', '2026-07-21 11:35:35', '2026-07-21 11:35:35'),
(69, 78, 'Sedang Diproses', '<p>Laporan ta sudah di serhkan kepada satgas penanganan.</p>', '2026-07-24 11:23:02', '2026-07-24 11:23:02'),
(70, 79, 'Menunggu Verifikasi', 'Laporan diterima sistem.', '2026-07-24 11:42:40', '2026-07-24 11:42:40'),
(71, 79, 'Sedang Diproses', '<p>nanti kita menerima surat undangan dan akan di tindaklanjuti dengan pemeriksaan kemudian klarifikasi dan pemulihan.</p>', '2026-07-24 11:44:33', '2026-07-24 11:44:33'),
(72, 80, 'Menunggu Verifikasi', 'Laporan diterima sistem.', '2026-07-24 12:16:05', '2026-07-24 12:16:05'),
(73, 80, 'Sedang Diproses', 'Laporan telah diverifikasi dan akan ditindaklanjuti segera.', '2026-07-24 12:16:43', '2026-07-24 12:16:43');

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
(1, 'Admin', 'admin', 'satgasppks@usn.ac.id', '081200001111', 'admin', NULL, NULL, '$2y$12$4neUuK.pEyyHvZ1MxtaTduG1KXG.2LL2N4E8REaKKvQuAtEv5aSnS', NULL, NULL, '2026-03-18 06:19:27', '2026-07-20 11:40:38'),
(20, 'Petugas', 'petugas1', 'petugas1@gmail.com', NULL, 'satgas', NULL, NULL, '$2y$12$k/3zEoJZE4Q4iavTL9whP.VsTp5n.DoHw7S3KuMrZ9xOWqlNBJA2W', 'petugas1', NULL, '2026-06-18 05:23:20', '2026-06-18 05:23:20'),
(21, 'mahasiswa1', 'mahasiswa1', 'i8169398@gmail.com', '085828340256', 'user', 'profil/VErP1Lvc82LKPb8dgLo3wzsPTwbC8zc8Iubwm42W.jpg', NULL, '$2y$12$Ys4Lo4GLdrpQ4kTSlsnIFO15FOQDZyrjgw93LXrB3oBtu4RfXsOn2', NULL, NULL, '2026-06-18 07:04:24', '2026-06-18 07:37:05'),
(22, 'Test Satgas', 'satgas123', 'satgas@usn.ac.id', '081234567890', 'user', NULL, NULL, '$2y$12$86zWNSu3YysxCc/dHnpGk.nljUEdhHr76V4lpVyvumHzL4XquYQt2', NULL, NULL, '2026-07-20 03:06:20', '2026-07-20 03:06:20'),
(23, 'Admin 2', 'admin2', 'admin2@usn.ac.id', '081200001112', 'admin', NULL, NULL, '$2y$12$1v/ZbmN4Eqi0dT9wivvUHe8uFhu/SElP7.Q6bcFgkwoxgIiqYn00G', NULL, NULL, '2026-07-24 02:32:48', '2026-07-24 03:15:23');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `arsips_user_id_foreign` (`user_id`);

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
-- Indexes for table `riwayat_laporans`
--
ALTER TABLE `riwayat_laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `riwayat_laporans_laporan_id_foreign` (`laporan_id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `arsips`
--
ALTER TABLE `arsips`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_laporans`
--
ALTER TABLE `riwayat_laporans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsips`
--
ALTER TABLE `arsips`
  ADD CONSTRAINT `arsips_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `riwayat_laporans`
--
ALTER TABLE `riwayat_laporans`
  ADD CONSTRAINT `riwayat_laporans_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
