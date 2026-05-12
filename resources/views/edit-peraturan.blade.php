@extends('layouts.app')
@section('header_title', 'Mode Edit - Peraturan')

@section('content')
    <div class="max-w-6xl mx-auto pb-12">
        <div
            class="flex flex-col md:flex-row justify-between items-center mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-yellow-800">Mode Edit Visual Aktif</h2>
                <p class="text-sm text-yellow-700">Tambahkan atau ubah peraturan yang berlaku. Unggah file PDF untuk setiap
                    peraturan.</p>
            </div>
            <a href="{{ route('laporkan') }}"
                class="mt-3 md:mt-0 text-gray-600 hover:text-gray-900 font-bold text-sm bg-white px-4 py-2 border rounded shadow-sm">Kembali</a>
        </div>

        @php
            $dataPeraturan =
                isset($kontenPeraturan) && !empty($kontenPeraturan->konten)
                    ? json_decode($kontenPeraturan->konten, true)
                    : [];
            $peraturan_items = $dataPeraturan['peraturan_items'] ?? [
                [
                    'nomor' => '30',
                    'tahun' => 'Permendikbudristek 2021',
                    'judul' => 'Pencegahan dan Penanganan Kekerasan Seksual (PPKS)',
                    'deskripsi' =>
                        'Menjamin hak warga kampus atas pendidikan yang aman, penanganan kasus berperspektif korban dan mengutamakan kerahasiaan.',
                    'file_url' => 'assets/aturan/TAHUN 2021.pdf',
                ],
                [
                    'nomor' => '17',
                    'tahun' => 'Permendikbudristek Tahun 2022',
                    'judul' => 'Pedoman Lingkungan Inklusif dan Aman',
                    'deskripsi' =>
                        'Mengatur komitmen institusi dalam menyelenggarakan pendidikan yang bebas kekerasan, mendorong tindakan proaktif.',
                    'file_url' => 'assets/aturan/TAHUN 2022.pdf',
                ],
            ];
        @endphp

        <form action="{{ route('informasi.peraturan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-bold text-lg text-[#800000]">Daftar Peraturan & Dasar Hukum</h3>
                    <button type="button" onclick="tambahPeraturan()"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-1.5 px-4 rounded text-xs transition">+
                        Tambah Peraturan</button>
                </div>

                <div id="peraturan-container" class="space-y-4">
                    @foreach ($peraturan_items as $idx => $item)
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 relative group">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-md w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Nomor Aturan (Misal:
                                        30)</label>
                                    <input type="text" name="nomor[{{ $idx }}]" value="{{ $item['nomor'] }}"
                                        class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase">Tahun (Misal:
                                        Permendikbudristek 2021)</label>
                                    <input type="text" name="tahun[{{ $idx }}]" value="{{ $item['tahun'] }}"
                                        class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase">Judul Peraturan</label>
                                <input type="text" name="judul[{{ $idx }}]" value="{{ $item['judul'] }}"
                                    class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>

                                <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Singkat</label>
                                <textarea name="deskripsi[{{ $idx }}]" rows="2"
                                    class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none resize-none" required>{{ $item['deskripsi'] }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase">File PDF Peraturan</label>
                                <input type="hidden" name="old_file_url[{{ $idx }}]"
                                    value="{{ $item['file_url'] }}">
                                <input type="file" name="file_upload[{{ $idx }}]" accept="application/pdf"
                                    class="w-full bg-white border border-gray-300 text-sm rounded p-1.5 focus:outline-none file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white cursor-pointer hover:file:bg-red-900 transition">
                                <p class="text-[10px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah file PDF
                                    sebelumnya.</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-8">
                <button type="submit"
                    class="bg-[#800000] hover:bg-red-900 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>

    <script>
        function tambahPeraturan() {
            const container = document.getElementById('peraturan-container');
            const id = Date.now(); // Digunakan untuk memastikan ID input array selaras (tidak tertukar)
            const html = `
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 relative group animate-pulse">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 bg-red-500 text-white rounded-md w-8 h-8 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow">&times;</button>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Nomor Aturan</label>
                    <input type="text" name="nomor[${id}]" placeholder="Contoh: 17" class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">Tahun</label>
                    <input type="text" name="tahun[${id}]" placeholder="Contoh: Permendikbudristek Tahun 2022" class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase">Judul Peraturan</label>
                <input type="text" name="judul[${id}]" placeholder="Contoh: Pedoman Lingkungan Inklusif" class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none mb-2" required>
                <label class="block text-xs font-bold text-gray-500 uppercase">Deskripsi Singkat</label>
                <textarea name="deskripsi[${id}]" rows="2" placeholder="Tulis deskripsi..." class="w-full border-gray-300 text-sm rounded p-2 focus:outline-none resize-none" required></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase">File PDF Peraturan</label>
                <input type="hidden" name="old_file_url[${id}]" value="">
                <input type="file" name="file_upload[${id}]" accept="application/pdf" required class="w-full bg-white border border-gray-300 text-sm rounded p-1.5 focus:outline-none file:mr-4 file:py-1 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white cursor-pointer hover:file:bg-red-900 transition">
            </div>
        </div>`;
            container.insertAdjacentHTML('beforeend', html);
            setTimeout(() => {
                container.lastElementChild.classList.remove('animate-pulse');
            }, 1000);
        }
    </script>
@endsection
