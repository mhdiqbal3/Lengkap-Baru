<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AgendaController extends Controller
{
    public function index()
    {
        // Semua user yang login bisa melihat halaman index agenda
        $agendas = Agenda::orderBy('created_at', 'desc')->get();
        return view('agenda', compact('agendas'));
    }

    public function store(Request $request)
    {
        // Izinkan Admin dan Satgas untuk menambah berita
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['judul', 'konten']);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/agenda');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $data['gambar'] = 'assets/agenda/' . $fileName;
        }

        Agenda::create($data);

        return redirect()->back()->with('success', 'Berita & Agenda berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // Izinkan Admin dan Satgas untuk mengubah berita
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $agenda = Agenda::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = $request->only(['judul', 'konten']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($agenda->gambar && File::exists(public_path($agenda->gambar))) {
                File::delete(public_path($agenda->gambar));
            }

            $file = $request->file('gambar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/agenda');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $data['gambar'] = 'assets/agenda/' . $fileName;
        }

        $agenda->update($data);

        return redirect()->back()->with('success', 'Berita & Agenda berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Izinkan Admin dan Satgas untuk menghapus berita
        if (!in_array(Auth::user()->role, ['admin', 'satgas'])) {
            abort(403, 'Akses Ditolak.');
        }

        $agenda = Agenda::findOrFail($id);

        // Hapus file gambar dari server jika ada
        if ($agenda->gambar && File::exists(public_path($agenda->gambar))) {
            File::delete(public_path($agenda->gambar));
        }

        $agenda->delete();

        return redirect()->back()->with('success', 'Berita & Agenda berhasil dihapus.');
    }
}
