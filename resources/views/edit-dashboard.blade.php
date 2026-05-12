@extends('layouts.app')
@section('header_title', 'Mode Edit - Dashboard Pelapor')
@section('content')
    <div class="max-w-6xl mx-auto pb-12">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Ubah teks informasi yang tampil pada Dashboard Pelapor secara real-time.
                    Anda bisa menambah/menghapus kartu dan poin.</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Kembali</a>
        </div>

        @php
            $data =
                isset($kontenDashboard) && !empty($kontenDashboard->konten)
                    ? json_decode($kontenDashboard->konten, true)
                    : [];
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            // Setup Data Array Default untuk Bentuk Kekerasan
            $bentuk_titles = $data['bentuk_item_titles'] ?? [
                $d('ks_title', 'Kekerasan Seksual'),
                $d('kf_title', 'Kekerasan Fisik'),
                $d('kp_title', 'Kekerasan Psikologis'),
            ];
            $bentuk_descs = $data['bentuk_item_descs'] ?? [
                $d(
                    'ks_desc',
                    'Termasuk pelecehan verbal, fisik, hingga pemaksaan melalui media digital atau intimidasi.',
                ),
                $d(
                    'kf_desc',
                    'Tindakan kontak fisik yang menyakiti atau membahayakan nyawa orang lain secara sengaja.',
                ),
                $d(
                    'kp_desc',
                    'Ejekan, pengucilan, atau ancaman yang merusak kesehatan mental dan rasa percaya diri seseorang.',
                ),
            ];

            // Setup Data Array Default untuk Hak Pelapor
            $hak_items = $data['hak_items'] ?? [
                $d('hak_1', 'Hak atas perlindungan identitas dan kerahasiaan informasi.'),
                $d('hak_2', 'Hak atas pendampingan psikologis, hukum, dan medis.'),
                $d('hak_3', 'Hak untuk mendapatkan informasi perkembangan kasus secara rutin.'),
                $d('hak_4', 'Hak atas rasa aman dan bebas dari ancaman pihak manapun.'),
            ];

            // Setup Data Array Default untuk Alur Pelaporan
            $alur_titles = $data['alur_item_titles'] ?? [
                $d('alur_1_title', 'Buat Laporan'),
                $d('alur_2_title', 'Verifikasi'),
                $d('alur_3_title', 'Investigasi'),
                $d('alur_4_title', 'Pemulihan'),
            ];
            $alur_descs = $data['alur_item_descs'] ?? [
                $d('alur_1_desc', 'Isi form pengaduan'),
                $d('alur_2_desc', 'Satgas memeriksa laporan'),
                $d('alur_3_desc', 'Proses pencarian fakta'),
                $d('alur_4_desc', 'Tindak lanjut & pendampingan'),
            ];
        @endphp

        <form action="{{ route('dashboard.update') }}" method="POST">
            @csrf

            <!-- Section 1: Teks Carousel -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <h3 class="font-bold text-lg text-[#800000] mb-4 border-b pb-2">1. Banner Carousel (Teks Pengantar)</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Judul Utama Carousel</label>
                        <input type="text" name="carousel_title"
                            value="{{ $d('carousel_title', 'Bersama Wujudkan Kampus Aman') }}"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:outline-none focus:border-[#800000]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Singkat</label>
                        <textarea name="carousel_desc" rows="2"
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:outline-none focus:border-[#800000]">{{ $d('carousel_desc', 'Satgas PPKS hadir untuk memberikan perlindungan, pendampingan, dan keadilan bagi seluruh civitas akademika.') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Bentuk Kekerasan -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-lg text-[#800000]">2. Kenali Bentuk Kekerasan</h3>
                    <button type="button" onclick="tambahBentuk()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-1.5 px-4 rounded text-xs transition">+
                        Tambah Kartu Baru</button>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 uppercase">Judul Seksi</label>
                    <input type="text" name="bentuk_title" value="{{ $d('bentuk_title', 'Kenali Bentuk Kekerasan') }}"
                        class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 focus:outline-none focus:border-[#800000]">
                </div>

                <div id="bentuk-container" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($bentuk_titles as $index => $title)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul Kotak</label>
                            <input type="text" name="bentuk_item_titles[]" value="{{ $title }}"
                                class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi</label>
                            <textarea name="bentuk_item_descs[]" rows="3"
                                class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none resize-none">{{ $bentuk_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Informasi Penting & Kontak -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <h3 class="font-bold text-lg text-[#800000] mb-4 border-b pb-2">3. Informasi Penting & Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul Hak Pelapor</label>
                            <button type="button" onclick="tambahHak()"
                                class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-1 px-3 rounded text-[10px] transition">+
                                Tambah Poin Hak</button>
                        </div>
                        <input type="text" name="hak_title"
                            value="{{ $d('hak_title', 'Hak Anda Sebagai Pelapor/Korban') }}"
                            class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-3 bg-gray-50 border">

                        <div id="hak-container" class="space-y-2">
                            @foreach ($hak_items as $index => $hak)
                                <div class="flex items-center gap-2 group relative">
                                    <input type="text" name="hak_items[]" value="{{ $hak }}"
                                        class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none bg-gray-50 border">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="bg-red-500 hover:bg-red-600 text-white rounded w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shrink-0"
                                        title="Hapus Poin">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Judul Kontak</label>
                        <input type="text" name="kontak_title"
                            value="{{ $d('kontak_title', 'Kontak Bantuan & Darurat') }}"
                            class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-3 bg-gray-50 border">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor WhatsApp</label>
                        <input type="text" name="kontak_wa" value="{{ $d('kontak_wa', '0812-XXXX-XX') }}"
                            class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-3 bg-gray-50 border">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Email</label>
                        <input type="text" name="kontak_email"
                            value="{{ $d('kontak_email', 'satgas.ppks@univ.ac.id') }}"
                            class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none bg-gray-50 border">
                    </div>
                </div>
            </div>

            <!-- Section 4: Alur -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-lg text-[#800000]">4. Alur Pelaporan</h3>
                    <button type="button" onclick="tambahAlur()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-1.5 px-4 rounded text-xs transition">+
                        Tambah Tahapan Baru</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Judul Seksi Alur</label>
                        <input type="text" name="alur_title" value="{{ $d('alur_title', 'Alur Penanganan Laporan') }}"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded p-2 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Seksi Alur</label>
                        <input type="text" name="alur_desc"
                            value="{{ $d('alur_desc', 'Langkah nyata kami untuk menjaga keamanan Anda.') }}"
                            class="w-full bg-gray-50 border border-gray-300 text-sm rounded p-2 focus:outline-none">
                    </div>
                </div>

                <div id="alur-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($alur_titles as $index => $title)
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>
                            <label class="block text-xs font-bold text-gray-500 uppercase">Judul Tahap</label>
                            <input type="text" name="alur_item_titles[]" value="{{ $title }}"
                                class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase">Teks Penjelasan</label>
                            <input type="text" name="alur_item_descs[]" value="{{ $alur_descs[$index] ?? '' }}"
                                class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg transition">Batal</a>
                <button type="submit"
                    class="bg-[#800000] hover:bg-red-900 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">Simpan
                    Pembaruan Dashboard</button>
            </div>
        </form>
    </div>

    <script>
        function tambahBentuk() {
            const container = document.getElementById('bentuk-container');
            const html = `
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>
                <label class="block text-xs font-bold text-gray-500 uppercase">Judul Kotak Baru</label>
                <input type="text" name="bentuk_item_titles[]" placeholder="Contoh: Perundungan" class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2">
                <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi</label>
                <textarea name="bentuk_item_descs[]" rows="3" placeholder="Tuliskan deskripsi..." class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none resize-none"></textarea>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }

        function tambahHak() {
            const container = document.getElementById('hak-container');
            const html = `
            <div class="flex items-center gap-2 group relative animate-pulse">
                <input type="text" name="hak_items[]" placeholder="Tuliskan poin hak baru..." class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none bg-gray-50 border">
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 hover:bg-red-600 text-white rounded w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shrink-0" title="Hapus Poin">&times;</button>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }

        function tambahAlur() {
            const container = document.getElementById('alur-container');
            const html = `
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>
                <label class="block text-xs font-bold text-gray-500 uppercase">Judul Tahap Baru</label>
                <input type="text" name="alur_item_titles[]" placeholder="Contoh: Pendampingan" class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-1">
                <label class="block text-xs font-bold text-gray-500 uppercase">Teks Penjelasan</label>
                <input type="text" name="alur_item_descs[]" placeholder="Keterangan..." class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none">
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }
    </script>
@endsection
