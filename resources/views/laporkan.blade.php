@extends('layouts.app')

@section('header_title', 'Layanan Pengaduan & Peraturan')

@section('content')
    @php
        // Mengambil data peraturan dari database
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

    <div class="max-w-6xl mx-auto pb-12" x-data="{
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

        {{-- Header Halaman --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Layanan Pengaduan</h1>
                <p class="text-gray-500 font-medium">Satgas PPKPT Universitas Sembilanbelas November Kolaka</p>
            </div>
            <button @click="showPengaduan = true"
                class="flex w-full sm:w-auto justify-center px-8 py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-all shadow-lg active:scale-95 items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Laporan Baru
            </button>
        </div>

        {{-- Notifikasi Sukses Global --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-sm"
                role="alert">
                <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <p class="font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ========================================== --}}
        {{-- SECTION PANDUAN PENGGUNAAN --}}
        {{-- ========================================== --}}
        <section id="panduan" class="mb-12 border-t border-gray-100 pt-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                <div class="max-w-2xl">
                    <span class="text-blue-600 text-sm font-black uppercase tracking-widest block mb-1">Bantuan
                        Sistem</span>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Panduan Penggunaan</h2>
                </div>
            </div>

            {{-- Form Upload Panduan Penggunaan (Khusus Admin) --}}
            @if (auth()->check() && auth()->user()->role === 'admin')
                <div
                    class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 shadow-sm">
                    <div>
                        <h3 class="font-black text-blue-900 mb-1 text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Upload PDF Panduan
                        </h3>
                        <p class="text-xs font-medium text-blue-700">File panduan ini akan ditampilkan di sini dan dapat
                            dibaca pelapor. Format harus PDF, Maks. 10MB.</p>
                    </div>
                    <form action="{{ route('panduan.upload') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto shrink-0">
                        @csrf
                        <input type="file" name="panduan" accept="application/pdf" required
                            class="block w-full text-xs text-blue-800 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 focus:outline-none bg-white border border-blue-100 rounded-xl p-1.5 shadow-sm cursor-pointer">
                        <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md whitespace-nowrap focus:outline-none active:scale-95">
                            Simpan PDF
                        </button>
                    </form>
                </div>
            @endif

            {{-- Kartu Baca Panduan untuk Pelapor (Semua User) --}}
            <div
                class="bg-white border border-gray-200 rounded-[2rem] p-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                <div class="flex items-center gap-5">
                    <div
                        class="w-16 h-16 shrink-0 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 mb-1 leading-snug">Buku Panduan Sistem PPKS</h3>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed">Langkah-langkah lengkap cara
                            menggunakan aplikasi, melaporkan kasus, dan melacak status pengaduan Anda.</p>
                    </div>
                </div>
                <button @click="showPanduanModal = true"
                    class="w-full sm:w-auto px-6 py-3 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-600 hover:text-white transition-colors shadow-sm whitespace-nowrap flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    Baca Panduan
                </button>
            </div>
        </section>


        {{-- ========================================== --}}
        {{-- SECTION PERATURAN YANG BERLAKU --}}
        {{-- ========================================== --}}
        <section id="peraturan" class="mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6 border-t border-gray-100 pt-10">
                <div class="max-w-2xl">
                    <span class="text-[#800000] text-sm font-black uppercase tracking-widest block mb-1">Dasar Hukum</span>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Peraturan yang Berlaku</h2>
                </div>

                {{-- Tombol Edit Peraturan Khusus Admin --}}
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('informasi.peraturan.edit') }}"
                        class="inline-flex w-full md:w-auto justify-center items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Peraturan
                    </a>
                @endif
            </div>

            {{-- Grid Tampilan Peraturan --}}
            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($peraturan_items as $item)
                    <button
                        @click="showPdfModal = true; pdfTitle = '{{ addslashes($item['judul']) }}'; pdfUrl = '{{ asset($item['file_url']) }}'"
                        class="w-full text-left flex flex-col sm:flex-row bg-white border border-gray-200 rounded-[2rem] p-8 shadow-sm hover:shadow-xl hover:border-[#800000]/30 transition-all duration-300 gap-5 items-start focus:outline-none group">

                        <div
                            class="w-16 h-16 shrink-0 {{ $loop->iteration % 2 == 0 ? 'bg-[#800000] shadow-red-900/20' : 'bg-gray-900 shadow-gray-900/20' }} text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-lg group-hover:scale-110 transition-transform">
                            {{ $item['nomor'] }}
                        </div>

                        <div>
                            <div
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                {{ $item['tahun'] }}
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                            </div>
                            <h3
                                class="text-lg font-black text-gray-900 mb-2 leading-snug group-hover:text-[#800000] transition-colors">
                                {{ $item['judul'] }}</h3>
                            <p class="text-gray-500 text-sm font-medium leading-relaxed">{{ $item['deskripsi'] }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- MODAL PDF VIEWER UNTUK PANDUAN PENGGUNAAN --}}
        <div x-show="showPanduanModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showPanduanModal = false"></div>
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col relative z-10 transform transition-all overflow-hidden"
                x-transition.scale>
                <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100 bg-gray-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-black text-gray-800 text-lg">Panduan Penggunaan Sistem</h3>
                    </div>
                    <button @click="showPanduanModal = false"
                        class="text-gray-400 hover:text-blue-600 bg-white p-2 rounded-xl shadow-sm border border-gray-200 transition-colors focus:outline-none hover:bg-blue-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 bg-gray-200 relative flex flex-col">
                    <iframe src="{{ asset('assets/aturan/panduan.pdf') }}"
                        class="w-full h-full relative z-10 border-none" title="Dokumen Panduan"></iframe>
                </div>
            </div>
        </div>

        {{-- MODAL PDF VIEWER UNTUK PERATURAN --}}
        <div x-show="showPdfModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showPdfModal = false"></div>
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col relative z-10 transform transition-all overflow-hidden"
                x-transition.scale>
                <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100 bg-gray-50 shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-red-100 text-[#800000] rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-black text-gray-800 text-lg" x-text="pdfTitle"></h3>
                    </div>
                    <button @click="showPdfModal = false"
                        class="text-gray-400 hover:text-red-500 bg-white p-2 rounded-xl shadow-sm border border-gray-200 transition-colors focus:outline-none hover:bg-red-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 bg-gray-200 relative flex flex-col">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 gap-3 z-0">
                        <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span class="text-sm font-bold">Memuat Dokumen...</span>
                    </div>
                    <iframe :src="pdfUrl" class="w-full h-full relative z-10 border-none"
                        title="Dokumen Peraturan"></iframe>
                </div>
            </div>
        </div>

        {{-- MODAL FORMULIR PENGADUAN --}}
        <div x-show="showPengaduan" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4 py-6"
            x-transition.opacity>
            <div class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full mx-auto max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
                x-transition.scale @click.away="showPengaduan = false">

                <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                    <h2 class="text-xl font-black text-gray-800 flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-red-100 text-[#800000] rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </div>
                        Formulir Laporan Pengaduan
                    </h2>
                    <button @click="showPengaduan = false"
                        class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-xl hover:bg-red-50 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scroll">
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
                                    korban akan dirahasiakan
                                    sepenuhnya sesuai mandat undang-undang. Anda juga berhak melapor secara anonim tanpa
                                    rasa takut.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="submitLaporan($event)">
                        @csrf
                        <div class="space-y-6">

                            {{-- Info Dasar Laporan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Judul Laporan <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="judul_lapor" required
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm"
                                        placeholder="Contoh: Pelecehan di Area Kantin FTI">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Kategori Kasus <span
                                            class="text-red-500">*</span></label>
                                    <select required name="jenis_kasus"
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm cursor-pointer">
                                        <option value="">-- Pilih Jenis Kasus --</option>
                                        <option value="Kekerasan Seksual">Kekerasan Seksual</option>
                                        <option value="Kekerasan Fisik">Kekerasan Fisik</option>
                                        <option value="Kekerasan Psikis">Kekerasan Psikis</option>
                                        <option value="Perundungan">Perundungan</option>
                                        <option value="Diskriminasi dan intoleransi">Diskriminasi dan Intoleransi</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-2">

                            {{-- Data Korban --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Nama Lengkap Korban <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="nama_korban" required
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm"
                                        placeholder="Masukkan nama lengkap korban">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Nomor Kontak (HP/WA) <span
                                            class="text-red-500">*</span></label>
                                    <input type="tel" name="no_hp_korban" required
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm"
                                        placeholder="Contoh: 081234567890">
                                </div>
                            </div>

                            {{-- Status Keadaan --}}
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 bg-gray-50/50 p-6 rounded-[1.5rem] border border-gray-100 shadow-inner">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Status Korban <span
                                            class="text-red-500">*</span></label>
                                    <select required name="status_korban"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer shadow-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="staff">Staff / Tendik</option>
                                        <option value="masyarakat_umum">Masyarakat Umum</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Jenis Kelamin <span
                                            class="text-red-500">*</span></label>
                                    <select required name="jenis_kelamin"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer shadow-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Disabilitas? <span
                                            class="text-red-500">*</span></label>
                                    <select required name="disabilitas"
                                        class="bg-white border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer shadow-sm">
                                        <option value="">-- Pilih --</option>
                                        <option value="tidak">Tidak</option>
                                        <option value="ya">Ya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-[#800000]">Status Pelaku <span
                                            class="text-red-500">*</span></label>
                                    <select required name="status_terlapor"
                                        class="bg-red-50 border border-red-200 text-[#800000] text-sm font-bold rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] block w-full p-3 outline-none cursor-pointer shadow-sm">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="staff">Staff / Tendik</option>
                                        <option value="masyarakat_umum">Masyarakat Umum</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-2">

                            {{-- Waktu & Lokasi --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Tanggal Kejadian <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" required name="tanggal_kejadian"
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all cursor-pointer shadow-sm">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Lokasi Kejadian <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" required name="lokasi_kejadian"
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm"
                                        placeholder="Contoh: Gedung Rektorat Lt. 2">
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700">Kronologi & Deskripsi Kejadian
                                    <span class="text-red-500">*</span></label>
                                <textarea rows="5" required name="deskripsi"
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-4 outline-none transition-all shadow-sm resize-none"
                                    placeholder="Ceritakan peristiwa yang Anda alami atau lihat secara detail dan jelas..."></textarea>
                            </div>

                            {{-- Bukti --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Link Video Kejadian
                                        (Opsional)</label>
                                    <input type="url" name="link_video"
                                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white block w-full p-3.5 outline-none transition-all shadow-sm"
                                        placeholder="https://drive.google.com/...">
                                    <p class="mt-2 text-[11px] font-medium text-gray-500 leading-relaxed">Sertakan link
                                        Google Drive atau YouTube jika Anda memiliki bukti berupa rekaman video.</p>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Unggah Bukti Foto
                                        (Opsional)</label>
                                    <div class="flex items-center justify-center w-full relative">
                                        <label for="dropzone-file"
                                            class="flex flex-col items-center justify-center w-full h-[7.5rem] border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors relative overflow-hidden group">
                                            <div
                                                class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                                <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:scale-110 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                    </path>
                                                </svg>
                                                <p class="mb-1 text-xs font-bold text-gray-600" x-show="!fileName">Klik
                                                    untuk mengunggah foto</p>
                                                <p class="mb-1 text-sm font-black text-[#800000] truncate max-w-[200px]"
                                                    x-show="fileName" x-text="fileName"></p>
                                                <p class="text-[10px] font-medium text-gray-400">JPEG, PNG, JPG (Maks. 5MB)
                                                </p>
                                            </div>
                                            <input id="dropzone-file" name="bukti" type="file"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                                                accept="image/jpeg, image/png, image/jpg" />
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-100 mt-6">
                                <button type="button" @click="showPengaduan = false"
                                    class="px-8 py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors shadow-sm text-center">Batal</button>
                                <button type="submit" :disabled="isLoading"
                                    class="px-8 py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-all shadow-md active:scale-95 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        style="display: none;">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span x-text="isLoading ? 'Mengirim Data...' : 'Kirim Laporan'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL SUKSES (Tampil setelah AJAX berhasil) --}}
        <div x-show="showSuccess" style="display: none;"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
            x-transition.opacity>
            <div class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full mx-4 overflow-hidden transform transition-all text-center"
                x-transition.scale>
                <div class="bg-green-500 p-8 flex justify-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Laporan Terkirim!</h3>
                    <p class="text-gray-500 text-sm font-medium mb-6 leading-relaxed">Pengaduan Anda telah masuk ke sistem
                        Satgas PPKPT. Mohon simpan <b class="text-gray-800">Nomor Tiket</b> berikut:</p>

                    <div class="bg-gray-50 border border-gray-200 py-4 rounded-2xl mb-8 shadow-inner">
                        <p class="text-[10px] text-gray-400 mb-1 uppercase tracking-widest font-black">KODE TIKET</p>
                        <p class="text-3xl font-black text-[#800000] tracking-wider" x-text="kodeTiket"></p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a href="{{ url('/riwayat') }}"
                            class="w-full py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-colors shadow-md">Lihat
                            Riwayat Laporan</a>
                        <button @click="showSuccess = false" type="button"
                            class="w-full py-3.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Tutup
                            Jendela Ini</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
