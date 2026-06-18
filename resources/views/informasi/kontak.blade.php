@extends('layouts.app')
@section('header_title', 'Kontak & Profil Satgas')
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
                <a href="{{ route('kontak.edit') }}"
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
                isset($kontenKontak) && !empty($kontenKontak->konten) ? json_decode($kontenKontak->konten, true) : [];
            if (!is_array($data)) {
                $data = [];
            }
            $d = function ($key, $default) use ($data) {
                return $data[$key] ?? $default;
            };
        @endphp

        <div class="relative bg-gradient-to-r from-[#0d2a80] to-[#800000] rounded-3xl overflow-hidden shadow-lg mb-10">
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>
            <div class="relative z-10 px-8 py-10 md:p-12 md:w-2/3">
                <span
                    class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full bg-white/20 text-white text-xs font-bold tracking-wider mb-4 border border-white/30 backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    {{ $d('hero_badge', 'HUBUNGI KAMI') }}
                </span>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4 leading-tight">
                    {!! nl2br(e($d('hero_title', "Kami Siap Mendengar\ndan Melindungi Anda."))) !!}
                </h1>
                <p class="text-blue-100 text-sm md:text-base leading-relaxed mb-6">
                    {{ $d('hero_desc', 'Jangan ragu untuk menghubungi Satgas PPKS USN Kolaka. Kami menjamin kerahasiaan identitas dan laporan Anda. Berikut adalah struktur keanggotaan dan kontak resmi kami.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">
                        {{ $d('wa_title', 'Hotline PPKS') }}</p>
                    <h3 class="text-lg font-bold text-gray-800">{{ $d('wa_nomor', '0812-XXXX-XXXX') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $d('wa_desc', 'Aktif pada jam kerja (08:00 - 16:00 WITA)') }}
                    </p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">
                        {{ $d('email_title', 'Email Pengaduan') }}</p>
                    <h3 class="text-lg font-bold text-gray-800">{{ $d('email_alamat', 'satgasppks@usn.ac.id') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">{{ $d('email_desc', 'Kami membalas dalam waktu 1x24 Jam') }}</p>
                </div>
            </div>

            <div
                class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">
                        {{ $d('alamat_title', 'Ruang Satgas') }}</p>
                    <h3 class="text-lg font-bold text-gray-800">{{ $d('alamat_singkat', 'Gedung Rektorat Lt. 1') }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $d('alamat_desc', 'Universitas Sembilanbelas November Kolaka') }}</p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 text-center border-b pb-4 mb-8">
                {{ $d('struktur_title', 'Struktur Organisasi Satgas PPKPT USN Kolaka') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div
                    class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-md text-white flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10"><svg class="w-32 h-32" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg></div>
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center font-bold text-xl shrink-0 z-10">
                        {{ substr($d('org_pengarah_nama', 'Dr. Nur Ihsan HL'), 0, 1) }}</div>
                    <div class="z-10">
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Pengarah</p>
                        <h3 class="text-lg font-bold leading-tight">
                            {{ $d('org_pengarah_nama', 'Dr. Nur Ihsan HL, S.Pd.,M.Hum') }}</h3>
                        <p class="text-sm text-gray-300">({{ $d('org_pengarah_jab', 'Rektor') }})</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 rounded-2xl shadow-md text-white flex items-center gap-5 relative overflow-hidden">
                    <div class="absolute right-0 top-0 opacity-10"><svg class="w-32 h-32" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg></div>
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center font-bold text-xl shrink-0 z-10">
                        {{ substr($d('org_pj_nama', 'Qammaddin'), 0, 1) }}</div>
                    <div class="z-10">
                        <p class="text-xs text-gray-300 uppercase tracking-widest font-semibold mb-1">Penanggungjawab</p>
                        <h3 class="text-lg font-bold leading-tight">
                            {{ $d('org_pj_nama', 'Qammaddin, S.Kom., M.Kom, CITSM, ECIH') }}</h3>
                        <p class="text-sm text-gray-300 line-clamp-1">({{ $d('org_pj_jab', 'Wakil Rektor III') }})</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center gap-6 mb-10">
                <div class="w-full md:w-1/3 bg-white border-t-4 border-[#800000] p-6 rounded-xl shadow-sm text-center">
                    <div
                        class="w-20 h-20 bg-red-50 text-[#800000] mx-auto rounded-full flex items-center justify-center font-bold text-2xl mb-4 border border-red-100">
                        {{ substr($d('org_ketua_nama', 'Muhamad'), 0, 1) }}</div>
                    <p class="text-xs text-[#800000] uppercase tracking-widest font-bold mb-1">Ketua Satgas</p>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-3">
                        {{ $d('org_ketua_nama', 'Muhamad Aksan Akbar, S.H., M.H') }}</h3>
                </div>

                <div class="w-full md:w-1/3 bg-white border-t-4 border-blue-600 p-6 rounded-xl shadow-sm text-center">
                    <div
                        class="w-20 h-20 bg-blue-50 text-blue-600 mx-auto rounded-full flex items-center justify-center font-bold text-2xl mb-4 border border-blue-100">
                        {{ substr($d('org_sek_nama', 'Irwan'), 0, 1) }}</div>
                    <p class="text-xs text-blue-600 uppercase tracking-widest font-bold mb-1">Sekretaris Satgas</p>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight mb-3">{{ $d('org_sek_nama', 'Irwan, S.Pi') }}
                    </h3>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="bg-blue-600 p-4 text-center">
                        <h3 class="font-bold text-white uppercase tracking-wider text-sm">
                            {{ $d('div1_nama', 'Divisi Pencegahan & Edukasi') }}</h3>
                    </div>
                    <div class="p-5 flex-1 bg-blue-50/30">
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">Koordinator</span>
                            <div class="flex items-center gap-3 mt-2">
                                <div
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-gray-600 shadow-sm border border-gray-100 shrink-0">
                                    {{ substr($d('div1_koor', 'Grace'), 0, 1) }}</div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $d('div1_koor', 'Dr. Grace Tedy Tulak, S.Kep.,Ns.,M.Kep') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Anggota</span>
                            <div class="space-y-3 mt-2">
                                @php $anggota1 = explode("\n", $d('div1_anggota', "Dr. Sarmadan, S.Pd., M.Pd.\nSaleh, S. Ag., M.A.")); @endphp
                                @foreach ($anggota1 as $anggota)
                                    @if (trim($anggota) != '')
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                                                {{ substr(trim($anggota), 0, 1) }}</div>
                                            <h4 class="text-sm font-medium text-gray-800 leading-tight">
                                                {{ trim($anggota) }}</h4>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="bg-orange-500 p-4 text-center">
                        <h3 class="font-bold text-white uppercase tracking-wider text-sm">
                            {{ $d('div2_nama', 'Divisi Informasi & Komunikasi') }}</h3>
                    </div>
                    <div class="p-5 flex-1 bg-orange-50/30">
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <span class="text-[10px] font-bold text-orange-600 uppercase">Koordinator</span>
                            <div class="flex items-center gap-3 mt-2">
                                <div
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-gray-600 shadow-sm border border-gray-100 shrink-0">
                                    {{ substr($d('div2_koor', 'Hj. Nuraidah'), 0, 1) }}</div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $d('div2_koor', 'Hj. Nuraidah Tayeb, S.Pd., M.M.Pd') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Anggota</span>
                            <div class="space-y-3 mt-2">
                                @php $anggota2 = explode("\n", $d('div2_anggota', "Arman Sagita, S.Kep., Ns.\nAriel Bezalel Santoso\nAndi Lena Patma Dewi")); @endphp
                                @foreach ($anggota2 as $anggota)
                                    @if (trim($anggota) != '')
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                                                {{ substr(trim($anggota), 0, 1) }}</div>
                                            <h4 class="text-sm font-medium text-gray-800 leading-tight">
                                                {{ trim($anggota) }}</h4>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="bg-green-600 p-4 text-center">
                        <h3 class="font-bold text-white uppercase tracking-wider text-sm">
                            {{ $d('div3_nama', 'Divisi Penanganan & Pemulihan') }}</h3>
                    </div>
                    <div class="p-5 flex-1 bg-green-50/30">
                        <div class="mb-4 pb-4 border-b border-gray-200">
                            <span class="text-[10px] font-bold text-green-600 uppercase">Koordinator</span>
                            <div class="flex items-center gap-3 mt-2">
                                <div
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center font-bold text-gray-600 shadow-sm border border-gray-100 shrink-0">
                                    {{ substr($d('div3_koor', 'Ns. Heriviyatno'), 0, 1) }}</div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 leading-tight">
                                        {{ $d('div3_koor', 'Ns. Heriviyatno Siagian, S.Kep., M.N') }}</h4>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Anggota</span>
                            <div class="space-y-3 mt-2">
                                @php $anggota3 = explode("\n", $d('div3_anggota', "Anis Ribcalia Septiana, S.Sos.,M.Si\nTukatman, S.Kep.Ns.M.Kep\nMariany, S.St.,M.Keb")); @endphp
                                @foreach ($anggota3 as $anggota)
                                    @if (trim($anggota) != '')
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-500 shrink-0">
                                                {{ substr(trim($anggota), 0, 1) }}</div>
                                            <h4 class="text-sm font-medium text-gray-800 leading-tight">
                                                {{ trim($anggota) }}</h4>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
