@extends('layouts.app')

@section('header_title', 'Informasi Pencegahan PPKS')

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
                <a href="{{ route('informasi.pencegahan.edit') }}"
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

        {{-- Setup Data JSON & Dynamic Arrays --}}
        @php
            $data =
                isset($kontenPencegahan) && !empty($kontenPencegahan->konten)
                    ? json_decode($kontenPencegahan->konten, true)
                    : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };

            // Persiapkan Data Array untuk Prinsip (Bisa ditambah tak terbatas)
            $prinsip_titles = $data['prinsip_item_titles'] ?? [
                '1. Kepentingan Terbaik',
                '2. Keadilan & Kesetaraan Gender',
                '3. Akuntabilitas & Independen',
                '4. Jaminan Ketidakberulangan',
            ];
            $prinsip_descs = $data['prinsip_item_descs'] ?? [
                'Menyediakan infrastruktur dan mekanisme pengaduan yang aman di kampus.',
                'Menyediakan pemulihan untuk korban kekerasan dan keadilan pelaporan.',
                'Transparansi program dan bertindak secara profesional tanpa konflik kepentingan.',
                'Memberikan sanksi tegas kepada pelaku dan meningkatkan keamanan kampus.',
            ];

            // Persiapkan Data Array untuk Tindakan
            $tindakan_titles = $data['tindakan_item_titles'] ?? [
                '1. Pahami Konsep "Consent" (Persetujuan)',
                '2. Jadilah "Bystander" yang Aktif',
            ];
            $tindakan_descs = $data['tindakan_item_descs'] ?? [
                'Segala bentuk aktivitas tanpa persetujuan yang jelas dikategorikan sebagai pelecehan.',
                'Jika melihat perilaku pelecehan, alihkan perhatian pelaku atau laporkan ke Satgas PPKS.',
            ];
        @endphp

        <div class="relative bg-gradient-to-r from-blue-900 to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-10">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>

            <div class="relative z-10 px-8 py-12 md:p-14 md:w-2/3">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/30 backdrop-blur-sm">
                    {{ $d('hero_badge', 'LINGKUNGAN KAMPUS AMAN') }}
                </span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    {!! nl2br(e($d('hero_title', "Mencegah Lebih Baik\nDaripada Menangani."))) !!}
                </h1>
                <p class="text-blue-100 text-sm md:text-base leading-relaxed mb-6">
                    {{ $d('hero_desc', 'Satgas PPKS USN Kolaka berkomitmen penuh untuk menghapuskan segala bentuk Kekerasan Seksual, Perundungan, dan Intoleransi melalui edukasi, kampanye, dan kebijakan yang berpihak pada korban.') }}
                </p>
                <a href="#peran-sivitas"
                    class="inline-flex items-center gap-2 bg-white text-[#800000] px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-gray-100 transition shadow-md">
                    {{ $d('hero_btn', 'Lihat Peran Kita') }}
                </a>
            </div>
        </div>

        <div class="mb-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">{{ $d('langkah_title', 'Langkah Pencegahan Satgas PPKS') }}
                </h2>
                <p class="text-gray-500 text-sm mt-2">
                    {{ $d('langkah_desc', 'Upaya sistematis yang kami lakukan berdasarkan Permendikbudristek No. 30 Tahun 2021.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-5"><svg
                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg></div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $d('l_1_title', 'Edukasi & Sosialisasi') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $d('l_1_desc', 'Penyisipan materi anti kekerasan seksual pada Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB) dan seminar berkala.') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-5"><svg
                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg></div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $d('l_2_title', 'Pakta Integritas') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $d('l_2_desc', 'Mewajibkan seluruh sivitas akademika (Mahasiswa, Dosen, Tendik) menandatangani pakta integritas penolakan PPKS.') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg></div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $d('l_3_title', 'Kampanye Publik') }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $d('l_3_desc', 'Pemasangan poster edukasi, rambu kawasan aman, serta kampanye di media sosial kampus tentang batas-batas interaksi.') }}
                    </p>
                </div>
            </div>
        </div>

        <div id="peran-sivitas" class="mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center border-b pb-4">
                {{ $d('peran_title', 'Apa Peran Anda di Kampus?') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-3xl border border-blue-100 shadow-sm">
                    <h3 class="text-2xl font-bold text-blue-900 mb-4">{{ $d('p_mhs_title', 'Mahasiswa') }}</h3>
                    <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                        {!! nl2br(
                            e(
                                $d(
                                    'p_mhs_desc',
                                    "- Perbanyak diskusi positif tentang HAM.\n- Ikuti sosialisasi anti kekerasan.\n- Cari tahu unit PPKS di kampus.\n- Terapkan relasi yang sehat.",
                                ),
                            ),
                        ) !!}
                    </div>
                </div>
                <div class="bg-gradient-to-br from-emerald-50 to-white p-8 rounded-3xl border border-emerald-100 shadow-sm">
                    <h3 class="text-2xl font-bold text-emerald-900 mb-4">{{ $d('p_dsn_title', 'Dosen & Tendik') }}</h3>
                    <div class="text-sm text-gray-700 leading-relaxed space-y-2">
                        {!! nl2br(
                            e(
                                $d(
                                    'p_dsn_desc',
                                    "- Perbanyak keterlibatan mahasiswa.\n- Perbanyak sosialisasi & pelatihan.\n- Perkenalkan layanan unit PPKS.\n- Terapkan relasi sehat dan setara.",
                                ),
                            ),
                        ) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-12">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ $d('prinsip_title', 'Prinsip Pengelola Perguruan Tinggi') }}</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($prinsip_titles as $index => $title)
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <h3 class="font-bold text-[#800000] text-lg mb-2">{{ $title }}</h3>
                        <p class="text-sm text-gray-600">{!! nl2br(e($prinsip_descs[$index] ?? '')) !!}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm mb-12">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">
                {{ $d('tindakan_title', 'Tindakan Sebagai Individu (Bystander)') }}</h2>
            <div class="space-y-4">
                @foreach ($tindakan_titles as $index => $title)
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                        <h3 class="font-bold text-gray-800">{{ $title }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{!! nl2br(e($tindakan_descs[$index] ?? '')) !!}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
