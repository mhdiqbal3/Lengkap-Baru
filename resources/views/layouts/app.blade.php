<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satgas PPKPT USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">

    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.8);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        div.dt-container div.dt-layout-row {
            margin-bottom: 1rem;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

{{-- LOGIKA PENGAMBILAN NOTIFIKASI, TEMA, DAN LAPORAN BARU --}}
@php
    $isAdmin = Auth::check() && Auth::user()?->role === 'admin';
    $isSatgas = Auth::check() && Auth::user()?->role === 'satgas';

    // Tema diseragamkan menjadi Marun untuk semua role
    $themeSidebar = 'bg-[#800000]';
    $themeAvatar = 'bg-[#800000]';
    $themeModalBtn = 'bg-[#800000] hover:bg-red-900';
    $themeTextActive = 'text-[#800000]';

    $unreadNotifs = collect();
    $notifCount = 0;
    $laporanBaruCount = 0; // Variabel untuk menampung jumlah laporan baru

    if (Auth::check()) {
        if (class_exists('\App\Models\Notification')) {
            try {
                $unreadNotifs = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
                $notifCount = $unreadNotifs->count();
            } catch (\Exception $e) {
            }
        }

        // Mengecek jumlah laporan dengan status 'Menunggu Verifikasi' khusus untuk Admin
        if ($isAdmin && class_exists('\App\Models\Laporan')) {
            try {
                $laporanBaruCount = \App\Models\Laporan::where('status', 'Menunggu Verifikasi')->count();
            } catch (\Exception $e) {
            }
        }
    }
@endphp

<body class="bg-gray-50 flex h-screen overflow-hidden font-sans" x-data="{
    sidebarOpen: true,
    showPengaduan: false,
    isAnonim: false,
    showSuccess: false,
    profileOpen: false,
    showNotif: false,
    showModalNotif: false,
    activeNotifId: '',
    activeNotifTitle: '',
    activeNotifMessage: ''
}">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'ml-0' : '-ml-64'"
        class="w-64 {{ $themeSidebar }} text-white flex flex-col h-full shadow-xl z-20 shrink-0 transition-all duration-300 ease-in-out relative">
        <div class="flex items-center justify-center h-20 border-b border-white/20 shrink-0 px-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white p-1 rounded-lg shadow flex-shrink-0 flex items-center justify-center">
                    <img src="{{ asset('assets/image/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col justify-center whitespace-nowrap overflow-hidden">
                    <h1 class="text-[13px] font-bold uppercase tracking-wider leading-tight text-white">Satgas PPKPT
                    </h1>
                    <span class="text-[11px] font-medium text-gray-200 tracking-wide uppercase">USN Kolaka</span>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 overflow-y-auto custom-scroll sidebar-scroll w-64">
            <div>
                <a href="{{ url('/index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('/') || request()->is('dashboard') || request()->is('index') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-sm whitespace-nowrap">Dashboard</span>
                </a>
            </div>

            <hr class="border-white/20 my-5">

            <div>
                <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-wider mb-2">Menu Utama</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ url('/laporkan') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('laporkan') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                </path>
                            </svg>
                            <span class="whitespace-nowrap">Laporkan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/cek-status') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('cek-status') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            <span class="whitespace-nowrap">Cek Status Laporan</span>
                        </a>
                    </li>

                    {{-- DROPDOWN INFORMASI --}}
                    <li x-data="{ open: {{ request()->is('informasi/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-2.5 text-sm rounded-xl hover:bg-white/10 transition text-gray-100 hover:text-white font-medium">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 opacity-80 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="whitespace-nowrap">Informasi</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }"
                                class="w-3.5 h-3.5 transition-transform opacity-70 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse class="pl-11 pr-4 py-2 space-y-2 bg-black/10 rounded-xl mt-1">
                            <li><a href="{{ route('informasi.pencegahan') }}"
                                    class="flex items-center gap-3 py-1.5 text-sm transition {{ request()->is('informasi/pencegahan') ? 'text-white font-semibold' : 'text-gray-300 hover:text-white' }}"><span
                                        class="whitespace-nowrap">Pencegahan</span></a></li>
                            <li><a href="{{ route('informasi.penanganan') }}"
                                    class="flex items-center gap-3 py-1.5 text-sm transition {{ request()->is('informasi/penanganan') ? 'text-white font-semibold' : 'text-gray-300 hover:text-white' }}"><span
                                        class="whitespace-nowrap">Penanganan</span></a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ url('/galeri') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('galeri') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="whitespace-nowrap">Galeri</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/riwayat') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('riwayat') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="whitespace-nowrap">Riwayat Saya</span>
                        </a>
                    </li>

                    @if ($isAdmin)
                        {{-- MENU DATA LAPORAN & ARSIP HANYA ADMIN --}}
                        <li>
                            <a href="{{ url('/laporan') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('laporan') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="whitespace-nowrap flex-1">Data Laporan</span>

                                {{-- Logika Animasi Titik --}}
                                @if ($laporanBaruCount > 0)
                                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                                        <span
                                            class="animate-ping absolute inline-flex h-full w-full rounded-full {{ request()->is('laporan') ? 'bg-red-400' : 'bg-white' }} opacity-75"></span>
                                        <span
                                            class="relative inline-flex rounded-full h-2.5 w-2.5 {{ request()->is('laporan') ? 'bg-red-600' : 'bg-white' }}"></span>
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/arsip') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('arsip') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                    </path>
                                </svg>
                                <span class="whitespace-nowrap">Arsip Kegiatan</span>
                            </a>
                        </li>
                    @endif

                    @if ($isAdmin || $isSatgas)
                        {{-- BERITA AGENDA BISA DIAKSES ADMIN & SATGAS --}}
                        <li>
                            <a href="{{ route('agenda.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('agenda.*') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                                <svg class="w-5 h-5 opacity-80 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14">
                                    </path>
                                </svg>
                                <span class="whitespace-nowrap text-sm">Berita Agenda</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <hr class="border-white/20 my-5">

            <div>
                <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-wider mb-2">Informasi Publik</p>
                <ul class="space-y-1">
                    <li><a href="{{ url('/kontak') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('kontak') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}"><span
                                class="whitespace-nowrap">Kontak</span></a></li>
                    <li><a href="{{ url('/tentang') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('tentang') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}"><span
                                class="whitespace-nowrap">Tentang Kami</span></a></li>
                </ul>
            </div>

            @if (Auth::check() && Auth::user()->role !== 'satgas') {{-- Sembunyikan dari Satgas --}}
                <hr class="border-white/20 my-5">
                <div>
                    <p class="px-4 text-[10px] font-bold text-gray-300 uppercase tracking-wider mb-2">Pengaturan</p>
                    <ul class="space-y-1">
                        @if ($isAdmin)
                            {{-- MENU PETUGAS SATGAS HANYA UNTUK ADMIN --}}
                            <li>
                                <a href="{{ route('petugas.index') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->routeIs('petugas.*') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                                    <svg class="w-4 h-4 opacity-80 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span class="whitespace-nowrap">Manajemen Petugas</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ url('/pengaturan') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-all duration-200 {{ request()->is('pengaturan') ? 'bg-white ' . $themeTextActive . ' shadow-sm font-bold' : 'text-gray-100 hover:bg-white/10 hover:text-white font-medium' }}">
                                <svg class="w-4 h-4 opacity-80 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                </svg>
                                <span
                                    class="whitespace-nowrap">{{ in_array(Auth::user()?->role, ['admin', 'satgas']) ? 'Pengaturan Umum' : 'Pengaturan Akun' }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F4F7FE] relative">
        <header
            class="h-20 bg-white shadow-sm flex items-center justify-between px-8 z-10 shrink-0 border-b border-gray-100">
            <div class="flex items-center gap-8 w-full max-w-2xl">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 -ml-2 text-gray-500 hover:bg-gray-100 hover:text-[#800000] rounded-xl transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <div class="relative w-full max-w-md hidden md:block">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text"
                        class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full pl-10 p-2.5 outline-none transition"
                        placeholder="Cari...">
                </div>
            </div>

            <div class="flex items-center gap-4 relative">
                {{-- NOTIFIKASI --}}
                <div class="relative">
                    <button @click="showNotif = !showNotif" @click.away="showNotif = false"
                        class="relative p-2 text-gray-400 hover:text-[#800000] transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if ($notifCount > 0)
                            <span
                                class="absolute top-1.5 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                        @endif
                    </button>
                    {{-- DROPDOWN NOTIFIKASI --}}
                    <div x-show="showNotif" x-cloak x-transition style="display: none;"
                        class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50 overflow-hidden">
                        <div class="px-4 py-2 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">Pemberitahuan</span>
                        </div>
                        <div class="max-h-64 overflow-y-auto custom-scroll">
                            @forelse($unreadNotifs as $notif)
                                <button type="button"
                                    @click="activeNotifId = '{{ $notif->id }}'; activeNotifTitle = '{{ addslashes($notif->title) }}'; activeNotifMessage = '{{ addslashes($notif->message) }}'; showModalNotif = true; showNotif = false;"
                                    class="w-full text-left block px-4 py-3 hover:bg-gray-50 transition border-b border-gray-50">
                                    <p class="text-xs font-bold {{ $themeTextActive }}">{{ $notif->title }}</p>
                                    <p class="text-[11px] text-gray-600 truncate">{{ $notif->message }}</p>
                                </button>
                            @empty
                                <div class="px-4 py-6 text-center">
                                    <p class="text-xs text-gray-500">Tidak ada notifikasi baru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="h-8 w-px bg-gray-200 mx-1"></div>

                {{-- PROFILE POJOK KANAN ATAS --}}
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false"
                        class="flex items-center gap-3 focus:outline-none hover:bg-gray-50 p-1.5 rounded-lg transition">

                        {{-- Logika Foto Profil: Tampilkan Foto jika ada, Inisial jika tidak --}}
                        <div
                            class="w-10 h-10 {{ $themeAvatar }} text-white rounded-full flex items-center justify-center font-bold text-lg shadow-sm overflow-hidden border-2 border-white shrink-0">
                            @if (Auth::user()?->foto)
                                <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()?->name ?? 'U', 0, 1) }}
                            @endif
                        </div>

                        <div class="text-left hidden md:block">
                            <p class="text-sm font-semibold text-gray-700 leading-none mb-1">
                                {{ Auth::user()?->name ?? 'Guest User' }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                {{ Auth::user()?->role ?? 'User' }}</p>
                        </div>
                        <svg :class="{ 'rotate-180': profileOpen }" class="w-4 h-4 text-gray-400 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="profileOpen" x-cloak x-transition.origin.top.right style="display: none;"
                        class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">

                        {{-- Hanya tampilkan pengaturan jika role bukan satgas --}}
                        @if (auth()->user()->role !== 'satgas')
                            <a href="{{ url('/pengaturan') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-[#800000] transition">
                                Pengaturan Akun
                            </a>
                            <hr class="my-1 border-gray-50">
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition font-bold">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8 custom-scroll relative">@yield('content')</div>

        {{-- MODAL NOTIFIKASI --}}
        <div x-show="showModalNotif" x-cloak style="display: none;"
            class="fixed inset-0 z-[120] flex items-center justify-center bg-gray-900/70 backdrop-blur-sm p-4"
            x-transition>
            <div
                class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all text-center">
                <div class="{{ $themeSidebar }} p-6 text-white">
                    <h3 class="text-xl font-bold" x-text="activeNotifTitle"></h3>
                </div>
                <div class="p-8">
                    <p class="text-gray-600 mb-8 leading-relaxed font-medium" x-text="activeNotifMessage"></p>
                    <div class="flex flex-col gap-3">
                        <form :action="'{{ url('/notifikasi') }}/' + activeNotifId + '/baca'" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-3 {{ $themeModalBtn }} text-white font-bold rounded-xl transition shadow-lg">Tandai
                                Dibaca & Cek Status</button>
                        </form>
                        <button @click="showModalNotif = false" type="button"
                            class="w-full py-3 bg-gray-100 text-gray-500 font-bold rounded-xl hover:bg-gray-200 transition">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
    @stack('scripts')
</body>

</html>
