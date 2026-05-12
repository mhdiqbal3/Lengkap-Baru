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
                'total' => $filtered->count(),
                'menunggu' => $filtered->where('status', 'Menunggu Verifikasi')->count(),
                'diproses' => $filtered->where('status', 'Sedang Diproses')->count(),
                'selesai' => $filtered->where('status', 'Selesai')->count(),
                'ditolak' => $filtered->where('status', 'Ditolak')->count(),
                'verbal' => $filtered
                    ->filter(
                        fn($i) => stripos($i->jenis_kasus ?? '', 'Verbal') !== false &&
                            stripos($i->jenis_kasus ?? '', 'Non') === false,
                    )
                    ->count(),
                'nonVerbal' => $filtered->filter(fn($i) => stripos($i->jenis_kasus ?? '', 'Non') !== false)->count(),
                'seksual' => $filtered->filter(fn($i) => stripos($i->jenis_kasus ?? '', 'Seksual') !== false)->count(),
                'lainnya' => $filtered
                    ->filter(
                        fn($i) => stripos($i->jenis_kasus ?? '', 'Verbal') === false &&
                            stripos($i->jenis_kasus ?? '', 'Seksual') === false &&
                            stripos($i->jenis_kasus ?? '', 'Non') === false,
                    )
                    ->count(),
            ];
        }

        $isAdmin = Auth::check() && Auth::user()?->role === 'admin';
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

        @if ($isAdmin)
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm space-y-5">
                <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 border-b border-gray-50 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 text-[#800000] rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Ringkasan Keseluruhan Laporan</h3>
                            <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider mt-0.5">Filter data
                                berdasarkan waktu</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 w-full xl:w-auto bg-gray-50 p-2 rounded-xl border border-gray-200">
                        <button type="button" onclick="applyFilter('semua')" data-period="semua"
                            class="filter-btn active flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all {{ $themeBg }} text-white shadow-md">Semua</button>
                        <button type="button" onclick="applyFilter('harian')" data-period="harian"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Hari
                            Ini</button>
                        <button type="button" onclick="applyFilter('mingguan')" data-period="mingguan"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Mingguan</button>
                        <button type="button" onclick="applyFilter('bulanan')" data-period="bulanan"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Bulanan</button>
                        <button type="button" onclick="applyFilter('tahunan')" data-period="tahunan"
                            class="filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200">Tahunan</button>
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
                            <h3 id="count-total" class="text-3xl font-black text-gray-800">{{ $stats['semua']['total'] }}
                            </h3>
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
                            <h3 id="count-menunggu" class="text-3xl font-black text-gray-800">
                                {{ $stats['semua']['menunggu'] }}</h3>
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
                            <h3 id="count-diproses" class="text-3xl font-black text-gray-800">
                                {{ $stats['semua']['diproses'] }}</h3>
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
                            <h3 id="count-selesai" class="text-3xl font-black text-gray-800">
                                {{ $stats['semua']['selesai'] }}</h3>
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
                            <h3 id="count-ditolak" class="text-3xl font-black text-gray-800">
                                {{ $stats['semua']['ditolak'] }}</h3>
                            <p class="text-xs font-bold text-gray-500 mt-1">Ditolak</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-7 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Distribusi Status Penanganan</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5"
                                id="label-periode-bar">Periode: Semua Waktu</p>
                        </div>
                        <div class="p-2 bg-red-50 rounded-xl text-[#800000]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012-2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative w-full h-[300px]">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-7 rounded-3xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Komposisi Kasus</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Jenis Kekerasan
                            </p>
                        </div>
                    </div>
                    <div class="relative w-full h-[300px] flex justify-center items-center">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>

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

            <div
                class="mt-12 mb-6 flex flex-col md:flex-row justify-between items-center gap-4 bg-yellow-50 p-5 rounded-2xl border border-yellow-200">
                <div>
                    <h2 class="text-lg font-black text-gray-800">Tampilan Dashboard Pelapor</h2>
                    <p class="text-sm text-gray-600">Bagian di bawah ini adalah tampilan yang dilihat oleh pelapor. Anda
                        dapat mengubah teksnya secara dinamis.</p>
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

        <div class="mt-8">
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mb-5">
                {{ $d('bentuk_title', 'Kenali Bentuk Kekerasan') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ($bentuk_titles as $index => $title)
                    <div
                        class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-red-100 text-[#800000] rounded-2xl flex items-center justify-center mb-4">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <div class="bg-gradient-to-br from-[#800000] to-red-900 rounded-3xl p-8 text-white shadow-md">
                <h3 class="text-lg md:text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ $d('hak_title', 'Hak Anda Sebagai Pelapor/Korban') }}
                </h3>
                <ul class="space-y-3 text-sm opacity-90">
                    @foreach ($hak_items as $hak)
                        @if (trim($hak) !== '')
                            <li class="flex items-start gap-2"><span> </span> {{ $hak }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#800000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $d('kontak_title', 'Kontak Bantuan & Darurat') }}
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Hotline Satgas (WhatsApp)</p>
                            <p class="text-sm font-bold text-gray-700">{{ $d('kontak_wa', '0812-XXXX-XX') }}</p>
                        </div>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $d('kontak_wa', '0812')) }}"
                            target="_blank"
                            class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.305 1.652zm6.599-3.835c1.516.903 3.003 1.387 4.793 1.388 5.439 0 9.865-4.426 9.868-9.867 0-2.637-1.026-5.112-2.891-6.977-1.864-1.864-4.337-2.891-6.97-2.891-5.442 0-9.866 4.426-9.869 9.868 0 1.908.531 3.448 1.474 5.073l-.951 3.446 3.546-.94z">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Email Resmi</p>
                            <p class="text-sm font-bold text-gray-700">{{ $d('kontak_email', 'satgas.ppks@univ.ac.id') }}
                            </p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-3xl p-8 md:p-10 border border-gray-100 shadow-sm text-center">
            <h3 class="text-xl md:text-2xl font-black text-gray-800 mb-2">
                {{ $d('alur_title', 'Alur Penanganan Laporan') }}</h3>
            <p class="text-sm text-gray-500 mb-8">{{ $d('alur_desc', 'Langkah nyata kami untuk menjaga keamanan Anda.') }}
            </p>

            <div
                class="grid grid-cols-1 md:grid-cols-{{ count($alur_titles) > 0 ? count($alur_titles) : 4 }} gap-8 relative">
                <div class="hidden md:block absolute top-8 left-[10%] right-[10%] h-0.5 bg-gray-100"></div>

                @foreach ($alur_titles as $index => $title)
                    <div class="relative z-10 group">
                        <div
                            class="w-16 h-16 {{ $index == 0 ? 'bg-[#800000] text-white border-white' : 'bg-white text-[#800000] border-[#800000]' }} rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-4 border-4 shadow-md group-hover:scale-110 transition-transform">
                            {{ $index + 1 }}
                        </div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $title }}</h4>
                        <p class="text-[11px] text-gray-500 mt-1 px-4">{{ $alur_descs[$index] ?? '' }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                <a href="{{ url('/laporkan') }}"
                    class="inline-flex items-center gap-3 bg-[#800000] hover:bg-red-900 text-white font-bold py-3 md:py-4 px-8 md:px-10 rounded-full shadow-lg transition transform hover:-translate-y-1 text-sm md:text-base">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Buat Laporan Sekarang
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($isAdmin)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const dashboardData = @json($stats);
            let barChart = null;
            let doughnutChart = null;

            document.addEventListener("DOMContentLoaded", function() {
                try {
                    Chart.defaults.font.family = "'Inter', 'sans-serif'";
                    Chart.defaults.color = '#9ca3af';

                    const ctxBar = document.getElementById('barChart');
                    if (ctxBar) {
                        barChart = new Chart(ctxBar.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'],
                                datasets: [{
                                    label: 'Jumlah Laporan',
                                    data: [
                                        dashboardData['semua'].menunggu,
                                        dashboardData['semua'].diproses,
                                        dashboardData['semua'].selesai,
                                        dashboardData['semua'].ditolak
                                    ],
                                    backgroundColor: ['#EAB308', '#F97316', '#22C55E', '#EF4444'],
                                    borderRadius: 8,
                                    barPercentage: 0.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });
                    }

                    const ctxDoughnut = document.getElementById('doughnutChart');
                    if (ctxDoughnut) {
                        doughnutChart = new Chart(ctxDoughnut.getContext('2d'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Verbal', 'Non-Verbal', 'Seksual', 'Lainnya'],
                                datasets: [{
                                    data: [
                                        dashboardData['semua'].verbal,
                                        dashboardData['semua'].nonVerbal,
                                        dashboardData['semua'].seksual,
                                        dashboardData['semua'].lainnya
                                    ],
                                    backgroundColor: ['#14B8A6', '#A855F7', '#800000', '#F59E0B'],
                                    borderWidth: 0,
                                    hoverOffset: 12
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            usePointStyle: true,
                                            boxWidth: 8
                                        }
                                    }
                                }
                            }
                        });
                    }
                } catch (error) {
                    console.error("Gagal meload grafik:", error);
                }
            });

            window.applyFilter = function(period) {
                if (!dashboardData || !dashboardData[period]) return;
                const data = dashboardData[period];
                animateValue("count-total", data.total);
                animateValue("count-menunggu", data.menunggu);
                animateValue("count-diproses", data.diproses);
                animateValue("count-selesai", data.selesai);
                animateValue("count-ditolak", data.ditolak);

                const labels = {
                    'semua': 'Semua Waktu',
                    'harian': 'Hari Ini',
                    'mingguan': 'Minggu Ini',
                    'bulanan': 'Bulan Ini',
                    'tahunan': 'Tahun Ini'
                };
                const labelEl = document.getElementById('label-periode-bar');
                if (labelEl) labelEl.innerText = 'Periode: ' + labels[period];

                document.querySelectorAll('.filter-btn').forEach(btn => {
                    if (btn.dataset.period === period) {
                        btn.className =
                            `filter-btn active flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all bg-[#800000] text-white shadow-md`;
                    } else {
                        btn.className =
                            "filter-btn flex-1 sm:flex-none px-4 py-2 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-800 hover:bg-gray-200";
                    }
                });

                if (barChart && doughnutChart) {
                    barChart.data.datasets[0].data = [data.menunggu, data.diproses, data.selesai, data.ditolak];
                    barChart.update();
                    doughnutChart.data.datasets[0].data = [data.verbal, data.nonVerbal, data.seksual, data.lainnya];
                    doughnutChart.update();
                }
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
            #count-total,
            #count-menunggu,
            #count-diproses,
            #count-selesai,
            #count-ditolak {
                transition: opacity 0.2s ease-in-out;
            }
        </style>
    @endif
@endpush
