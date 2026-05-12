<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\AgendaController;

// --- RUTE PUBLIC (Bisa diakses tanpa login) ---

// RUTE UNTUK HALAMAN DEPAN (LANDING PAGE)
Route::get('/', function () {
    $kontenPencegahan = \App\Models\KontenHalaman::where('halaman', 'pencegahan')->first();
    $kontenPenanganan = \App\Models\KontenHalaman::where('halaman', 'penanganan')->first();
    $kontenTentang = \App\Models\KontenHalaman::where('halaman', 'tentang')->first();
    $kontenKontak = \App\Models\KontenHalaman::where('halaman', 'kontak')->first();
    $kontenPeraturan = \App\Models\KontenHalaman::where('halaman', 'peraturan')->first();

    $galeris = \App\Models\Arsip::whereIn('status_publikasi', ['sosialisasi', 'poster'])
        ->orderBy('tanggal', 'desc')->take(8)->get();

    // Mengambil 3 Data Agenda Terbaru untuk Landing Page
    $agendas = \App\Models\Agenda::where('status', 'publikasi')->orderBy('tanggal', 'desc')->take(3)->get();

    // Mengambil gambar carousel
    $carousels = [];
    $path = public_path('assets/image/kolosel');

    if (\Illuminate\Support\Facades\File::exists($path)) {
        $files = \Illuminate\Support\Facades\File::files($path);
        foreach ($files as $file) {
            $carousels[] = ['url' => asset('assets/image/kolosel/' . $file->getFilename()), 'nama' => $file->getFilename()];
        }
    }

    if (empty($carousels)) {
        $carousels[] = ['url' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1920&auto=format&fit=crop', 'nama' => 'default1'];
    }

    return view('depan', compact('kontenPencegahan', 'kontenPenanganan', 'kontenTentang', 'kontenKontak', 'galeris', 'carousels', 'kontenPeraturan', 'agendas'));
});

// PERBAIKAN: Rute Detail Berita (Public)
Route::get('/berita/{slug}', [AgendaController::class, 'show'])->name('agenda.show');

// Route untuk Manajemen Gambar Pop-up Akan Datang (Promo)
Route::post('/informasi/promo/upload', [InformasiController::class, 'uploadPromo'])->name('promo.upload');
Route::post('/informasi/promo/hapus', [InformasiController::class, 'hapusPromo'])->name('promo.hapus');

// Rute Autentikasi (Login & Register)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// --- RUTE CEK STATUS TIKET (PENCARIAN AJAX DARI LANDING PAGE) ---
Route::post('/cek-status', [LaporanController::class, 'cariStatus'])->name('cek-status.cari');


// --- RUTE TERPROTEKSI (Harus Login: Admin, Satgas, Masyarakat) ---
Route::middleware(['auth'])->group(function () {

    // Dashboard & Notifikasi
    Route::get('/index', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/notifikasi/{id}/baca', [LaporanController::class, 'bacaNotifikasi'])->name('notifikasi.baca');

    // Route untuk Edit Konten Dashboard Pelapor (Dinamic Teks)
    Route::get('/dashboard/edit', [DashboardController::class, 'editDashboard'])->name('dashboard.edit');
    Route::post('/dashboard/update', [DashboardController::class, 'updateDashboard'])->name('dashboard.update');

    // Fitur Pelaporan
    Route::get('/laporkan', [LaporanController::class, 'create'])->name('laporkan');
    Route::post('/lapor', [LaporanController::class, 'store'])->name('lapor.store');
    Route::get('/riwayat', [LaporanController::class, 'riwayat'])->name('riwayat');

    // Halaman Cek Status
    Route::get('/cek-status', [LaporanController::class, 'cekStatus'])->name('cek-status');

    // Fitur Edit & Hapus Laporan
    Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

    // Rute Lihat Detail dan Cetak PDF
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{id}/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/cetak-pdf/{id}', [LaporanController::class, 'cetakPdf']);

    // Informasi Publik & Galeri (Bisa dilihat Satgas, tapi tidak bisa edit karena rute edit ada di grup admin)
    Route::get('/informasi/pencegahan', [InformasiController::class, 'pencegahan'])->name('informasi.pencegahan');
    Route::get('/informasi/penanganan', [InformasiController::class, 'penanganan'])->name('informasi.penanganan');
    Route::get('/kontak', [InformasiController::class, 'kontak'])->name('kontak');
    Route::get('/tentang', [InformasiController::class, 'tentang'])->name('tentang');
    Route::get('/galeri', [InformasiController::class, 'galeri'])->name('galeri');

    // PERBAIKAN: Memungkinkan Satgas mengakses daftar Berita Agenda (Index)
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');

    // Pengaturan Sistem
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
    Route::post('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');

    // --- KHUSUS ADMIN (Satgas tidak bisa masuk ke sini) ---
    Route::middleware(['admin'])->group(function () {
        // Data Laporan Admin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/{id}/status', [LaporanController::class, 'updateStatus'])->name('laporan.update-status');

        // Fungsi CRUD Agenda lainnya (Hanya Admin yang bisa Tambah/Edit/Hapus)
        Route::resource('agenda', AgendaController::class)->except(['index', 'show']);

        // Arsip Kegiatan
        Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
        Route::post('/arsip/simpan', [ArsipController::class, 'store'])->name('arsip.store');
        Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
        Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');

        // Route untuk Manajemen Gambar Kolosel Beranda
        Route::post('/carousel/upload', [DashboardController::class, 'uploadCarousel'])->name('carousel.upload');
        Route::post('/carousel/hapus', [DashboardController::class, 'hapusCarousel'])->name('carousel.hapus');

        // Route untuk Edit Konten Halaman Publik (Hanya Admin)
        Route::get('/informasi/pencegahan/edit', [InformasiController::class, 'editPencegahan'])->name('informasi.pencegahan.edit');
        Route::post('/informasi/pencegahan/update', [InformasiController::class, 'updatePencegahan'])->name('informasi.pencegahan.update');
        Route::get('/informasi/penanganan/edit', [InformasiController::class, 'editPenanganan'])->name('informasi.penanganan.edit');
        Route::post('/informasi/penanganan/update', [InformasiController::class, 'updatePenanganan'])->name('informasi.penanganan.update');
        Route::get('/kontak/edit', [InformasiController::class, 'editKontak'])->name('kontak.edit');
        Route::post('/kontak/update', [InformasiController::class, 'updateKontak'])->name('kontak.update');
        Route::get('/tentang/edit', [InformasiController::class, 'editTentang'])->name('tentang.edit');
        Route::post('/tentang/update', [InformasiController::class, 'updateTentang'])->name('tentang.update');

        // Rute untuk Peraturan Dinamis
        Route::get('/informasi/peraturan/edit', [InformasiController::class, 'editPeraturan'])->name('informasi.peraturan.edit');
        Route::post('/informasi/peraturan/update', [InformasiController::class, 'updatePeraturan'])->name('informasi.peraturan.update');

        // Rute Manajemen Petugas (Satgas)
        Route::get('/petugas', [\App\Http\Controllers\PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [\App\Http\Controllers\PetugasController::class, 'store'])->name('petugas.store');
        // Tambahkan rute update di bawah ini:
        Route::put('/petugas/{id}', [\App\Http\Controllers\PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{id}', [\App\Http\Controllers\PetugasController::class, 'destroy'])->name('petugas.destroy');
    });
});
