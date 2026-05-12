@extends('layouts.app')

@section('header_title', 'Edit Informasi Penanganan PPKS')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Ubah teks langsung di dalam kotak. Anda bisa menambah atau menghapus
                    bagian Alur dan Prinsip.</p>
            </div>
            <a href="{{ route('informasi.penanganan') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Batalkan
                Edit</a>
        </div>

        @php
            $data =
                isset($kontenPenanganan) && !empty($kontenPenanganan->konten)
                    ? json_decode($kontenPenanganan->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            $alur_titles = $data['alur_titles'] ?? [
                '1. Penerimaan Laporan',
                '2. Pemeriksaan & Klarifikasi',
                '3. Kesimpulan & Rekomendasi',
                '4. Pemulihan Korban',
            ];
            $alur_descs = $data['alur_descs'] ?? [
                'Satgas menerima laporan melalui website, WhatsApp, atau pelaporan langsung dengan menjamin kerahasiaan identitas.',
                'Satgas melakukan penggalian informasi dari pelapor, korban, saksi, dan terlapor secara terpisah dan aman.',
                'Satgas menyusun kesimpulan dan memberikan rekomendasi sanksi kepada Pimpinan Perguruan Tinggi.',
                'Memberikan layanan pendampingan psikologis, medis, maupun bantuan hukum jika diperlukan oleh korban.',
            ];

            $prinsip_titles = $data['prinsip_titles'] ?? [
                'Berpihak pada Korban',
                'Kerahasiaan Identitas',
                'Keamanan & Perlindungan',
            ];
            $prinsip_descs = $data['prinsip_descs'] ?? [
                'Semua proses penanganan mengutamakan kepentingan, kebutuhan, dan kenyamanan korban.',
                'Identitas semua pihak yang terlibat, terutama korban dan pelapor, dijaga ketat dari publikasi.',
                'Memastikan korban aman dari ancaman, intimidasi, maupun serangan balik dari pihak pelaku.',
            ];
        @endphp

        <form action="{{ route('informasi.penanganan.update') }}" method="POST">
            @csrf

            <div class="relative bg-gradient-to-r from-blue-900 to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-12">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="relative z-10 px-8 py-12 md:p-14 md:w-2/3">
                    <input type="text" name="hero_badge" value="{{ $d('hero_badge', 'PROSEDUR RESMI SATGAS') }}"
                        class="w-full md:w-1/2 bg-white/10 text-white text-xs font-bold tracking-wider mb-4 border border-dashed border-white/50 px-3 py-2 rounded focus:ring-2 focus:ring-white focus:outline-none">
                    <textarea name="hero_title" rows="2"
                        class="w-full bg-transparent border border-dashed border-white/50 text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_title', "Mendampingi Korban,\nMenegakkan Keadilan.") }}</textarea>
                    <textarea name="hero_desc" rows="3"
                        class="w-full bg-transparent border border-dashed border-white/50 text-blue-100 text-sm md:text-base leading-relaxed mb-6 rounded p-2 focus:bg-black/20 focus:outline-none">{{ $d('hero_desc', 'Satgas PPKS bertugas memproses setiap laporan kekerasan secara objektif, rahasia, dan independen. Anda tidak sendirian, kami siap mendengar dan menindaklanjuti laporan Anda.') }}</textarea>
                    <input type="text" name="hero_btn" value="{{ $d('hero_btn', 'Buat Laporan Sekarang') }}"
                        class="bg-white text-[#800000] px-5 py-2.5 rounded-lg font-bold text-sm border-2 border-dashed border-[#800000] focus:outline-none w-56">
                </div>
            </div>

            <div class="mb-12 border-t pt-8">
                <div class="mb-6 flex justify-between items-center">
                    <input type="text" name="prinsip_title_main"
                        value="{{ $d('prinsip_title_main', 'Prinsip Penanganan Kami') }}"
                        class="w-1/2 text-center bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 rounded p-1 focus:bg-white focus:outline-none">
                    <button type="button" onclick="tambahPrinsip()"
                        class="bg-red-100 hover:bg-red-200 text-red-800 font-bold py-2 px-4 rounded text-sm transition">+
                        Tambah Prinsip</button>
                </div>

                <div id="prinsip-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($prinsip_titles as $index => $title)
                        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative group text-center">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                            <input type="text" name="prinsip_titles[]" value="{{ $title }}"
                                class="w-full text-center bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                            <textarea name="prinsip_descs[]" rows="3"
                                class="w-full text-center bg-transparent border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none">{{ $prinsip_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm mb-12">
                <div class="flex justify-between items-center mb-8 border-b pb-4">
                    <input type="text" name="alur_title_main"
                        value="{{ $d('alur_title_main', 'Alur Penanganan Laporan') }}"
                        class="w-1/2 bg-transparent border border-dashed border-gray-400 text-2xl font-bold text-gray-800 rounded p-1 focus:outline-none">
                    <button type="button" onclick="tambahAlur()"
                        class="bg-red-100 hover:bg-red-200 text-red-800 font-bold py-2 px-4 rounded text-sm transition">+
                        Tambah Alur Baru</button>
                </div>

                <div id="alur-container" class="space-y-4">
                    @foreach ($alur_titles as $index => $title)
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-300 relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-md w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                            <input type="text" name="alur_titles[]" value="{{ $title }}"
                                class="w-3/4 bg-white border border-dashed border-red-400 font-bold text-[#800000] text-lg mb-2 rounded p-1 focus:outline-none">
                            <textarea name="alur_descs[]" rows="2"
                                class="w-full bg-white border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none">{{ $alur_descs[$index] ?? '' }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            <div
                class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50 flex justify-center gap-4">
                <a href="{{ route('informasi.penanganan') }}"
                    class="bg-gray-200 text-gray-800 font-bold py-3 px-8 rounded-lg transition">Batal</a>
                <button class="bg-[#800000] hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition"
                    type="submit">
                    Simpan Penanganan
                </button>
            </div>
            <div class="h-24"></div>
        </form>
    </div>

    <script>
        function tambahPrinsip() {
            const container = document.getElementById('prinsip-container');
            const html = `
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative group text-center animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                <input type="text" name="prinsip_titles[]" placeholder="Judul Prinsip" class="w-full text-center bg-transparent border border-dashed border-gray-400 font-bold text-gray-800 text-lg mb-2 rounded p-1 focus:outline-none">
                <textarea name="prinsip_descs[]" rows="3" placeholder="Deskripsi prinsip..." class="w-full text-center bg-transparent border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none"></textarea>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }

        function tambahAlur() {
            const container = document.getElementById('alur-container');
            const html = `
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-300 relative group animate-pulse">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 bg-red-500 text-white rounded-md w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">&times;</button>
                <input type="text" name="alur_titles[]" placeholder="Nomor & Judul Alur (Misal: 5. Tahap Akhir)" class="w-3/4 bg-white border border-dashed border-red-400 font-bold text-[#800000] text-lg mb-2 rounded p-1 focus:outline-none">
                <textarea name="alur_descs[]" rows="2" placeholder="Penjelasan mengenai tahap ini..." class="w-full bg-white border border-dashed border-gray-300 text-sm text-gray-600 rounded p-1 focus:outline-none resize-none"></textarea>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }
    </script>
@endsection
