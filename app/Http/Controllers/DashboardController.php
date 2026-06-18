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
            'ditolak' => (clone $query)->where('status', 'Ditolak')->count(),
        ];

        $chartStatus = [
            'Menunggu' => $cards['menunggu'],
            'Diproses' => $cards['diproses'],
            'Selesai' => $cards['selesai'],
            'Ditolak' => $cards['ditolak'],
        ];

        $jenisKasusData = (clone $query)
            ->selectRaw('jenis_kasus, count(*) as total')
            ->groupBy('jenis_kasus')
            ->pluck('total', 'jenis_kasus')
            ->toArray();

        if (empty($jenisKasusData)) {
            $jenisKasusData = ['Belum ada data' => 1];
        }

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

        return view('dashboard.index', compact('cards', 'filter', 'chartStatus', 'jenisKasusData', 'carousels', 'kontenDashboard'));
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