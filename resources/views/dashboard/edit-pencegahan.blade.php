@extends('layouts.app')

@section('header_title', 'Mode Edit Langsung - Pencegahan PPKS')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Ubah teks langsung di dalam kotak. Anda juga bisa menambah poin baru pada
                    bagian Prinsip dan Tindakan.</p>
            </div>
            <a href="{{ route('informasi.pencegahan') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Batalkan
                Edit</a>
        </div>

        @php
            $data =
                isset($kontenPencegahan) && !empty($kontenPencegahan->konten)
                    ? json_decode($kontenPencegahan->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            // Array Default
            $prinsip_titles = $data['prinsip_item_titles'] ?? [
                '1. Kepentingan Terbaik',
                '2. Keadilan & Kesetaraan Gender',
                '3. Akuntabilitas & Independen',
                '4. Jaminan Ketidakberulangan',
            ];
            $prinsip_descs = $data['prinsip_item_descs'] ?? [
                'Menyediakan infrastruktur dan mekanisme pengaduan yang aman di kampus.',
                'Menyediakan pemulihan untuk korban kekerasan dan keadilan pelaporan.',
                'Transparansi program dan bertindak secara profesional tanpa konflik kepentingan.',
                'Memberikan sanksi tegas kepada pelaku dan meningkatkan keamanan kampus.',
            ];

            $tindakan_titles = $data['tindakan_item_titles'] ?? [
                '1. Pahami Konsep "Consent" (Persetujuan)',
                '2. Jadilah "Bystander" yang Aktif',
            ];
            $tindakan_descs = $data['tindakan_item_descs'] ?? [
                'Segala bentuk aktivitas tanpa persetujuan yang jelas dikategorikan sebagai pelecehan.',
                'Jika melihat perilaku pelecehan, alihkan perhatian pelaku atau laporkan ke Satgas PPKS.',
            ];
        @endphp

        <form action="{{ route('informasi.pencegahan.update') }}" method="POST">
            @csrf

            <div class="relative bg-gradient-to-r from-blue-900 to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-10">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="relative z-10 px-8 py-12 md:p-14 md:w-2/3">
                    <input type="text" name="hero_badge" value="{{ $d('hero_badge', 'LINGKUNGAN KAMPUS AMAN') }}"
                        class="w-full md:w-1/2 bg-white/10 text-white text-xs font-bold tracking-wider mb-4 border border-dashed border-white/50 px-3 py-2 rounded focus:ring-2 focus:ring-white focus:outline-none">
                    <textarea name="hero_title" rows="2"
                        class="w-full bg-transparent border border-dashed border-white/50 text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_title', "Mencegah Lebih Baik\nDaripada Menangani.") }}</textarea>
                    <textarea name="hero_desc" rows="3"
                        class="w-full bg-transparent border border-dashed border-white/50 text-blue-100 text-sm md:text-base leading-relaxed mb-6 rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_desc', 'Satgas PPKS USN Kolaka berkomitmen penuh untuk menghapuskan segala bentuk Kekerasan Seksual, Perundungan, dan Intoleransi melalui edukasi, kampanye, dan kebijakan yang berpihak pada korban.') }}</textarea>
                    <input type="text" name="hero_btn" value="{{ $d('hero_btn', 'Lihat Peran Kita') }}"
                        class="bg-white text-[#800000] px-5 py-2.5 rounded-lg font-bold text-sm border-2 border-dashed border-[#800000] focus:outline-none w-48">
                </div>
            </div>

            <div class="mb-12">
                <div class="text-center mb-8">
                    <input type="text" name="langkah_title"
                        value="{{ $d('langkah_title', 'Langkah Pencegahan Satgas PPKS') }}"
                        class="w-full text-center bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 rounded p-1 mb-2 focus:bg-white focus:outline-none">
                    <input type="text" name="langkah_desc"
                        value="{{ $d('langkah_desc', 'Upaya sistematis yang kami lakukan berdasarkan Permendikbudristek No. 30 Tahun 2021.') }}"
                        class="w-full text-center bg-transparent border border-dashed border-gray-300 text-gray-500 text-sm rounded p-1 focus:bg-white focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-blue-200 shadow-sm relative">
                        <input type="text" name="l_1_title" value="{{ $d('l_1_title', 'Edukasi & Sosialisasi') }}"
                            class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 mb-2 rounded p-1 focus:bg-gray-50 focus:outline-none">
                        <textarea name="l_1_desc" rows="4"
                            class="w-full bg-transparent border border-dashed border-gray-300 text-gray-600 text-sm rounded p-1 focus:bg-gray-50 focus:outline-none resize-none">{{ $d('l_1_desc', 'Penyisipan materi anti kekerasan seksual pada Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) dan seminar berkala.') }}</textarea>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-blue-200 shadow-sm relative">
                        <input type="text" name="l_2_title" value="{{ $d('l_2_title', 'Pakta Integritas') }}"
                            class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 mb-2 rounded p-1 focus:bg-gray-50 focus:outline-none">
                        <textarea name="l_2_desc" rows="4"
                            class="w-full bg-transparent border border-dashed border-gray-300 text-gray-600 text-sm rounded p-1 focus:bg-gray-50 focus:outline-none resize-none">{{ $d('l_2_desc', 'Mewajibkan seluruh sivitas akademika (Mahasiswa, Dosen, Tendik) menandatangani pakta integritas penolakan PPKS.') }}</textarea>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-blue-200 shadow-sm relative">
                        <input type="text" name="l_3_title" value="{{ $d('l_3_title', 'Kampanye Publik') }}"
                            class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 mb-2 rounded p-1 focus:bg-gray-50 focus:outline-none">
                        <textarea name="l_3_desc" rows="4"
                            class="w-full bg-transparent border border-dashed border-gray-300 text-gray-600 text-sm rounded p-1 focus:bg-gray-50 focus:outline-none resize-none">{{ $d('l_3_desc', 'Pemasangan poster edukasi, rambu kawasan aman, serta kampanye di media sosial kampus tentang batas-batas interaksi.') }}</textarea>
                    </div>
                </div>
            </div>

            <div id="peran-sivitas" class="mb-12 border-t border-gray-200 pt-8">
                <input type="text" name="peran_title" value="{{ $d('peran_title', 'Apa Peran Anda di Kampus?') }}"
                    class="w-full text-center bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 mb-6 pb-2 rounded focus:bg-white focus:outline-none">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-3xl border border-blue-200 shadow-sm relative">
                        <input type="text" name="p_mhs_title" value="{{ $d('p_mhs_title', 'Mahasiswa') }}"
                            class="w-full bg-transparent border border-dashed border-blue-400 text-2xl font-bold text-blue-900 mb-4 rounded p-1 focus:bg-white focus:outline-none">
                        <textarea name="p_mhs_desc" rows="5"
                            class="w-full bg-transparent border border-dashed border-blue-300 text-sm text-gray-700 leading-relaxed rounded p-1 focus:bg-white focus:outline-none resize-none">{{ $d('p_mhs_desc', "- Perbanyak diskusi positif tentang HAM.\n- Ikuti sosialisasi anti kekerasan.\n- Cari tahu unit PPKS di kampus.\n- Terapkan relasi yang sehat.") }}</textarea>
                    </div>
                    <div
                        class="bg-gradient-to-br from-emerald-50 to-white p-8 rounded-3xl border border-emerald-200 shadow-sm relative">
                        <input type="text" name="p_dsn_title" value="{{ $d('p_dsn_title', 'Dosen & Tendik') }}"
                            class="w-full bg-transparent border border-dashed border-emerald-400 text-2xl font-bold text-emerald-900 mb-4 rounded p-1 focus:bg-white focus:outline-none">
                        <textarea name="p_dsn_desc" rows="5"
                            class="w-full bg-transparent border border-dashed border-emerald-300 text-sm text-gray-700 leading-relaxed rounded p-1 focus:bg-white focus:outline-none resize-none">{{ $d('p_dsn_desc', "- Perbanyak keterlibatan mahasiswa.\n- Perbanyak sosialisasi & pelatihan.\n- Perkenalkan layanan unit PPKS.\n- Terapkan relasi sehat dan setara.") }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mb-12 border-t border-gray-200 pt-8">
                <div class="mb-6 flex justify-between items-center">
                    <input type="text" name="prinsip_title"
                        value="{{ $d('prinsip_title', 'Prinsip Pengelola Perguruan Tinggi') }}"
                        class="w-2/3 bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 rounded p-1 focus:bg-white focus:outline-none">
                    <button type="button" onclick="tambahPrinsip()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded text-sm transition">+
                        Tambah Prinsip Baru</button>
                </div>

                <div id="prinsip-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($prinsip_titles as $index => $title)
                        <div class="bg-white p-6 rounded-2xl border border-gray-300 shadow-sm relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition"
                                title="Hapus Kotak Ini">&times;</button>
                            <input type="text" name="prinsip_item_titles[]" value="{{ $title }}"
                                class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-[#800000] text-lg mb-2 rounded p-1 focus:bg-gray-50 focus:outline-none">
                            <textarea name="prinsip_item_descs[]" rows="2"
                                class="w-full bg-transparent border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:bg-gray-50 focus:outline-none resize-none">{{ $prinsip_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm mb-12 border-t pt-8">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <input type="text" name="tindakan_title"
                        value="{{ $d('tindakan_title', 'Tindakan Sebagai Individu (Bystander)') }}"
                        class="w-2/3 bg-transparent border border-dashed border-gray-400 text-xl font-bold text-gray-800 rounded p-1 focus:bg-gray-50 focus:outline-none">
                    <button type="button" onclick="tambahTindakan()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded text-sm transition">+
                        Tambah Tindakan Baru</button>
                </div>

                <div id="tindakan-container" class="space-y-4">
                    @foreach ($tindakan_titles as $index => $title)
                        <div class="p-4 bg-gray-50 border border-gray-300 rounded-xl relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition"
                                title="Hapus Kotak Ini">&times;</button>
                            <input type="text" name="tindakan_item_titles[]" value="{{ $title }}"
                                class="w-full bg-white border border-dashed border-gray-400 font-bold text-gray-800 mb-2 rounded p-1 focus:outline-none">
                            <textarea name="tindakan_item_descs[]" rows="2"
                                class="w-full bg-white border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none">{{ $tindakan_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-center gap-4">
                <a href="{{ route('informasi.pencegahan') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-lg transition">Batal</a>
                <button
                    class="bg-[#800000] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition"
                    type="submit">
                    Simpan Tampilan Ini
                </button>
            </div>

            <div class="h-24"></div>
        </form>
    </div>

    <script>
        function tambahPrinsip() {
            const container = document.getElementById('prinsip-container');
            const count = container.children.length + 1;
            const html = `
            <div class="bg-white p-6 rounded-2xl border border-gray-300 shadow-sm relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition" title="Hapus Kotak Ini">&times;</button>
                <input type="text" name="prinsip_item_titles[]" placeholder="Tulis Judul Prinsip Baru..." class="w-full bg-transparent border border-dashed border-gray-400 font-bold text-[#800000] text-lg mb-2 rounded p-1 focus:bg-gray-50 focus:outline-none">
                <textarea name="prinsip_item_descs[]" rows="2" placeholder="Tulis deskripsi di sini..." class="w-full bg-transparent border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:bg-gray-50 focus:outline-none resize-none"></textarea>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);

            // Hapus efek kedip setelah 1 detik
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }

        function tambahTindakan() {
            const container = document.getElementById('tindakan-container');
            const count = container.children.length + 1;
            const html = `
            <div class="p-4 bg-gray-50 border border-gray-300 rounded-xl relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow opacity-0 group-hover:opacity-100 transition" title="Hapus Kotak Ini">&times;</button>
                <input type="text" name="tindakan_item_titles[]" placeholder="Tulis Judul Tindakan Baru..." class="w-full bg-white border border-dashed border-gray-400 font-bold text-gray-800 mb-2 rounded p-1 focus:outline-none">
                <textarea name="tindakan_item_descs[]" rows="2" placeholder="Tulis penjelasan di sini..." class="w-full bg-white border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none"></textarea>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);

            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }
    </script>
@endsection
