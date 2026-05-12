@extends('layouts.app')
@section('header_title', 'Mode Edit Langsung - Tentang Kami')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Ubah teks Visi, Misi, Latar Belakang, dan Nilai Dasar. Anda juga bisa
                    menambah atau menghapus daftar Misi.</p>
            </div>
            <a href="{{ route('tentang') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Batalkan
                Edit</a>
        </div>

        @php
            $data =
                isset($kontenTentang) && !empty($kontenTentang->konten)
                    ? json_decode($kontenTentang->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            $misi_items = $data['misi_items'] ?? [
                'Menyelenggarakan program edukasi dan sosialisasi pencegahan kekerasan seksual secara berkala.',
                'Menyediakan layanan pengaduan yang mudah diakses, responsif, dan terjamin kerahasiaannya.',
                'Memberikan pendampingan psikologis, hukum, dan akademik bagi korban kekerasan.',
                'Menindaklanjuti laporan dengan adil dan merekomendasikan sanksi tegas bagi pelaku.',
            ];

            $nilai_titles = $data['nilai_titles'] ?? ['Kerahasiaan', 'Empati', 'Keadilan', 'Inklusif'];
            $nilai_descs = $data['nilai_descs'] ?? [
                'Kami menjamin 100% privasi dan identitas pelapor serta korban dalam setiap penanganan kasus.',
                'Setiap tindakan selalu menggunakan perspektif korban (victim-centered) dan menghindari victim blaming.',
                'Investigasi dilakukan secara objektif, proporsional, serta bebas dari konflik kepentingan.',
                'Terbuka untuk semua golongan, setara gender, dan memastikan aksesibilitas bagi penyandang disabilitas.',
            ];
            $colors = ['blue', 'pink', 'green', 'orange'];
        @endphp

        <form action="{{ route('tentang.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="relative bg-gradient-to-r from-[#0d2a80] to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-12">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="relative z-10 px-8 py-12 md:p-14 md:w-3/4">
                    <input type="text" name="hero_badge" value="{{ $d('hero_badge', 'PROFIL SATGAS PPKS') }}"
                        class="w-full md:w-1/3 bg-white/10 text-white text-xs font-bold tracking-wider mb-4 border border-dashed border-white/50 px-3 py-2 rounded focus:ring-2 focus:ring-white focus:outline-none">
                    <textarea name="hero_title" rows="2"
                        class="w-full bg-transparent border border-dashed border-white/50 text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_title', "Mewujudkan Kampus yang\nAman, Setara, dan Inklusif.") }}</textarea>
                    <textarea name="hero_desc" rows="3"
                        class="w-full bg-transparent border border-dashed border-white/50 text-blue-100 text-sm md:text-base leading-relaxed rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_desc', 'Satuan Tugas Pencegahan dan Penanganan Kekerasan Seksual (Satgas PPKS) Universitas Sembilanbelas November Kolaka hadir sebagai garda terdepan pelindung sivitas akademika.') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16 items-center border-b border-gray-200 pb-10">
                <div>
                    <input type="text" name="latar_title" value="{{ $d('latar_title', 'Latar Belakang') }}"
                        class="w-1/2 bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-2xl mb-4 border-l-4 border-l-[#800000] pl-3 rounded p-1 focus:bg-white focus:outline-none">
                    <textarea name="latar_desc" rows="12"
                        class="w-full bg-gray-50 border border-dashed border-gray-300 text-gray-600 text-sm md:text-base leading-relaxed rounded p-3 focus:bg-white focus:outline-none resize-none">{{ $d('latar_desc', "Pembentukan Satgas PPKS USN Kolaka merupakan wujud komitmen nyata universitas dalam merespons dan mengimplementasikan Permendikbudristek Nomor 30 Tahun 2021 tentang Pencegahan dan Penanganan Kekerasan Seksual di Lingkungan Perguruan Tinggi.\n\nKami menyadari bahwa perguruan tinggi harus menjadi ruang yang aman bagi penyemaian ilmu pengetahuan. Tidak boleh ada ruang bagi tindakan kekerasan seksual, perundungan, maupun intoleransi. Satgas ini beranggotakan unsur pendidik, tenaga kependidikan, dan mahasiswa yang telah lulus uji seleksi dan pelatihan khusus.\n\nKami hadir tidak hanya untuk menangani laporan, tetapi juga berfokus pada edukasi, kampanye pencegahan, dan pemulihan korban dengan prinsip berperspektif pada korban.") }}</textarea>
                </div>

                <div class="bg-gray-100 p-4 rounded-xl border border-dashed border-gray-400 relative">
                    <span class="absolute -top-3 left-4 bg-gray-600 text-white text-xs px-2 py-1 rounded shadow">Edit Gambar
                        & Teks</span>

                    <p class="text-xs font-bold text-gray-500 mb-1 mt-2">Unggah Gambar Latar Baru:</p>
                    <input type="file" name="latar_img_upload" accept="image/*"
                        class="w-full bg-white border border-gray-300 text-sm mb-2 rounded p-1.5 focus:outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white cursor-pointer hover:file:bg-red-900 transition">
                    <p class="text-[10px] text-gray-400 mb-4">Biarkan kosong jika tidak ingin mengubah gambar saat ini.
                        Format: JPG, PNG (Maks 5MB).</p>

                    <p class="text-xs font-bold text-gray-500 mb-1">Teks di atas gambar:</p>
                    <input type="text" name="latar_img_cap"
                        value="{{ $d('latar_img_cap', 'Kampus USN Kolaka yang aman dan nyaman.') }}"
                        class="w-full bg-white border border-gray-300 text-sm rounded p-2 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
                <div class="bg-gradient-to-br from-[#800000] to-red-900 p-8 rounded-3xl shadow-md text-white">
                    <input type="text" name="visi_badge" value="{{ $d('visi_badge', 'Pandangan Ke Depan') }}"
                        class="w-full bg-transparent border border-dashed border-red-400 text-red-200 font-bold tracking-widest uppercase text-xs mb-2 rounded p-1 focus:outline-none">
                    <input type="text" name="visi_title" value="{{ $d('visi_title', 'Visi Kami') }}"
                        class="w-full bg-transparent border border-dashed border-red-400 text-3xl font-extrabold mb-4 rounded p-1 focus:outline-none">
                    <textarea name="visi_desc" rows="4"
                        class="w-full bg-transparent border border-dashed border-red-400 text-red-50 text-lg leading-relaxed font-medium rounded p-2 focus:bg-black/20 focus:outline-none resize-none">{{ $d('visi_desc', 'Mewujudkan lingkungan Universitas Sembilanbelas November Kolaka yang aman, setara, inklusif, dan terbebas dari segala bentuk kekerasan seksual.') }}</textarea>
                </div>

                <div class="bg-gray-50 p-8 rounded-3xl shadow-sm border border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <input type="text" name="misi_badge" value="{{ $d('misi_badge', 'Langkah Nyata') }}"
                            class="w-1/2 bg-transparent border border-dashed border-gray-400 text-[#800000] font-bold tracking-widest uppercase text-xs rounded p-1 focus:outline-none">
                        <button type="button" onclick="tambahMisi()"
                            class="bg-red-100 hover:bg-red-200 text-red-800 text-xs font-bold py-1 px-2 rounded">+ Tambah
                            Misi</button>
                    </div>
                    <input type="text" name="misi_title" value="{{ $d('misi_title', 'Misi Utama') }}"
                        class="w-full bg-transparent border border-dashed border-gray-400 text-3xl font-extrabold text-gray-800 mb-6 rounded p-1 focus:outline-none">

                    <div id="misi-container" class="space-y-3">
                        @foreach ($misi_items as $index => $misi)
                            <div class="flex items-start gap-2 group relative">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="bg-red-500 text-white rounded p-1 mt-1 opacity-0 group-hover:opacity-100 transition"
                                    title="Hapus">&times;</button>
                                <textarea name="misi_items[]" rows="2"
                                    class="w-full bg-white border border-dashed border-gray-300 text-gray-600 text-sm rounded p-2 focus:outline-none resize-none">{{ $misi }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mb-12 border-t border-gray-200 pt-8">
                <div class="flex justify-between items-center mb-8">
                    <input type="text" name="nilai_title_main"
                        value="{{ $d('nilai_title_main', 'Nilai-Nilai Dasar Kami') }}"
                        class="w-1/2 bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 rounded p-1 focus:outline-none">
                    <button type="button" onclick="tambahNilai()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded text-sm">+ Tambah
                        Nilai Dasar</button>
                </div>

                <div id="nilai-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($nilai_titles as $index => $title)
                        @php $color = $colors[$index % 4]; @endphp
                        <div class="bg-white p-6 rounded-2xl border border-gray-300 shadow-sm text-center relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                            <div class="w-10 h-2 bg-{{ $color }}-200 mx-auto rounded mb-3"></div>
                            <input type="text" name="nilai_titles[]" value="{{ $title }}"
                                class="w-full text-center bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                            <textarea name="nilai_descs[]" rows="3"
                                class="w-full text-center bg-transparent border border-dashed border-gray-300 text-xs text-gray-500 rounded p-1 focus:outline-none resize-none">{{ $nilai_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-center gap-4">
                <a href="{{ route('tentang') }}"
                    class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg transition">Batal</a>
                <button
                    class="bg-[#800000] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition"
                    type="submit">
                    Simpan Tentang Kami
                </button>
            </div>

            <div class="h-24"></div>
        </form>
    </div>

    <script>
        function tambahMisi() {
            const container = document.getElementById('misi-container');
            const html = `
            <div class="flex items-start gap-2 group relative animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white rounded p-1 mt-1 opacity-0 group-hover:opacity-100 transition" title="Hapus">&times;</button>
                <textarea name="misi_items[]" rows="2" placeholder="Tuliskan misi baru..." class="w-full bg-white border border-dashed border-gray-400 text-gray-600 text-sm rounded p-2 focus:outline-none resize-none"></textarea>
            </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }

        function tambahNilai() {
            const container = document.getElementById('nilai-container');
            const html = `
            <div class="bg-white p-6 rounded-2xl border border-gray-300 shadow-sm text-center relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                <div class="w-10 h-2 bg-gray-300 mx-auto rounded mb-3"></div>
                <input type="text" name="nilai_titles[]" placeholder="Judul Nilai" class="w-full text-center bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                <textarea name="nilai_descs[]" rows="3" placeholder="Penjelasan..." class="w-full text-center bg-transparent border border-dashed border-gray-300 text-xs text-gray-500 rounded p-1 focus:outline-none resize-none"></textarea>
            </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }
    </script>
@endsection
