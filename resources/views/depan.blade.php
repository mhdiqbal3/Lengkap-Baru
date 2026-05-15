<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satgas PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>

<body class="bg-white text-gray-800 font-sans antialiased overflow-x-hidden" x-data="landingPageData()">
    @php
        // Helper Pembongkar JSON dari Database
        $dec = function ($konten) {
            return $konten && !empty($konten->konten) ? json_decode($konten->konten, true) : [];
        };

        $dTentang = $dec($kontenTentang);
        $dPencegahan = $dec($kontenPencegahan);
        $dPenanganan = $dec($kontenPenanganan);
        $dKontak = $dec($kontenKontak);

        $t = function ($key, $default) use ($dTentang) {
            return $dTentang[$key] ?? $default;
        };

        $p = function ($key, $default) use ($dPencegahan) {
            return $dPencegahan[$key] ?? $default;
        };

        $n = function ($key, $default) use ($dPenanganan) {
            return $dPenanganan[$key] ?? $default;
        };

        $k = function ($key, $default) use ($dKontak) {
            return $dKontak[$key] ?? $default;
        };

        // --- TAMBAHAN LOGIKA UNTUK BENTUK KEKERASAN (Menarik data dari Dashboard) ---
        $kontenDashboard = \App\Models\KontenHalaman::where('halaman', 'dashboard')->first();
        $dataDashboard =
            $kontenDashboard && !empty($kontenDashboard->konten) ? json_decode($kontenDashboard->konten, true) : [];
        $d = function ($key, $default) use ($dataDashboard) {
            return $dataDashboard[$key] ?? $default;
        };

        $bentuk_titles = $dataDashboard['bentuk_item_titles'] ?? [
            $d('ks_title', 'Kekerasan Seksual'),
            $d('kf_title', 'Kekerasan Fisik'),
            $d('kp_title', 'Kekerasan Psikologis'),
        ];
        $bentuk_descs = $dataDashboard['bentuk_item_descs'] ?? [
            $d('ks_desc', 'Termasuk pelecehan verbal, fisik, hingga pemaksaan melalui media digital atau intimidasi.'),
            $d('kf_desc', 'Tindakan kontak fisik yang menyakiti atau membahayakan nyawa orang lain secara sengaja.'),
            $d(
                'kp_desc',
                'Ejekan, pengucilan, atau ancaman yang merusak kesehatan mental dan rasa percaya diri seseorang.',
            ),
        ];
        // ----------------------------------------------------------------------------

        // Ekstrak URL Gambar Carousel
        $carouselUrls = isset($carousels)
            ? array_column($carousels, 'url')
            : ['https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1920&auto=format&fit=crop'];

        // Logika Pengambilan Gambar Promo (Akan Datang)
        $promoPath = public_path('assets/image/promo');
        $promoImage = null;
        if (\File::exists($promoPath)) {
            $files = \File::files($promoPath);
            if (count($files) > 0) {
                $promoImage = asset('assets/image/promo/' . $files[0]->getFilename());
            }
        }

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

    <nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 10) ? true : false"
        :class="{ 'glass-nav shadow-sm': scrolled, 'bg-transparent': !scrolled }"
        class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20">
                <div class="flex-shrink-0 flex items-center">
                    <a href="#" class="flex items-center gap-2">
                        <img src="{{ asset('assets/image/logo.PNG') }}" alt="Logo"
                            class="w-9 h-9 md:w-10 md:h-10 object-contain bg-white rounded-full p-0.5 shadow-sm border border-gray-100">
                        <span class="font-extrabold text-lg tracking-tight transition-colors"
                            :class="scrolled ? 'text-gray-900' : 'text-white'">SATGAS PPKPT USN KOLAKA</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="#beranda" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Beranda</a>
                    <a href="#peraturan" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Peraturan</a>

                    <div x-data="{ dropdownOpen: false }" class="relative" @mouseenter="dropdownOpen = true"
                        @mouseleave="dropdownOpen = false">
                        <button class="text-xs font-bold transition-colors flex items-center gap-1"
                            :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">
                            Informasi <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="dropdownOpen" x-transition
                            class="absolute top-full left-0 w-36 bg-white rounded-lg shadow-lg py-2 border border-gray-100 overflow-hidden text-gray-800">
                            <a href="#pencegahan"
                                class="block px-4 py-2 text-[11px] font-bold hover:bg-red-50 hover:text-[#800000]">Pencegahan</a>
                            <a href="#penanganan"
                                class="block px-4 py-2 text-[11px] font-bold hover:bg-red-50 hover:text-[#800000]">Penanganan</a>
                        </div>
                    </div>

                    <a href="#tentang" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Tentang
                        Kami</a>
                    <a href="#galeri" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Galeri</a>
                    <a href="#agenda" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Berita
                        & Agenda</a>
                    <a href="#kontak" class="text-xs font-bold transition-colors"
                        :class="scrolled ? 'text-gray-600 hover:text-[#800000]' : 'text-gray-100 hover:text-white'">Kontak</a>

                    <a href="{{ route('login') }}"
                        class="bg-[#800000] text-white px-5 py-2 rounded-full font-bold text-xs hover:bg-red-900 transition shadow-sm">Login</a>
                </div>

                <div class="flex items-center md:hidden">
                    <button @click="open = !open" class="focus:outline-none"
                        :class="scrolled ? 'text-gray-800' : 'text-white'">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" x-collapse
            class="md:hidden bg-white shadow-xl absolute w-full border-t border-gray-100 text-gray-800">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="#beranda" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Beranda</a>
                <a href="#peraturan" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Peraturan</a>
                <a href="#pencegahan" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Pencegahan</a>
                <a href="#penanganan" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Penanganan</a>
                <a href="#tentang" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Tentang Kami</a>
                <a href="#galeri" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Galeri</a>
                <a href="#agenda" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Berita & Agenda</a>
                <a href="#kontak" @click="open = false"
                    class="block px-3 py-2 text-xs font-bold hover:text-[#800000]">Kontak</a>
            </div>
        </div>
    </nav>

    <section id="beranda"
        class="relative pt-24 pb-12 lg:pt-32 lg:pb-16 overflow-hidden min-h-[85vh] flex items-center bg-gray-900">
        <div class="absolute inset-0 z-0">
            <template x-for="(slide, index) in slides" :key="index">
                <img x-show="activeSlide === index" x-transition:enter="transition opacity-1000 duration-1000"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition opacity-1000 duration-1000" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" :src="slide"
                    class="absolute inset-0 w-full h-full object-cover">
            </template>
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" data-aos-duration="1000" class="text-center lg:text-left">
                    <span
                        class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-red-50/20 backdrop-blur text-white text-[10px] font-bold tracking-widest mb-4 border border-white/30 uppercase shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full"></span>
                        Segera Laporkan Tindak Kekerasan
                    </span>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4 drop-shadow-lg">
                        {!! nl2br(e($t('hero_title', "Kampus Aman,\nBebas Kekerasan."))) !!}
                    </h1>
                    <p class="text-base text-gray-200 mb-8 max-w-md mx-auto lg:mx-0 leading-relaxed drop-shadow-md">
                        {{ $t('hero_desc', 'Satuan Tugas Pencegahan dan Penanganan Kekerasan Seksual (Satgas PPKS) Universitas Sembilanbelas November Kolaka hadir sebagai garda terdepan pelindung sivitas akademika.') }}
                    </p>

                    <a href="{{ route('login') }}"
                        class="inline-block bg-[#800000] text-white px-8 py-3 rounded-xl font-bold text-sm hover:bg-red-900 shadow-2xl transition transform hover:-translate-y-1 border border-red-900">
                        Laporkan ! </a>
                </div>

                <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200"
                    class="flex justify-center lg:justify-end">
                    <div
                        class="bg-white/95 backdrop-blur-md p-6 rounded-3xl shadow-2xl border border-gray-100 max-w-sm w-full">
                        <div class="flex items-center gap-3 mb-5 border-b border-gray-200 pb-4">
                            <div
                                class="w-12 h-12 bg-red-50 text-[#800000] rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 leading-tight">Lacak Status Pengaduan</h3>
                                <p class="text-gray-500 text-[10px] mt-1">Masukkan Nomor Tiket</p>
                            </div>
                        </div>

                        <form action="{{ route('cek-status.cari') }}" method="POST"
                            @submit.prevent="submitCekStatus($event)" class="space-y-4">
                            @csrf
                            <div>
                                <input type="text" name="kode_tiket" placeholder="Nomor Tiket Anda"
                                    class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-[#800000]/50 focus:border-[#800000] block p-3.5 shadow-inner font-mono font-bold text-center uppercase"
                                    required>
                            </div>
                            <button type="submit" :disabled="isChecking"
                                class="w-full bg-[#800000] text-white hover:bg-red-900 rounded-xl px-4 py-3.5 text-center font-bold text-sm transition shadow-lg flex justify-center items-center gap-2 disabled:opacity-50">
                                <span x-show="!isChecking">Cari Status Laporan</span>
                                <span x-show="isChecking">Mencari Data...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="peraturan" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Dasar
                    Hukum</span>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Peraturan yang Berlaku</h2>
                <div class="w-12 h-1.5 bg-[#800000] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach ($peraturan_items as $item)
                    <button
                        @click="showPdfModal = true; pdfTitle = '{{ addslashes($item['judul']) }}'; pdfUrl = '{{ asset($item['file_url']) }}'"
                        data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}"
                        class="w-full text-left flex flex-col sm:flex-row bg-white border border-gray-200 rounded-[2rem] p-6 shadow-sm hover:shadow-lg hover:border-[#800000]/30 transition-all gap-5 items-start focus:outline-none">

                        <div
                            class="w-14 h-14 shrink-0 {{ $loop->iteration % 2 == 0 ? 'bg-[#800000]' : 'bg-gray-900' }} text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg {{ $loop->iteration % 2 == 0 ? 'shadow-red-900/20' : '' }}">
                            {{ $item['nomor'] }}
                        </div>

                        <div>
                            <div
                                class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                {{ $item['tahun'] }}
                                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                            </div>
                            <h3
                                class="text-base font-black text-gray-900 mb-2 leading-snug group-hover:text-[#800000]">
                                {{ $item['judul'] }}</h3>
                            <p class="text-gray-600 text-sm font-medium leading-relaxed">{{ $item['deskripsi'] }}</p>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section id="bentuk-kekerasan" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Waspada</span>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ $d('bentuk_title', 'Kenali Bentuk Kekerasan') }}</h2>
                <div class="w-12 h-1.5 bg-[#800000] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($bentuk_titles as $index => $title)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}"
                        class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all">
                        <div
                            class="w-12 h-12 bg-red-100 text-[#800000] rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800">{{ $title }}</h4>
                        <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $bentuk_descs[$index] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="pencegahan" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Sistem
                    Proteksi</span>
                <h2 class="text-3xl font-extrabold text-gray-900">{{ $p('langkah_title', 'Langkah Pencegahan') }}</h2>
                <div class="w-12 h-1.5 bg-[#800000] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @php
                    $l_titles = [];
                    $l_descs = [];

                    foreach ($dPencegahan as $key => $val) {
                        if (strpos($key, '_title') !== false && strpos($key, 'l_') !== false) {
                            $num = explode('_', $key)[1];
                            $l_titles[$num] = $val;
                            $l_descs[$num] = $dPencegahan['l_' . $num . '_desc'] ?? '';
                        }
                    }

                    if (empty($l_titles)) {
                        $l_titles = [1 => 'Sosialisasi', 2 => 'Pakta Integritas', 3 => 'Kampanye'];
                        $l_descs = [
                            1 => 'Penyisipan materi PPKS.',
                            2 => 'Penandatanganan pakta.',
                            3 => 'Poster edukasi.',
                        ];
                    }
                @endphp

                @foreach ($l_titles as $num => $title)
                    <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}"
                        class="bg-gray-50 p-6 rounded-2xl border border-gray-100 text-center hover:-translate-y-1 hover:shadow-md transition">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">{{ $title }}</h3>
                        <p class="text-gray-500 text-[11px] leading-relaxed">{{ $l_descs[$num] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="penanganan" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-aos="fade-up">
                <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Prosedur
                    Resmi</span>
                <h2 class="text-2xl font-extrabold text-gray-900">
                    {{ $n('alur_title_main', 'Alur Penanganan Laporan') }}</h2>
                <div class="w-12 h-1.5 bg-[#800000] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @php
                    $alur_titles = $dPenanganan['alur_titles'] ?? [
                        'Penerimaan',
                        'Pemeriksaan',
                        'Kesimpulan',
                        'Pemulihan',
                    ];
                    $alur_descs = $dPenanganan['alur_descs'] ?? ['', '', '', ''];
                @endphp

                @foreach ($alur_titles as $index => $title)
                    <div data-aos="fade-up" data-aos-delay="{{ $index * 50 }}"
                        class="bg-white p-6 rounded-2xl border border-gray-200 relative hover:border-[#800000] hover:shadow-md transition overflow-hidden">
                        <div class="text-[#800000] font-black text-4xl absolute top-3 right-4 opacity-5">
                            0{{ $index + 1 }}</div>
                        <h3 class="text-sm font-bold mb-2 relative z-10 text-gray-900 mt-4">{{ $title }}</h3>
                        <p class="text-gray-500 text-[11px] leading-relaxed relative z-10">
                            {{ $alur_descs[$index] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="tentang" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Profil
                    Satgas</span>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tentang Kami</h2>
                <div class="w-12 h-1.5 bg-[#800000] mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
                <div data-aos="fade-right">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-[#800000] pl-3">
                        {{ $t('latar_title', 'Latar Belakang') }}</h3>
                    <div class="text-gray-600 text-[11px] md:text-xs leading-relaxed space-y-3">
                        {!! nl2br(
                            e(
                                $t(
                                    'latar_desc',
                                    'Satgas PPKS USN Kolaka dibentuk sebagai wujud komitmen merespons Permendikbudristek No 30 Tahun 2021.',
                                ),
                            ),
                        ) !!}
                    </div>
                </div>

                <div data-aos="fade-left" class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="text-sm font-bold text-[#800000] mb-2">Visi Kami</h3>
                    <p class="text-gray-700 text-[11px] md:text-xs leading-relaxed italic mb-4">
                        "{!! nl2br(e($t('visi_desc', 'Mewujudkan kampus aman dan inklusif.'))) !!}"</p>

                    <h3 class="text-sm font-bold text-[#800000] mb-3">Misi Utama</h3>
                    <ul class="space-y-2">
                        @php $misi_items = $dTentang['misi_items'] ?? ['Edukasi & Sosialisasi Berkala', 'Layanan Pengaduan Aman']; @endphp
                        @foreach ($misi_items as $index => $misi)
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-5 h-5 bg-red-100 text-[#800000] rounded-full flex items-center justify-center font-bold text-[9px] shrink-0">
                                    {{ $index + 1 }}</div>
                                <p class="text-gray-600 text-[11px] leading-relaxed">{{ $misi }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="galeri" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-[100%] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4" data-aos="fade-up">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Galeri & Dokumentasi</h2>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Dokumentasi dan sosialisasi program pencegahan
                        Satgas PPKPT USN Kolaka.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse ($galeris as $item)
                    <div x-data="{ showModal: false }" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 50 }}"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full text-left">
                        <div @click="showModal = true"
                            class="relative aspect-[4/3] bg-gray-100 overflow-hidden shrink-0 cursor-pointer">
                            <img src="{{ asset($item->dokumentasi) }}"
                                alt="{{ $item->judul_kegiatan ?? $item->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity">
                            </div>

                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-3 py-1.5 text-[9px] font-black uppercase tracking-widest rounded-lg text-white shadow-md backdrop-blur-md {{ $item->status_publikasi == 'poster' ? 'bg-[#800000]/90' : 'bg-blue-600/90' }}">
                                    {{ $item->status_publikasi == 'poster' ? 'Poster Edukasi' : 'Sosialisasi' }}
                                </span>
                            </div>

                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div
                                    class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1">
                            <div
                                class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-tight">
                                <svg class="w-3.5 h-3.5 text-[#800000]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 leading-snug mb-2 line-clamp-2 h-[2.5rem]">
                                {{ $item->judul_kegiatan ?? $item->judul }}
                            </h3>
                            <p class="text-[11px] text-gray-500 font-medium line-clamp-2 mb-4">{{ $item->deskripsi }}
                            </p>

                            <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1 text-[10px] font-bold text-gray-400 truncate">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                    </svg>
                                    <span class="truncate">{{ $item->lokasi }}</span>
                                </div>
                                <button @click="showModal = true"
                                    class="shrink-0 px-3 py-1.5 bg-gray-50 hover:bg-[#800000] text-gray-600 hover:text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-colors border border-gray-200 focus:outline-none">
                                    Lihat
                                </button>
                            </div>
                        </div>

                        <template x-teleport="body">
                            <div x-show="showModal" style="display: none;"
                                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4"
                                x-transition.opacity>
                                <div @click.away="showModal = false"
                                    class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col md:flex-row shadow-2xl text-left"
                                    x-transition.scale>
                                    <div
                                        class="w-full md:w-1/2 bg-gray-100 flex items-center justify-center relative min-h-[300px] md:min-h-full">
                                        <img src="{{ asset($item->dokumentasi) }}"
                                            class="w-full h-full object-contain max-h-[40vh] md:max-h-[85vh]">
                                    </div>
                                    <div
                                        class="w-full md:w-1/2 p-6 md:p-8 flex flex-col overflow-y-auto custom-scroll bg-white relative">
                                        <button @click="showModal = false"
                                            class="absolute top-4 right-4 p-2 bg-gray-100 text-gray-500 hover:text-red-500 rounded-xl transition focus:outline-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>

                                        <div class="mt-6 md:mt-2 mb-6">
                                            <h2 class="text-xl md:text-2xl font-black text-gray-800 leading-tight">
                                                {{ $item->judul_kegiatan ?? $item->judul }}</h2>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-6">
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                <p
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                                                    Tanggal</p>
                                                <p class="text-xs md:text-sm font-bold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                                </p>
                                            </div>
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                                <p
                                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">
                                                    Lokasi</p>
                                                <p class="text-xs md:text-sm font-bold text-gray-800">
                                                    {{ $item->lokasi }}</p>
                                            </div>
                                        </div>

                                        <div class="mb-6 flex-1">
                                            <p
                                                class="text-[11px] font-bold text-[#800000] uppercase tracking-widest mb-2">
                                                Deskripsi Kegiatan</p>
                                            <div class="text-gray-600 text-xs md:text-sm leading-relaxed space-y-2">
                                                {!! nl2br(e($item->deskripsi)) !!}</div>
                                        </div>

                                        <div class="pt-4 border-t border-gray-100">
                                            <button @click="showModal = false"
                                                class="w-full py-3 bg-gray-100 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-200 transition">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                @empty
                    <div
                        class="col-span-full py-10 flex flex-col items-center justify-center text-center bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-400 text-sm font-medium">Belum ada dokumentasi terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="agenda" class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center md:text-left flex flex-col md:flex-row justify-between items-end gap-4"
                data-aos="fade-up">
                <div>
                    <span class="text-[#800000] font-bold tracking-widest uppercase text-[10px] mb-1 block">Update
                        Terkini</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Artikel Berita</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($agendas as $agenda)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}"
                        class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col overflow-hidden group">
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if ($agenda->thumbnail)
                                <img src="{{ asset($agenda->thumbnail) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">Tanpa Gambar
                                </div>
                            @endif
                            <div
                                class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm">
                                <p class="text-[10px] font-black text-[#800000] uppercase">
                                    {{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <h3
                                class="text-lg font-black text-gray-900 leading-tight mb-3 group-hover:text-[#800000] transition-colors line-clamp-2 h-[3.5rem]">
                                {{ $agenda->judul }}
                            </h3>
                            <p class="text-sm text-gray-500 mb-6 line-clamp-3">
                                {{ Str::limit(strip_tags($agenda->konten), 120) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('agenda.show', $agenda->slug) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-[#800000] hover:text-red-900 group/link">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 transform group-hover/link:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-10 text-center bg-white rounded-3xl border border-gray-200 border-dashed">
                        <p class="text-gray-500 text-sm font-medium">Belum ada agenda atau berita kegiatan yang
                            diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer id="kontak" class="bg-[#800000] text-white pt-16 pb-8 border-t-4 border-red-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('assets/image/logo.PNG') }}" alt="Logo"
                            class="w-10 h-10 md:w-12 md:h-12 object-contain bg-white rounded-full p-1 shadow">
                        <span class="font-extrabold text-xl tracking-tight">Satgas PPKS</span>
                    </div>
                    <p class="text-red-100 text-xs leading-relaxed">Garda terdepan pelindung sivitas akademika USN
                        Kolaka. Kami siap mendengar dan melindungi Anda.</p>
                </div>
                <div>
                    <h3 class="text-sm font-bold mb-4 border-b border-red-400 pb-2 inline-block">Kontak Cepat</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2 text-red-100 text-xs">
                            <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <div><span
                                    class="block text-white font-bold">{{ $k('wa_title', 'Hotline PPKS') }}</span>{{ $k('wa_nomor', '0812-XXXX-XXXX') }}
                            </div>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold mb-4 border-b border-red-400 pb-2 inline-block">Lokasi Kami</h3>
                    <div class="flex items-start gap-2 text-red-100 text-xs">
                        <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                        </svg>
                        <div><span
                                class="block text-white font-bold">{{ $k('alamat_singkat', 'Gedung Rektorat Lt. 1') }}</span>{!! nl2br(e($k('alamat_desc', 'USN Kolaka'))) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="border-t border-red-900 pt-6 text-center text-red-300 text-xs flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} Satuan Tugas Pencegahan dan Penanganan Kekerasan di Lingkungan Perguruan
                    Tinggi
                    Universitas Sembilanbelas November Kolaka.</p>
            </div>
        </div>
    </footer>

    <template x-teleport="body">
        <div x-show="showPdfModal" style="display: none;"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showPdfModal = false"></div>
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col relative z-10 transform transition-all"
                x-transition.scale>
                <div
                    class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
                    <h3 class="font-bold text-gray-800 text-sm md:text-base" x-text="pdfTitle"></h3>
                    <button @click="showPdfModal = false"
                        class="text-gray-400 hover:text-red-500 bg-white p-2 rounded-lg border border-gray-200 focus:outline-none"><svg
                            class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg></button>
                </div>
                <div class="flex-1 bg-gray-200 rounded-b-2xl overflow-hidden relative">
                    <iframe :src="pdfUrl" class="w-full h-full relative z-10 border-none"
                        title="Dokumen Peraturan"></iframe>
                </div>
            </div>
        </div>
    </template>

    @if ($promoImage)
        <template x-teleport="body">
            <div x-show="showPromoModal" style="display: none;"
                class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-900/90 backdrop-blur-sm p-4 md:p-10"
                x-transition.opacity>

                <button @click="showPromoModal = false"
                    class="absolute top-4 right-4 md:top-8 md:right-8 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 p-2 rounded-full backdrop-blur-md transition-colors focus:outline-none z-50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div @click.away="showPromoModal = false"
                    class="relative w-full max-w-md flex flex-col items-center transform transition-all"
                    x-transition.scale>
                    <img src="{{ $promoImage }}" alt="Promo"
                        class="w-full h-auto max-h-[85vh] object-contain shadow-2xl bg-transparent">
                </div>
            </div>
        </template>
    @endif

    <template x-teleport="body">
        <div x-show="showResultModal" style="display: none;"
            class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-900/90 backdrop-blur-md p-4 sm:p-6"
            x-transition.opacity>

            <div @click.away="showResultModal = false" class="w-full max-w-3xl relative transform transition-all"
                x-transition.scale>

                <div
                    class="w-full max-h-[85vh] overflow-y-auto custom-scroll bg-white rounded-[2rem] shadow-2xl overflow-hidden relative">
                    <div x-html="resultHtml" class="w-full"></div>
                </div>

            </div>
        </div>
    </template>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 800,
            offset: 50
        });

        function landingPageData() {
            return {
                showPromoModal: false,
                showPdfModal: false,
                showResultModal: false,
                pdfUrl: '',
                pdfTitle: '',
                resultHtml: '',
                isChecking: false,
                activeSlide: 0,
                slides: {!! json_encode($carouselUrls) !!},
                init() {
                    @if ($promoImage)
                        setTimeout(() => {
                            this.showPromoModal = true;
                        }, 1000);
                    @endif

                    if (this.slides.length > 1) {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.slides.length
                        }, 4000);
                    }
                },

                async submitCekStatus(e) {
                    this.isChecking = true;
                    try {
                        let res = await fetch(e.target.action, {
                            method: 'POST',
                            body: new FormData(e.target),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        let html = await res.text();
                        let doc = new DOMParser().parseFromString(html, 'text/html');

                        let modalContent = doc.querySelector('#modal-content-laporan') || doc.querySelector(
                            '#modal-content-error');

                        if (modalContent) {
                            let finalHtml = modalContent.outerHTML;
                            finalHtml = finalHtml.replaceAll('showModal = false', 'showResultModal = false');
                            this.resultHtml = finalHtml;
                            this.showResultModal = true;
                        } else {
                            alert('Sistem gagal memuat data. Mohon pastikan format tiket Anda benar.');
                        }
                    } catch (err) {
                        alert('Terjadi kesalahan jaringan atau sistem sedang sibuk.');
                    }
                    this.isChecking = false;
                }
            }
        }
    </script>
</body>

</html>
