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
Route::get('/', function () {
    $kontenPencegahan = \App\Models\KontenHalaman::where('halaman', 'pencegahan')->first();
    $kontenPenanganan = \App\Models\KontenHalaman::where('halaman', 'penanganan')->first();
    $kontenTentang = \App\Models\KontenHalaman::where('halaman', 'tentang')->first();
    $kontenKontak = \App\Models\KontenHalaman::where('halaman', 'kontak')->first();
    $kontenPeraturan = \App\Models\KontenHalaman::where('halaman', 'peraturan')->first();

    $galeris = \App\Models\Arsip::whereNotNull('dokumentasi')
        ->orderBy('tanggal', 'desc')->take(8)->get();

    $agendas = \App\Models\Agenda::where('status', 'publikasi')->orderBy('tanggal', 'desc')->take(3)->get();

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
    // --- DATA UNTUK GRAFIK TREN KEKERASAN (5 Tahun, Tahun ini di tengah) ---
    $currentYear = (int) date('Y');
    $years = [
        $currentYear - 2,
        $currentYear - 1,
        $currentYear,
        $currentYear + 1,
        $currentYear + 2
    ];

    $laporans = \App\Models\Laporan::whereYear('created_at', '>=', $currentYear - 2)
                    ->whereYear('created_at', '<=', $currentYear + 2)
                    ->get();
                    
    $kontenDashboard = \App\Models\KontenHalaman::where('halaman', 'dashboard')->first();
    $dataDashboard = $kontenDashboard && !empty($kontenDashboard->konten) ? json_decode($kontenDashboard->konten, true) : [];
    $jenisKasuses = collect($dataDashboard['bentuk_item_titles'] ?? [
        $dataDashboard['ks_title'] ?? 'Kekerasan Seksual',
        $dataDashboard['kf_title'] ?? 'Kekerasan Fisik',
        $dataDashboard['kp_title'] ?? 'Kekerasan Psikologis',
    ]);

    $datasets = [];
    $colors = ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#06B6D4', '#14B8A6', '#F43F5E', '#84CC16'];
    $colorIndex = 0;

    foreach ($jenisKasuses as $jenis) {
        $data = [];
        foreach ($years as $year) {
            $count = $laporans->filter(function($l) use ($year, $jenis) {
                return trim(strtolower($l->jenis_kasus)) === trim(strtolower($jenis)) &&
                       \Carbon\Carbon::parse($l->created_at)->year == $year;
            })->count();
            $data[] = $count;
        }
        $datasets[] = [
            'label' => $jenis,
            'data' => $data,
            'backgroundColor' => $colors[$colorIndex % count($colors)],
            'borderColor' => $colors[$colorIndex % count($colors)],
            'borderWidth' => 3,
            'fill' => false,
            'tension' => 0.4
        ];
        $colorIndex++;
    }

    $chartData = [
        'labels' => $years,
        'datasets' => $datasets
    ];
    $chartJson = json_encode($chartData);
    // -------------------------------------------------------------

    return view('public.depan', compact('kontenPencegahan', 'kontenPenanganan', 'kontenTentang', 'kontenKontak', 'galeris', 'carousels', 'kontenPeraturan', 'agendas', 'chartJson'));
});

// Rute Detail Berita (Public)
Route::get('/berita/{slug}', [AgendaController::class, 'show'])->name('agenda.show');

// Route untuk Manajemen Gambar Pop-up
Route::post('/informasi/promo/upload', [InformasiController::class, 'uploadPromo'])->name('promo.upload');
Route::post('/informasi/promo/hapus', [InformasiController::class, 'hapusPromo'])->name('promo.hapus');

// Rute Autentikasi Dasar
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// --- RUTE LUPA PASSWORD (VIA OTP EMAIL) ---
Route::get('/lupa-password', [AuthController::class, 'showLupaPasswordForm'])->name('password.request');
Route::post('/lupa-password/kirim-otp', [AuthController::class, 'kirimOtp'])->name('password.email');
Route::get('/lupa-password/verifikasi-otp', [AuthController::class, 'showVerifikasiOtpForm'])->name('password.verify');
Route::post('/lupa-password/verifikasi-otp', [AuthController::class, 'prosesVerifikasiOtp'])->name('password.verify.post');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'prosesResetPassword'])->name('password.update');

// Rute Cek Status Tiket
Route::post('/cek-status', [LaporanController::class, 'cariStatus'])->name('cek-status.cari');


// --- RUTE TERPROTEKSI (Harus Login) ---
Route::middleware(['auth'])->group(function () {

    Route::get('/index', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/notifikasi/{id}/baca', [LaporanController::class, 'bacaNotifikasi'])->name('notifikasi.baca');
    Route::post('/notifikasi/hapus-semua', [LaporanController::class, 'hapusSemuaNotifikasi'])->name('notifikasi.hapus_semua');

    Route::get('/dashboard/edit', [DashboardController::class, 'editDashboard'])->name('dashboard.edit');
    Route::post('/dashboard/update', [DashboardController::class, 'updateDashboard'])->name('dashboard.update');

    Route::get('/laporkan', [LaporanController::class, 'create'])->name('laporkan');
    Route::post('/lapor', [LaporanController::class, 'store'])->name('lapor.store');
    Route::get('/riwayat', [LaporanController::class, 'riwayat'])->name('riwayat');


    Route::get('/cek-status', [LaporanController::class, 'cekStatus'])->name('cek-status');

    // Rute Laporan - Bisa diakses Admin & Satgas (hanya GET)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{id}/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/cetak-pdf/{id}', [LaporanController::class, 'cetakPdf']);

    Route::get('/informasi/pencegahan', [InformasiController::class, 'pencegahan'])->name('informasi.pencegahan');
    Route::get('/informasi/penanganan', [InformasiController::class, 'penanganan'])->name('informasi.penanganan');
    Route::get('/kontak', [InformasiController::class, 'kontak'])->name('kontak');
    Route::get('/tentang', [InformasiController::class, 'tentang'])->name('tentang');
    Route::get('/galeri', [InformasiController::class, 'galeri'])->name('galeri');

    // Rute Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');
    Route::post('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/notifikasi', [PengaturanController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi');


    // --- KHUSUS ADMIN ---
    Route::middleware(['admin'])->group(function () {
        Route::post('/laporan/{id}/status', [LaporanController::class, 'updateStatus'])->name('laporan.update-status');
        Route::put('/laporan/riwayat/{id}', [LaporanController::class, 'updateRiwayat'])->name('laporan.riwayat.update');
        Route::delete('/laporan/riwayat/{id}', [LaporanController::class, 'destroyRiwayat'])->name('laporan.riwayat.destroy');
        Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        Route::post('/laporan/upload-ttd', [\App\Http\Controllers\LaporanController::class, 'uploadTtd'])->name('laporan.upload-ttd');


        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::resource('agenda', AgendaController::class)->except(['index', 'show']);

        Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
        Route::post('/arsip/simpan', [ArsipController::class, 'store'])->name('arsip.store');
        Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
        Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');

        Route::post('/carousel/upload', [DashboardController::class, 'uploadCarousel'])->name('carousel.upload');
        Route::post('/carousel/hapus', [DashboardController::class, 'hapusCarousel'])->name('carousel.hapus');

        Route::get('/informasi/pencegahan/edit', [InformasiController::class, 'editPencegahan'])->name('informasi.pencegahan.edit');
        Route::post('/informasi/pencegahan/update', [InformasiController::class, 'updatePencegahan'])->name('informasi.pencegahan.update');
        Route::get('/informasi/penanganan/edit', [InformasiController::class, 'editPenanganan'])->name('informasi.penanganan.edit');
        Route::post('/informasi/penanganan/update', [InformasiController::class, 'updatePenanganan'])->name('informasi.penanganan.update');
        Route::get('/kontak/edit', [InformasiController::class, 'editKontak'])->name('kontak.edit');
        Route::post('/kontak/update', [InformasiController::class, 'updateKontak'])->name('kontak.update');
        Route::get('/tentang/edit', [InformasiController::class, 'editTentang'])->name('tentang.edit');
        Route::post('/tentang/update', [InformasiController::class, 'updateTentang'])->name('tentang.update');

        Route::get('/informasi/peraturan/edit', [InformasiController::class, 'editPeraturan'])->name('informasi.peraturan.edit');
        Route::post('/informasi/peraturan/update', [InformasiController::class, 'updatePeraturan'])->name('informasi.peraturan.update');

        Route::post('/informasi/panduan/upload', [\App\Http\Controllers\InformasiController::class, 'uploadPanduan'])->name('panduan.upload');

        Route::get('/petugas', [\App\Http\Controllers\PetugasController::class, 'index'])->name('petugas.index');
        Route::post('/petugas', [\App\Http\Controllers\PetugasController::class, 'store'])->name('petugas.store');
        Route::put('/petugas/{id}', [\App\Http\Controllers\PetugasController::class, 'update'])->name('petugas.update');
        Route::delete('/petugas/{id}', [\App\Http\Controllers\PetugasController::class, 'destroy'])->name('petugas.destroy');
    });
});
