@extends('layouts.app')

@section('header_title', 'Tentang Kami')

@section('content')
    <div class="max-w-6xl mx-auto">

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Tombol Edit Khusus Admin --}}
        @if (auth()->check() && auth()->user()->role === 'admin')
            <div class="mb-6 flex justify-end">
                <a href="{{ route('tentang.edit') }}"
                    class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Teks Halaman Ini
                </a>
            </div>
        @endif

        {{-- Setup Data JSON --}}
        @php
            $data =
                isset($kontenTentang) && !empty($kontenTentang->konten)
                    ? json_decode($kontenTentang->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            // Default Misi
            $misi_items = $data['misi_items'] ?? [
                'Menyelenggarakan program edukasi dan sosialisasi pencegahan kekerasan seksual secara berkala.',
                'Menyediakan layanan pengaduan yang mudah diakses, responsif, dan terjamin kerahasiaannya.',
                'Memberikan pendampingan psikologis, hukum, dan akademik bagi korban kekerasan.',
                'Menindaklanjuti laporan dengan adil dan merekomendasikan sanksi tegas bagi pelaku.',
            ];

            // Default Nilai
            $nilai_titles = $data['nilai_titles'] ?? ['Kerahasiaan', 'Empati', 'Keadilan', 'Inklusif'];
            $nilai_descs = $data['nilai_descs'] ?? [
                'Kami menjamin 100% privasi dan identitas pelapor serta korban dalam setiap penanganan kasus.',
                'Setiap tindakan selalu menggunakan perspektif korban (victim-centered) dan menghindari victim blaming.',
                'Investigasi dilakukan secara objektif, proporsional, serta bebas dari konflik kepentingan.',
                'Terbuka untuk semua golongan, setara gender, dan memastikan aksesibilitas bagi penyandang disabilitas.',
            ];

            // Warna & Icon berulang untuk nilai
            $colors = ['blue', 'pink', 'green', 'orange'];
            $svgs = [
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>',
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
            ];
        @endphp

        <div class="relative bg-gradient-to-r from-[#0d2a80] to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-12">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>

            <div class="relative z-10 px-8 py-12 md:p-14 md:w-3/4">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/30 backdrop-blur-sm">
                    {{ $d('hero_badge', 'PROFIL SATGAS PPKS') }}
                </span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    {!! nl2br(e($d('hero_title', "Mewujudkan Kampus yang\nAman, Setara, dan Inklusif."))) !!}
                </h1>
                <p class="text-blue-100 text-sm md:text-base leading-relaxed">
                    {{ $d('hero_desc', 'Satuan Tugas Pencegahan dan Penanganan Kekerasan Seksual (Satgas PPKS) Universitas Sembilanbelas November Kolaka hadir sebagai garda terdepan pelindung sivitas akademika.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16 items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-l-4 border-[#800000] pl-4">
                    {{ $d('latar_title', 'Latar Belakang') }}</h2>
                <div class="space-y-4 text-gray-600 text-sm md:text-base leading-relaxed">
                    {!! nl2br(
                        e(
                            $d(
                                'latar_desc',
                                "Pembentukan Satgas PPKS USN Kolaka merupakan wujud komitmen nyata universitas dalam merespons dan mengimplementasikan Permendikbudristek Nomor 30 Tahun 2021 tentang Pencegahan dan Penanganan Kekerasan Seksual di Lingkungan Perguruan Tinggi.\n\nKami menyadari bahwa perguruan tinggi harus menjadi ruang yang aman bagi penyemaian ilmu pengetahuan. Tidak boleh ada ruang bagi tindakan kekerasan seksual, perundungan, maupun intoleransi. Satgas ini beranggotakan unsur pendidik, tenaga kependidikan, dan mahasiswa yang telah lulus uji seleksi dan pelatihan khusus.\n\nKami hadir tidak hanya untuk menangani laporan, tetapi juga berfokus pada edukasi, kampanye pencegahan, dan pemulihan korban dengan prinsip berperspektif pada korban.",
                            ),
                        ),
                    ) !!}
                </div>
            </div>

            <div
                class="relative h-64 md:h-full min-h-[300px] bg-gray-100 rounded-2xl overflow-hidden shadow-sm border border-gray-200 group">
                <img src="{{ Str::startsWith($d('latar_img_url', ''), 'http') ? $d('latar_img_url', '') : asset($d('latar_img_url', 'assets/image/default.jpg')) }}"
                    alt="Latar Belakang" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                    <p class="text-white font-medium text-sm">
                        {{ $d('latar_img_cap', 'Kampus USN Kolaka yang aman dan nyaman.') }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            <div
                class="bg-gradient-to-br from-[#800000] to-red-900 p-8 rounded-3xl shadow-md text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                    <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
                <div class="relative z-10">
                    <span
                        class="text-red-200 font-bold tracking-widest uppercase text-xs mb-2 block">{{ $d('visi_badge', 'Pandangan Ke Depan') }}</span>
                    <h2 class="text-3xl font-extrabold mb-4">{{ $d('visi_title', 'Visi Kami') }}</h2>
                    <p class="text-red-50 text-lg leading-relaxed font-medium">
                        "{!! nl2br(
                            e(
                                $d(
                                    'visi_desc',
                                    'Mewujudkan lingkungan Universitas Sembilanbelas November Kolaka yang aman, setara, inklusif, dan terbebas dari segala bentuk kekerasan seksual.',
                                ),
                            ),
                        ) !!}"
                    </p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="relative z-10">
                    <span
                        class="text-[#800000] font-bold tracking-widest uppercase text-xs mb-2 block">{{ $d('misi_badge', 'Langkah Nyata') }}</span>
                    <h2 class="text-3xl font-extrabold text-gray-800 mb-6">{{ $d('misi_title', 'Misi Utama') }}</h2>
                    <ul class="space-y-4 text-gray-600 text-sm">
                        @foreach ($misi_items as $index => $misi)
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-6 h-6 bg-red-50 text-[#800000] rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="font-bold text-xs">{{ $index + 1 }}</span>
                                </div>
                                <p>{!! nl2br(e($misi)) !!}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">
                {{ $d('nilai_title_main', 'Nilai-Nilai Dasar Kami') }}</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($nilai_titles as $index => $title)
                    @php
                        // Perulangan warna dan icon agar otomatis cantik
                        $color = $colors[$index % 4];
                        $svg = $svgs[$index % 4];
                    @endphp
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center hover:-translate-y-1 transition duration-300">
                        <div
                            class="w-14 h-14 bg-{{ $color }}-50 text-{{ $color }}-600 mx-auto rounded-full flex items-center justify-center mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">{!! $svg !!}</svg>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $title }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">{!! nl2br(e($nilai_descs[$index] ?? '')) !!}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
