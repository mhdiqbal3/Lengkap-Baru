@extends('layouts.app')

@section('header_title', 'Data Laporan Pengaduan')

@section('content')

    <style>
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    {{-- LOGIKA FILTER KOLEKSI DATA (Diambil dari Dashboard) --}}
    @php
        use Carbon\Carbon;
        use App\Models\Laporan;
        use Illuminate\Support\Facades\Auth;

        $allLaporans = Laporan::all();
        $now = Carbon::now();
        $periods = ['semua', 'harian', 'mingguan', 'bulanan', 'tahunan'];
        $stats = [];

        foreach ($periods as $key) {
            $filtered = $allLaporans;

            if ($key === 'harian') {
                // FIX: gunakan created_at (tanggal laporan masuk) bukan tanggal_kejadian
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->created_at ? Carbon::parse($item->created_at) : null;
                    return $tanggalLapor && $tanggalLapor->isSameDay($now);
                });
            } elseif ($key === 'mingguan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->created_at ? Carbon::parse($item->created_at) : null;
                    return $tanggalLapor && $tanggalLapor->isSameWeek($now);
                });
            } elseif ($key === 'bulanan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->created_at ? Carbon::parse($item->created_at) : null;
                    return $tanggalLapor && $tanggalLapor->isSameMonth($now);
                });
            } elseif ($key === 'tahunan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->created_at ? Carbon::parse($item->created_at) : null;
                    return $tanggalLapor && $tanggalLapor->isSameYear($now);
                });
            }

            $stats[$key] = [
                'total' => $filtered->count(),
                'menunggu' => $filtered->where('status', 'Menunggu Verifikasi')->count(),
                'diproses' => $filtered->where('status', 'Sedang Diproses')->count(),
                'selesai' => $filtered->where('status', 'Selesai')->count(),
                'ditolak' => $filtered->where('status', 'Ditolak')->count(),
            ];
        }

        $isAdmin = Auth::check() && Auth::user()?->role === 'admin';
        $themeBg = $isAdmin ? 'bg-[#800000]' : 'bg-blue-900';
    @endphp

    <div class="max-w-[100%] mx-auto pb-10">

        {{-- Statistik Laporan dengan Filter (Persis seperti Dashboard) --}}
        <div x-data="{ showRingkasan: true }" class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-5 mb-8 transition-all duration-300">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-50 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $isAdmin ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 cursor-pointer" @click="showRingkasan = !showRingkasan">
                        <h3 class="text-lg font-bold text-gray-800 hover:text-[#800000] transition-colors">Ringkasan Laporan</h3>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider mt-0.5">Filter data berdasarkan waktu</p>
                    </div>
                </div>

                <div class="flex flex-row items-center gap-2 xl:gap-4 w-full xl:w-auto">
                    {{-- Panel Filter Tombol --}}
                    <div x-show="showRingkasan" x-transition.opacity class="flex overflow-x-auto w-full xl:w-auto bg-gray-50 p-1.5 rounded-xl border border-gray-200 hide-scroll">
                        <button type="button" onclick="applyFilter('semua')" data-period="semua" class="filter-btn active shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $themeBg }} text-white shadow-md">Semua</button>
                        <button type="button" onclick="applyFilter('harian')" data-period="harian" class="filter-btn shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200 relative flex items-center justify-center gap-1.5">
                            Harian
                            @if ($stats['harian']['menunggu'] > 0)
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                </span>
                            @endif
                        </button>
                        <button type="button" onclick="applyFilter('mingguan')" data-period="mingguan" class="filter-btn shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Mingguan</button>
                        <button type="button" onclick="applyFilter('bulanan')" data-period="bulanan" class="filter-btn shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Bulanan</button>
                    </div>

                    {{-- Toggle Button --}}
                    <button @click="showRingkasan = !showRingkasan" class="shrink-0 p-2.5 bg-gray-50 border border-gray-200 hover:bg-gray-100 text-gray-600 hover:text-gray-800 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-[#800000]/20">
                        <svg x-show="showRingkasan" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                        <svg x-show="!showRingkasan" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>
            </div>

            <div x-show="showRingkasan" x-transition.opacity class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Kartu Total Laporan --}}
                <div class="bg-gradient-to-br from-red-800 to-[#800000] rounded-2xl p-5 text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 col-span-2 lg:col-span-1 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 -bottom-6 w-28 h-28 bg-white/5 rounded-full"></div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 id="count-total" class="text-4xl font-black">{{ $stats['semua']['total'] }}</h3>
                    <p class="text-xs font-bold text-white/80 mt-1 uppercase tracking-wider">Total Laporan</p>
                </div>
                {{-- Kartu Menunggu --}}
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 border border-transparent shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden text-white">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 -bottom-6 w-28 h-28 bg-white/5 rounded-full"></div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 id="count-menunggu" class="text-4xl font-black">{{ $stats['semua']['menunggu'] }}</h3>
                    <p class="text-xs font-bold text-white/80 mt-1 uppercase tracking-wider">Menunggu</p>
                </div>
                {{-- Kartu Diproses --}}
                <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-5 border border-transparent shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden text-white">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 -bottom-6 w-28 h-28 bg-white/5 rounded-full"></div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 id="count-diproses" class="text-4xl font-black">{{ $stats['semua']['diproses'] }}</h3>
                    <p class="text-xs font-bold text-white/80 mt-1 uppercase tracking-wider">Diproses</p>
                </div>
                {{-- Kartu Selesai --}}
                <div class="bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl p-5 border border-transparent shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden text-white">
                    <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -right-2 -bottom-6 w-28 h-28 bg-white/5 rounded-full"></div>
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 id="count-selesai" class="text-4xl font-black">{{ $stats['semua']['selesai'] }}</h3>
                    <p class="text-xs font-bold text-white/80 mt-1 uppercase tracking-wider">Selesai</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex justify-between items-center"
                x-data="{ show: true }" x-show="show">
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

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">

            {{-- Header Kontrol Tabel --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-800">Tabel Laporan</h3>
                    <span id="tabel-periode-label" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Periode: Semua Waktu</span>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <div id="dt-export-btn-area"></div>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <button type="button" onclick="document.getElementById('modalTtd').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center text-sm shadow-sm transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Tanda Tangan
                    </button>
                    @endif
                </div>
            </div>

            {{-- DataTable --}}
            <div class="overflow-x-auto w-full">
                <table id="tableLaporan" class="w-full text-sm text-left text-gray-600" style="min-width: 1700px;">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center w-16">No</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Kode Tiket
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Tgl. Lapor
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Judul Laporan
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Jenis Kasus
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">Pelapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap">No. WhatsApp
                            </th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Status Korban</th>
                            <th scope="col"
                                class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center text-red-500">
                                Status Terlapor</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                L/P</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Disabilitas</th>
                            <th scope="col" class="px-4 py-5 font-bold tracking-wider whitespace-nowrap text-center">
                                Saksi</th>
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
                        @foreach ($laporans as $index => $item)
                            <tr class="bg-white hover:bg-gray-50/50 transition-colors group laporan-row" 
                                data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}"
                                data-status="{{ $item->status }}"
                                x-data="{ showEdit: false, showBukti: false, showDetail: false, showDelete: false, showKeluhan: false }">
                                <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-bold text-[#800000] whitespace-nowrap">{{ $item->kode_tiket }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500" data-sort="{{ $item->created_at }}">
                                    {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-4 font-bold text-gray-800 min-w-[200px]">{{ $item->judul_lapor }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">{{ ucfirst($item->jenis_kasus) }}</span>
                                </td>
                                <td class="px-4 py-4 font-medium text-gray-700 whitespace-nowrap">{{ $item->nama_korban }}
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($item->no_hp_korban)
                                        <a href="https://wa.me/{{ '62' . ltrim($item->no_hp_korban, '0') }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 text-green-600 hover:text-green-700 bg-green-50 px-3 py-1.5 rounded-lg font-bold transition-colors">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12.031 0C5.383 0 0 5.383 0 12.031c0 2.124.553 4.195 1.604 6.012L.19 24l6.14-1.583c1.76.963 3.754 1.472 5.801 1.472 6.648 0 12.031-5.383 12.031-12.031S18.679 0 12.031 0zm0 21.86c-1.802 0-3.568-.484-5.116-1.402l-.367-.217-3.8.98.998-3.705-.238-.379C2.476 15.541 1.95 13.81 1.95 12.031c0-5.562 4.519-10.081 10.081-10.081 5.563 0 10.082 4.519 10.082 10.081s-4.519 10.081-10.082 10.081zm5.534-7.551c-.303-.152-1.795-.886-2.073-.987-.278-.101-.481-.152-.684.152-.202.303-.784.987-.96 1.189-.177.202-.354.227-.657.076-1.353-.679-2.457-1.442-3.411-3.084-.177-.303-.019-.467.133-.618.136-.136.303-.354.455-.53.152-.177.202-.303.303-.505.101-.202.051-.379-.025-.531-.076-.152-.684-1.645-.936-2.251-.246-.593-.497-.512-.684-.521-.177-.008-.379-.01-.582-.01-.202 0-.531.076-.809.379-.278.303-1.062 1.037-1.062 2.53 0 1.493 1.088 2.934 1.239 3.136.152.202 2.138 3.265 5.178 4.577 1.303.561 2.054.675 2.825.642.85-.036 2.655-1.085 3.033-2.133.379-1.048.379-1.946.265-2.133-.114-.187-.417-.288-.72-.44z">
                                                </path>
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
                                <td class="px-4 py-4 text-center whitespace-nowrap font-medium text-red-600">
                                    {{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}
                                    @if ($item->status_terlapor === 'lainnya' && $item->status_terlapor_lainnya)
                                        <span class="block text-xs text-red-400 mt-1">({{ $item->status_terlapor_lainnya }})</span>
                                    @endif
                                </td>
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

                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-3 py-1.5 text-[11px] uppercase tracking-wider font-extrabold rounded-xl border shadow-sm
                                        {{ $item->status == 'Menunggu Verifikasi' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                        {{ $item->status == 'Sedang Diproses' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                        {{ $item->status == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                        {{ $item->status == 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

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
                                        {{-- Tombol Lihat Detail --}}
                                        <button @click="showDetail = true"
                                            class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100 shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>

                                        @if(auth()->user()->role === 'admin')
                                            {{-- Tombol Verifikasi --}}
                                            <button @click="showEdit = true"
                                                class="p-2 text-yellow-600 bg-yellow-50 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors border border-yellow-100 shadow-sm"
                                                title="Verifikasi Status">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button @click="showDelete = true"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100 shadow-sm"
                                                title="Hapus Laporan">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        @endif

                                        {{-- Tombol Cetak Laporan --}}
                                        <a href="{{ url('/laporan/cetak-pdf/' . $item->id) }}" target="_blank"
                                            class="p-2 text-[#800000] bg-red-50 hover:bg-[#800000] hover:text-white rounded-lg transition-colors border border-red-100 shadow-sm"
                                            title="Cetak Laporan">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- Tombol Keluhan Admin: Tampil jika ada keluhan masuk --}}
                                        @if ($item->keluhan)
                                            <div class="relative">
                                                <button @click="showKeluhan = true"
                                                    class="relative p-2 rounded-lg transition-colors border shadow-sm focus:outline-none
                                                    {{ $item->keluhan_dibaca ? 'text-gray-400 bg-gray-100 border-gray-200 hover:bg-gray-200' : 'text-orange-600 bg-orange-50 border-orange-200 hover:bg-orange-500 hover:text-white' }}"
                                                    title="Lihat Keluhan Pelapor">
                                                    {{-- Titik merah berkedip jika belum dibaca --}}
                                                    @if (!$item->keluhan_dibaca)
                                                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
                                                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                                                    @endif
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Modal Lihat Detail Data --}}
                                    <template x-teleport="body">
                                        <div x-show="showDetail" style="display: none;"
                                            class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                            x-transition.opacity>
                                            <div @click.away="showDetail = false"
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
                                                    <button @click="showDetail = false"
                                                        class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all focus:outline-none backdrop-blur-sm border border-white/10 hover:scale-105 active:scale-95 shadow-sm">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                {{-- Body Content --}}
                                                <div class="overflow-y-auto p-6 sm:p-8 custom-scroll bg-gray-50 flex-1">
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
                                                            <div class="shrink-0 flex items-center">
                                                                <span
                                                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest border shadow-sm
                                                                    {{ $item->status == 'Menunggu Verifikasi' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                                                    {{ $item->status == 'Sedang Diproses' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                                                    {{ $item->status == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                                    {{ $item->status == 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                                                    <span class="w-2 h-2 rounded-full 
                                                                    {{ $item->status == 'Menunggu Verifikasi' ? 'bg-yellow-500 animate-pulse' : '' }}
                                                                    {{ $item->status == 'Sedang Diproses' ? 'bg-blue-500 animate-pulse' : '' }}
                                                                    {{ $item->status == 'Selesai' ? 'bg-green-500' : '' }}
                                                                    {{ $item->status == 'Ditolak' ? 'bg-red-500' : '' }}"></span>
                                                                    Status: {{ $item->status }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Grid Info --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                                                                        <span class="text-gray-300">•</span>
                                                                        <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                                        <span class="text-gray-300">•</span>
                                                                        <span class="bg-white border border-gray-200 px-2.5 py-1 rounded-md shadow-sm {{ $item->disabilitas == 'ya' ? 'text-red-600' : '' }}">{{ $item->disabilitas == 'ya' ? 'Disabilitas' : 'Non-Disabilitas' }}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Kronologi & Bukti --}}
                                                    <div class="bg-white border border-gray-100 shadow-sm shadow-gray-200/50 rounded-2xl p-6 hover:shadow-md transition-shadow">
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
                                                                        @click="showDetail = false; setTimeout(() => showBukti = true, 300)"
                                                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors focus:outline-none w-full justify-center md:justify-start">
                                                                        <svg class="w-5 h-5" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
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
                                                                                stroke-linejoin="round" stroke-width="2"
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
                                                            <h3 class="text-lg font-black text-gray-800 tracking-tight">
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
                                                <div
                                                    class="p-6 bg-gray-100/50 flex flex-col justify-start items-center gap-6 flex-1 overflow-y-auto custom-scroll min-h-[300px]">
                                                    {{-- Bukti Foto --}}
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
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                            <h4 class="text-base font-bold text-gray-800 mb-1">Bukti Video
                                                            </h4>
                                                            <p class="text-xs text-gray-500 mb-4">Terdapat lampiran bukti
                                                                tambahan berupa video.</p>
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

                                    {{-- Modal Verifikasi Status --}}
                                    <template x-teleport="body">
                                        <div x-show="showEdit" style="display: none;"
                                            class="fixed inset-0 z-[9998] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
                                            x-transition.opacity>
                                            <div @click.away="showEdit = false"
                                                class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden transform transition-all text-left"
                                                x-transition.scale>

                                                {{-- Scrollable Header & Body --}}
                                                <div class="flex-1 overflow-y-auto custom-scroll pb-6">
                                                    {{-- Header Gradient --}}
                                                    <div class="relative bg-gradient-to-br from-[#800000] via-red-800 to-rose-900 px-7 pt-7 pb-12 overflow-hidden shrink-0">
                                                    <div class="absolute -top-6 -right-6 w-28 h-28 bg-white/10 rounded-full blur-2xl"></div>
                                                    <div class="absolute bottom-0 left-10 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
                                                    <button @click="showEdit = false"
                                                        class="absolute top-4 right-4 p-1.5 bg-white/20 hover:bg-white/30 text-white rounded-full transition-colors focus:outline-none">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                    <div class="relative z-10 flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center border border-white/30 shadow-lg backdrop-blur-sm shrink-0">
                                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-red-200 text-[10px] font-bold uppercase tracking-widest mb-0.5">Update Penanganan</p>
                                                            <h3 class="text-xl font-black text-white tracking-tight leading-tight">Verifikasi Status</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Body card overlap --}}
                                                <div class="relative -mt-6 mx-5 bg-white rounded-2xl shadow-lg border border-gray-100 px-5 pt-5 pb-4">
                                                    {{-- Tiket badge --}}
                                                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-gray-100">
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-8 h-8 bg-[#800000]/10 rounded-xl flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Tiket</span>
                                                        </div>
                                                        <span class="text-base font-black text-[#800000] tracking-widest bg-red-50 px-3 py-1 rounded-xl border border-red-100">{{ $item->kode_tiket }}</span>
                                                    </div>

                                                    <form action="{{ route('laporan.update-status', $item->id) }}" method="POST" id="formStatus_{{ $item->id }}"
                                                        x-data="{
                                                            selectedStatus: '{{ $item->status }}'
                                                        }">
                                                        @csrf
                                                        <p class="text-xs font-black text-gray-500 uppercase tracking-wider mb-3">Tetapkan Status Baru <span class="text-red-500">*</span></p>
                                                        <div class="space-y-2">
                                                            <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all
                                                                {{ $item->status == 'Menunggu Verifikasi' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300 hover:bg-yellow-50/50' }}"
                                                                :class="selectedStatus === 'Menunggu Verifikasi' ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200 hover:border-yellow-300 hover:bg-yellow-50/50'">
                                                                <input type="radio" name="status" value="Menunggu Verifikasi"
                                                                    {{ $item->status == 'Menunggu Verifikasi' ? 'checked' : '' }}
                                                                    x-model="selectedStatus"
                                                                    class="text-yellow-500 focus:ring-yellow-400 shrink-0">
                                                                <div class="flex items-center gap-2 flex-1">
                                                                    <span class="text-xl leading-none">⏳</span>
                                                                    <div>
                                                                        <span class="text-sm font-bold text-gray-700 block leading-tight">Menunggu Verifikasi</span>
                                                                        <span class="text-xs text-gray-400">Laporan menunggu tindak lanjut</span>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all"
                                                                :class="selectedStatus === 'Sedang Diproses' ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50/50'">
                                                                <input type="radio" name="status" value="Sedang Diproses"
                                                                    {{ $item->status == 'Sedang Diproses' ? 'checked' : '' }}
                                                                    x-model="selectedStatus"
                                                                    class="text-blue-500 focus:ring-blue-400 shrink-0">
                                                                <div class="flex items-center gap-2 flex-1">
                                                                    <span class="text-xl leading-none">🔄</span>
                                                                    <div>
                                                                        <span class="text-sm font-bold text-gray-700 block leading-tight">Sedang Diproses</span>
                                                                        <span class="text-xs text-gray-400">Laporan aktif ditangani tim</span>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                            <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all"
                                                                :class="selectedStatus === 'Selesai' ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-green-300 hover:bg-green-50/50'">
                                                                <input type="radio" name="status" value="Selesai"
                                                                    {{ $item->status == 'Selesai' ? 'checked' : '' }}
                                                                    x-model="selectedStatus"
                                                                    class="text-green-500 focus:ring-green-400 shrink-0">
                                                                <div class="flex items-center gap-2 flex-1">
                                                                    <span class="text-xl leading-none">✅</span>
                                                                    <div>
                                                                        <span class="text-sm font-bold text-gray-700 block leading-tight">Selesai</span>
                                                                        <span class="text-xs text-gray-400">Penanganan laporan selesai</span>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>


                                                    </form>
                                                </div>
                                                </div>

                                                {{-- Footer (Fixed) --}}
                                                <div class="px-5 py-4 flex gap-3 border-t border-gray-100 bg-gray-50/50 shrink-0">
                                                    <button type="button" @click="showEdit = false"
                                                        class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition text-sm">Batal</button>
                                                    <button type="submit" form="formStatus_{{ $item->id }}"
                                                        class="flex-1 py-3 bg-gradient-to-r from-[#800000] to-red-800 text-white font-bold rounded-xl hover:from-red-800 hover:to-[#800000] transition shadow-md shadow-red-900/20 active:scale-95 text-sm flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Simpan Status
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- MODAL KONFIRMASI HAPUS --}}
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
                                                <p class="text-gray-500 text-sm mb-8 font-medium">Laporan dengan Kode
                                                    <strong class="text-gray-800">{{ $item->kode_tiket }}</strong> akan
                                                    dihapus secara permanen dan tidak dapat dipulihkan.
                                                </p>

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

                                    {{-- Modal Keluhan Admin --}}
                                    @if ($item->keluhan)
                                        <template x-teleport="body">
                                            <div x-show="showKeluhan" style="display: none;"
                                                class="fixed inset-0 z-[9997] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                                x-transition.opacity
                                                @open.window="showKeluhan = true">
                                                <div @click.away="showKeluhan = false"
                                                    class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col overflow-hidden transform transition-all text-left"
                                                    x-transition.scale>

                                                    {{-- Header --}}
                                                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-7 py-5 text-white flex justify-between items-center relative overflow-hidden shrink-0">
                                                        <div class="absolute -top-5 -right-5 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                                                        <div class="flex items-center gap-4 relative z-10">
                                                            <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center border border-white/25 shadow-lg">
                                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <span class="text-orange-100 text-[10px] font-black uppercase tracking-widest block mb-0.5">Nomor Tiket Laporan</span>
                                                                <h3 class="text-xl font-black drop-shadow-md tracking-tight">{{ $item->kode_tiket }}</h3>
                                                                <span class="text-orange-100/80 text-xs font-medium block mt-0.5">{{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Makassar')->translatedFormat('d M Y, H:i') }} WITA</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2 relative z-10">
                                                            <button @click="showKeluhan = false"
                                                                class="p-2 bg-white/20 hover:bg-white/30 rounded-xl transition-all focus:outline-none">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Body --}}
                                                    <div class="flex-1 overflow-y-auto p-6 custom-scroll bg-gray-50">
                                                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4">
                                                            <div class="flex justify-between items-center mb-3">
                                                                <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest flex items-center gap-1.5 mb-0">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                    Isi Keluhan
                                                                </p>
                                                                <div>
                                                                    @if ($item->keluhan_dibaca)
                                                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 px-3 py-1.5 rounded-full">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                                            Sudah Dibaca
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-orange-600 bg-orange-50 border border-orange-200 px-3 py-1.5 rounded-full">
                                                                            <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                                                                            Belum Dibaca
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50 p-4 rounded-xl border border-gray-100 leading-relaxed">
                                                                {!! $item->keluhan !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Footer --}}
                                                    <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end items-center gap-3 shrink-0">
                                                        @if (!$item->keluhan_dibaca)
                                                            <form action="{{ route('laporan.baca-keluhan', $item->id) }}" method="POST" class="m-0">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all shadow-md active:scale-95 text-sm">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    Tandai Sudah Dibaca
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-500 font-bold rounded-xl text-sm border border-gray-200">
                                                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                                Keluhan Sudah Dibaca
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL PENGATURAN TANDA TANGAN --}}
    <div id="modalTtd" class="fixed inset-0 z-[10000] bg-gray-900/80 backdrop-blur-sm hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6">

                {{-- Header Modal --}}
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-extrabold text-gray-900">Pengaturan Tanda Tangan</h3>
                    <button type="button" onclick="document.getElementById('modalTtd').classList.add('hidden')"
                        class="text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 p-1.5 rounded-lg transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Mengambil data lama jika sudah pernah diisi --}}
                @php
                    $kontenSurat = \App\Models\KontenHalaman::where('halaman', 'pengaturan_surat')->first();
                    $dataSurat = $kontenSurat ? json_decode($kontenSurat->konten, true) : [];
                @endphp

                <form action="{{ route('laporan.upload-ttd') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf

                    {{-- Input File Tanda Tangan --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">File Tanda Tangan (Opsional)</label>
                        <input type="file" name="file_ttd" accept="image/png, image/jpeg, image/jpg"
                            class="w-full border border-gray-300 rounded-xl p-2 text-sm focus:ring-[#800000] focus:border-[#800000] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white hover:file:bg-red-900 cursor-pointer transition outline-none">
                        <p class="text-[10px] text-gray-500 mt-1.5">Abaikan jika tidak ingin mengubah gambar. Disarankan
                            format PNG transparan.</p>
                    </div>

                    {{-- Input Nama Ketua --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap & Gelar Pejabat</label>
                        <input type="text" name="nama_ketua" placeholder="Contoh: Muhamad Aksan Akbar, S.H., M.H"
                            value="{{ $dataSurat['nama_ketua'] ?? '' }}"
                            class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-1 focus:ring-[#800000] focus:border-[#800000] outline-none transition shadow-sm">
                    </div>

                    {{-- Input NIP Ketua --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nip_ketua" placeholder="Contoh: 19800101 200501 1 001"
                            value="{{ $dataSurat['nip_ketua'] ?? '' }}"
                            class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:ring-1 focus:ring-[#800000] focus:border-[#800000] outline-none transition shadow-sm">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5 mt-4">
                        <button type="button" onclick="document.getElementById('modalTtd').classList.add('hidden')"
                            class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition focus:outline-none">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#800000] text-white rounded-xl text-sm font-bold hover:bg-red-900 transition shadow-md focus:outline-none">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- STYLE TAMBAHAN UNTUK SCROLL DAN ANIMASI --}}
    <style>
        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #count-total,
        #count-menunggu,
        #count-diproses,
        #count-selesai,
        #count-ditolak {
            transition: opacity 0.2s ease-in-out;
        }

        /* Modifikasi tombol excel datatables */
        .dt-buttons .dt-button {
            background-color: #16a34a !important;
            color: white !important;
            border: none !important;
            padding: 0.45rem 0.9rem !important;
            border-radius: 0.5rem !important;
            font-weight: bold !important;
            font-size: 0.8rem !important;
            transition: all 0.3s !important;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05) !important;
            display: inline-flex !important;
            align-items: center !important;
        }
        .dt-buttons .dt-button:hover {
            background-color: #15803d !important;
        }

        /* Sembunyikan hanya default buttons karena sudah dipindah */
        div.dataTables_wrapper > div.dt-buttons { display: none; }

        /* Styling kontrol filter dan length */
        div.dataTables_wrapper div.dataTables_length,
        div.dataTables_wrapper div.dataTables_filter {
            float: none;
            font-size: 0.8rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-bottom: 0;
            text-align: left;
        }
        div.dataTables_wrapper div.dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
        }
        div.dataTables_wrapper div.dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.4rem 0.85rem;
            font-size: 0.8rem;
            outline: none;
            background: #f9fafb;
            width: 220px;
            transition: border-color 0.2s, background 0.2s;
            margin-left: 0.5rem;
        }
        div.dataTables_wrapper div.dataTables_filter input:focus {
            border-color: #800000;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(128,0,0,0.08);
        }
        div.dataTables_wrapper div.dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.35rem 0.5rem;
            font-size: 0.8rem;
            background: #f9fafb;
            margin: 0 0.3rem;
        }

        /* Info + Pagination */
        div.dataTables_wrapper div.dataTables_info {
            padding-top: 0.5rem;
            font-size: 0.8rem;
            color: #6b7280;
            float: none;
        }
        div.dataTables_wrapper div.dataTables_paginate {
            padding-top: 0;
            float: none;
            text-align: right;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button {
            padding: 0.3rem 0.6rem;
            border-radius: 0.4rem;
            font-size: 0.8rem;
            border: 1px solid transparent;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current {
            background: #800000 !important;
            color: white !important;
            border-color: #800000 !important;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #e5e7eb !important;
        }
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
            background: #900000 !important;
            color: white !important;
        }

    </style>
@endsection

@push('scripts')


    {{-- SCRIPT UNTUK EXPORT EXCEL DATATABLES --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        const dashboardData = @json($stats);
        const isAdmin = {{ $isAdmin ? 'true' : 'false' }};

        // ============================================================
        // VARIABEL FILTER AKTIF (disimpan di scope modul)
        // ============================================================
        let activePeriod   = 'semua';
        let filterStart    = null;
        let filterEnd      = null;
        let dtTable        = null;

        $(document).ready(function () {
            // ---- Daftarkan SATU custom search function (sekali, di awal) ----
            $.fn.dataTable.ext.search.push(function (settings, rowData, dataIndex) {
                // Hanya berlaku untuk tabel laporan ini
                if (settings.nTable.id !== 'tableLaporan') return true;
                // Jika periode "semua", tampilkan semua baris
                if (activePeriod === 'semua' || !filterStart || !filterEnd) return true;

                // Ambil data-date dari node <tr>
                const node       = dtTable ? dtTable.row(dataIndex).node() : null;
                const dateStr    = node ? $(node).data('date') : null;
                if (!dateStr) return false;

                const parts   = dateStr.split('-');
                const rowDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));

                return rowDate >= filterStart && rowDate <= filterEnd;
            });

            // ---- Inisialisasi DataTable ----
            dtTable = $('#tableLaporan').DataTable({
                "language": {
                    "search":      "Cari Data:",
                    "lengthMenu":  "Tampilkan _MENU_ entri",
                    "emptyTable":  "Tidak ada laporan pada periode yang dipilih.",
                    "zeroRecords": "Tidak ada laporan yang sesuai dengan filter ini.",
                    "info":        "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty":   "Menampilkan 0 sampai 0 dari 0 entri",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next":     "Selanjutnya"
                    }
                },
                "pagingType": "simple_numbers",
                "dom": '<"flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"lf>Brt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip>',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export Excel',
                    title: 'Data Laporan Pengaduan PPKS',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12] }
                }],
                "pageLength":  10,
                "scrollX":     false,
                "order":       [[2, "desc"]],
                "columnDefs": [
                    { "orderable": false, "targets": -1 },
                    { "orderable": false, "targets": -3 }
                ],
                "initComplete": function () {
                    // Pindahkan tombol Export ke area header
                    var exportArea = document.getElementById('dt-export-btn-area');
                    if (exportArea) {
                        var dtBtns = document.querySelector('.dt-buttons');
                        if (dtBtns) exportArea.appendChild(dtBtns);
                    }
                }
            });
        });

        // ============================================================
        // FUNGSI APPLY FILTER — dipanggil oleh tombol periode
        // ============================================================
        window.applyFilter = function (period) {
            if (!dashboardData || !dashboardData[period]) return;

            // --- Update kartu statistik ---
            const data = dashboardData[period];
            animateValue("count-total",    data.total);
            animateValue("count-menunggu", data.menunggu);
            animateValue("count-diproses", data.diproses);
            animateValue("count-selesai",  data.selesai);
            animateValue("count-ditolak",  data.ditolak);

            // --- Highlight tombol aktif ---
            document.querySelectorAll('.filter-btn').forEach(function (btn) {
                if (btn.dataset.period === period) {
                    btn.className = `filter-btn active shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all ${isAdmin ? 'bg-[#800000]' : 'bg-blue-900'} text-white shadow-md`;
                } else {
                    btn.className = 'filter-btn shrink-0 px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200';
                }
            });

            // --- Hitung rentang tanggal ---
            const now   = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

            activePeriod = period;
            filterStart  = null;
            filterEnd    = null;

            if (period === 'harian') {
                filterStart = today;
                filterEnd   = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59, 999);
            } else if (period === 'mingguan') {
                const dow   = today.getDay() === 0 ? 6 : today.getDay() - 1; // Senin = 0
                filterStart = new Date(today.getTime() - dow * 86400000);
                filterEnd   = new Date(filterStart.getTime() + 7 * 86400000 - 1);
            } else if (period === 'bulanan') {
                filterStart = new Date(now.getFullYear(), now.getMonth(), 1);
                filterEnd   = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59, 999);
            } else if (period === 'tahunan') {
                filterStart = new Date(now.getFullYear(), 0, 1);
                filterEnd   = new Date(now.getFullYear(), 11, 31, 23, 59, 59, 999);
            }

            // --- Trigger redraw DataTable (custom search function di atas akan membaca filterStart/filterEnd) ---
            if (dtTable) {
                dtTable.draw();
            }

            // --- Update label periode ---
            const periodLabels = {
                'semua':   'Semua Waktu',
                'harian':  'Hari Ini (' + today.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + ')',
                'mingguan':'Minggu Ini',
                'bulanan': 'Bulan Ini (' + now.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' }) + ')',
                'tahunan': 'Tahun ' + now.getFullYear(),
            };
            const labelEl = document.getElementById('tabel-periode-label');
            if (labelEl) {
                labelEl.textContent = 'Periode: ' + (periodLabels[period] || 'Semua Waktu');
            }
        };

        function animateValue(id, end) {
            const obj = document.getElementById(id);
            if (!obj) return;
            obj.style.opacity = 0;
            setTimeout(function () {
                obj.innerText = end;
                obj.style.opacity = 1;
            }, 150);
        }
    </script>
@endpush
