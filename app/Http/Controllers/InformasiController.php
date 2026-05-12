<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use App\Models\KontenHalaman;

class InformasiController extends Controller
{
    public function pencegahan()
    {
        $kontenPencegahan = KontenHalaman::where('halaman', 'pencegahan')->first();
        return view('pencegahan', compact('kontenPencegahan'));
    }

    public function editPencegahan()
    {
        $kontenPencegahan = KontenHalaman::where('halaman', 'pencegahan')->first();
        return view('edit-pencegahan', compact('kontenPencegahan'));
    }

    public function updatePencegahan(Request $request)
    {
        $dataKonten = $request->except(['_token']);
        KontenHalaman::updateOrCreate(
            ['halaman' => 'pencegahan'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->route('informasi.pencegahan')->with('success', 'Konten Halaman Pencegahan berhasil diperbarui!');
    }

    public function penanganan()
    {
        $kontenPenanganan = KontenHalaman::where('halaman', 'penanganan')->first();
        return view('penanganan', compact('kontenPenanganan'));
    }

    public function editPenanganan()
    {
        $kontenPenanganan = KontenHalaman::where('halaman', 'penanganan')->first();
        return view('edit-penanganan', compact('kontenPenanganan'));
    }

    public function updatePenanganan(Request $request)
    {
        $dataKonten = $request->except(['_token']);
        KontenHalaman::updateOrCreate(
            ['halaman' => 'penanganan'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->route('informasi.penanganan')->with('success', 'Konten Halaman Penanganan berhasil diperbarui!');
    }

    public function galeri()
    {
        $galeris = Arsip::whereIn('status_publikasi', ['sosialisasi', 'poster'])
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('galeri', compact('galeris'));
    }

    public function kontak()
    {
        $kontenKontak = KontenHalaman::where('halaman', 'kontak')->first();
        return view('kontak', compact('kontenKontak'));
    }

    public function editKontak()
    {
        $kontenKontak = KontenHalaman::where('halaman', 'kontak')->first();
        return view('edit-kontak', compact('kontenKontak'));
    }

    public function updateKontak(Request $request)
    {
        $dataKonten = $request->except(['_token']);
        KontenHalaman::updateOrCreate(
            ['halaman' => 'kontak'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->route('kontak')->with('success', 'Konten Halaman Kontak berhasil diperbarui!');
    }

    public function tentang()
    {
        $kontenTentang = KontenHalaman::where('halaman', 'tentang')->first();
        return view('tentang', compact('kontenTentang'));
    }

    public function editTentang()
    {
        $kontenTentang = KontenHalaman::where('halaman', 'tentang')->first();
        return view('edit-tentang', compact('kontenTentang'));
    }

    public function updateTentang(Request $request)
    {
        $dataKonten = $request->except(['_token', 'latar_img_upload']);

        $existing = KontenHalaman::where('halaman', 'tentang')->first();
        $existingData = $existing && $existing->konten ? json_decode($existing->konten, true) : [];

        if ($request->hasFile('latar_img_upload')) {
            $request->validate([
                'latar_img_upload' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            $file = $request->file('latar_img_upload');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('assets/image/tentang');

            if (!\Illuminate\Support\Facades\File::exists($destinationPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);

            $dataKonten['latar_img_url'] = 'assets/image/tentang/' . $filename;
        } else {
            $dataKonten['latar_img_url'] = $existingData['latar_img_url'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1000&auto=format&fit=crop';
        }

        KontenHalaman::updateOrCreate(
            ['halaman' => 'tentang'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->route('tentang')->with('success', 'Konten Halaman Tentang berhasil diperbarui!');
    }

    public function uploadPromo(Request $request)
    {
        $request->validate(['gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120']);
        $path = public_path('assets/image/promo');

        if (!\Illuminate\Support\Facades\File::exists($path)) {
            \Illuminate\Support\Facades\File::makeDirectory($path, 0755, true);
        }

        \Illuminate\Support\Facades\File::cleanDirectory($path);

        $file = $request->file('gambar');
        $nama_file = time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '', $file->getClientOriginalName());
        $file->move($path, $nama_file);

        return redirect()->back()->with('success', 'Gambar Poster Akan Datang berhasil ditambahkan!');
    }

    public function hapusPromo(Request $request)
    {
        $path = public_path('assets/image/promo/' . $request->nama_file);

        if (\Illuminate\Support\Facades\File::exists($path)) {
            \Illuminate\Support\Facades\File::delete($path);
        }

        return redirect()->back()->with('success', 'Gambar Poster Akan Datang berhasil dihapus!');
    }

    // --- FUNGSI BARU: MENGATUR PERATURAN ---
    public function editPeraturan()
    {
        $kontenPeraturan = KontenHalaman::where('halaman', 'peraturan')->first();
        return view('edit-peraturan', compact('kontenPeraturan'));
    }

    public function updatePeraturan(Request $request)
    {
        $peraturan_items = [];
        $titles = $request->input('judul', []);
        $nomors = $request->input('nomor', []);
        $tahuns = $request->input('tahun', []);
        $descs = $request->input('deskripsi', []);
        $old_files = $request->input('old_file_url', []);

        foreach ($titles as $idx => $title) {
            $file_url = $old_files[$idx] ?? '';

            // Jika ada file PDF baru yang diunggah pada kotak bersangkutan
            if ($request->hasFile("file_upload.{$idx}")) {
                $file = $request->file("file_upload.{$idx}");
                $filename = time() . '_' . $idx . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('assets/aturan');

                if (!\Illuminate\Support\Facades\File::exists($destinationPath)) {
                    \Illuminate\Support\Facades\File::makeDirectory($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $filename);
                $file_url = 'assets/aturan/' . $filename;
            }

            $peraturan_items[] = [
                'nomor' => $nomors[$idx] ?? '',
                'tahun' => $tahuns[$idx] ?? '',
                'judul' => $title,
                'deskripsi' => $descs[$idx] ?? '',
                'file_url' => $file_url,
            ];
        }

        KontenHalaman::updateOrCreate(
            ['halaman' => 'peraturan'],
            ['konten' => json_encode(['peraturan_items' => $peraturan_items])]
        );

        return redirect()->route('laporkan')->with('success', 'Peraturan berhasil diperbarui!');
    }
}
