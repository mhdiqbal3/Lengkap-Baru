@extends('layouts.app')
@section('header_title', 'Dashboard Satgas PPKS')
@section('content')
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
                'total'    => $filtered->count(),
                'menunggu' => $filtered->where('status', 'Menunggu Verifikasi')->count(),
                'diproses' => $filtered->where('status', 'Sedang Diproses')->count(),
                'selesai'  => $filtered->where('status', 'Selesai')->count(),
            ];
        }

        $isAdmin = Auth::check() && Auth::user()?->role === 'admin';
        $isSatgas = Auth::check() && Auth::user()?->role === 'satgas';
        $canViewSummary = $isAdmin || $isSatgas;
        $themeBg = 'bg-[#800000]';
    @endphp

<div class="max-w-[100%] mx-auto pb-12 space-y-6">
        @if (session('success'))
            <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-[9999] bg-green-500 text-white px-6 py-3 rounded-full shadow-xl text-sm font-bold flex items-center gap-2"
                x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('login_success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl flex items-start sm:items-center justify-between shadow-sm transition-all duration-300"
            id="welcome-notification" role="alert">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 text-green-600 p-2 rounded-full shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-sm sm:text-base">
                        Selamat datang, <span class="font-bold">{{ Auth::user()?->name ?? 'User' }}</span>!
                    </p>
                </div>
            </div>
            <button onclick="document.getElementById('welcome-notification').style.display='none'"
                class="opacity-60 hover:opacity-100 p-1 text-green-800 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif

        @if ($canViewSummary)
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                {{-- Header Gradient --}}
                <div class="bg-gradient-to-r from-[#800000] to-red-800 px-6 py-5 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white">Ringkasan Laporan</h3>
                            <p class="text-[11px] font-medium text-white/70 uppercase tracking-wider mt-0.5">Filter data berdasarkan waktu</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 w-full xl:w-auto bg-white/20 p-1.5 rounded-xl">
                        <button type="button" onclick="applyFilter('semua')" data-period="semua"
                            class="filter-btn active flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all bg-white text-[#800000] shadow-md">Semua</button>
                        <button type="button" onclick="applyFilter('harian')" data-period="harian"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-white/80 hover:text-white hover:bg-white/20">Harian</button>
                        <button type="button" onclick="applyFilter('mingguan')" data-period="mingguan"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-white/80 hover:text-white hover:bg-white/20">Mingguan</button>
                        <button type="button" onclick="applyFilter('bulanan')" data-period="bulanan"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-white/80 hover:text-white hover:bg-white/20">Bulanan</button>
                    </div>
                </div>
                <div class="p-5 grid grid-cols-2 lg:grid-cols-4 gap-4">
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

            {{-- GRAFIK LINE CHART + DOUGHNUT PREMIUM --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                {{-- LINE CHART: Statistik Penanganan per Rentang Waktu --}}
                <div class="lg:col-span-2 bg-white p-7 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Statistik Penanganan</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5" id="label-periode-line">Rentang: Bulanan</p>
                        </div>
                        <div class="flex gap-1 bg-gray-100 p-1 rounded-xl shrink-0">
                            <button onclick="switchLineRange('harian')" id="btn-harian"
                                class="line-range-btn active-range px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#800000] text-white shadow">Harian</button>
                            <button onclick="switchLineRange('mingguan')" id="btn-mingguan"
                                class="line-range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800">Mingguan</button>
                            <button onclick="switchLineRange('bulanan')" id="btn-bulanan"
                                class="line-range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800">Bulanan</button>
                        </div>
                    </div>
                    {{-- Wrapper overflow untuk scroll horizontal --}}
                    <div class="overflow-x-auto flex-1">
                        <div id="lineChartWrapper" style="min-width: 600px; height: 350px; position: relative;">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- DOUGHNUT: Total Jenis Kekerasan --}}
                <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Total Jenis Kekerasan</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Semua Data Jenis Kasus</p>
                        </div>
                        <div class="p-2 bg-red-50 rounded-xl text-[#800000]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative flex-1 flex justify-center items-center" style="min-height:220px">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        @if ($isAdmin)
            <div x-data="{ showCarouselModal: false }">
                <button @click="showCarouselModal = true"
                    class="fixed bottom-6 right-6 z-50 bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-full shadow-2xl flex items-center justify-center group transition transform hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span
                        class="absolute right-16 w-max bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded opacity-0 group-hover:opacity-100 transition shadow-lg">Ubah
                        Gambar Beranda</span>
                </button>
                <template x-teleport="body">
                    <div x-show="showCarouselModal" style="display: none;"
                        class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm p-4"
                        x-transition.opacity>
                        <div @click.away="showCarouselModal = false"
                            class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl"
                            x-transition.scale>
                            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 text-lg">Manajemen Gambar Beranda</h3>
                                <button @click="showCarouselModal = false"
                                    class="text-gray-400 hover:text-red-500 bg-white p-1.5 rounded-lg border border-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-6 overflow-y-auto">
                                <form action="{{ route('carousel.upload') }}" method="POST"
                                    enctype="multipart/form-data"
                                    class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
                                    @csrf
                                    <p class="text-sm font-bold text-gray-800 mb-3">Unggah Gambar Baru</p>
                                    <div class="flex gap-3">
                                        <input type="file" name="gambar" accept="image/*"
                                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#800000] file:text-white cursor-pointer"
                                            required>
                                        <button type="submit"
                                            class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-green-700">Upload</button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 font-medium">Pilih gambar resolusi tinggi
                                        (Landscape). Maksimal 5MB.</p>
                                </form>
                                <p class="text-sm font-bold text-gray-800 mb-3">Daftar Gambar:</p>
                                <div class="grid grid-cols-2 gap-4">
                                    @isset($carousels)
                                        @foreach ($carousels as $carousel)
                                            <div
                                                class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm aspect-video">
                                                <img src="{{ $carousel['url'] }}" class="w-full h-full object-cover">
                                                @if ($carousel['nama'] !== 'default1')
                                                    <form action="{{ route('carousel.hapus') }}" method="POST"
                                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                        @csrf
                                                        <input type="hidden" name="nama_file"
                                                            value="{{ $carousel['nama'] }}">
                                                        <button type="submit" onclick="return confirm('Hapus gambar ini?')"
                                                            class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg">Hapus</button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-8 mb-4 flex flex-col md:flex-row justify-between items-center gap-4 bg-gradient-to-r from-amber-50 to-yellow-50 p-6 rounded-2xl border border-yellow-200 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-yellow-400 rounded-2xl flex items-center justify-center shadow-md shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-800">Tampilan Dashboard Pelapor</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Bagian di bawah ini adalah tampilan yang dilihat oleh pelapor. Anda dapat mengubah teksnya secara dinamis.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard.edit') }}"
                    class="shrink-0 inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Teks Dashboard
                </a>
            </div>
        @endif

        {{-- INTEGRASI DATA DINAMIS PELAPOR --}}
        @php
            $dataDashboard =
                isset($kontenDashboard) && !empty($kontenDashboard->konten)
                    ? json_decode($kontenDashboard->konten, true)
                    : [];
            $d = function ($key, $default) use ($dataDashboard) {
                return $dataDashboard[$key] ?? $default;
            };

            // Menarik data dinamis bentuk kekerasan
            $bentuk_titles = $dataDashboard['bentuk_item_titles'] ?? [
                $d('ks_title', 'Kekerasan Seksual'),
                $d('kf_title', 'Kekerasan Fisik'),
                $d('kp_title', 'Kekerasan Psikologis'),
            ];
            $bentuk_descs = $dataDashboard['bentuk_item_descs'] ?? [
                $d(
                    'ks_desc',
                    'Termasuk pelecehan verbal, fisik, hingga pemaksaan melalui media digital atau intimidasi.',
                ),
                $d(
                    'kf_desc',
                    'Tindakan kontak fisik yang menyakiti atau membahayakan nyawa orang lain secara sengaja.',
                ),
                $d(
                    'kp_desc',
                    'Ejekan, pengucilan, atau ancaman yang merusak kesehatan mental dan rasa percaya diri seseorang.',
                ),
            ];

            // Menarik data dinamis hak pelapor
            $hak_items = $dataDashboard['hak_items'] ?? [
                $d('hak_1', 'Hak atas perlindungan identitas dan kerahasiaan informasi.'),
                $d('hak_2', 'Hak atas pendampingan psikologis, hukum, dan medis.'),
                $d('hak_3', 'Hak untuk mendapatkan informasi perkembangan kasus secara rutin.'),
                $d('hak_4', 'Hak atas rasa aman dan bebas dari ancaman pihak manapun.'),
            ];

            // Menarik data dinamis alur
            $alur_titles = $dataDashboard['alur_item_titles'] ?? [
                $d('alur_1_title', 'Buat Laporan'),
                $d('alur_2_title', 'Verifikasi'),
                $d('alur_3_title', 'Investigasi'),
                $d('alur_4_title', 'Pemulihan'),
            ];
            $alur_descs = $dataDashboard['alur_item_descs'] ?? [
                $d('alur_1_desc', 'Isi form pengaduan'),
                $d('alur_2_desc', 'Satgas memeriksa laporan'),
                $d('alur_3_desc', 'Proses pencarian fakta'),
                $d('alur_4_desc', 'Tindak lanjut & pendampingan'),
            ];
        @endphp

        @isset($carousels)
            <div x-data="{
                activeSlide: 0,
                slides: [{{ implode(',', array_keys($carousels)) }}],
                loop() {
                    setInterval(() => {
                        this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1
                    }, 5000)
                }
            }" x-init="loop()"
                class="relative w-full h-56 md:h-[320px] bg-gray-200 rounded-3xl overflow-hidden shadow-sm group">
                @foreach ($carousels as $index => $carousel)
                    <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition opacity duration-1000"
                        x-transition:leave="transition opacity duration-1000" class="absolute inset-0">
                        <img src="{{ $carousel['url'] }}" class="w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex items-end">
                            <div class="p-6 md:p-8 text-white max-w-2xl">
                                <span
                                    class="bg-red-600 text-[10px] uppercase font-bold px-3 py-1 rounded-full mb-3 inline-block shadow-sm">Informasi
                                    Terkini</span>
                                <h2 class="text-2xl md:text-3xl font-black mb-2 leading-tight">
                                    {{ $d('carousel_title', 'Bersama Wujudkan Kampus Aman') }}</h2>
                                <p class="text-xs md:text-sm opacity-90 leading-relaxed">
                                    {{ $d('carousel_desc', 'Satgas PPKS hadir untuk memberikan perlindungan, pendampingan, dan keadilan bagi seluruh civitas akademika.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    @foreach ($carousels as $index => $carousel)
                        <button @click="activeSlide = {{ $index }}"
                            :class="activeSlide === {{ $index }} ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            </div>
        @endisset
    </div>
@endsection

@push('scripts')
    @if ($canViewSummary)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const dashboardData  = @json($stats);
            const timeSeriesData = @json($timeSeriesData);
            const allJenisKasus  = @json($allJenisKasus);

            let lineChart    = null;
            let doughnutChart = null;
            let currentRange = 'harian';

            // Palet warna yang premium untuk multi-line
            const colorPalette = ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#06B6D4', '#14B8A6', '#F43F5E', '#84CC16'];

            function buildLineDatasets(range) {
                const seriesData = timeSeriesData[range]?.series ?? {};
                return Object.keys(seriesData).map((jenis, i) => {
                    const c = colorPalette[i % colorPalette.length];
                    return {
                        label: jenis,
                        data: seriesData[jenis],
                        borderColor: c,
                        backgroundColor: c,
                        pointStyle: 'circle',
                        borderWidth: 3,
                        tension: 0.4, 
                        fill: false,
                    };
                });
            }

            function renderLegend() {
                // Not used anymore as we rely on built-in legend
            }

            document.addEventListener("DOMContentLoaded", function() {
                try {
                    Chart.defaults.font.family = "'Inter', sans-serif";
                    Chart.defaults.color = '#9ca3af';

                    // ===== LINE CHART =====
                    const ctxLine = document.getElementById('lineChart');
                    if (ctxLine) {
                        const rangeData = timeSeriesData['harian'];
                        lineChart = new Chart(ctxLine.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: rangeData?.labels ?? [],
                                datasets: buildLineDatasets('harian'),
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: {
                                        position: 'left',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20,
                                            font: {
                                                family: "'Inter', sans-serif",
                                                weight: '600'
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                        titleColor: '#1f2937',
                                        bodyColor: '#4b5563',
                                        borderColor: '#e5e7eb',
                                        borderWidth: 1,
                                        padding: 12,
                                        boxPadding: 6,
                                        usePointStyle: true,
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { stepSize: 1, precision: 0 },
                                        grid: { 
                                            display: true,
                                            color: '#e5e7eb' // Garis grid horizontal
                                        },
                                        border: { display: false }
                                    },
                                    x: {
                                        grid: { 
                                            display: true,
                                            color: '#e5e7eb' // Garis grid vertikal
                                        },
                                        ticks: { maxRotation: 30, minRotation: 0 },
                                        border: { display: false }
                                    }
                                }
                            }
                        });
                        renderLegend();
                    }

                    // ===== DOUGHNUT CHART (Total Jenis Kekerasan — semua data) =====
                    const ctxDoughnut = document.getElementById('doughnutChart');
                    if (ctxDoughnut) {
                        const totalsData = timeSeriesData['_totals'] ?? {};
                        const jenisLabels = Object.keys(totalsData);
                        const jenisValues = Object.values(totalsData);
                        const doughnutColors = colorPalette;

                        doughnutChart = new Chart(ctxDoughnut.getContext('2d'), {
                            type: 'doughnut',
                            data: {
                                labels: jenisLabels,
                                datasets: [{
                                    data: jenisValues,
                                    backgroundColor: doughnutColors.slice(0, jenisLabels.length),
                                    borderWidth: 0,
                                    hoverOffset: 14,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '72%',
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                    tooltip: {
                                        backgroundColor: 'rgba(17,24,39,0.92)',
                                        titleColor: '#f3f4f6',
                                        bodyColor: '#d1d5db',
                                        padding: 10,
                                    }
                                }
                            }
                        });
                    }

                } catch (error) {
                    console.error("Gagal meload grafik:", error);
                }
            });

            // Ganti rentang waktu line chart
            window.switchLineRange = function(range) {
                currentRange = range;
                const rangeData = timeSeriesData[range];
                if (!lineChart || !rangeData) return;

                lineChart.data.labels   = rangeData.labels;
                lineChart.data.datasets = buildLineDatasets(range);
                lineChart.update();
                renderLegend();

                // Update label
                const labelMap = { bulanan: 'Bulanan', mingguan: 'Mingguan', harian: 'Harian' };
                const labelEl = document.getElementById('label-periode-line');
                if (labelEl) labelEl.innerText = 'Rentang: ' + labelMap[range];

                // Update tombol aktif
                document.querySelectorAll('.line-range-btn').forEach(btn => {
                    if (btn.id === 'btn-' + range) {
                        btn.className = 'line-range-btn active-range px-3 py-1.5 rounded-lg text-xs font-bold transition-all bg-[#800000] text-white shadow';
                    } else {
                        btn.className = 'line-range-btn px-3 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800';
                    }
                });
            }

            // Filter kartu ringkasan (Semua/Hari Ini/dll)
            window.applyFilter = function(period) {
                if (!dashboardData || !dashboardData[period]) return;
                const data = dashboardData[period];
                animateValue("count-total",    data.total);
                animateValue("count-menunggu", data.menunggu);
                animateValue("count-diproses", data.diproses);
                animateValue("count-selesai",  data.selesai);

                const labels = {
                    'semua': 'Semua Waktu', 'harian': 'Harian',
                    'mingguan': 'Mingguan', 'bulanan': 'Bulanan'
                };
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    if (btn.dataset.period === period) {
                        btn.className = `filter-btn active flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all bg-white text-[#800000] shadow-md`;
                    } else {
                        btn.className = "filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-white/80 hover:text-white hover:bg-white/20";
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
        <style>
            #count-total, #count-menunggu, #count-diproses, #count-selesai {
                transition: opacity 0.2s ease-in-out;
            }
        </style>
    @endif
@endpush
