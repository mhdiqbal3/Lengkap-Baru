<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arsip;
use Illuminate\Support\Facades\File;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::orderBy('tanggal', 'desc')->limit(50)->get();
        return view('arsip', compact('arsips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan'   => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string',
            'tanggal'          => 'required|date',
            'lokasi'           => 'required|string|max:255',
            'status_publikasi' => 'required|string',
            'deskripsi'        => 'required|string',
            'dokumentasi'      => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            // Membuat nama file unik
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Tentukan lokasi folder: public/assets/kegiatan
            $destinationPath = public_path('assets/kegiatan');

            // Cek jika folder belum ada, maka buat foldernya
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Pindahkan file
            $file->move($destinationPath, $fileName);

            // Simpan path yang akan digunakan oleh helper asset()
            $dokumentasiPath = 'assets/kegiatan/' . $fileName;
        }

        Arsip::create([
            'user_id'          => auth()->id(),
            'judul_kegiatan'   => $request->judul_kegiatan,
            'jenis_kegiatan'   => $request->jenis_kegiatan,
            'tanggal'          => $request->tanggal,
            'lokasi'           => $request->lokasi,
            'status_publikasi' => $request->status_publikasi,
            'deskripsi'        => $request->deskripsi,
            'dokumentasi'      => $dokumentasiPath,
        ]);

        return redirect()->route('arsip.index')->with('success', 'Data kegiatan berhasil disimpan!');
    }

    public function destroy($id)
    {
        $arsip = Arsip::findOrFail($id);

        // Menghapus file fisik gambar dari folder public/assets/kegiatan agar tidak menjadi sampah
        if ($arsip->dokumentasi && File::exists(public_path($arsip->dokumentasi))) {
            File::delete(public_path($arsip->dokumentasi));
        }

        // Hapus data dari database
        $arsip->delete();

        return redirect()->route('arsip.index')->with('success', 'Data arsip berhasil dihapus permanen!');
    }

    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);

        $request->validate([
            'judul_kegiatan'   => 'required|string|max:255',
            'jenis_kegiatan'   => 'required|string',
            'tanggal'          => 'required|date',
            'lokasi'           => 'required|string|max:255',
            'status_publikasi' => 'required|string',
            'deskripsi'        => 'required|string',
            'dokumentasi'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $dokumentasiPath = $arsip->dokumentasi; // Gunakan gambar lama sebagai default

        // Jika user mengunggah gambar baru
        if ($request->hasFile('dokumentasi')) {
            // Hapus gambar lama dari folder public/assets/kegiatan jika ada
            if ($arsip->dokumentasi && \Illuminate\Support\Facades\File::exists(public_path($arsip->dokumentasi))) {
                \Illuminate\Support\Facades\File::delete(public_path($arsip->dokumentasi));
            }

            // Simpan gambar baru
            $file = $request->file('dokumentasi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/kegiatan');

            if (!\Illuminate\Support\Facades\File::exists($destinationPath)) {
                \Illuminate\Support\Facades\File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $dokumentasiPath = 'assets/kegiatan/' . $fileName;
        }

        // Update data ke database (Typo jenis_kegiatab sudah diperbaiki)
        $arsip->update([
            'judul_kegiatan'   => $request->judul_kegiatan,
            'jenis_kegiatan'   => $request->jenis_kegiatan,
            'tanggal'          => $request->tanggal,
            'lokasi'           => $request->lokasi,
            'status_publikasi' => $request->status_publikasi,
            'deskripsi'        => $request->deskripsi,
            'dokumentasi'      => $dokumentasiPath,
        ]);

        return redirect()->route('arsip.index')->with('success', 'Data arsip berhasil diperbarui!');
    }
}
