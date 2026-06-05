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
        // 1. Validasi input
        $request->validate([
            'judul_lapor'      => 'required|string|max:255',
            'jenis_kasus'      => 'required|string',
            'nama_korban'      => 'required|string|max:255',
            'no_hp_korban'     => 'required|string|max:20',
            'status_korban'    => 'required|string',
            'status_terlapor'  => 'required|string',
            'jenis_kelamin'    => 'required|in:L,P',
            'disabilitas'      => 'required|in:ya,tidak',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian'  => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'link_video'       => 'nullable|url',
            'bukti'            => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            // ---------------------------------------------------------
            // PERBAIKAN: Logika Generate Kode Tiket Anti-Duplikat
            // ---------------------------------------------------------
            // Gunakan DB facade untuk memastikan membaca data paling akhir
            $latestLaporan = \Illuminate\Support\Facades\DB::table('laporans')
                ->orderBy('id', 'desc')
                ->first();

            if (!$latestLaporan) {
                $kodeTiket = 'PPKPT_001';
            } else {
                // Ambil kode terakhir (contoh: "PPKPT_005")
                $lastCode = $latestLaporan->kode_tiket;

                // Pisahkan string untuk mendapatkan angkanya saja
                $parts = explode('_', $lastCode);
                $lastNumber = isset($parts[1]) ? intval($parts[1]) : 0;

                // Tambahkan 1 untuk laporan baru (005 + 1 = 6)
                $newNumber = $lastNumber + 1;

                // Format kembali menjadi PPKPT_XXX (hasil: PPKPT_006)
                $kodeTiket = 'PPKPT_' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
            // ---------------------------------------------------------

            // Upload bukti jika ada
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('assets/bukti', 'public');
            }

            // Simpan ke database
            $laporan = \App\Models\Laporan::create([
                'user_id'          => \Illuminate\Support\Facades\Auth::id(), // Akan otomatis terisi jika login, null jika anonim
                'kode_tiket'       => $kodeTiket,
                'judul_lapor'      => $request->judul_lapor,
                'jenis_kasus'      => $request->jenis_kasus,
                'nama_korban'      => $request->nama_korban,
                'no_hp_korban'     => $request->no_hp_korban,
                'status_korban'    => $request->status_korban,
                'status_terlapor'  => $request->status_terlapor,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'disabilitas'      => $request->disabilitas,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'lokasi_kejadian'  => $request->lokasi_kejadian,
                'deskripsi'        => $request->deskripsi,
                'link_video'       => $request->link_video,
                'bukti'            => $buktiPath,
                'status'           => 'Menunggu Verifikasi',
            ]);

            // Jika dipanggil via AJAX (Dari Laporkan Blade)
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'kode_tiket' => $kodeTiket,
                    'message' => 'Laporan berhasil dikirim'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil dikirim! Kode Tiket: ' . $kodeTiket);
        } catch (\Exception $e) {
            // Tangkap dan kembalikan error ke AJAX agar tampil di alert halaman
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
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
                'title' => "{$laporan->kode_tiket} Diperbarui",
                'message' => "Laporan Anda kini berstatus: {$laporan->status}.",
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
