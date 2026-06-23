@php
    // SCRIPT BARU UNTUK MENGAMBIL DATA TTD DARI DATABASE
    $pengaturanSurat = \App\Models\KontenHalaman::where('halaman', 'pengaturan_surat')->first();
    $ttdPath = null;
    if ($pengaturanSurat) {
        $dataSurat = json_decode($pengaturanSurat->konten, true);
        if (isset($dataSurat['ttd_url'])) {
            $ttdPath = public_path($dataSurat['ttd_url']);
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Bukti Laporan</title>
    <style>
        /* Pengaturan Kertas Letter untuk DomPDF agar muat 1 halaman */
        @page {
            size: letter;
            margin: 15mm 20mm;
            /* Margin atas-bawah diperkecil agar tidak tumpah ke hal 2 */
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            /* Ukuran font dioptimalkan */
            color: #000;
            line-height: 1.3;
            /* Jarak antar baris lebih rapat */
            margin: 0;
            padding: 0;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 4px double #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .kop-surat td {
            vertical-align: middle;
            text-align: center;
        }

        .logo-usn {
            width: 75px;
            /* Sedikit dikecilkan agar hemat ruang */
        }

        .logo-satgas {
            width: 75px;
        }

        .header-text {
            font-size: 12pt;
            margin: 0;
            line-height: 1.1;
        }

        .header-text-small {
            font-size: 9pt;
            font-weight: normal;
            margin-top: 3px;
        }

        /* Judul Surat */
        .judul-surat {
            text-align: center;
            margin-bottom: 15px;
            margin-top: 5px;
        }

        .judul-surat h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: none;
            text-transform: uppercase;
        }

        .judul-surat p {
            margin: 2px 0 0 0;
            font-size: 11pt;
        }

        /* Konten Isi */
        .content-laporan {
            text-align: justify;
        }

        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .tabel-data td {
            vertical-align: top;
            padding: 3px 0;
            /* Padding dipersempit */
        }

        /* Box Kronologi dioptimalkan agar tidak memakan tempat berlebih */
        .box-kronologi {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            min-height: 80px;
            /* Tinggi minimal dikurangi */
            text-align: justify;
            font-size: 10.5pt;
            line-height: 1.3;
        }

        /* Tanda Tangan Sisi Kanan */
        .wrapper-ttd {
            width: 100%;
            margin-top: 0;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ttd-spacer {
            width: 58%;
        }

        .ttd-content {
            width: 42%;
            text-align: left;
        }

        .nama-ttd {
            margin-top: 5px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <table class="kop-surat">
        <tr>
            <td width="15%">
                <img src="{{ public_path('assets/image/USN.png') }}" class="logo-usn" alt="Logo USN">
            </td>
            <td width="65%">
                <div class="header-text">
                    KEMENTERIAN PENDIDIKAN TINGGI,<br>SAINS, DAN TEKNOLOGI
                    <br>
                    <strong>UNIVERSITAS SEMBILANBELAS NOVEMBER KOLAKA</strong>
                    <br>
                    <strong>SATUAN TUGAS PENCEGAHAN DAN PENANGANAN KEKERASAN DI LINGKUNGAN PERGURUAN TINGGI</strong>
                </div>
                <div class="header-text-small">
                    Jl. Pemuda No. 339, Kabupaten Kolaka, Sulawesi Tenggara
                    <br>Telepon (0405) 2321132; Laman: http://usn.ac.id
                    <br>
                </div>
            </td>
            <td width="15%">
                <img src="{{ public_path('assets/image/logo.PNG') }}" class="logo-satgas" alt="Logo Satgas">
            </td>
        </tr>
    </table>

    <div class="judul-surat">
        <h3><u>BUKTI PENERIMAAN LAPORAN PENGADUAN</u></h3>
        @php
            $romans = [
                '1' => 'I',
                '2' => 'II',
                '3' => 'III',
                '4' => 'IV',
                '5' => 'V',
                '6' => 'VI',
                '7' => 'VII',
                '8' => 'VIII',
                '9' => 'IX',
                '10' => 'X',
                '11' => 'XI',
                '12' => 'XII',
            ];
            $monthRoman = $romans[\Carbon\Carbon::parse($laporan->created_at)->format('n')];
            $year = \Carbon\Carbon::parse($laporan->created_at)->format('Y');
            // Menghitung urutan laporan ini (berdasarkan ID / urutan pembuatan)
            $urutan = \App\Models\Laporan::where('id', '<=', $laporan->id)->count();
            $nomor = str_pad($urutan, 3, '0', STR_PAD_LEFT);
        @endphp
        <p>Nomor: {{ $nomor }}/DST/UN56/SATGAS-PPKPT/{{ $monthRoman }}/{{ $year }}</p>
    </div>

    <div class="content-laporan">
        <p style="text-indent: 30px; margin-bottom: 10px;">Pada tanggal
            <strong>{{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d F Y') }}</strong>, Satuan Tugas
            Pencegahan dan Penanganan Kekerasan Di Lingkungan Perguruan Tinggi Universitas Sembilanbelas November Kolaka
            telah
            menerima
            laporan pengaduan dengan rincian sebagai berikut:
        </p>

        <table class="tabel-data">
            <tr>
                <td width="23%">Nama Pelapor/Korban</td>
                <td width="2%">:</td>
                <td>{{ $laporan->is_anonim ? 'Anonim (Dirahasiakan)' : $laporan->nama_korban }}</td>
            </tr>
            <tr>
                <td>Status Korban</td>
                <td>:</td>
                <td>
                    {{ ucfirst($laporan->status_korban) }}
                    @if ($laporan->status_korban === 'lainnya' && $laporan->status_korban_lainnya)
                        ({{ $laporan->status_korban_lainnya }})
                    @endif
                </td>
            </tr>
            <tr>
                <td>Kategori Kekerasan</td>
                <td>:</td>
                <td>{{ $laporan->jenis_kasus }}</td>
            </tr>
            <tr>
                <td>Status Terlapor</td>
                <td>:</td>
                <td>{{ ucwords(str_replace('_', ' ', $laporan->status_terlapor)) }}</td>
            </tr>
            <tr>
                <td>Tanggal Kejadian</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lokasi Kejadian</td>
                <td>:</td>
                <td>{{ $laporan->lokasi_kejadian }}</td>
            </tr>
            @php
                $saksi = $laporan->saksi ? json_decode($laporan->saksi, true) : null;
            @endphp
            @if ($saksi && !empty($saksi['nama']))
                <tr>
                    <td>Saksi Kejadian</td>
                    <td>:</td>
                    <td>
                        {{ $saksi['nama'] }}
                        @if (!empty($saksi['pekerjaan']))
                            ({{ $saksi['pekerjaan'] }})
                        @endif
                        @if (!empty($saksi['telepon']))
                            - {{ $saksi['telepon'] }}
                        @endif
                    </td>
                </tr>
            @endif
            <tr>
                <td>Status Penanganan</td>
                <td>:</td>
                <td>{{ $laporan->status }}</td>
            </tr>
        </table>

        <p style="margin-top: 5px; margin-bottom: 2px;">Kronologi / Deskripsi Singkat:</p>
        <div class="box-kronologi">
            {{ $laporan->deskripsi }}
        </div>

        <p style="text-indent: 30px; margin-top: 25px;">Demikian surat bukti penerimaan laporan ini dibuat secara
            otomatis oleh sistem informasi Satgas PPKPT Universitas Sembilanbelas November Kolaka untuk dipergunakan
            sebagaimana mestinya.</p>
    </div>

    <div class="wrapper-ttd">
        <table class="ttd-table">
            <tr>
                <td class="ttd-spacer"></td>
                <td class="ttd-content">
                    <p style="margin-bottom: 2px;">Kolaka, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p style="margin-top: 0;"><strong>Ketua PPKPT USN Kolaka,</strong></p>

                    <div style="margin: 10px 0; min-height: 60px;">
                        @if ($ttdPath && file_exists($ttdPath))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($ttdPath)) }}"
                                alt="TTD" style="max-height: 80px; max-width: 150px;">
                        @else
                            <br><br><br>
                        @endif
                    </div>

                    <div class="nama-ttd">
                        {{ $dataSurat['nama_ketua'] ?? '(Nama Ketua Belum Diatur)' }}
                    </div>
                    <p style="margin-top: 2px;">NIP.
                        {{ $dataSurat['nip_ketua'] ?? '........................................' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
