@extends('layouts.app')

@section('header_title', 'Data Laporan Pengaduan')

@section('content')
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
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->tanggal_kejadian ? Carbon::parse($item->tanggal_kejadian) : null;
                    return $tanggalLapor && $tanggalLapor->isSameDay($now);
                });
            } elseif ($key === 'mingguan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->tanggal_kejadian ? Carbon::parse($item->tanggal_kejadian) : null;
                    return $tanggalLapor && $tanggalLapor->isSameWeek($now);
                });
            } elseif ($key === 'bulanan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->tanggal_kejadian ? Carbon::parse($item->tanggal_kejadian) : null;
                    return $tanggalLapor && $tanggalLapor->isSameMonth($now);
                });
            } elseif ($key === 'tahunan') {
                $filtered = $allLaporans->filter(function ($item) use ($now) {
                    $tanggalLapor = $item->tanggal_kejadian ? Carbon::parse($item->tanggal_kejadian) : null;
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
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-5 mb-8">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-50 pb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 {{ $isAdmin ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Ringkasan Laporan</h3>
                        <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider mt-0.5">Filter data
                            berdasarkan waktu</p>
                    </div>
                </div>

                {{-- Panel Filter Tombol --}}
                <div
                    class="flex overflow-x-auto w-full xl:w-auto bg-gray-50 p-1.5 rounded-xl border border-gray-200 hide-scroll">
                    <button type="button" onclick="applyFilter('semua')" data-period="semua"
                        class="filter-btn active w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $themeBg }} text-white shadow-md">Semua</button>
                    {{-- TOMBOL HARI INI DENGAN INDIKATOR MERAH --}}
                    <button type="button" onclick="applyFilter('harian')" data-period="harian"
                        class="filter-btn w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200 relative flex items-center justify-center gap-1.5">
                        Hari Ini
                        {{-- Logika: Munculkan titik merah berkedip JIKA ada laporan 'menunggu' hari ini --}}
                        @if ($stats['harian']['menunggu'] > 0)
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                    <button type="button" onclick="applyFilter('mingguan')" data-period="mingguan"
                        class="filter-btn w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Mingguan</button>
                    <button type="button" onclick="applyFilter('bulanan')" data-period="bulanan"
                        class="filter-btn w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Bulanan</button>
                    <button type="button" onclick="applyFilter('tahunan')" data-period="tahunan"
                        class="filter-btn w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Tahunan</button>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
                <div
                    class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden col-span-2 lg:col-span-1">
                    <div class="flex justify-between items-start mb-3">
                        <div
                            class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 id="count-total" class="text-3xl font-black text-gray-800">{{ $stats['semua']['total'] }}</h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Total Laporan</p>
                    </div>
                </div>
                <div
                    class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3">
                        <div
                            class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 id="count-menunggu" class="text-3xl font-black text-gray-800">{{ $stats['semua']['menunggu'] }}
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Menunggu</p>
                    </div>
                </div>
                <div
                    class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3">
                        <div
                            class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 id="count-diproses" class="text-3xl font-black text-gray-800">{{ $stats['semua']['diproses'] }}
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Diproses</p>
                    </div>
                </div>
                <div
                    class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3">
                        <div
                            class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 id="count-selesai" class="text-3xl font-black text-gray-800">{{ $stats['semua']['selesai'] }}
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Selesai</p>
                    </div>
                </div>
                <div
                    class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:bg-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3">
                        <div
                            class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 id="count-ditolak" class="text-3xl font-black text-gray-800">{{ $stats['semua']['ditolak'] }}
                        </h3>
                        <p class="text-xs font-bold text-gray-500 mt-1">Ditolak</p>
                    </div>
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

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col p-6">
            <div class="overflow-x-auto custom-scroll flex-1 relative w-full">
                <table id="tableLaporan" class="w-full text-sm text-left text-gray-600 min-w-[1700px] mt-4">
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
                            <tr class="bg-white hover:bg-gray-50/50 transition-colors group" x-data="{ showEdit: false, showBukti: false, showDetail: false }">
                                <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-bold text-[#800000] whitespace-nowrap">{{ $item->kode_tiket }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d M Y') }}</td>
                                <td class="px-4 py-4 font-bold text-gray-800 min-w-[200px]">{{ $item->judul_lapor }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">{{ ucfirst($item->jenis_kasus) }}</span>
                                </td>
                                {{-- Menghapus Anonim (selalu memunculkan nama asli di sisi admin) --}}
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

                                <td class="px-4 py-4 text-center whitespace-nowrap">{{ ucfirst($item->status_korban) }}
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap font-medium text-red-600">
                                    {{ ucwords(str_replace('_', ' ', $item->status_terlapor)) }}</td>
                                <td class="px-4 py-4 text-center">{{ $item->jenis_kelamin }}</td>
                                <td class="px-4 py-4 text-center text-gray-400">{{ ucfirst($item->disabilitas) }}</td>

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

                                <td
                                    class="px-6 py-4 transition-colors text-center sticky right-0 bg-white group-hover:bg-gray-50 z-20 border-l border-gray-100 shadow-[-10px_0_15px_-3px_rgba(0,0,0,0.05)]">
                                    <div class="flex items-center justify-center gap-2">
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

                                        <button @click="showEdit = true"
                                            class="p-2 text-yellow-600 bg-yellow-50 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors border border-yellow-100 shadow-sm"
                                            title="Verifikasi Status">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>

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
                                    </div>

                                    {{-- Modal Lihat Detail Data --}}
                                    <template x-teleport="body">
                                        <div x-show="showDetail" style="display: none;"
                                            class="fixed inset-0 z-[10000] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                                            x-transition.opacity>
                                            <div @click.away="showDetail = false"
                                                class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col text-left overflow-hidden transform transition-all"
                                                x-transition.scale>

                                                {{-- Header Merah --}}
                                                <div
                                                    class="bg-[#800000] p-6 sm:px-8 text-white flex justify-between items-center relative shrink-0">
                                                    <div>
                                                        <span
                                                            class="text-red-200 text-xs font-bold uppercase tracking-widest block mb-1">Detail
                                                            Tiket Pengaduan</span>
                                                        <h3 class="text-2xl sm:text-3xl font-black">
                                                            {{ $item->kode_tiket }}</h3>
                                                    </div>
                                                    <button @click="showDetail = false"
                                                        class="p-2 bg-white/20 hover:bg-white/40 rounded-full transition-colors focus:outline-none">
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
                                                        class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
                                                        <div
                                                            class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                                            <div>
                                                                <p
                                                                    class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">
                                                                    Judul Pengaduan</p>
                                                                <p class="font-black text-gray-800 text-xl lg:text-2xl">
                                                                    {{ $item->judul_lapor }}</p>
                                                            </div>
                                                            <div class="shrink-0">
                                                                <span
                                                                    class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border
                                                                    {{ $item->status == 'Menunggu Verifikasi' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                                                    {{ $item->status == 'Sedang Diproses' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                                                    {{ $item->status == 'Selesai' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                                                    {{ $item->status == 'Ditolak' ? 'bg-red-50 text-red-700 border-red-200' : '' }}">
                                                                    Status: {{ $item->status }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Grid Info --}}
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                        <div
                                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                            <h4
                                                                class="font-bold text-[#800000] border-b border-gray-50 pb-3 mb-4 flex items-center gap-2">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
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

                                                        <div
                                                            class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                            <h4
                                                                class="font-bold text-[#800000] border-b border-gray-50 pb-3 mb-4 flex items-center gap-2">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
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
                                                                        {{ ucfirst($item->status_korban) }} •
                                                                        {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                                        •
                                                                        {{ $item->disabilitas == 'ya' ? 'Disabilitas' : 'Non-Disabilitas' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Kronologi & Bukti --}}
                                                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                                                        <div class="mb-6">
                                                            <p
                                                                class="text-[12px] font-bold text-gray-400 uppercase tracking-wider mb-2">
                                                                Deskripsi & Kronologi Singkat</p>
                                                            <div class="p-4 bg-gray-50 border border-gray-100ph">
                                                                <p class="text-gray-700">
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
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
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
                                                class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full overflow-hidden transform transition-all text-left"
                                                x-transition.scale>
                                                <div
                                                    class="bg-gray-50 border-b border-gray-100 px-6 py-5 flex justify-between items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center shadow-sm">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-lg font-black text-gray-800 leading-tight">
                                                                Verifikasi Status</h3>
                                                            <p
                                                                class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">
                                                                Update penanganan</p>
                                                        </div>
                                                    </div>
                                                    <button @click="showEdit = false"
                                                        class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition"><svg
                                                            class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg></button>
                                                </div>
                                                <form action="{{ route('laporan.update-status', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="p-6">
                                                        <div
                                                            class="mb-6 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl flex items-center justify-between">
                                                            <span
                                                                class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Nomor
                                                                Tiket:</span><span
                                                                class="text-base font-black text-blue-900 tracking-wide">{{ $item->kode_tiket }}</span>
                                                        </div>
                                                        <div class="space-y-2.5">
                                                            <label class="block text-sm font-bold text-gray-700">Tetapkan
                                                                Status Baru <span class="text-red-500">*</span></label>
                                                            <div class="relative">
                                                                <select name="status"
                                                                    class="w-full bg-white border-2 border-gray-200 text-gray-800 rounded-xl p-3.5 pr-10 outline-none focus:border-[#800000] focus:ring-0 font-bold appearance-none cursor-pointer transition-colors shadow-sm">
                                                                    <option value="Menunggu Verifikasi"
                                                                        {{ $item->status == 'Menunggu Verifikasi' ? 'selected' : '' }}>
                                                                        ⏳ Menunggu Verifikasi</option>
                                                                    <option value="Sedang Diproses"
                                                                        {{ $item->status == 'Sedang Diproses' ? 'selected' : '' }}>
                                                                        🔄 Sedang Diproses</option>
                                                                    <option value="Selesai"
                                                                        {{ $item->status == 'Selesai' ? 'selected' : '' }}>
                                                                        ✅ Selesai</option>
                                                                    <option value="Ditolak"
                                                                        {{ $item->status == 'Ditolak' ? 'selected' : '' }}>
                                                                        ❌ Ditolak</option>
                                                                </select>
                                                                <div
                                                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 9l-7 7-7-7"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 border-t border-gray-100 px-6 py-4 flex gap-3">
                                                        <button type="button" @click="showEdit = false"
                                                            class="w-1/2 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-100 transition">Batal</button>
                                                        <button type="submit"
                                                            class="w-1/2 py-2.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 shadow-md shadow-red-900/20 transition">Update
                                                            Data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            font-weight: bold !important;
            font-size: 0.875rem !important;
            transition: all 0.3s !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }

        .dt-buttons .dt-button:hover {
            background-color: #15803d !important;
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

        $(document).ready(function() {
            $('#tableLaporan').DataTable({
                "language": {
                    "search": "Cari Data:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "emptyTable": "Belum ada laporan pengaduan yang masuk.",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "paginate": {
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya"
                    }
                },
                "pagingType": "simple_numbers",

                // Susunan DOM diperbarui: 'B' (Button) ada di atas 'f' (Filter/Pencarian) di sisi kanan
                "dom": '<"flex flex-col md:flex-row justify-between items-start gap-4 mb-6"<"left-side flex flex-col gap-3"l><"right-side flex flex-col items-end gap-2"Bf>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"ip>',

                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export Excel',
                    title: 'Data Laporan Pengaduan PPKS',
                    exportOptions: {
                        // Telah diperbarui untuk mengambil kolom ke-8 (Status Terlapor)
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12]
                    }
                }],
                "pageLength": 10,
                "scrollX": true,
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": -1
                    },
                    {
                        "orderable": false,
                        "targets": -3 // Kolom Bukti
                    }
                ],
                "initComplete": function() {
                    var tableTitle =
                        '<span class="text-base text-gray-700 font-bold mt-1">Tabel Laporan</span>';
                    $('.left-side').prepend(tableTitle);
                }
            });
        });

        window.applyFilter = function(period) {
            if (!dashboardData || !dashboardData[period]) return;

            const data = dashboardData[period];

            animateValue("count-total", data.total);
            animateValue("count-menunggu", data.menunggu);
            animateValue("count-diproses", data.diproses);
            animateValue("count-selesai", data.selesai);
            animateValue("count-ditolak", data.ditolak);

            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.period === period) {
                    btn.className =
                        `filter-btn active w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all ${isAdmin ? 'bg-[#800000]' : 'bg-blue-900'} text-white shadow-md`;
                } else {
                    btn.className =
                        "filter-btn w-full sm:w-auto px-5 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200";
                }
            });
        }

        function animateValue(id, end) {
            let obj = document.getElementById(id);
            if (!obj) return;
            obj.style.opacity = 0;
            setTimeout(() => {
                obj.innerText = end;
                obj.style.opacity = 1;
            }, 150);
        }
    </script>
@endpush
