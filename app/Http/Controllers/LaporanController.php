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

        return view('laporan.riwayat', compact('laporans'));
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

        return view('laporan.laporan', compact('laporans', 'rekapan'));
    }

    public function cetak($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('laporan.laporan-cetak', compact('laporan'));
    }

    public function show($id)
    {
        $laporan = Laporan::with('user')->findOrFail($id);
        return view('laporan.laporan-detail', compact('laporan'));
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
            'status_korban_lainnya' => 'nullable|string|max:255',
            'status_terlapor'  => 'required|string',
            'jenis_kelamin'    => 'required|in:L,P',
            'disabilitas'      => 'required|in:ya,tidak',
            'saksi_nama'       => 'nullable|string|max:255',
            'saksi_pekerjaan'  => 'nullable|string|max:255',
            'saksi_telepon'    => 'nullable|string|max:20',
            'saksi_alamat'     => 'nullable|string',
            'tanggal_kejadian' => 'required|date',
            'lokasi_kejadian'  => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'link_video'       => 'nullable|url',
            'bukti'            => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            // ---------------------------------------------------------
            // PERBAIKAN: Logika Generate Kode Tiket Anti-Duplikat (Acak 8 Digit)
            // ---------------------------------------------------------
            do {
                $kodeTiket = strtoupper(\Illuminate\Support\Str::random(8));
            } while (\Illuminate\Support\Facades\DB::table('laporans')->where('kode_tiket', $kodeTiket)->exists());
            // ---------------------------------------------------------

            // Upload bukti jika ada
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('assets/bukti', 'public');
            }

            // Siapkan data saksi jika diisi
            $saksiData = null;
            if ($request->filled('saksi_nama') || $request->filled('saksi_pekerjaan') || $request->filled('saksi_telepon') || $request->filled('saksi_alamat')) {
                $saksiData = json_encode([
                    'nama' => $request->saksi_nama,
                    'pekerjaan' => $request->saksi_pekerjaan,
                    'telepon' => $request->saksi_telepon,
                    'alamat' => $request->saksi_alamat,
                ]);
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
                'status_korban_lainnya' => $request->status_korban === 'lainnya' ? $request->status_korban_lainnya : null,
                'status_terlapor'  => $request->status_terlapor,
                'status_terlapor_lainnya' => $request->status_terlapor === 'lainnya' ? $request->status_terlapor_lainnya : null,
                'jenis_kelamin'    => $request->jenis_kelamin,
                'disabilitas'      => $request->disabilitas,
                'tanggal_kejadian' => $request->tanggal_kejadian,
                'lokasi_kejadian'  => $request->lokasi_kejadian,
                'saksi'            => $saksiData,
                'deskripsi'        => $request->deskripsi,
                'link_video'       => $request->link_video ?? '',
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
        return view('laporan.cek-status');
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
            return view('laporan.cek-status', ['error' => 'Kode tiket tidak ditemukan.']);
        }

        return view('laporan.cek-status', compact('laporan'));
    }

    public function create()
    {
        $kontenPeraturan = KontenHalaman::where('halaman', 'peraturan')->first();
        return view('laporan.laporkan', compact('kontenPeraturan'));
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
        $pdf = Pdf::loadView('laporan.cetak-laporan', compact('laporan'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('Bukti_Laporan_' . $laporan->kode_tiket . '.pdf');
    }

    public function uploadTtd(Request $request)
    {
        // Validasi diubah: file_ttd sekarang opsional (nullable) agar bisa edit nama saja
        $request->validate([
            'file_ttd' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'nama_ketua' => 'nullable|string|max:255',
            'nip_ketua' => 'nullable|string|max:255',
        ]);

        $konten = \App\Models\KontenHalaman::where('halaman', 'pengaturan_surat')->first();
        $dataKonten = $konten ? json_decode($konten->konten, true) : [];

        // 1. Proses Upload Gambar (Jika ada gambar baru yang dipilih)
        if ($request->hasFile('file_ttd')) {
            $path = public_path('assets/image/surat');
            if (!\Illuminate\Support\Facades\File::exists($path)) {
                \Illuminate\Support\Facades\File::makeDirectory($path, 0755, true);
            }

            $file = $request->file('file_ttd');
            $filename = 'ttd_admin_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $filename);

            // Hapus gambar lama agar tidak menumpuk
            if (isset($dataKonten['ttd_url']) && \Illuminate\Support\Facades\File::exists(public_path($dataKonten['ttd_url']))) {
                \Illuminate\Support\Facades\File::delete(public_path($dataKonten['ttd_url']));
            }

            $dataKonten['ttd_url'] = 'assets/image/surat/' . $filename;
        }

        // 2. Simpan Data Nama dan NIP
        if ($request->filled('nama_ketua')) {
            $dataKonten['nama_ketua'] = $request->nama_ketua;
        }
        if ($request->filled('nip_ketua')) {
            $dataKonten['nip_ketua'] = $request->nip_ketua;
        }

        // Simpan ke database
        \App\Models\KontenHalaman::updateOrCreate(
            ['halaman' => 'pengaturan_surat'],
            ['konten' => json_encode($dataKonten)]
        );

        return redirect()->back()->with('success', 'Pengaturan Surat (Tanda Tangan & Pejabat) berhasil disimpan!');
    }
}
