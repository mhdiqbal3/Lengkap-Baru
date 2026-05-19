<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Notification;
use App\Models\KontenHalaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function riwayat(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        if ($perPage == 0) {
            $perPage = 999999;
        }

        $search = $request->input('search');

        $query = Laporan::where('user_id', Auth::id());

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhere('judul_lapor', 'like', "%{$search}%");
            });
        }

        $laporans = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('riwayat', compact('laporans'));
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        if ($perPage == 0) {
            $perPage = 999999;
        }

        $search = $request->input('search');
        $query = Laporan::with('user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_tiket', 'like', "%{$search}%")
                    ->orWhere('judul_lapor', 'like', "%{$search}%")
                    ->orWhere('nama_korban', 'like', "%{$search}%");
            });
        }

        $laporans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $rekapan = [
            'total' => Laporan::count(),
            'menunggu' => Laporan::where('status', 'Menunggu Verifikasi')->count(),
            'diproses' => Laporan::where('status', 'Sedang Diproses')->count(),
            'selesai' => Laporan::where('status', 'Selesai')->count(),
            'ditolak' => Laporan::where('status', 'Ditolak')->count(),
        ];

        return view('laporan', compact('laporans', 'rekapan'));
    }

    public function cetak($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('laporan-cetak', compact('laporan'));
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('laporan-detail', compact('laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_lapor' => 'required|string|max:255',
            'jenis_kasus' => 'required|string',
            'nama_korban' => 'required|string',
            'no_hp_korban' => 'required|string',
            'status_korban' => 'required|string',
            'status_terlapor' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'disabilitas' => 'required|in:ya,tidak',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian' => 'required|string',
            'deskripsi' => 'required|string',
            'link_video' => 'nullable|url',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $pathBukti = null;

            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('assets/bukti');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                $file->move($destinationPath, $fileName);
                $pathBukti = 'assets/bukti/' . $fileName;
            }

            $lastLaporan = Laporan::where('kode_tiket', 'like', 'PPKPT_%')->orderBy('id', 'desc')->first();

            if (!$lastLaporan) {
                $newKodeTiket = 'PPKPT_001';
            } else {
                $lastNumber = (int) substr($lastLaporan->kode_tiket, 5);
                $newNumber = $lastNumber + 1;
                $newKodeTiket = 'PPPT-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }

            $laporan = Laporan::create([
                'user_id' => Auth::id(),
                'kode_tiket' => $newKodeTiket,
                'judul_lapor' => $request->judul_lapor,
                'jenis_kasus' => $request->jenis_kasus,
                'nama_korban' => $request->nama_korban,
                'no_hp_korban' => $request->no_hp_korban,
                'status_korban' => $request->status_korban,
                'status_terlapor' => $request->status_terlapor,
                'jenis_kelamin' => $request->jenis_kelamin,
                'disabilitas' => $request->disabilitas,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'lokasi_kejadian' => $request->lokasi_kejadian,
                'deskripsi' => $request->deskripsi,
                // PERBAIKAN: Jika request link_video null, masukkan string kosong ''
                'link_video' => $request->link_video ?? '',
                'bukti' => $pathBukti,
                'status' => 'Menunggu Verifikasi',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dikirim dengan Kode Tiket: ' . $laporan->kode_tiket,
                'kode_tiket' => $laporan->kode_tiket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cekStatus()
    {
        return view('cek-status');
    }

    public function cariStatus(Request $request)
    {
        $request->validate([
            'kode_tiket' => 'required|string'
        ], [
            'kode_tiket.required' => 'Mohon masukkan Kode Tiket Anda terlebih dahulu.'
        ]);

        $kode_tiket = strtoupper(trim($request->kode_tiket));

        $laporan = Laporan::where('kode_tiket', $kode_tiket)->first();
        $request->flash();

        if (!$laporan) {
            return view('cek-status', ['error' => 'Kode tiket tidak ditemukan.']);
        }

        return view('cek-status', compact('laporan'));
    }

    public function create()
    {
        $kontenPeraturan = KontenHalaman::where('halaman', 'peraturan')->first();
        return view('laporkan', compact('kontenPeraturan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Sedang Diproses,Selesai,Ditolak'
        ]);

        $laporan = Laporan::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->save();

        if ($laporan->user_id) {
            Notification::create([
                'user_id' => $laporan->user_id,
                'title' => 'Status Laporan Diperbarui',
                'message' => "Laporan Anda dengan kode tiket {$laporan->kode_tiket} kini berstatus: {$laporan->status}.",
                'url' => url('/cek-status'),
                'is_read' => false
            ]);
        }

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui dan notifikasi telah dikirim ke pelapor!');
    }

    public function bacaNotifikasi($id)
    {
        $notif = Notification::find($id);

        if ($notif && $notif->user_id == Auth::id()) {
            $notif->update(['is_read' => true]);
        }

        return redirect('/cek-status');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_lapor' => 'required|string|max:255',
            'jenis_kasus' => 'required|string',
            'no_hp_korban' => 'required|string',
            'status_korban' => 'required|string',
            // PERBAIKAN TAMBAHAN: pastikan form edit juga divalidasi
            'status_terlapor' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'disabilitas' => 'required|in:ya,tidak',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian' => 'required|string',
            'deskripsi' => 'required|string',
            'link_video' => 'nullable|url',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $laporan = Laporan::findOrFail($id);

        if ($laporan->status !== 'Menunggu Verifikasi') {
            return redirect()->back()->with('error', 'Laporan yang sudah diproses tidak dapat diedit.');
        }

        $pathBukti = $laporan->bukti;

        if ($request->hasFile('bukti')) {
            if ($laporan->bukti && File::exists(public_path($laporan->bukti))) {
                File::delete(public_path($laporan->bukti));
            }

            $file = $request->file('bukti');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/bukti');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);
            $pathBukti = 'assets/bukti/' . $fileName;
        }

        $isAnonim = $request->has('is_anonim') ? true : false;

        $laporan->update([
            'judul_lapor' => $request->judul_lapor,
            'jenis_kasus' => $request->jenis_kasus,
            'is_anonim' => $isAnonim,
            'nama_korban' => $isAnonim ? null : $request->nama_korban,
            'no_hp_korban' => $request->no_hp_korban,
            'status_korban' => $request->status_korban,
            // PERBAIKAN TAMBAHAN: masukkan data baru ke fungsi update juga
            'status_terlapor' => $request->status_terlapor,
            'jenis_kelamin' => $request->jenis_kelamin,
            'disabilitas' => $request->disabilitas,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'lokasi_kejadian' => $request->lokasi_kejadian,
            'deskripsi' => $request->deskripsi,
            'link_video' => $request->link_video ?? '',
            'bukti' => $pathBukti,
        ]);

        return redirect()->back()->with('success', 'Data laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);

        if ($laporan->bukti && File::exists(public_path($laporan->bukti))) {
            File::delete(public_path($laporan->bukti));
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus secara permanen.');
    }

    public function cetakPdf($id)
    {
        $laporan = \App\Models\Laporan::findOrFail($id);
        $pdf = Pdf::loadView('cetak-laporan', compact('laporan'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Bukti_Laporan_' . $laporan->kode_tiket . '.pdf');
    }
}
