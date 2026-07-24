<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\KontenHalaman;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');
        $query = Laporan::query();
        $now = Carbon::now();

        switch ($filter) {
            case 'hari':
                $query->whereDate('created_at', $now->toDateString());
                break;
            case 'minggu':
                $query->whereBetween('created_at', [$now->startOfWeek(), $now->endOfWeek()]);
                break;
            case 'bulan':
                $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
                break;
            case 'tahun':
                $query->whereYear('created_at', $now->year);
                break;
            case 'semua':
            default:
                break;
        }

        $cards = [
            'total' => (clone $query)->count(),
            'menunggu' => (clone $query)->where('status', 'Menunggu Verifikasi')->count(),
            'diproses' => (clone $query)->where('status', 'Sedang Diproses')->count(),
            'selesai' => (clone $query)->where('status', 'Selesai')->count(),
        ];

        $chartStatus = [
            'Menunggu' => $cards['menunggu'],
            'Diproses' => $cards['diproses'],
            'Selesai' => $cards['selesai'],
        ];

        // Bentuk Kekerasan Standar
        $predefinedJenisKasus = [
            'Kekerasan Seksual' => 0,
            'Kekerasan Fisik' => 0,
            'Kekerasan Psikis' => 0,
            'Perundungan' => 0,
            'Diskriminasi dan Intoleransi' => 0,
            'Kebijakan Unsur Kekerasan' => 0,
        ];

        // Ambil semua jenis kasus dari data laporan
        $dbJenisKasus = Laporan::selectRaw('jenis_kasus, count(*) as total')
            ->whereNotNull('jenis_kasus')
            ->where('jenis_kasus', '!=', '')
            ->groupBy('jenis_kasus')
            ->pluck('total', 'jenis_kasus')
            ->toArray();

        // Gabungkan predefined dengan data aktual dari DB
        $allJenisKasus = $predefinedJenisKasus;
        foreach ($dbJenisKasus as $jenis => $total) {
            if (isset($allJenisKasus[$jenis])) {
                $allJenisKasus[$jenis] += $total;
            } else {
                $allJenisKasus[$jenis] = $total; // Kasus "Lainnya" yang diketik manual
            }
        }

        // Data time-series untuk line chart (12 bulan terakhir)
        $timeSeriesData = $this->buildTimeSeriesData();

        $carouselPath = public_path('assets/image/kolosel');
        $carousels = [];

        if (File::exists($carouselPath)) {
            $files = File::files($carouselPath);
            foreach ($files as $file) {
                $carousels[] = [
                    'nama' => $file->getFilename(),
                    'url' => asset('assets/image/kolosel/' . $file->getFilename())
                ];
            }
        }

        $kontenDashboard = KontenHalaman::where('halaman', 'dashboard')->first();

        return view('dashboard.index', compact('cards', 'filter', 'chartStatus', 'allJenisKasus', 'timeSeriesData', 'carousels', 'kontenDashboard'));
    }

    private function buildTimeSeriesData(): array
    {
        $now = Carbon::now();

        // === DATA BULANAN: 12 bulan terakhir ===
        $monthlyLabels = [];
        $monthlyJenisKasus = [];
        $predefinedJenisKasus = [
            'Kekerasan Seksual',
            'Kekerasan Fisik',
            'Kekerasan Psikis',
            'Perundungan',
            'Diskriminasi dan Intoleransi',
            'Kebijakan Unsur Kekerasan',
        ];

        $dbJenisKasus = Laporan::selectRaw('DISTINCT jenis_kasus')
            ->whereNotNull('jenis_kasus')
            ->where('jenis_kasus', '!=', '')
            ->pluck('jenis_kasus')
            ->toArray();

        // Gabung predefined dengan yg ada di DB
        $allJenisKasus = array_unique(array_merge($predefinedJenisKasus, $dbJenisKasus));

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('M Y');
        }

        foreach ($allJenisKasus as $jenis) {
            $monthlyJenisKasus[$jenis] = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = $now->copy()->subMonths($i);
                // FIX: Hitung hanya laporan BARU di bulan tersebut (bukan kumulatif)
                $monthlyJenisKasus[$jenis][] = Laporan::where('jenis_kasus', $jenis)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        }

        // === DATA MINGGUAN: 12 minggu terakhir ===
        $weeklyLabels = [];
        $weeklyJenisKasus = [];
        for ($i = 11; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $weekEnd   = $now->copy()->subWeeks($i)->endOfWeek();
            $weeklyLabels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
        }
        foreach ($allJenisKasus as $jenis) {
            $weeklyJenisKasus[$jenis] = [];
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd   = $now->copy()->subWeeks($i)->endOfWeek();
                // FIX: Hitung hanya laporan BARU di minggu tersebut (bukan kumulatif)
                $weeklyJenisKasus[$jenis][] = Laporan::where('jenis_kasus', $jenis)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->count();
            }
        }

        // === DATA HARIAN: 14 hari terakhir ===
        $dailyLabels = [];
        $dailyJenisKasus = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i);
            $dailyLabels[] = $day->format('d M');
        }
        foreach ($allJenisKasus as $jenis) {
            $dailyJenisKasus[$jenis] = [];
            for ($i = 13; $i >= 0; $i--) {
                $day = $now->copy()->subDays($i);
                // FIX: Hitung hanya laporan BARU di hari tersebut (bukan kumulatif)
                $dailyJenisKasus[$jenis][] = Laporan::where('jenis_kasus', $jenis)
                    ->whereDate('created_at', $day->toDateString())
                    ->count();
            }
        }

        return [
            'bulanan'  => ['labels' => $monthlyLabels,  'series' => $monthlyJenisKasus],
            'mingguan' => ['labels' => $weeklyLabels,   'series' => $weeklyJenisKasus],
            'harian'   => ['labels' => $dailyLabels,    'series' => $dailyJenisKasus],
        ];
    }

    public function uploadCarousel(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Ambil nama asli tanpa ekstensi dan bersihkan dari karakter unik
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^a-zA-Z0-9_]/', '', $originalName);
            
            // Paksa ekstensi menjadi .webp
            $filename = time() . '_' . $cleanName . '.webp';

            $destinationPath = public_path('assets/image/kolosel');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // ========================================================
            // PROSES KONVERSI KE WEBP (MENGGUNAKAN NATIVE PHP GD)
            // ========================================================
            $mime = $file->getMimeType();
            $image = null;

            // Membaca file berdasarkan tipe MIME
            if ($mime == 'image/jpeg') {
                $image = @imagecreatefromjpeg($file->getRealPath());
            } elseif ($mime == 'image/png') {
                $image = @imagecreatefrompng($file->getRealPath());
                // Mempertahankan transparansi untuk gambar PNG
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
            } elseif ($mime == 'image/webp') {
                $image = @imagecreatefromwebp($file->getRealPath());
            }

            // Jika gambar berhasil dibaca
            if ($image) {
                // Simpan sebagai WebP dengan kualitas 90 (Kualitas Jernih)
                imagewebp($image, $destinationPath . '/' . $filename, 90);
                imagedestroy($image); // Bebaskan memori
            } else {
                // Fallback aman: Jika sistem gagal membaca, simpan file seperti biasa tanpa konversi
                $fallbackExtension = $file->getClientOriginalExtension();
                $fallbackFilename = time() . '_' . $cleanName . '.' . $fallbackExtension;
                $file->move($destinationPath, $fallbackFilename);
            }
            // ========================================================

            return redirect()->route('dashboard')->with('success', 'Gambar beranda berhasil ditambahkan dan dikonversi ke format WebP resolusi tinggi!');
        }

        return redirect()->route('dashboard')->with('error', 'Gagal mengunggah gambar.');
    }

    public function hapusCarousel(Request $request)
    {
        $request->validate(['nama_file' => 'required|string']);

        $filePath = public_path('assets/image/kolosel/' . $request->nama_file);

        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('dashboard')->with('success', 'Gambar beranda berhasil dihapus!');
        }

        return redirect()->route('dashboard')->with('error', 'Gambar tidak ditemukan.');
    }

    public function editDashboard()
    {
        $kontenDashboard = KontenHalaman::where('halaman', 'dashboard')->first();
        return view('dashboard.edit-dashboard', compact('kontenDashboard'));
    }

    public function updateDashboard(Request $request)
    {
        $dataKonten = $request->except(['_token']);
        KontenHalaman::updateOrCreate(
            ['halaman' => 'dashboard'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->route('dashboard')->with('success', 'Konten Dashboard berhasil diperbarui!');
    }
}