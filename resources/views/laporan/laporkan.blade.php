@extends('layouts.app')

@section('header_title', 'Layanan Pengaduan & Peraturan')

@section('content')
    @php
        $dataPeraturan = isset($kontenPeraturan) && !empty($kontenPeraturan->konten)
                ? json_decode($kontenPeraturan->konten, true)
                : [];
        
        $peraturan_items = $dataPeraturan['peraturan_items'] ?? [
            [
                'nomor' => '55',
                'tahun' => 'Permendikbudristek 2024',
                'judul' => 'Pencegahan dan Penanganan Kekerasan di Lingkungan Perguruan Tinggi',
                'deskripsi' =>
                    'Menjamin penyelenggaraan tridharma yang ramah, aman, inklusif, setara, dan bebas dari kekerasan dengan memperluas bentuk pencegahan dan penanganan kekerasan.',
                'file_url' => 'assets/aturan/TAHUN 2024.pdf',
            ],
        ];

        // Hapus peraturan nomor 30 dan 17
        $peraturan_items = array_filter($peraturan_items, function($item) {
            return !in_array($item['nomor'], ['30', '17']);
        });
    @endphp

    <div class="max-w-5xl mx-auto pb-16 pt-8 px-4 sm:px-6 lg:px-8" x-data="{
        showPengaduan: false,
        showSuccess: false,
        showPdfModal: false,
        showPanduanModal: false,
        pdfUrl: '',
        pdfTitle: '',
        isLoading: false,
        kodeTiket: '',
        fileName: '',
        submitLaporan(event) {
            this.isLoading = true;
            let formData = new FormData(event.target);
    
            fetch(event.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.isLoading = false;
                    if (data.status === 'success') {
                        this.kodeTiket = data.kode_tiket;
                        this.showPengaduan = false;
                        this.showSuccess = true;
                        event.target.reset();
                        this.fileName = '';
                    } else {
                        alert('Gagal mengirim laporan: ' + (data.message || 'Periksa kembali isian Anda.'));
                    }
                })
                .catch(error => {
                    this.isLoading = false;
                    alert('Terjadi kesalahan pada sistem saat mengirim laporan.');
                    console.error(error);
                });
        }
    }">

        {{-- Notifikasi Sukses Global (Diubah Minimalis Maroon) --}}
        @if (session('success'))
            <div class="bg-white border border-[#800000]/20 text-[#800000] px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm"
                role="alert">
                <div class="w-8 h-8 bg-red-50 text-[#800000] rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- HEADER HALAMAN: Minimalis --}}
        <div
            class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-5 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Layanan Pengaduan</h1>
                <p class="text-gray-500 font-medium text-sm">Satgas PPKPT Universitas Sembilanbelas November Kolaka</p>
            </div>
            <button @click="showPengaduan = true"
                class="w-full md:w-auto px-8 py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-[#600000] transition-colors shadow-md active:scale-95 flex items-center justify-center gap-2 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Laporan Baru
            </button>
        </div>

        {{-- Form Upload Panduan Penggunaan (Khusus Admin - Minimalis Maroon) --}}
        @if (auth()->check() && auth()->user()->role === 'admin')
            <div
                class="bg-white border border-gray-200 rounded-2xl p-6 mb-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-5 shadow-sm">
                <div>
                    <h3 class="font-black text-gray-900 mb-1 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Perbarui Panduan Penggunaan
                    </h3>
                    <p class="text-xs font-medium text-gray-500">File PDF panduan ini akan otomatis tampil di halaman
                        pelapor dan Beranda.</p>
                </div>
                <form action="{{ route('panduan.upload') }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto shrink-0">
                    @csrf
                    <input type="file" name="panduan" accept="application/pdf" required
                        class="block w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 focus:outline-none bg-gray-50 border border-gray-200 rounded-lg p-1 transition-colors cursor-pointer">
                    <button type="submit"
                        class="w-full sm:w-auto bg-[#800000] hover:bg-[#600000] text-white px-6 py-2.5 rounded-lg font-bold text-xs transition-colors shadow-sm whitespace-nowrap focus:outline-none">
                        Simpan PDF
                    </button>
                </form>
            </div>
        @endif

        {{-- SECTION PANDUAN PENGGUNAAN: Minimalis --}}
        <div
            class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 mb-10 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 hover:border-[#800000]/30 transition-colors">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5">
                <div
                    class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-[#800000] shrink-0 border border-red-100">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 mb-1 tracking-tight">Buku Panduan Sistem PPKPT USN KOLAKA
                    </h3>
                    <p class="text-gray-500 text-sm font-medium leading-relaxed max-w-xl">
                        Pelajari langkah-langkah tata cara menggunakan aplikasi, melaporkan kasus dengan aman, serta melacak
                        status pengaduan Anda.
                    </p>
                </div>
            </div>

            <button @click="showPanduanModal = true"
                class="w-full sm:w-auto px-6 py-3 bg-white border-2 border-[#800000] text-[#800000] font-bold rounded-xl hover:bg-red-50 transition-colors whitespace-nowrap flex items-center justify-center gap-2 focus:outline-none">
                Baca Panduan
            </button>
        </div>

        {{-- SECTION PERATURAN: Minimalis --}}
        <section id="peraturan" class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
                <div class="max-w-2xl">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight">Peraturan yang Berlaku</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Dasar hukum pencegahan dan penanganan laporan.</p>
                </div>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('informasi.peraturan.edit') }}"
                        class="inline-flex w-full md:w-auto justify-center items-center gap-2 bg-white text-[#800000] border-2 border-[#800000] hover:bg-red-50 px-5 py-2.5 rounded-xl font-bold text-sm transition-colors shadow-sm focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Peraturan
                    </a>
                @endif
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                @foreach ($peraturan_items as $item)
                    <button
                        @click="showPdfModal = true; pdfTitle = '{{ addslashes($item['judul']) }}'; pdfUrl = '{{ asset($item['file_url']) }}'"
                        class="w-full text-left flex flex-col sm:flex-row bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:border-[#800000]/50 hover:shadow-md transition-all gap-5 items-start focus:outline-none group">

                        <div
                            class="w-12 h-12 shrink-0 bg-gray-50 text-[#800000] border border-gray-100 rounded-xl flex items-center justify-center font-black text-lg group-hover:bg-[#800000] group-hover:text-white transition-colors">
                            {{ $item['nomor'] }}
                        </div>

                        <div class="flex-1">
                            <div
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                {{ $item['tahun'] }}
                                <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                <span class="text-[#800000] flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                    BACA PDF
                                </span>
                            </div>
                            <h3
                                class="text-base font-black text-gray-900 mb-2 leading-snug group-hover:text-[#800000] transition-colors">
                                {{ $item['judul'] }}</h3>
                            <p class="text-gray-500 text-xs font-medium leading-relaxed line-clamp-3">
                                {{ $item['deskripsi'] }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- MODAL PDF VIEWER UNTUK PANDUAN PENGGUNAAN --}}
        <div x-show="showPanduanModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-gray-900/80 backdrop-blur-sm sm:px-4 sm:py-6"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showPanduanModal = false"></div>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full max-w-5xl h-[90vh] sm:h-[85vh] flex flex-col relative z-10 transform transition-all overflow-hidden"
                x-transition.scale>
                {{-- Header --}}
                <div class="flex justify-between items-center px-5 py-3.5 border-b border-gray-100 bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-50 text-[#800000] rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base">Panduan Penggunaan Sistem</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Tombol Buka Penuh (Tab Baru = Native PDF Viewer Fullscreen) --}}
                        <a href="{{ asset('assets/aturan/panduan.pdf') }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] bg-red-50 border border-red-100 hover:bg-[#800000] hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            Buka Penuh
                        </a>
                        <button @click="showPanduanModal = false"
                            class="text-gray-400 hover:text-[#800000] bg-gray-50 p-2 rounded-lg transition-colors focus:outline-none hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                {{-- PDF Viewer --}}
                <div class="flex-1 bg-gray-100 relative flex flex-col overflow-hidden">
                    <iframe src="{{ asset('assets/aturan/panduan.pdf') }}" class="w-full h-full border-none relative z-10"
                        title="Dokumen Panduan"></iframe>
                </div>
            </div>
        </div>

        {{-- MODAL PDF VIEWER UNTUK PERATURAN --}}
        <div x-show="showPdfModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-gray-900/80 backdrop-blur-sm sm:px-4 sm:py-6"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showPdfModal = false"></div>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full max-w-5xl h-[90vh] sm:h-[85vh] flex flex-col relative z-10 transform transition-all overflow-hidden"
                x-transition.scale>
                {{-- Header --}}
                <div class="flex justify-between items-center px-5 py-3.5 border-b border-gray-100 bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-50 text-[#800000] rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm sm:text-base truncate max-w-[180px] sm:max-w-md" x-text="pdfTitle"></h3>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Tombol Buka Penuh --}}
                        <a :href="pdfUrl" target="_blank"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] bg-red-50 border border-red-100 hover:bg-[#800000] hover:text-white px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                            Buka Penuh
                        </a>
                        <button @click="showPdfModal = false"
                            class="text-gray-400 hover:text-[#800000] bg-gray-50 p-2 rounded-lg transition-colors focus:outline-none hover:bg-red-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                {{-- PDF Viewer --}}
                <div class="flex-1 bg-gray-100 relative flex flex-col overflow-hidden">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 gap-3 z-0">
                        <svg class="w-6 h-6 animate-spin text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span class="text-xs font-bold">Memuat...</span>
                    </div>
                    <iframe :src="pdfUrl" class="w-full h-full border-none relative z-10"
                        title="Dokumen Peraturan"></iframe>
                </div>
            </div>
        </div>


        {{-- MODAL FORMULIR PENGADUAN --}}
        <div x-show="showPengaduan" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
            x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
                x-transition.scale @click.away="showPengaduan = false">

                {{-- Form Header Minimalis --}}
                <div
                    class="px-8 py-5 flex justify-between items-center border-b border-gray-100 bg-white shrink-0 z-10 relative">
                    <h2 class="text-lg font-black text-gray-900 flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-red-50 text-[#800000] rounded-lg flex items-center justify-center border border-red-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        Formulir Pengaduan Resmi
                    </h2>
                    <button @click="showPengaduan = false"
                        class="text-gray-400 hover:text-[#800000] hover:bg-red-50 transition-colors p-2 rounded-xl focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 md:p-8 overflow-y-auto custom-scroll bg-white">

                    {{-- KOTAK PERINGATAN BIRU: SESUAI GAMBAR YANG DIMINTA --}}
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-5 mb-8 rounded-r-xl shadow-sm">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-black text-blue-900">Privasi Anda Terjamin Sepenuhnya</h3>
                                <p class="text-sm text-blue-800 mt-1 font-medium leading-relaxed">Identitas pelapor dan
                                    korban akan dirahasiakan sepenuhnya sesuai mandat undang-undang. Anda juga berhak
                                    melapor secara anonim tanpa rasa takut.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="submitLaporan($event)">
                        @csrf
                        <div class="space-y-6">

                            {{-- Info Dasar Laporan --}}
                            <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Informasi Dasar Laporan
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Judul Pengaduan <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="judul_lapor" required
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Contoh: Pelecehan Verbal di Ruang Dosen">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Kategori Kasus <span
                                                class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <select required name="jenis_kasus"
                                                class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all cursor-pointer appearance-none">
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="Kekerasan Seksual">Kekerasan Seksual</option>
                                                <option value="Kekerasan Fisik">Kekerasan Fisik</option>
                                                <option value="Kekerasan Psikis">Kekerasan Psikis</option>
                                                <option value="Perundungan">Perundungan</option>
                                                <option value="Diskriminasi dan intoleransi">Diskriminasi dan Intoleransi</option>
                                                <option value="Kebijakan yang mengandung unsur kekerasan">Kebijakan yang mengandung unsur kekerasan</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Data Korban & Status Keadaan --}}
                            <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Data Korban & Terlapor
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Nama Lengkap Korban <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="nama_korban" required
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Masukkan nama lengkap sesuai identitas">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Nomor Kontak (WhatsApp Aktif)
                                            <span class="text-red-500">*</span></label>
                                        <input type="tel" name="no_hp_korban" required
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Contoh: 081234567890">
                                    </div>
                                </div>

                                {{-- Status Keadaan --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" x-data="{ statusKorban: '', statusTerlapor: '' }">
                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                            Korban <span class="text-red-500">*</span></label>
                                        <select required name="status_korban" x-model="statusKorban"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                            <option value="">-- Pilih --</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                            <option value="dosen">Dosen</option>
                                            <option value="staff">Staff / Tendik</option>
                                            <option value="masyarakat_umum">Masyarakat Umum</option>
                                            <option value="lainnya">Lainnya (Sebutkan)</option>
                                        </select>
                                        
                                        <div x-show="statusKorban === 'lainnya'" style="display: none;" class="mt-2">
                                            <input type="text" name="status_korban_lainnya" :required="statusKorban === 'lainnya'"
                                                class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all"
                                                placeholder="Sebutkan status korban...">
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Gender
                                            <span class="text-red-500">*</span></label>
                                        <select required name="jenis_kelamin"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                            <option value="">-- Pilih --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Disabilitas
                                            <span class="text-red-500">*</span></label>
                                        <select required name="disabilitas"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                            <option value="">-- Pilih --</option>
                                            <option value="tidak">Tidak</option>
                                            <option value="ya">Ya</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block mb-2 text-xs font-bold text-[#800000] uppercase tracking-wider">Status
                                            Terlapor <span class="text-red-500">*</span></label>
                                        <select required name="status_terlapor" x-model="statusTerlapor"
                                            class="bg-red-50 border border-red-200 text-[#800000] text-sm font-bold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer">
                                            <option value="">-- Terlapor --</option>
                                            <option value="mahasiswa">Mahasiswa</option>
                                            <option value="dosen">Dosen</option>
                                            <option value="staff">Staff / Tendik</option>
                                            <option value="masyarakat_umum">Masyarakat Umum</option>
                                            <option value="lainnya">Lainnya (Sebutkan)</option>
                                        </select>
                                        
                                        <div x-show="statusTerlapor === 'lainnya'" style="display: none;" class="mt-2">
                                            <input type="text" name="status_terlapor_lainnya" :required="statusTerlapor === 'lainnya'"
                                                class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all"
                                                placeholder="Sebutkan status terlapor...">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEKSI 3: Waktu & Lokasi --}}
                            <div class="bg-gray-50 rounded-xl p-4 space-y-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Waktu & Lokasi Kejadian
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Tanggal Kejadian <span class="text-red-500">*</span></label>
                                        <input type="date" required name="tanggal_kejadian"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all cursor-pointer">
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-gray-800">Lokasi Kejadian Secara Spesifik <span class="text-red-500">*</span></label>
                                        <input type="text" required name="lokasi_kejadian"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Contoh: Depan Lab Komputer FTI Lt. 2">
                                    </div>
                                </div>
                            </div>

                            {{-- SEKSI 4: Kronologi --}}
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                    Kronologi Singkat & Jelas <span class="text-red-500">*</span>
                                </h4>
                                <textarea rows="4" required name="deskripsi"
                                    class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-4 outline-none transition-all resize-none leading-relaxed placeholder:font-normal placeholder:text-gray-400"
                                    placeholder="Ceritakan peristiwa yang terjadi secara berurutan dan jelas. Apa yang terjadi? Siapa yang terlibat? Bagaimana situasinya?"></textarea>
                            </div>

                            {{-- SEKSI 5: Informasi Saksi (Opsional) --}}
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Informasi Saksi <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">(Opsional)</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Saksi</label>
                                        <input type="text" name="saksi_nama"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Nama lengkap saksi">
                                    </div>
                                    <div>
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Pekerjaan Saksi</label>
                                        <input type="text" name="saksi_pekerjaan"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Pekerjaan saksi">
                                    </div>
                                    <div>
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Telepon Saksi</label>
                                        <input type="tel" name="saksi_telepon"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Contoh: 081234567890">
                                    </div>
                                    <div>
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat Saksi</label>
                                        <input type="text" name="saksi_alamat"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none transition-all placeholder:font-normal placeholder:text-gray-400"
                                            placeholder="Alamat saksi">
                                    </div>
                                </div>
                            </div>

                            {{-- SEKSI 6: Bukti --}}
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Lampiran Bukti <span class="text-gray-400 font-normal normal-case tracking-normal ml-1">(Opsional)</span>
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Tautan Bukti Video</label>
                                        <p class="mb-3 text-xs font-medium text-gray-400 leading-relaxed">Link Google Drive atau YouTube.</p>
                                        <input type="url" name="link_video"
                                            class="bg-white border border-gray-200 text-gray-900 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3.5 outline-none transition-all placeholder:font-normal placeholder:text-gray-400 mt-auto"
                                            placeholder="Contoh: https://drive.google.com/...">
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="block mb-1.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Unggah File Bukti Gambar</label>
                                        <div class="flex items-center justify-center w-full relative mt-auto h-[6.5rem]">
                                            <label for="dropzone-file"
                                                class="flex flex-col items-center justify-center w-full h-full border-2 border-gray-200 border-dashed rounded-xl cursor-pointer bg-white hover:bg-red-50 hover:border-[#800000]/30 transition-all relative overflow-hidden group">
                                                <div class="flex flex-col items-center justify-center text-center px-4">
                                                    <svg class="w-6 h-6 mb-1.5 text-gray-400 group-hover:text-[#800000] transition-colors"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4">
                                                        </path>
                                                    </svg>
                                                    <p class="mb-0.5 text-xs font-bold text-gray-600 group-hover:text-[#800000]"
                                                        x-show="!fileName">Klik untuk unggah foto</p>
                                                    <p class="mb-0.5 text-sm font-black text-[#800000] truncate max-w-[200px]"
                                                        x-show="fileName" x-text="fileName"></p>
                                                    <p class="text-[10px] font-medium text-gray-400">JPEG, PNG, WebP (Ukuran berapapun, otomatis dikompres ke maks. 2MB)</p>
                                                </div>
                                                <input id="dropzone-file" name="bukti" type="file"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                                                    accept="image/jpeg, image/png, image/jpg, image/webp" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 mt-4 border-t border-gray-100">
                                <button type="button" @click="showPengaduan = false"
                                    class="w-full sm:w-auto px-8 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors text-center focus:outline-none">Tutup</button>

                                <button type="submit" :disabled="isLoading"
                                    class="w-full sm:w-auto px-10 py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-all shadow-md active:scale-95 flex justify-center items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed focus:outline-none">
                                    <svg x-show="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    <svg x-show="isLoading" class="animate-spin h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        style="display: none;">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span x-text="isLoading ? 'Memproses...' : 'Kirim Laporan'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL SUKSES: Minimalis Maroon --}}
        <div x-show="showSuccess" style="display: none;"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
            x-transition.opacity>
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden transform transition-all text-center relative p-8"
                x-transition.scale>

                <div
                    class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-red-100">
                    <svg class="w-10 h-10 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>

                <h3 class="text-2xl font-black text-gray-900 mb-2 tracking-tight">Laporan Terkirim!</h3>
                <p class="text-gray-500 text-sm font-medium mb-6 leading-relaxed">Pengaduan Anda telah diamankan. Mohon
                    simpan <b>Nomor Tiket</b> rahasia Anda di bawah ini:</p>

                <div class="bg-gray-50 border border-gray-200 py-4 w-full rounded-2xl mb-8">
                    <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-widest font-black">KODE TIKET PELACAKAN</p>
                    <p class="text-3xl font-black text-[#800000] tracking-wider" x-text="kodeTiket"></p>
                </div>

                <div class="flex flex-col gap-3 w-full">
                    <a href="{{ url('/riwayat') }}"
                        class="w-full py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-colors shadow-md focus:outline-none">
                        Lihat Riwayat Laporan
                    </a>
                    <button @click="showSuccess = false" type="button"
                        class="w-full py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors focus:outline-none">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection
