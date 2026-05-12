@extends('layouts.app')

@section('header_title', 'Galeri & Edukasi')

@section('content')
    <div class="max-w-[100%] mx-auto pb-10">

        {{-- Header Galeri --}}
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Galeri Edukasi & Kegiatan</h2>
            <p class="text-gray-500 text-base mt-2 font-medium max-w-2xl">Kumpulan dokumentasi, poster edukasi, dan
                sosialisasi program pencegahan dan penanganan kekerasan di lingkungan perguruan tinggi USN Kolaka.</p>
        </div>

        {{-- Grid Layout - Dibuat Identik 4 Kolom di Desktop --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">

            @forelse ($galeris as $item)
                {{-- PERBAIKAN: Menambahkan x-data="{ showModal: false }" untuk kontrol pop-up --}}
                <div x-data="{ showModal: false }"
                    class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col h-full">

                    {{-- Bagian Gambar (Bisa diklik juga untuk buka modal) --}}
                    <div @click="showModal = true"
                        class="relative aspect-[4/3] bg-gray-100 overflow-hidden shrink-0 cursor-pointer">
                        <img src="{{ asset($item->dokumentasi) }}" alt="{{ $item->judul_kegiatan }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-in-out">

                        {{-- Efek Overlay Gradient --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity">
                        </div>

                        {{-- Badge Status --}}
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-xl text-white shadow-xl backdrop-blur-md
                            {{ $item->status_publikasi == 'poster' ? 'bg-[#800000]/90' : 'bg-blue-600/90' }}">
                                {{ $item->status_publikasi == 'poster' ? 'Poster Edukasi' : 'Sosialisasi' }}
                            </span>
                        </div>

                        {{-- Ikon Zoom di tengah saat hover --}}
                        <div
                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white shadow-lg border border-white/30">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Konten Teks --}}
                    <div class="p-6 flex flex-col flex-1">

                        {{-- Tanggal --}}
                        <div
                            class="flex items-center gap-2 text-[11px] font-black text-gray-400 mb-3 uppercase tracking-tighter">
                            <svg class="w-4 h-4 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                        </div>

                        {{-- Judul (Dibatasi 2 baris agar tinggi kartu tetap sama) --}}
                        <h3
                            class="text-lg font-black text-gray-800 leading-tight mb-4 line-clamp-2 group-hover:text-[#800000] transition-colors h-[3rem]">
                            {{ $item->judul_kegiatan ?? $item->judul }}
                        </h3>

                        {{-- Deskripsi Singkat (Dibatasi agar rapi) --}}
                        <p class="text-sm text-gray-500 font-medium line-clamp-2 mb-6">
                            {{ $item->deskripsi }}
                        </p>

                        {{-- PERBAIKAN: Footer Lokasi (Kiri) dan Tombol Lihat (Kanan) --}}
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-1.5 text-[12px] font-bold text-gray-400 truncate">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ $item->lokasi }}</span>
                            </div>

                            {{-- Tombol Lihat Detail --}}
                            <button @click="showModal = true"
                                class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-[#800000] text-gray-600 hover:text-white text-[11px] font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm focus:outline-none">
                                Lihat
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- PERBAIKAN: MODAL POP-UP DETAIL --}}
                    <template x-teleport="body">
                        <div x-show="showModal" style="display: none;"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4 sm:p-6"
                            x-transition.opacity>
                            <div @click.away="showModal = false"
                                class="bg-white rounded-[2rem] w-full max-w-5xl max-h-[90vh] sm:max-h-[85vh] overflow-hidden flex flex-col md:flex-row shadow-2xl transform transition-all text-left"
                                x-transition.scale>

                                {{-- Bagian Kiri: Preview Gambar Penuh --}}
                                <div
                                    class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center relative min-h-[300px] md:min-h-full">
                                    {{-- Menggunakan object-contain agar gambar tidak terpotong --}}
                                    <img src="{{ asset($item->dokumentasi) }}" alt="{{ $item->judul_kegiatan }}"
                                        class="w-full h-full object-contain max-h-[40vh] md:max-h-[85vh]">

                                    {{-- Badge Status di dalam Modal --}}
                                    <div class="absolute top-6 left-6">
                                        <span
                                            class="px-4 py-2 text-xs font-black uppercase tracking-widest rounded-xl text-white shadow-lg
                                        {{ $item->status_publikasi == 'poster' ? 'bg-[#800000]' : 'bg-blue-600' }}">
                                            {{ $item->status_publikasi == 'poster' ? 'Poster Edukasi' : 'Sosialisasi' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Bagian Kanan: Detail Teks Penuh --}}
                                <div
                                    class="w-full md:w-1/2 p-6 md:p-10 flex flex-col overflow-y-auto custom-scroll bg-white relative">

                                    {{-- Tombol Tutup Silang (Pojok Kanan Atas) --}}
                                    <button @click="showModal = false"
                                        class="absolute top-6 right-6 p-2 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition focus:outline-none">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <div class="mt-8 md:mt-4 mb-6">
                                        <p class="text-xs font-bold text-[#800000] uppercase tracking-wider mb-2">Detail
                                            Publikasi</p>
                                        <h2 class="text-2xl md:text-3xl font-black text-gray-800 leading-tight">
                                            {{ $item->judul_kegiatan ?? $item->judul }}</h2>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                            <p
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg> Tanggal
                                            </p>
                                            <p class="text-sm font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                            <p
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                </svg> Lokasi
                                            </p>
                                            <p class="text-sm font-bold text-gray-800">{{ $item->lokasi }}</p>
                                        </div>
                                    </div>

                                    <div class="mb-8 flex-1">
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                                            Deskripsi Kegiatan</p>
                                        <div class="text-gray-600 text-sm leading-relaxed space-y-4">
                                            {{-- Menampilkan deskripsi penuh dengan mempertahankan format paragraf --}}
                                            {!! nl2br(e($item->deskripsi)) !!}
                                        </div>
                                    </div>

                                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row gap-3 shrink-0">
                                        <button @click="showModal = false"
                                            class="w-full py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                                            Tutup
                                        </button>
                                        <a href="{{ asset($item->dokumentasi) }}" download
                                            class="w-full py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 shadow-md flex justify-center items-center gap-2 transition active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                </path>
                                            </svg>
                                            Unduh Gambar
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            @empty
                <div
                    class="col-span-full py-24 flex flex-col items-center justify-center text-center bg-white rounded-[3rem] border border-gray-100 border-dashed shadow-inner">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800">Galeri Masih Kosong</h3>
                    <p class="text-gray-500 text-sm mt-2 font-medium">Belum ada dokumentasi yang dipublikasikan.</p>
                </div>
            @endforelse

        </div>
    </div>
@endsection
