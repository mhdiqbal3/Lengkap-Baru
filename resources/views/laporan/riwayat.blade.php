@extends('layouts.app')

@section('header_title', 'Riwayat Pengaduan Saya')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        .note-modal-backdrop { z-index: 10001 !important; display: none !important; }
        .note-modal { z-index: 10002 !important; }
        .note-dropdown-menu { z-index: 10005 !important; }
        .note-editor.note-frame .note-editing-area .note-editable { background-color: #ffffff; }
    </style>
    <div class="max-w-[100%] mx-auto pb-10">
        {{-- Header Halaman --}}
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Riwayat Laporan Anda</h2>
            <p class="text-gray-500 text-sm mt-1.5 font-medium">Daftar seluruh pengaduan yang telah Anda kirimkan beserta
                status penanganannya.</p>
        </div>

        {{-- Alert Success & Error --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex justify-between items-center"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-center gap-2 font-bold">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800"><svg class="w-4 h-4"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6 flex justify-between items-center"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-center gap-2 font-bold">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800"><svg class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg></button>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col p-6">

            <div class="overflow-x-auto custom-scroll flex-1 relative w-full">
                <table id="tableRiwayat" class="w-full text-sm text-left text-gray-600 min-w-[1700px] mt-4">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center w-16">No</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Kode Tiket</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Tgl. Lapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Judul Laporan</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Jenis Kasus</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Pelapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">No. WhatsApp</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">Status Korban</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center text-red-500">Status Terlapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">L/P</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">Disabilitas</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">Saksi</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">Bukti</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider text-center whitespace-nowrap">Status Penanganan</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap sticky right-0 bg-gray-50 z-30 border-l border-gray-200 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @if (isset($laporans))
                            @foreach ($laporans as $index => $item)
                                <tr class="bg-white hover:bg-gray-50/50 transition-colors group" x-data="{ showView: false, showEdit: false, showDelete: false, showBukti: false, showKeluhan: false, tanggapanDibaca: false, showStatus: false }">
                                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 font-bold text-[#800000] whitespace-nowrap">{{ $item->kode_tiket }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-gray-500" data-sort="{{ $item->created_at }}">
                                        {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-4 font-bold text-gray-800 min-w-[200px]">{{ $item->judul_lapor }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">{{ ucfirst($item->jenis_kasus) }}</span>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-gray-700 whitespace-nowrap">{{ $item->user ? $item->user->name : 'Anonim' }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if ($item->no_hp_korban)
                                            <a href="https://wa.me/{{ '62' . ltrim($item->no_hp_korban, '0') }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 text-green-600 hover:text-green-700 bg-green-50 px-3 py-1.5 rounded-lg font-bold transition-colors">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12.031 0C5.383 0 0 5.383 0 12.031c0 2.124.553 4.195 1.604 6.012L.19 24l6.14-1.583c1.76.963 3.754 1.472 5.801 1.472 6.648 0 12.031-5.383 12.031-12.031S18.679 0 12.031 0zm0 21.86c-1.802 0-3.568-.484-5.116-1.402l-.367-.217-3.8.98.998-3.705-.238-.379C2.476 15.541 1.95 13.81 1.95 12.031c0-5.562 4.519-10.081 10.081-10.081 5.563 0 10.082 4.519 10.082 10.081s-4.519 10.081-10.082 10.081zm5.534-7.551c-.303-.152-1.795-.886-2.073-.987-.278-.101-.481-.152-.684.152-.202.303-.784.987-.96 1.189-.177.202-.354.227-.657.076-1.353-.679-2.457-1.442-3.411-3.084-.177-.303-.019-.467.133-.618.136-.136.303-.354.455-.53.152-.177.202-.303.303-.505.101-.202.051-.379-.025-.531-.076-.152-.684-1.645-.936-2.251-.246-.593-.497-.512-.684-.521-.177-.008-.379-.01-.582-.01-.202 0-.531.076-.809.379-.278.303-1.062 1.037-1.062 2.53 0 1.493 1.088 2.934 1.239 3.136.152.202 2.138 3.265 5.178 4.577 1.303.561 2.054.675 2.825.642.85-.036 2.655-1.085 3.033-2.133.379-1.048.379-1.946.265-2.133-.114-.187-.417-.288-.72-.44z"></path>
                                                </svg>
                                                {{ $item->no_hp_korban }}
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        {{ ucfirst($item->status_korban) }}
                                        @if ($item->status_korban === 'lainnya' && $item->status_korban_lainnya)
                                            <span class="block text-xs text-gray-500 mt-1">({{ $item->status_korban_lainnya }})</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap font-medium text-red-600">{{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}</td>
                                    <td class="px-4 py-4 text-center">{{ $item->jenis_kelamin }}</td>
                                    <td class="px-4 py-4 text-center text-gray-400">{{ ucfirst($item->disabilitas) }}</td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        @php
                                            $saksi = $item->saksi ? json_decode($item->saksi, true) : null;
                                        @endphp
                                        @if($saksi && !empty($saksi['nama']))
                                            <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded-md">{{ $saksi['nama'] }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Tombol Lihat Bukti Gambar & Video --}}
                                    <td class="px-4 py-4 text-center">
                                        @if ($item->bukti || $item->link_video)
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
                                                Lihat
                                            </button>
                                        @else
                                            <span
                                                class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded border border-gray-100">Tidak
                                                ada</span>
                                        @endif
                                    </td>

                                    {{-- Tombol Status Penanganan --}}
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <button @click="showStatus = true"
                                            class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                            Lihat Status
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </td>

                                    {{-- Kolom Aksi Sticky --}}
                                    <td x-data="{ showActions: false }"
                                        class="px-6 py-4 transition-colors text-center sticky right-0 bg-white group-hover:bg-gray-50 z-20 border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                        
                                        <!-- Tombol Pemicu Mobile (Titik Tiga) -->
                                        <div class="lg:hidden flex items-center justify-center w-full h-full relative">
                                            <button @click="showActions = !showActions" @click.away="showActions = false" 
                                                class="p-2 text-gray-500 bg-gray-50 hover:bg-gray-200 rounded-lg transition-colors border border-gray-200 shadow-sm focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Container Tombol -->
                                        <div :class="showActions ? 'flex absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-white p-2 rounded-xl shadow-[0_0_15px_rgba(0,0,0,0.1)] border border-gray-200 z-[60]' : 'hidden lg:flex'" 
                                             class="items-center justify-center gap-2 w-max">
                                            <button @click="showView = true"
                                                class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg transition-colors shadow-sm border border-blue-100"
                                                title="Lihat Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>

                                            @if ($item->status == 'Menunggu Verifikasi')
                                                <button @click="showEdit = true"
                                                    class="p-2 text-yellow-600 bg-yellow-50 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors shadow-sm border border-yellow-100"
                                                    title="Edit Laporan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Tombol Keluhan (hanya saat Sedang Diproses) --}}
                                            @if ($item->status == 'Sedang Diproses')
                                                @php
                                                    $latestKeluhan = $item->keluhanss()->whereNotNull('catatan_satgas')->latest('updated_at')->first();
                                                    $adaTanggapanBaru = $latestKeluhan ? true : false;
                                                    $tanggapanKey = $latestKeluhan ? 'tanggapan_dibaca_' . $latestKeluhan->id . '_' . $latestKeluhan->updated_at->timestamp : '';
                                                @endphp
                                                <button 
                                                    @click="showKeluhan = true; tanggapanDibaca = true; @if($tanggapanKey) localStorage.setItem('{{ $tanggapanKey }}', 'true') @endif"
                                                    class="relative p-2 text-orange-600 bg-orange-50 hover:bg-orange-500 hover:text-white rounded-lg transition-colors shadow-sm border border-orange-100"
                                                    title="{{ $adaTanggapanBaru ? 'Ada tanggapan dari Satgas!' : 'Kirim Keluhan' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                        </path>
                                                    </svg>
                                                    @if($adaTanggapanBaru)
                                                        <span x-data="{ dibaca: localStorage.getItem('{{ $tanggapanKey }}') === 'true' }" 
                                                              x-show="!dibaca && !tanggapanDibaca"
                                                            class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white animate-pulse"></span>
                                                    @endif
                                                </button>
                                            @endif

                                            <button @click="showDelete = true"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-colors shadow-sm border border-red-100"
                                                title="Hapus Laporan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- MODAL LIHAT BUKTI GAMBAR & VIDEO --}}
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
                                                                    Lampiran Bukti</h3>
                                                                <p
                                                                    class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">
                                                                    {{ $item->kode_tiket }}</p>
                                                            </div>
                                                        </div>
                                                        <button @click="showBukti = false"
                                                            class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition focus:outline-none">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    {{-- Perubahan Disini: justify-start agar gambar tidak terpotong ke atas --}}
                                                    <div
                                                        class="p-6 bg-gray-100/50 flex flex-col justify-start items-center gap-6 flex-1 overflow-y-auto custom-scroll min-h-[300px]">
                                                        {{-- Bukti Foto: max-h dihapus, diganti w-full h-auto --}}
                                                        @if ($item->bukti)
                                                            <img src="{{ asset($item->bukti) }}" alt="Bukti Laporan"
                                                                class="w-full h-auto object-contain rounded-xl shadow-sm border border-gray-200">
                                                        @endif

                                                        {{-- Bukti Video --}}
                                                        @if ($item->link_video)
                                                            <div
                                                                class="w-full max-w-sm bg-white p-5 rounded-2xl border border-gray-200 shadow-sm text-center">
                                                                <div
                                                                    class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                                                    <svg class="w-6 h-6" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <h4 class="text-base font-bold text-gray-800 mb-1">Bukti
                                                                    Video</h4>
                                                                <p class="text-xs text-gray-500 mb-4">Terdapat lampiran
                                                                    bukti tambahan berupa video.</p>
                                                                <a href="{{ $item->link_video }}" target="_blank"
                                                                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors focus:outline-none w-full shadow-md">
                                                                    Buka Video Laporan
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div
                                                        class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end gap-3 shrink-0">
                                                        <button @click="showBukti = false"
                                                            class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Tutup</button>

                                                        @if ($item->bukti)
                                                            <a href="{{ asset($item->bukti) }}" download
                                                                class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4">
                                                                    </path>
                                                                </svg> Unduh Foto
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Modal View Detail --}}
                                        <template x-teleport="body">
                                            <div x-show="showView" style="display: none;"
                                                class="fixed inset-0 z-[9998] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                x-transition.opacity>
                                                <div @click.away="showView = false"
                                                    class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col text-left overflow-hidden transform transition-all"
                                                    x-transition.scale>

                                                    {{-- Header Gradient Merah --}}
                                                    <div
                                                        class="bg-gradient-to-r from-[#800000] to-red-900 p-6 sm:px-8 text-white flex justify-between items-center relative shrink-0 border-b-4 border-red-950/20 shadow-inner">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/20 shadow-lg">
                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="text-red-200 text-[10px] font-bold uppercase tracking-widest block mb-0.5">Detail
                                                                    Tiket Pengaduan</span>
                                                                <h3 class="text-2xl sm:text-3xl font-black drop-shadow-md tracking-tight">
                                                                    {{ $item->kode_tiket }}</h3>
                                                            </div>
                                                        </div>
                                                        <button @click="showView = false"
                                                            class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all focus:outline-none backdrop-blur-sm border border-white/10 hover:scale-105 active:scale-95 shadow-sm">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    {{-- Isi Modal --}}
                                                    <div
                                                        class="overflow-y-auto p-6 sm:p-8 custom-scroll bg-gray-50 flex-1">
                                                        {{-- Judul & Status --}}
                                                        <div
                                                            class="bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-md shadow-gray-200/40 rounded-2xl p-6 mb-6 relative overflow-hidden">
                                                            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 translate-x-10 -translate-y-10"></div>
                                                            <div
                                                                class="flex flex-col md:flex-row md:items-start justify-between gap-5 relative z-10">
                                                                <div>
                                                                    <p
                                                                        class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                        Judul Pengaduan</p>
                                                                    <p class="font-black text-gray-800 text-xl lg:text-2xl leading-tight">
                                                                        {{ $item->judul_lapor }}</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                            {{-- Kotak 1: Informasi Kejadian --}}
                                                            <div
                                                                class="bg-white border border-gray-100 shadow-sm shadow-gray-200/50 rounded-2xl p-6 hover:shadow-md transition-shadow">
                                                                <h4
                                                                    class="font-black text-[#800000] border-b-2 border-gray-100 pb-3 mb-5 flex items-center gap-2.5 text-sm uppercase tracking-wide">
                                                                    <div class="p-1.5 bg-red-50 rounded-lg text-[#800000]">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                    Informasi Kejadian
                                                                </h4>
                                                                <div class="space-y-3">
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori Kasus</p>
                                                                        <p class="font-black text-gray-800 text-sm">{{ strtoupper($item->jenis_kasus) }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal Kejadian</p>
                                                                        <p class="font-black text-gray-800 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->translatedFormat('d F Y') }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Lokasi Kejadian</p>
                                                                        <p class="font-black text-gray-800 text-sm">{{ $item->lokasi_kejadian }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-red-50 hover:bg-red-100/50 transition-colors rounded-xl border border-red-100">
                                                                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Status Terlapor (Pelaku)</p>
                                                                        <p class="font-black text-red-700 text-sm">{{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}</p>
                                                                    </div>
                                                                    @php
                                                                        $saksi = $item->saksi ? json_decode($item->saksi, true) : null;
                                                                    @endphp
                                                                    @if($saksi && !empty($saksi['nama']))
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Saksi Kejadian</p>
                                                                        <p class="font-black text-gray-800 text-sm">
                                                                            {{ $saksi['nama'] }} 
                                                                            @if(!empty($saksi['pekerjaan'])) <span class="text-xs text-gray-500 font-medium">({{ $saksi['pekerjaan'] }})</span> @endif
                                                                        </p>
                                                                        @if(!empty($saksi['telepon']) || !empty($saksi['alamat']))
                                                                            <div class="mt-1.5 pt-1.5 border-t border-gray-200">
                                                                                @if(!empty($saksi['telepon'])) <p class="text-[11px] text-gray-600 font-medium flex gap-1.5 items-center"><svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $saksi['telepon'] }}</p> @endif
                                                                                @if(!empty($saksi['alamat'])) <p class="text-[11px] text-gray-600 font-medium flex gap-1.5 items-start mt-1"><svg class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> <span class="line-clamp-2">{{ $saksi['alamat'] }}</span></p> @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            {{-- Kotak 2: Profil Pelapor / Korban --}}
                                                            <div
                                                                class="bg-white border border-gray-100 shadow-sm shadow-gray-200/50 rounded-2xl p-6 hover:shadow-md transition-shadow">
                                                                <h4
                                                                    class="font-black text-[#800000] border-b-2 border-gray-100 pb-3 mb-5 flex items-center gap-2.5 text-sm uppercase tracking-wide">
                                                                    <div class="p-1.5 bg-red-50 rounded-lg text-[#800000]">
                                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                    Profil Pelapor / Korban
                                                                </h4>
                                                                <div class="space-y-3">
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</p>
                                                                        <p class="font-black text-gray-800 text-sm">{{ $item->nama_korban }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nomor Kontak (HP/WA)</p>
                                                                        <p class="font-black text-gray-800 text-sm">{{ $item->no_hp_korban }}</p>
                                                                    </div>
                                                                    <div class="flex flex-col gap-1 p-3.5 bg-gray-50 hover:bg-gray-100/50 transition-colors rounded-xl border border-gray-100">
                                                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Identitas Lainnya</p>
                                                                        <p class="font-black text-gray-800 text-sm flex flex-wrap gap-1 items-center">
                                                                            <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm">{{ ucfirst($item->status_korban) }} @if ($item->status_korban === 'lainnya' && $item->status_korban_lainnya) ({{ $item->status_korban_lainnya }}) @endif</span>
                                                                            <span class="text-gray-300">â€¢</span>
                                                                            <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                                            <span class="text-gray-300">â€¢</span>
                                                                            <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm {{ $item->disabilitas == 'ya' ? 'text-red-600' : '' }}">{{ $item->disabilitas == 'ya' ? 'Disabilitas' : 'Non-Disabilitas' }}</span>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Kotak 3: Deskripsi & Bukti --}}
                                                        <div
                                                            class="bg-white border border-gray-100 shadow-sm shadow-gray-200/50 rounded-2xl p-6 hover:shadow-md transition-shadow">
                                                            <div class="mb-6">
                                                                <p
                                                                    class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2.5 flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                                                    Deskripsi & Kronologi Singkat</p>
                                                                <div class="p-5 bg-gray-50/80 border border-gray-100 rounded-xl">
                                                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-sm">
                                                                        {{ $item->deskripsi }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <p
                                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                                        Lampiran Bukti Foto</p>
                                                                    @if ($item->bukti)
                                                                        <button
                                                                            @click="showView = false; setTimeout(() => showBukti = true, 300)"
                                                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors focus:outline-none w-full justify-center md:justify-start">
                                                                            <svg class="w-5 h-5" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            Buka Foto Bukti
                                                                        </button>
                                                                    @else
                                                                        <div
                                                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-500 font-medium rounded-xl border border-gray-200">
                                                                            Tidak ada lampiran foto
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div>
                                                                    <p
                                                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                                        Link Video Kejadian</p>
                                                                    @if ($item->link_video)
                                                                        <a href="{{ $item->link_video }}" target="_blank"
                                                                            class="inline-flex items-center justify-center md:justify-start gap-2 px-5 py-2.5 bg-red-50 text-red-700 font-bold rounded-xl border border-red-100 hover:bg-red-100 transition-colors focus:outline-none w-full">
                                                                            <svg class="w-5 h-5" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            Buka Video Laporan
                                                                        </a>
                                                                    @else
                                                                        <div
                                                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-500 font-medium rounded-xl border border-gray-200">
                                                                            Tidak ada video
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Modal Edit --}}
                                        @if ($item->status == 'Menunggu Verifikasi')
                                            <template x-teleport="body">
                                                <div x-show="showEdit" style="display: none;"
                                                    class="fixed inset-0 z-[9998] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                    x-transition.opacity>
                                                    <div @click.away="showEdit = false"
                                                        class="bg-white rounded-[2rem] shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col text-left overflow-hidden transform transition-all"
                                                        x-transition.scale>

                                                        <div
                                                            class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                                                            <div class="flex items-center gap-4">
                                                                <div
                                                                    class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center shadow-sm">
                                                                    <svg class="w-6 h-6" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <h3
                                                                        class="text-xl font-black text-gray-800 tracking-tight">
                                                                        Edit Data Laporan</h3>
                                                                    <p class="text-sm font-bold text-[#800000]">
                                                                        {{ $item->kode_tiket }}</p>
                                                                </div>
                                                            </div>
                                                            <button @click="showEdit = false"
                                                                class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all focus:outline-none shadow-sm">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <form action="{{ route('laporan.update', $item->id) }}"
                                                            method="POST" enctype="multipart/form-data"
                                                            class="overflow-y-auto p-8 custom-scroll flex-1">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-8">
                                                                <h4
                                                                    class="text-base font-extrabold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                                                    <svg class="w-5 h-5 text-[#800000]" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                    1. Informasi Kejadian
                                                                </h4>
                                                                <div
                                                                    class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-5 shadow-sm">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-bold text-gray-700 mb-2">Judul
                                                                            Laporan <span
                                                                                class="text-red-500">*</span></label>
                                                                        <input type="text" name="judul_lapor"
                                                                            value="{{ $item->judul_lapor }}" required
                                                                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none transition-all shadow-sm">
                                                                    </div>
                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Jenis
                                                                                Kasus <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select name="jenis_kasus" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                                <option value="Kekerasan Seksual"
                                                                                    {{ $item->jenis_kasus == 'Kekerasan Seksual' ? 'selected' : '' }}>
                                                                                    Kekerasan Seksual</option>
                                                                                <option value="Kekerasan Fisik"
                                                                                    {{ $item->jenis_kasus == 'Kekerasan Fisik' ? 'selected' : '' }}>
                                                                                    Kekerasan Fisik</option>
                                                                                <option value="Kekerasan Psikis"
                                                                                    {{ $item->jenis_kasus == 'Kekerasan Psikis' ? 'selected' : '' }}>
                                                                                    Kekerasan Psikis</option>
                                                                                <option value="Perundungan"
                                                                                    {{ $item->jenis_kasus == 'Perundungan' ? 'selected' : '' }}>
                                                                                    Perundungan</option>
                                                                                <option
                                                                                    value="Diskriminasi dan intoleransi"
                                                                                    {{ $item->jenis_kasus == 'Diskriminasi dan intoleransi' ? 'selected' : '' }}>
                                                                                    Diskriminasi dan Intoleransi</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Tanggal
                                                                                Kejadian <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <input type="date" name="tanggal_kejadian"
                                                                                value="{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('Y-m-d') }}"
                                                                                required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-bold text-gray-700 mb-2">Lokasi
                                                                            Kejadian <span
                                                                                class="text-red-500">*</span></label>
                                                                        <input type="text" name="lokasi_kejadian"
                                                                            value="{{ $item->lokasi_kejadian }}" required
                                                                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none transition-all shadow-sm">
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-bold text-gray-700 mb-2">Deskripsi
                                                                            & Kronologi Lengkap <span
                                                                                class="text-red-500">*</span></label>
                                                                        <textarea name="deskripsi" rows="5" required
                                                                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none transition-all shadow-sm">{{ $item->deskripsi }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mb-8">
                                                                <h4
                                                                    class="text-base font-extrabold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                                                    <svg class="w-5 h-5 text-[#800000]" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                        </path>
                                                                    </svg>
                                                                    2. Identitas Pelapor / Korban & Terlapor
                                                                </h4>
                                                                <div
                                                                    class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-5 shadow-sm">

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Nama
                                                                                Lengkap Korban <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <input type="text" name="nama_korban"
                                                                                value="{{ $item->nama_korban }}" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none shadow-sm">
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Nomor
                                                                                HP/WhatsApp <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <input type="text" name="no_hp_korban"
                                                                                value="{{ $item->no_hp_korban }}" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none shadow-sm">
                                                                        </div>
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Status
                                                                                Korban <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select name="status_korban" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                                <option value="mahasiswa"
                                                                                    {{ $item->status_korban == 'mahasiswa' ? 'selected' : '' }}>
                                                                                    Mahasiswa</option>
                                                                                <option value="dosen"
                                                                                    {{ $item->status_korban == 'dosen' ? 'selected' : '' }}>
                                                                                    Dosen</option>
                                                                                <option value="staff"
                                                                                    {{ $item->status_korban == 'staff' ? 'selected' : '' }}>
                                                                                    Staff / Tendik</option>
                                                                                <option value="masyarakat_umum"
                                                                                    {{ $item->status_korban == 'masyarakat_umum' ? 'selected' : '' }}>
                                                                                    Masyarakat Umum</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Jenis
                                                                                Kelamin <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select name="jenis_kelamin" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                                <option value="L"
                                                                                    {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                                                                    Laki-laki</option>
                                                                                <option value="P"
                                                                                    {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                                                                    Perempuan</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Disabilitas?
                                                                                <span class="text-red-500">*</span></label>
                                                                            <select name="disabilitas" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                                <option value="tidak"
                                                                                    {{ $item->disabilitas == 'tidak' ? 'selected' : '' }}>
                                                                                    Tidak</option>
                                                                                <option value="ya"
                                                                                    {{ $item->disabilitas == 'ya' ? 'selected' : '' }}>
                                                                                    Ya</option>
                                                                            </select>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-bold text-gray-700 mb-2">Status
                                                                                Terlapor <span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select name="status_terlapor" required
                                                                                class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none cursor-pointer shadow-sm">
                                                                                <option value="mahasiswa"
                                                                                    {{ $item->status_terlapor == 'mahasiswa' ? 'selected' : '' }}>
                                                                                    Mahasiswa</option>
                                                                                <option value="dosen"
                                                                                    {{ $item->status_terlapor == 'dosen' ? 'selected' : '' }}>
                                                                                    Dosen</option>
                                                                                <option value="staff"
                                                                                    {{ $item->status_terlapor == 'staff' ? 'selected' : '' }}>
                                                                                    Staff / Tendik</option>
                                                                                <option value="masyarakat_umum"
                                                                                    {{ $item->status_terlapor == 'masyarakat_umum' ? 'selected' : '' }}>
                                                                                    Masyarakat Umum</option>
                                                                                <option value="tidak_diketahui"
                                                                                    {{ $item->status_terlapor == 'tidak_diketahui' ? 'selected' : '' }}>
                                                                                    Tidak Diketahui</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <h4
                                                                    class="text-base font-extrabold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                                                    <svg class="w-5 h-5 text-[#800000]" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                                        </path>
                                                                    </svg>
                                                                    3. Bukti Pendukung
                                                                </h4>
                                                                <div
                                                                    class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-bold text-gray-700 mb-2">Link
                                                                            Video Kejadian (Opsional)</label>
                                                                        <input type="url" name="link_video"
                                                                            value="{{ $item->link_video }}"
                                                                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-3 outline-none transition-all shadow-sm"
                                                                            placeholder="Contoh: https://drive.google.com/...">
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-bold text-gray-700 mb-2">Upload
                                                                            Bukti Gambar Baru (Opsional)</label>
                                                                        <input type="file" name="bukti"
                                                                            accept="image/*"
                                                                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] p-2 outline-none cursor-pointer shadow-sm">
                                                                        <p class="text-xs text-gray-500 mt-2">Biarkan
                                                                            kosong jika Anda tidak ingin mengubah/mengganti
                                                                            foto bukti lama. Maksimal 5MB (JPG, PNG).</p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="flex flex-col sm:flex-row justify-end gap-3 pt-8 mt-4 border-t border-gray-100">
                                                                <button type="button" @click="showEdit = false"
                                                                    class="px-8 py-3.5 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition-colors w-full sm:w-auto">Batal</button>
                                                                <button type="submit"
                                                                    class="px-8 py-3.5 bg-[#f7b500] text-white font-bold rounded-xl hover:bg-yellow-900 transition-all active:scale-95 w-full sm:w-auto">Simpan
                                                                    Pembaruan Data</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </template>
                                        @endif

                                        <template x-teleport="body">
                                            <div x-show="showDelete" style="display: none;"
                                                class="fixed inset-0 z-[9998] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
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
                                                    <h3 class="text-2xl font-black text-gray-900 mb-2">Hapus Laporan?</h3>
                                                    <p class="text-gray-500 text-sm mb-8 font-medium">Laporan <strong
                                                            class="text-gray-800">{{ $item->kode_tiket }}</strong> akan
                                                        dihapus secara permanen dan tidak dapat dipulihkan.</p>

                                                    <div class="flex justify-center gap-3">
                                                        <button @click="showDelete = false"
                                                            class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors w-full">Batal</button>
                                                        <form action="{{ route('laporan.destroy', $item->id) }}"
                                                            method="POST" class="w-full m-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-colors shadow-md w-full">Ya,
                                                                Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Modal Keluhan Pelapor (hanya muncul saat Sedang Diproses) --}}
                                        @if ($item->status == 'Sedang Diproses')
                                            <template x-teleport="body">
                                                <div x-show="showKeluhan" style="display: none;"
                                                    class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                    x-transition.opacity>
                                                    <div @click.away="showKeluhan = false"
                                                        class="bg-white rounded-[2rem] shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col text-left overflow-hidden transform transition-all"
                                                        x-transition.scale
                                                        x-data="{ showIsiKeluhan: false }">

                                                        {{-- Header Orange --}}
                                                        <div class="bg-gradient-to-r from-orange-500 to-amber-500 p-6 text-white flex justify-between items-center shrink-0">
                                                            <div class="flex items-center gap-3">
                                                                <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/20">
                                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <span class="text-orange-100 text-[10px] font-bold uppercase tracking-widest block">Formulir Keluhan</span>
                                                                    <h3 class="text-xl font-black tracking-tight">{{ $item->kode_tiket }}</h3>
                                                                </div>
                                                            </div>
                                                            <button @click="showKeluhan = false"
                                                                class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition focus:outline-none">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <div class="overflow-y-auto flex-1 p-6 custom-scroll bg-gray-50">

                                                            {{-- Riwayat keluhan sebelumnya --}}
                                                            @php
                                                                $keluhanLama = $item->keluhanss()->get();
                                                            @endphp
                                                            @if($keluhanLama->isNotEmpty())
                                                                <div class="mb-5">
                                                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Keluhan Sebelumnya</p>
                                                                    <div class="space-y-3">
                                                                        @foreach($keluhanLama as $kl)
                                                                            <div class="bg-white border {{ $kl->catatan_satgas ? 'border-green-200' : 'border-orange-100' }} rounded-xl shadow-sm overflow-hidden">
                                                                                <div class="flex items-center justify-between mb-0 gap-2 flex-wrap px-4 pt-4 pb-3 {{ $kl->catatan_satgas ? 'bg-green-50' : '' }}">
                                                                                    <span class="text-xs font-bold text-orange-700 bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100">{{ $kl->label_kategori }}</span>
                                                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border
                                                                                        {{ $kl->status === 'ditindaklanjuti' ? 'text-green-600 bg-green-50 border-green-100' : 'text-yellow-700 bg-yellow-50 border-yellow-100' }}">
                                                                                        {{ $kl->status === 'ditindaklanjuti' ? 'âœ“ Tanggapan' : 'â³ Menunggu Tanggapan' }}
                                                                                    </span>
                                                                                </div>
                                                                                @if($kl->isi_keluhan)
                                                                                    <p class="text-sm text-gray-700 px-4 pb-2 break-words whitespace-pre-wrap">{{ $kl->isi_keluhan }}</p>
                                                                                @endif
                                                                                {{-- Notifikasi Tanggapan Satgas --}}
                                                                                @if($kl->catatan_satgas)
                                                                                    <div class="mx-3 mb-3 mt-1 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-3.5">
                                                                                        <div class="flex items-center gap-2 mb-1.5">
                                                                                            <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                                                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                                                </svg>
                                                                                            </div>
                                                                                            <p class="text-[10px] font-black text-green-700 uppercase tracking-wider">Tanggapan dari Satgas</p>
                                                                                        </div>
                                                                                        <div class="text-sm text-gray-800 font-medium pl-7 prose prose-sm max-w-none">{!! $kl->catatan_satgas !!}</div>
                                                                                        <p class="text-[10px] text-gray-400 mt-1.5 pl-7">{{ \Carbon\Carbon::parse($kl->updated_at)->translatedFormat('d F Y, H:i') }}</p>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="mx-3 mb-3 mt-1 bg-amber-50 border border-amber-100 rounded-xl p-3 flex items-center gap-2">
                                                                                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                        </svg>
                                                                                        <p class="text-xs text-amber-700 font-medium">Menunggu tanggapan dari Satgas...</p>
                                                                                    </div>
                                                                                @endif
                                                                                <p class="text-[10px] text-gray-400 px-4 pb-3">Dikirim: {{ \Carbon\Carbon::parse($kl->created_at)->translatedFormat('d F Y, H:i') }}</p>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="w-full h-px bg-orange-100 mb-5"></div>
                                                            @endif

                                                            {{-- Form Keluhan Baru --}}
                                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Kirim Keluhan Baru</p>
                                                            <form id="formKeluhan_{{ $item->id }}" action="{{ route('keluhan.store') }}" method="POST" class="space-y-5">
                                                                @csrf
                                                                <input type="hidden" name="laporan_id" value="{{ $item->id }}">

                                                                {{-- Kategori Keluhan (radio) --}}
                                                                <div>
                                                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Keluhan <span class="text-red-500">*</span></label>
                                                                    <div class="space-y-2.5">
                                                                        <label class="flex items-center gap-3 p-3.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 hover:bg-orange-50/50 transition-all has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50">
                                                                            <input type="radio" name="kategori" value="belum_dihubungi" required
                                                                                @change="showIsiKeluhan = false"
                                                                                class="text-orange-500 focus:ring-orange-400">
                                                                            <div>
                                                                                <span class="text-sm font-semibold text-gray-700">Belum dihubungi Satgas</span>
                                                                                <p class="text-xs text-gray-400">Tim Satgas belum menghubungi saya</p>
                                                                            </div>
                                                                        </label>
                                                                        <label class="flex items-center gap-3 p-3.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 hover:bg-orange-50/50 transition-all has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50">
                                                                            <input type="radio" name="kategori" value="terlalu_lama"
                                                                                @change="showIsiKeluhan = false"
                                                                                class="text-orange-500 focus:ring-orange-400">
                                                                            <div>
                                                                                <span class="text-sm font-semibold text-gray-700">Penanganan terlalu lama</span>
                                                                                <p class="text-xs text-gray-400">Proses penanganan memakan waktu sangat lama</p>
                                                                            </div>
                                                                        </label>

                                                                        <label class="flex items-center gap-3 p-3.5 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-orange-300 hover:bg-orange-50/50 transition-all has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50">
                                                                            <input type="radio" name="kategori" value="lainnya"
                                                                                @change="showIsiKeluhan = true"
                                                                                class="text-orange-500 focus:ring-orange-400">
                                                                            <div>
                                                                                <span class="text-sm font-semibold text-gray-700">Lainnya</span>
                                                                                <p class="text-xs text-gray-400">Keluhan lain yang tidak termasuk di atas</p>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                {{-- Isi Keluhan (muncul jika Lainnya) --}}
                                                                <div x-show="showIsiKeluhan" x-transition style="display:none;">
                                                                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Keterangan Keluhan <span class="text-red-500">*</span></label>
                                                                    <textarea name="isi_keluhan" id="isiKeluhanEditor_{{ $item->id }}" :required="showIsiKeluhan"
                                                                        placeholder="Jelaskan keluhan Anda secara detail..."
                                                                        class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl p-3 outline-none transition-all shadow-sm"></textarea>
                                                                </div>
                                                            </form>
                                                        </div>

                                                        {{-- Tombol Aksi (Freeze â€” di luar area scroll, selalu terlihat) --}}
                                                        <div class="shrink-0 bg-white border-t border-gray-100 px-6 py-4 flex gap-3">
                                                            <button type="button" @click="showKeluhan = false"
                                                                class="flex-1 px-5 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</button>
                                                            <button type="submit" form="formKeluhan_{{ $item->id }}"
                                                                class="flex-1 px-5 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-all active:scale-95 shadow-md shadow-orange-200 flex items-center justify-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                                </svg>
                                                                Kirim Keluhan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        @endif
                                        
                                        {{-- Modal Detail Tiket Laporan --}}
                                        <template x-teleport="body">
                                            <div x-show="showStatus" style="display: none;"
                                                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                x-transition.opacity>
                                                <div class="absolute inset-0" @click="showStatus = false"></div>
                                                <div class="bg-white rounded-[2rem] shadow-2xl max-w-3xl w-full relative z-10 flex flex-col max-h-[90vh] overflow-hidden transform transition-all"
                                                    x-transition.scale>
                                                    {{-- Header --}}
                                                    <div class="bg-gradient-to-br from-[#800000] via-red-800 to-rose-900 px-7 py-6 text-white relative shrink-0 overflow-hidden">
                                                        <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                                                        <div class="absolute bottom-0 right-24 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
                                                        <button @click="showStatus = false"
                                                            class="absolute top-4 right-4 z-50 p-2 bg-white/15 hover:bg-white/30 text-white rounded-full transition-colors focus:outline-none backdrop-blur-sm">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                        <div class="relative z-10 flex items-center gap-4 pr-12">
                                                            <div class="w-12 h-12 flex items-center justify-center bg-white/15 rounded-2xl border border-white/25 shadow-lg shrink-0">
                                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <span class="text-red-200 text-[10px] font-black uppercase tracking-widest block mb-0.5">Detail Tiket Pengaduan</span>
                                                                <h3 class="text-2xl font-black tracking-widest drop-shadow-md">{{ $item->kode_tiket }}</h3>
                                                                <p class="text-red-200 text-xs mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i') }} WITA</p>
                                                            </div>
                                                            @php
                                                                $sBadge = ['Menunggu Verifikasi'=>'bg-yellow-400 text-yellow-900','Sedang Diproses'=>'bg-blue-400 text-blue-900','Selesai'=>'bg-green-400 text-green-900','Ditolak'=>'bg-gray-300 text-gray-800'];
                                                                $bStyle = $sBadge[$item->status] ?? 'bg-white text-gray-800';
                                                            @endphp
                                                            <div class="shrink-0 px-4 py-2 rounded-xl font-extrabold text-xs shadow-lg {{ $bStyle }}">{{ $item->status }}</div>
                                                        </div>
                                                    </div>
                                                    {{-- Body --}}
                                                    <div class="flex-1 overflow-y-auto custom-scroll bg-gray-50/60">
                                                        <div class="p-6 space-y-5">
                                                            {{-- Judul --}}
                                                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Judul Pengaduan</p>
                                                                <h4 class="text-lg font-black text-gray-800 leading-tight">{{ $item->judul_lapor }}</h4>
                                                            </div>
                                                            {{-- Grid 2 kolom --}}
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                {{-- Informasi Kejadian --}}
                                                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                                                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                                                                        <div class="w-8 h-8 bg-[#800000]/10 rounded-xl flex items-center justify-center">
                                                                            <svg class="w-4 h-4 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                                        </div>
                                                                        <h5 class="text-xs font-black text-[#800000] uppercase tracking-wide">Informasi Kejadian</h5>
                                                                    </div>
                                                                    <div class="space-y-3">
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Kategori Kasus</p><span class="inline-block bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-lg border border-purple-100">{{ ucfirst($item->jenis_kasus) }}</span></div>
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Tanggal Kejadian</p><p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->translatedFormat('d F Y') }}</p></div>
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Lokasi Kejadian</p><p class="text-sm font-bold text-gray-800">{{ $item->lokasi_kejadian }}</p></div>
                                                                        <div class="bg-red-50 rounded-xl p-3 border border-red-100"><p class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-0.5">Status Terlapor</p><p class="text-sm font-bold text-red-700">{{ ucwords(str_replace('_',' ',$item->status_terlapor)) }}</p></div>
                                                                    </div>
                                                                </div>
                                                                {{-- Profil Korban --}}
                                                                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                                                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100">
                                                                        <div class="w-8 h-8 bg-[#800000]/10 rounded-xl flex items-center justify-center">
                                                                            <svg class="w-4 h-4 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                        </div>
                                                                        <h5 class="text-xs font-black text-[#800000] uppercase tracking-wide">Profil Korban / Pelapor</h5>
                                                                    </div>
                                                                    <div class="space-y-3">
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama Lengkap</p><p class="text-sm font-bold text-gray-800">{{ $item->nama_korban }}</p></div>
                                                                        @if($item->no_hp_korban)
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP / WhatsApp</p><p class="text-sm font-bold text-green-700">{{ $item->no_hp_korban }}</p></div>
                                                                        @endif
                                                                        <div>
                                                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Identitas</p>
                                                                            <div class="flex flex-wrap gap-1.5">
                                                                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded-lg">{{ ucfirst($item->status_korban) }}@if($item->status_korban === 'lainnya' && $item->status_korban_lainnya) ({{ $item->status_korban_lainnya }})@endif</span>
                                                                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded-lg">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                                                <span class="text-xs font-semibold px-2 py-1 rounded-lg {{ $item->disabilitas == 'ya' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600' }}">{{ $item->disabilitas == 'ya' ? 'Disabilitas' : 'Non-Disabilitas' }}</span>
                                                                            </div>
                                                                        </div>
                                                                        @php $saksiR = $item->saksi ? json_decode($item->saksi, true) : null; @endphp
                                                                        @if($saksiR && !empty($saksiR['nama']))
                                                                        <div><p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Saksi</p><p class="text-sm font-bold text-gray-800">{{ $saksiR['nama'] }}@if(!empty($saksiR['pekerjaan'])) <span class="font-medium text-gray-500">({{ $saksiR['pekerjaan'] }})</span>@endif</p></div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- Kronologi --}}
                                                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Kronologi / Deskripsi</p>
                                                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $item->deskripsi }}</p>
                                                                </div>
                                                            </div>
                                                            {{-- Lampiran --}}
                                                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Lampiran Bukti</p>
                                                                <div class="flex flex-wrap gap-3">
                                                                    @if($item->bukti)
                                                                        <a href="{{ asset($item->bukti) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-700 font-bold text-sm rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>Lihat Foto Bukti</a>
                                                                    @else
                                                                        <span class="text-xs text-gray-400 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">Tidak ada lampiran foto</span>
                                                                    @endif
                                                                    @if($item->link_video)
                                                                        <a href="{{ $item->link_video }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-700 font-bold text-sm rounded-xl border border-red-100 hover:bg-red-100 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>Buka Video Laporan</a>
                                                                    @else
                                                                        <span class="text-xs text-gray-400 bg-gray-50 px-3 py-2 rounded-xl border border-gray-100">Tidak ada lampiran video</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- Footer --}}
                                                    <div class="px-6 py-4 flex justify-end border-t border-gray-100 bg-white shrink-0">
                                                        <button @click="showStatus = false"
                                                            class="px-8 py-3 bg-gradient-to-r from-[#800000] to-red-800 text-white font-bold rounded-xl hover:from-red-800 hover:to-[#800000] transition-all shadow-md active:scale-95 flex items-center gap-2 text-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            Tutup
                                                        </button>
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
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
        // Inisialisasi Summernote untuk setiap textarea isi keluhan
        document.addEventListener('alpine:initialized', () => {
            document.querySelectorAll('[id^="isiKeluhanEditor_"]').forEach(function(el) {
                $(el).summernote({
                    placeholder: 'Jelaskan keluhan Anda secara detail...',
                    height: 180,
                    dialogsInBody: true,
                    toolbar: [
                        ['font', ['bold', 'italic', 'underline']],
                        ['para', ['ul', 'ol']],
                        ['view', ['fullscreen']]
                    ]
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#tableRiwayat').DataTable({
                "language": {
                    "search": "Cari Laporan:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "emptyTable": "Belum ada riwayat laporan yang Anda kirimkan.",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya"
                    }
                },
                "pagingType": "simple_numbers",
                "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-5"<"title-length-wrap flex flex-col items-start gap-3"l><"action-search-wrap flex flex-col items-end gap-3"f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"ip>',
                "pageLength": 10,
                "scrollX": true,
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": -1 // Kolom Aksi
                    },
                    {
                        "orderable": false,
                        "targets": -3 // Kolom Bukti
                    }
                ],
                "initComplete": function() {
                    var tableTitle =
                        '<span class="text-base font-bold text-gray-700">Tabel Riwayat</span>';
                    $('.title-length-wrap').prepend(tableTitle);

                    var btnLapor = `
                    <a href="{{ route('laporkan') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 transition-all shadow-md active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Laporan Baru
                    </a>`;

                    $('.action-search-wrap').prepend(btnLapor);
                }
            });
        });
    </script>
@endpush
