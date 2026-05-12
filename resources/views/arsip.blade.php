@extends('layouts.app')

@section('header_title', 'Arsip Kegiatan')

@section('content')
    @php
        // Mengambil data gambar promo dari folder
        $promoPath = public_path('assets/image/promo');
        $promos = [];
        if (\Illuminate\Support\Facades\File::exists($promoPath)) {
            $files = \Illuminate\Support\Facades\File::files($promoPath);
            foreach ($files as $file) {
                $promos[] = [
                    'nama' => $file->getFilename(),
                    'url' => asset('assets/image/promo/' . $file->getFilename()),
                ];
            }
        }
    @endphp

    <div class="max-w-[100%] mx-auto" x-data="{ showTambahModal: {{ $errors->any() ? 'true' : 'false' }}, showSuccess: {{ session('success') ? 'true' : 'false' }}, showPromoModal: false }">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Arsip & Publikasi Kegiatan</h2>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Kelola data kegiatan Satgas PPKT. Kegiatan yang
                    dipublikasi akan otomatis tampil di Galeri.</p>
            </div>

            <button @click="showPromoModal = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-500 text-white text-sm font-bold rounded-xl hover:bg-yellow-600 transition-all shadow-md shadow-yellow-500/20 active:scale-95 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                    </path>
                </svg>
                Coming Soon
            </button>
        </div>

        <div id="btnTambahContainer" class="hidden">
            <button @click="showTambahModal = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 transition-all shadow-md shadow-red-900/20 active:scale-95 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kegiatan Baru
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col p-6">
            <div class="overflow-x-auto custom-scroll flex-1 relative w-full">
                <table id="tableArsip" class="w-full text-sm text-left text-gray-600 min-w-[1200px] mt-4">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center w-16">No</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Judul Kegiatan
                            </th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Jenis Kegiatan
                            </th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Tanggal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Lokasi</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Deskripsi
                                Singkat</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap">
                                Dokumentasi</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap">
                                Status Publikasi</th>
                            <th scope="col"
                                class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap sticky right-0 bg-gray-50 z-30 border-l border-gray-200 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if (isset($arsips))
                            @foreach ($arsips as $index => $item)
                                <tr class="bg-white hover:bg-gray-50/50 transition-colors group" x-data="{ showDelete: false, showEdit: false, showBukti: false }">
                                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-800 min-w-[200px]">
                                        {{ $item->judul_kegiatan ?? $item->judul }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ ucfirst($item->jenis_kegiatan ?? $item->jenis) }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-600">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $item->lokasi }}</td>
                                    <td class="px-6 py-4 text-gray-500 truncate max-w-xs" title="{{ $item->deskripsi }}">
                                        {{ \Illuminate\Support\Str::limit($item->deskripsi, 40) }}</td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($item->dokumentasi)
                                            <button @click="showBukti = true"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors shadow-sm focus:outline-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Gambar
                                            </button>
                                        @else
                                            <span
                                                class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded border border-gray-100">Tidak
                                                ada</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="px-3 py-1.5 text-[11px] uppercase tracking-wider font-extrabold rounded-xl border shadow-sm
                                            {{ $item->status_publikasi == 'sosialisasi' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                            {{ $item->status_publikasi == 'poster' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                            {{ $item->status_publikasi == 'tidak_dipublikasi' || $item->status_publikasi == 'internal' ? 'bg-gray-50 text-gray-700 border-gray-200' : '' }}">
                                            @if ($item->status_publikasi == 'sosialisasi')
                                                Sosialisasi
                                            @elseif($item->status_publikasi == 'poster')
                                                Poster
                                            @else
                                                Internal
                                            @endif
                                        </span>
                                    </td>

                                    <td
                                        class="px-6 py-4 transition-colors text-center sticky right-0 bg-white group-hover:bg-gray-50 z-20 border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="showEdit = true"
                                                class="p-2 text-yellow-600 bg-yellow-50 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors border border-yellow-100 shadow-sm"
                                                title="Edit Arsip">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button @click="showDelete = true"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100 shadow-sm"
                                                title="Hapus Arsip">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- MODAL LIHAT BUKTI GAMBAR --}}
                                        <template x-teleport="body">
                                            <div x-show="showBukti" style="display: none;"
                                                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                x-transition.opacity>
                                                <div @click.away="showBukti = false"
                                                    class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden transform transition-all text-left"
                                                    x-transition.scale>
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <h3
                                                                    class="text-lg font-black text-gray-800 tracking-tight">
                                                                    Dokumentasi Arsip</h3>
                                                                <p
                                                                    class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">
                                                                    {{ Str::limit($item->judul_kegiatan ?? $item->judul, 40) }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <button @click="showBukti = false"
                                                            class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition focus:outline-none"><svg
                                                                class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg></button>
                                                    </div>
                                                    <div
                                                        class="p-6 bg-gray-100/50 flex justify-center items-center flex-1 overflow-y-auto custom-scroll min-h-[300px]">
                                                        <img src="{{ asset($item->dokumentasi) }}"
                                                            alt="Dokumentasi Kegiatan"
                                                            class="max-h-[50vh] w-auto object-contain rounded-xl shadow-sm border border-gray-200">
                                                    </div>
                                                    <div
                                                        class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end gap-3 shrink-0">
                                                        <button @click="showBukti = false"
                                                            class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Tutup</button>
                                                        <a href="{{ asset($item->dokumentasi) }}" download
                                                            class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-2"><svg
                                                                class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                </path>
                                                            </svg> Unduh Gambar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- MODAL EDIT ARSIP --}}
                                        <template x-teleport="body">
                                            <div x-show="showEdit" style="display: none;"
                                                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4"
                                                x-transition.opacity>
                                                <div class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] flex flex-col overflow-hidden text-left"
                                                    x-transition.scale @click.away="showEdit = false">
                                                    <div
                                                        class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                                                        <div class="flex items-center gap-3">
                                                            <div
                                                                class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center shadow-sm">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <h2 class="text-xl font-bold text-gray-800">Edit Data Arsip
                                                            </h2>
                                                        </div>
                                                        <button @click="showEdit = false"
                                                            class="text-gray-400 hover:text-red-500 transition p-2 rounded-xl hover:bg-red-50 focus:outline-none"><svg
                                                                class="w-6 h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg></button>
                                                    </div>
                                                    <div class="p-8 overflow-y-auto custom-scroll">
                                                        <form action="{{ route('arsip.update', $item->id) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            x-data="{ fileName: '', filePreview: '{{ $item->dokumentasi ? asset($item->dokumentasi) : '' }}' }">
                                                            @csrf @method('PUT')
                                                            <div class="space-y-6">
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                    <div>
                                                                        <label
                                                                            class="block mb-2 text-sm font-bold text-gray-700">Judul
                                                                            Kegiatan / Poster <span
                                                                                class="text-red-500">*</span></label>
                                                                        <input type="text" name="judul_kegiatan"
                                                                            value="{{ $item->judul_kegiatan ?? $item->judul }}"
                                                                            required
                                                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none">
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block mb-2 text-sm font-bold text-gray-700">Jenis
                                                                            Kegiatan <span
                                                                                class="text-red-500">*</span></label>
                                                                        <select required name="jenis_kegiatan"
                                                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                                                            @php $jenis = $item->jenis_kegiatan ?? $item->jenis; @endphp
                                                                            <option value="seminar"
                                                                                {{ $jenis == 'seminar' ? 'selected' : '' }}>
                                                                                Seminar / Workshop</option>
                                                                            <option value="kampanye"
                                                                                {{ $jenis == 'kampanye' ? 'selected' : '' }}>
                                                                                Kampanye Offline / Online</option>
                                                                            <option value="rilis_poster"
                                                                                {{ $jenis == 'rilis_poster' ? 'selected' : '' }}>
                                                                                Rilis Poster Edukasi</option>
                                                                            <option value="rapat"
                                                                                {{ $jenis == 'rapat' ? 'selected' : '' }}>
                                                                                Rapat Koordinasi</option>
                                                                            <option value="lainnya"
                                                                                {{ $jenis == 'lainnya' ? 'selected' : '' }}>
                                                                                Lainnya</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                    <div>
                                                                        <label
                                                                            class="block mb-2 text-sm font-bold text-gray-700">Tanggal
                                                                            Pelaksanaan <span
                                                                                class="text-red-500">*</span></label>
                                                                        <input type="date" required name="tanggal"
                                                                            value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block mb-2 text-sm font-bold text-gray-700">Lokasi
                                                                            <span class="text-red-500">*</span></label>
                                                                        <input type="text" required name="lokasi"
                                                                            value="{{ $item->lokasi }}"
                                                                            class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none">
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <label
                                                                        class="block mb-2 text-sm font-bold text-[#800000]">Status
                                                                        Publikasi Galeri <span
                                                                            class="text-red-500">*</span></label>
                                                                    <select required name="status_publikasi"
                                                                        class="bg-red-50/30 border border-red-200 text-red-900 text-sm font-bold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none shadow-sm cursor-pointer">
                                                                        <option value="sosialisasi"
                                                                            {{ $item->status_publikasi == 'sosialisasi' ? 'selected' : '' }}>
                                                                            Publikasikan sebagai "Sosialisasi Pencegahan"
                                                                        </option>
                                                                        <option value="poster"
                                                                            {{ $item->status_publikasi == 'poster' ? 'selected' : '' }}>
                                                                            Publikasikan sebagai "Poster Edukasi"</option>
                                                                        <option value="internal"
                                                                            {{ in_array($item->status_publikasi, ['internal', 'tidak_dipublikasi']) ? 'selected' : '' }}>
                                                                            Internal (Tidak Dipublikasikan ke Galeri)
                                                                        </option>
                                                                    </select>
                                                                </div>

                                                                <div>
                                                                    <label
                                                                        class="block mb-2 text-sm font-bold text-gray-700">Deskripsi
                                                                        Kegiatan <span
                                                                            class="text-red-500">*</span></label>
                                                                    <textarea rows="3" required name="deskripsi"
                                                                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none">{{ $item->deskripsi }}</textarea>
                                                                </div>

                                                                <div>
                                                                    <label
                                                                        class="block mb-2 text-sm font-bold text-gray-700">Ganti
                                                                        Gambar (Opsional)</label>
                                                                    <div class="flex items-center justify-center w-full">
                                                                        <label for="dokumentasi_edit_{{ $item->id }}"
                                                                            class="flex flex-col items-center justify-center w-full min-h-[12rem] border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-all group overflow-hidden relative">
                                                                            <div x-show="!filePreview"
                                                                                class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-500">
                                                                                <div
                                                                                    class="w-14 h-14 bg-white rounded-full shadow-sm border border-gray-200 flex items-center justify-center mb-4">
                                                                                    <svg class="w-6 h-6 text-gray-400"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </div>
                                                                                <p class="mb-1 text-sm font-medium">Klik
                                                                                    untuk mengganti gambar</p>
                                                                            </div>
                                                                            <div x-show="filePreview"
                                                                                style="display: none;"
                                                                                class="w-full h-full absolute inset-0">
                                                                                <img :src="filePreview"
                                                                                    class="w-full h-full object-cover">
                                                                                <div
                                                                                    class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                                                    <span
                                                                                        class="text-white font-bold text-sm bg-black/50 px-4 py-2 rounded-lg">Klik
                                                                                        untuk ganti gambar</span>
                                                                                </div>
                                                                            </div>
                                                                            <input
                                                                                id="dokumentasi_edit_{{ $item->id }}"
                                                                                name="dokumentasi" type="file"
                                                                                accept="image/*" class="hidden"
                                                                                @change="fileName = $event.target.files[0].name; let reader = new FileReader(); reader.onload = (e) => { filePreview = e.target.result }; reader.readAsDataURL($event.target.files[0])" />
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                                                                    <button type="button" @click="showEdit = false"
                                                                        class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                                                                    <button type="submit"
                                                                        class="px-6 py-3 bg-[#f7b500] text-white font-bold rounded-xl hover:bg-yellow-600 transition shadow-md flex items-center gap-2">Simpan
                                                                        Pembaruan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- MODAL HAPUS --}}
                                        <template x-teleport="body">
                                            <div x-show="showDelete" style="display: none;"
                                                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
                                                x-transition.opacity>
                                                <div @click.away="showDelete = false"
                                                    class="bg-white rounded-3xl shadow-2xl max-w-sm w-full text-center p-8 transform transition-all"
                                                    x-transition.scale>
                                                    <div
                                                        class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                                        <svg class="w-10 h-10 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Hapus Arsip?</h3>
                                                    <p class="text-gray-500 text-sm mb-8 font-medium">Arsip <strong
                                                            class="text-gray-800">{{ $item->judul_kegiatan ?? $item->judul }}</strong>
                                                        akan dihapus permanen.</p>
                                                    <div class="flex justify-center gap-3">
                                                        <button @click="showDelete = false"
                                                            class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors w-full">Batal</button>
                                                        <form action="{{ route('arsip.destroy', $item->id) }}"
                                                            method="POST" class="w-full m-0">@csrf
                                                            @method('DELETE')<button type="submit"
                                                                class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-md w-full">Ya,
                                                                Hapus</button></form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah Kegiatan Biasa --}}
        <div x-show="showTambahModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4"
            x-transition.opacity>
            <div class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] flex flex-col overflow-hidden"
                x-transition.scale @click.away="showTambahModal = false">
                <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                    <h2 class="text-xl font-bold text-gray-800">Tambah Arsip Kegiatan Baru</h2>
                    <button @click="showTambahModal = false"
                        class="text-gray-400 hover:text-red-500 transition p-2 rounded-xl hover:bg-red-50 focus:outline-none"><svg
                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>
                <div class="p-8 overflow-y-auto custom-scroll">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6">
                            <p class="font-bold text-sm mb-2">Mohon periksa kembali isian Anda:</p>
                            <ul class="list-disc pl-5 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data"
                        x-data="{ fileName: '', filePreview: '' }">
                        @csrf
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div><label class="block mb-2 text-sm font-bold text-gray-700">Judul Kegiatan / Poster
                                        <span class="text-red-500">*</span></label><input type="text"
                                        name="judul_kegiatan" value="{{ old('judul_kegiatan') }}" required
                                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all">
                                </div>
                                <div><label class="block mb-2 text-sm font-bold text-gray-700">Jenis Kegiatan <span
                                            class="text-red-500">*</span></label>
                                    <select required name="jenis_kegiatan"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all cursor-pointer">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="seminar"
                                            {{ old('jenis_kegiatan') == 'seminar' ? 'selected' : '' }}>Seminar / Workshop
                                        </option>
                                        <option value="kampanye"
                                            {{ old('jenis_kegiatan') == 'kampanye' ? 'selected' : '' }}>Kampanye Offline /
                                            Online</option>
                                        <option value="rilis_poster"
                                            {{ old('jenis_kegiatan') == 'rilis_poster' ? 'selected' : '' }}>Rilis Poster
                                            Edukasi</option>
                                        <option value="rapat" {{ old('jenis_kegiatan') == 'rapat' ? 'selected' : '' }}>
                                            Rapat Koordinasi</option>
                                        <option value="lainnya"
                                            {{ old('jenis_kegiatan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div><label class="block mb-2 text-sm font-bold text-gray-700">Tanggal Pelaksanaan <span
                                            class="text-red-500">*</span></label><input type="date" required
                                        name="tanggal" value="{{ old('tanggal') }}"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all cursor-pointer">
                                </div>
                                <div><label class="block mb-2 text-sm font-bold text-gray-700">Lokasi <span
                                            class="text-red-500">*</span></label><input type="text" required
                                        name="lokasi" value="{{ old('lokasi') }}"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all">
                                </div>
                            </div>
                            <div class="py-2">
                                <hr class="border-gray-100">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-[#800000]">Status Publikasi Galeri <span
                                        class="text-red-500">*</span></label>
                                <select required name="status_publikasi"
                                    class="bg-red-50/30 border border-red-200 text-red-900 text-sm font-bold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-4 outline-none shadow-sm cursor-pointer">
                                    <option value="">-- Tentukan Status Publikasi --</option>
                                    <option value="sosialisasi"
                                        {{ old('status_publikasi') == 'sosialisasi' ? 'selected' : '' }}>Publikasikan
                                        sebagai "Sosialisasi Pencegahan"</option>
                                    <option value="poster" {{ old('status_publikasi') == 'poster' ? 'selected' : '' }}>
                                        Publikasikan sebagai "Poster Edukasi"</option>
                                    <option value="internal"
                                        {{ old('status_publikasi') == 'internal' ? 'selected' : '' }}>Internal (Tidak
                                        Dipublikasikan ke Galeri)</option>
                                </select>
                            </div>
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Deskripsi Kegiatan <span
                                        class="text-red-500">*</span></label>
                                <textarea rows="4" required name="deskripsi"
                                    class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-4 outline-none transition-all">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700">Unggah Dokumentasi / Gambar <span
                                        class="text-red-500">*</span></label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="dokumentasi"
                                        class="flex flex-col items-center justify-center w-full min-h-[12rem] border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-400 transition-all group overflow-hidden relative">
                                        <div x-show="!filePreview"
                                            class="flex flex-col items-center justify-center pt-5 pb-6 text-gray-500 group-hover:text-gray-700">
                                            <div
                                                class="w-14 h-14 bg-white rounded-full shadow-sm border border-gray-200 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="mb-1 text-sm font-medium"><span class="font-bold text-gray-800">Klik
                                                    untuk memilih gambar</span></p>
                                        </div>
                                        <div x-show="filePreview" style="display: none;"
                                            class="w-full h-full absolute inset-0"><img :src="filePreview"
                                                class="w-full h-full object-cover"></div>
                                        <input id="dokumentasi" name="dokumentasi" type="file" accept="image/*"
                                            required class="hidden"
                                            @change="fileName = $event.target.files[0].name; let reader = new FileReader(); reader.onload = (e) => { filePreview = e.target.result }; reader.readAsDataURL($event.target.files[0])" />
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end gap-4 pt-8 border-t border-gray-100">
                                <button type="button" @click="showTambahModal = false"
                                    class="px-8 py-3.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition shadow-sm text-center">Batal</button>
                                <button type="submit"
                                    class="px-8 py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-all shadow-md active:scale-95 flex items-center gap-2"><svg
                                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg> Simpan & Publikasikan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL UPLOAD POSTER PROMO / AKAN DATANG --}}
        <template x-teleport="body">
            <div x-show="showPromoModal" style="display: none;"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4"
                x-transition.opacity>
                <div @click.away="showPromoModal = false"
                    class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl"
                    x-transition.scale>
                    <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-yellow-500 text-white rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="font-bold text-yellow-800 text-lg">Manajemen Poster (Akan Datang)</h3>
                        </div>
                        <button @click="showPromoModal = false"
                            class="text-gray-400 hover:text-red-500 bg-white p-1.5 rounded-lg border border-gray-200"><svg
                                class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>

                    <div class="p-6 overflow-y-auto custom-scroll">
                        <form action="{{ route('promo.upload') }}" method="POST" enctype="multipart/form-data"
                            class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                            @csrf
                            <p class="text-sm font-bold text-gray-800 mb-3">Unggah Poster Pop-up Baru</p>
                            <div class="flex gap-3">
                                <input type="file" name="gambar" accept="image/*"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#800000] file:text-white cursor-pointer bg-white border border-gray-300 rounded-lg p-1 shadow-sm"
                                    required>
                                <button type="submit"
                                    class="bg-yellow-500 text-white px-5 py-2 rounded-lg text-sm font-bold shrink-0 hover:bg-yellow-600 shadow-sm">Upload
                                    & Tampilkan</button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Catatan: Mengunggah gambar baru akan otomatis
                                menggantikan gambar pop-up sebelumnya di halaman depan.</p>
                        </form>

                        <p class="text-sm font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Poster Pop-up Saat
                            Ini:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if (count($promos) > 0)
                                @foreach ($promos as $index => $promo)
                                    <div
                                        class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm aspect-[3/4]">
                                        <img src="{{ $promo['url'] }}" class="w-full h-full object-contain bg-gray-100">
                                        <form action="{{ route('promo.hapus') }}" method="POST"
                                            class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            @csrf
                                            <input type="hidden" name="nama_file" value="{{ $promo['nama'] }}">
                                            <button type="submit"
                                                onclick="return confirm('Hapus poster ini? Pop-up tidak akan muncul lagi di halaman depan.')"
                                                class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-red-600 shadow-md">Hapus
                                                Poster</button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="col-span-full py-8 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                                    Belum ada poster yang diaktifkan.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Modal Alert Success --}}
        <div x-show="showSuccess" style="display: none;"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
            x-transition.opacity>
            <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-sm w-full mx-4 overflow-hidden transform transition-all text-center"
                x-transition.scale>
                <div class="bg-green-500 p-10 flex justify-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-2xl">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="p-10">
                    <h3 class="text-3xl font-black text-gray-800 mb-2">Aksi Berhasil!</h3>
                    <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">{{ session('success') }}</p>
                    <button @click="showSuccess = false" type="button"
                        class="w-full py-4 bg-[#800000] text-white font-bold rounded-2xl hover:bg-red-900 transition shadow-xl shadow-red-900/30 active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#tableArsip').DataTable({
                "language": {
                    "search": "Cari Kegiatan:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "emptyTable": "Belum ada arsip kegiatan.",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya"
                    }
                },
                "pagingType": "simple_numbers",
                "dom": '<"flex flex-col md:flex-row justify-between items-start gap-4 mb-6"<"left-side flex flex-col gap-3"l><"right-side flex flex-col items-end gap-3"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"ip>',
                "pageLength": 10,
                "scrollX": true,
                "order": [
                    [3, "desc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": -1
                }, {
                    "orderable": false,
                    "targets": 6
                }],
                "initComplete": function() {
                    var tableTitle =
                        '<span class="text-base text-gray-700 font-bold">Tabel Daftar Arsip</span>';
                    $('.left-side').prepend(tableTitle);

                    var btnTambahElem = $('#btnTambahContainer').children().detach();
                    $('.right-side').prepend(btnTambahElem);

                    var filterSelect = `
                    <select id="statusFilter" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] px-4 py-2.5 outline-none transition-all cursor-pointer shadow-sm w-full md:w-auto font-medium">
                        <option value="">Semua Status Publikasi</option>
                        <option value="Sosialisasi">Sosialisasi Pencegahan</option>
                        <option value="Poster">Poster Edukasi</option>
                        <option value="Internal">Internal / Tidak Dipublikasi</option>
                    </select>
                `;
                    $('.dataTables_filter').before(filterSelect);
                    $('.right-side select#statusFilter, .right-side .dataTables_filter').wrapAll(
                        '<div class="flex flex-col sm:flex-row gap-3 items-center w-full sm:w-auto mt-2"></div>'
                    );
                }
            });

            $('#statusFilter').on('change', function() {
                table.column(7).search(this.value).draw();
            });
        });
    </script>
@endpush
