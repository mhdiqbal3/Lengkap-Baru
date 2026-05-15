<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    public function index()
    {
        // Semua user yang login (Admin, Satgas, Masyarakat) bisa melihat daftar agenda
        $agendas = Agenda::orderBy('created_at', 'desc')->get();
        return view('agenda', compact('agendas'));
    }

    /**
     * Menampilkan detail agenda berdasarkan slug.
     */
    public function show($slug)
    {
        // Menggunakan firstOrFail agar mengembalikan model tunggal, bukan collection
        $agenda = Agenda::where('slug', $slug)->firstOrFail();

        // Mengambil data agenda lain untuk rekomendasi (perbaikan Undefined Variable)
        $agendas_lain = Agenda::where('id', '!=', $agenda->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('agenda-detail', compact('agenda', 'agendas_lain'));
    }

    public function store(Request $request)
    {
        // --- PERBAIKAN: Izinkan Admin DAN Satgas ---
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['judul', 'konten', 'penulis', 'tanggal', 'status']);

        // Otomatis buat slug
        $data['slug'] = Str::slug($request->judul) . '-' . time();

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/agenda');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $data['thumbnail'] = 'assets/agenda/' . $fileName;
        }

        Agenda::create($data);

        return redirect()->back()->with('success', 'Berita & Agenda berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // --- PERBAIKAN: Izinkan Admin DAN Satgas ---
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['judul', 'konten', 'penulis', 'tanggal', 'status']);

        // Update slug jika judul berubah
        if ($agenda->judul !== $request->judul) {
            $data['slug'] = Str::slug($request->judul) . '-' . time();
        }

        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama
            if ($agenda->thumbnail && File::exists(public_path($agenda->thumbnail))) {
                File::delete(public_path($agenda->thumbnail));
            }

            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/agenda');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $data['thumbnail'] = 'assets/agenda/' . $fileName;
        }

        $agenda->update($data);

        return redirect()->back()->with('success', 'Berita & Agenda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // --- PERBAIKAN: Izinkan Admin DAN Satgas ---
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $agenda = Agenda::findOrFail($id);

        if ($agenda->thumbnail && File::exists(public_path($agenda->thumbnail))) {
            File::delete(public_path($agenda->thumbnail));
        }

        $agenda->delete();

        return redirect()->back()->with('success', 'Berita & Agenda berhasil dihapus.');
    }
}
