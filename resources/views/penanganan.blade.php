@extends('layouts.app')

@section('header_title', 'Informasi Penanganan PPKS')

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
                <a href="{{ route('informasi.penanganan.edit') }}"
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
                isset($kontenPenanganan) && !empty($kontenPenanganan->konten)
                    ? json_decode($kontenPenanganan->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            // Default Array Alur
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

            // Default Array Prinsip
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

        <div class="relative bg-gradient-to-r from-blue-900 to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-12">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>

            <div class="relative z-10 px-8 py-12 md:p-14 md:w-2/3">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/30 backdrop-blur-sm">
                    {{ $d('hero_badge', 'PROSEDUR RESMI SATGAS') }}
                </span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    {!! nl2br(e($d('hero_title', "Mendampingi Korban,\nMenegakkan Keadilan."))) !!}
                </h1>
                <p class="text-blue-100 text-sm md:text-base leading-relaxed mb-6">
                    {{ $d('hero_desc', 'Satgas PPKS bertugas memproses setiap laporan kekerasan secara objektif, rahasia, dan independen. Anda tidak sendirian, kami siap mendengar dan menindaklanjuti laporan Anda.') }}
                </p>
                <a href="{{ route('laporkan') }}"
                    class="inline-flex items-center gap-2 bg-white text-[#800000] px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-gray-100 transition shadow-md">
                    {{ $d('hero_btn', 'Buat Laporan Sekarang') }}
                </a>
            </div>
        </div>

        <div class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">{{ $d('prinsip_title_main', 'Prinsip Penanganan Kami') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($prinsip_titles as $index => $title)
                    <div
                        class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition text-center">
                        <div
                            class="w-12 h-12 mx-auto bg-red-50 text-[#800000] rounded-full flex items-center justify-center mb-4">
                            <span class="font-bold text-xl">{{ $index + 1 }}</span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">{{ $title }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($prinsip_descs[$index] ?? '')) !!}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">
                {{ $d('alur_title_main', 'Alur Penanganan Laporan') }}</h2>

            <div
                class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-300 before:to-transparent">
                @foreach ($alur_titles as $index => $title)
                    <div
                        class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-[#800000] text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <span class="font-bold text-sm">{{ $index + 1 }}</span>
                        </div>
                        <div
                            class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[#800000] text-lg mb-1">{{ $title }}</h3>
                            <p class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($alur_descs[$index] ?? '')) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
