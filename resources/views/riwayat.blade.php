@extends('layouts.app')

@section('header_title', 'Riwayat Pengaduan Saya')

@section('content')
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
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Judul Laporan
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Jenis Kasus</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Nama Korban</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Status Korban</th>
                            <th scope="col"
                                class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center text-red-500">Status
                                Terlapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">L/P
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Disabilitas</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">No. HP</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Lokasi Kejadian
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Bukti</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider text-center whitespace-nowrap">
                                Status Penanganan</th>
                            <th scope="col"
                                class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap sticky right-0 bg-gray-50 z-30 border-l border-gray-200 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @if (isset($laporans))
                            @foreach ($laporans as $index => $item)
                                <tr class="bg-white hover:bg-gray-50/50 transition-colors group" x-data="{ showView: false, showEdit: false, showDelete: false, showBukti: false }">
                                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 font-bold text-[#800000] whitespace-nowrap">{{ $item->kode_tiket }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d M Y') }}</td>
                                    <td class="px-4 py-4 font-bold text-gray-800 min-w-[200px]">{{ $item->judul_lapor }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">{{ ucfirst($item->jenis_kasus) }}</span>
                                    </td>
                                    <td class="px-4 py-4 font-medium text-gray-700 whitespace-nowrap">
                                        {{ $item->nama_korban }}</td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        {{ ucwords(str_replace('_', ' ', $item->status_korban)) }}</td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap font-medium text-red-600">
                                        {{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}</td>
                                    <td class="px-4 py-4 text-center">{{ $item->jenis_kelamin }}</td>
                                    <td class="px-4 py-4 text-center text-gray-400">{{ ucfirst($item->disabilitas) }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $item->no_hp_korban }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        {{ \Illuminate\Support\Str::limit($item->lokasi_kejadian, 20) }}</td>

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
                                        <a href="{{ url('/cek-status') }}"
                                            class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                            Lihat Status
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>

                                    {{-- Kolom Aksi Sticky --}}
                                    <td
                                        class="px-6 py-4 transition-colors text-center sticky right-0 bg-white group-hover:bg-gray-50 z-20 border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                        <div class="flex items-center justify-center gap-2">
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

                                                    {{-- Header Modal --}}
                                                    <div
                                                        class="bg-[#800000] p-6 sm:px-8 text-white flex justify-between items-center relative shrink-0">
                                                        <div>
                                                            <span
                                                                class="text-red-200 text-xs font-bold uppercase tracking-widest block mb-1">Detail
                                                                Tiket Pengaduan</span>
                                                            <h3 class="text-2xl sm:text-3xl font-black">
                                                                {{ $item->kode_tiket }}</h3>
                                                        </div>
                                                        <button @click="showView = false"
                                                            class="p-2 bg-white/20 hover:bg-white/40 rounded-full transition-colors focus:outline-none">
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
                                                        {{-- Bagian Judul --}}
                                                        <div
                                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
                                                            <p
                                                                class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                                Judul Pengaduan</p>
                                                            <p class="font-black text-gray-800 text-xl lg:text-2xl">
                                                                {{ $item->judul_lapor }}</p>
                                                        </div>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                            {{-- Kotak 1: Informasi Kejadian --}}
                                                            <div
                                                                class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                                <h4
                                                                    class="font-bold text-[#800000] border-b border-gray-50 pb-3 mb-4 flex items-center gap-2">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    Informasi Kejadian
                                                                </h4>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Kategori Kasus</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ strtoupper($item->jenis_kasus) }}</p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Tanggal Kejadian</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->translatedFormat('d F Y') }}
                                                                        </p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Lokasi Kejadian</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ $item->lokasi_kejadian }}</p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-0.5">
                                                                            Status Terlapor (Pelaku)</p>
                                                                        <p class="font-bold text-red-600">
                                                                            {{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- Kotak 2: Profil Pelapor / Korban --}}
                                                            <div
                                                                class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                                <h4
                                                                    class="font-bold text-[#800000] border-b border-gray-50 pb-3 mb-4 flex items-center gap-2">
                                                                    <svg class="w-5 h-5" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                                        </path>
                                                                    </svg>
                                                                    Profil Pelapor / Korban
                                                                </h4>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Nama Lengkap</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ $item->nama_korban }}</p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Nomor Kontak (HP/WA)</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ $item->no_hp_korban }}</p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">
                                                                            Identitas Lainnya</p>
                                                                        <p class="font-bold text-gray-800">
                                                                            {{ ucwords(str_replace('_', ' ', $item->status_korban)) }}
                                                                            •
                                                                            {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                                            •
                                                                            {{ $item->disabilitas == 'ya' ? 'Disabilitas' : 'Non-Disabilitas' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Kotak 3: Deskripsi & Bukti --}}
                                                        <div
                                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                            <div class="mb-6">
                                                                <p
                                                                    class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                                    Deskripsi & Kronologi Singkat</p>
                                                                <div class="p-4 bg-gray-50 border border-gray-100">
                                                                    <p class="text-gray-700 whitespace-pre-wrap">
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
