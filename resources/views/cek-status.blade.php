@if (!request()->ajax())
    @extends('layouts.app')
    @section('header_title', 'Cek Status Laporan')
    @section('content')
    @endif

    @php
        $showModal = isset($laporan) || isset($error) ? 'true' : 'false';
    @endphp

    <div class="max-w-4xl mx-auto pb-10" x-data="{ showModal: {{ $showModal }} }">
        <div class="mb-8 text-center sm:text-left">
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Lacak Status Pengaduan</h2>
            <p class="text-gray-500 text-sm mt-2 font-medium leading-relaxed max-w-2xl">
                Masukkan Kode Tiket yang Anda dapatkan saat melapor untuk melacak progres penanganan kasus.
            </p>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-4 sm:p-8 mb-8 relative z-20">
            <form action="{{ route('cek-status.cari') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-center">
                @csrf
                <div class="w-full relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="kode_tiket" value="{{ old('kode_tiket', request('kode_tiket')) }}" required
                        class="w-full pl-14 pr-4 py-4 sm:py-5 bg-gray-50 border border-gray-200 text-gray-900 text-lg font-bold rounded-2xl focus:ring-4 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white outline-none transition-all uppercase placeholder-normal-case placeholder:font-medium placeholder:text-gray-400 shadow-inner"
                        placeholder="Contoh: PPKS-001">
                </div>
                <button type="submit"
                    class="w-full md:w-auto px-10 py-4 sm:py-5 bg-[#800000] text-white font-extrabold text-lg rounded-2xl hover:bg-red-900 transition-all shadow-xl shadow-red-900/20 active:scale-95 whitespace-nowrap flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Lacak Status
                </button>
            </form>

            @error('kode_tiket')
                <p class="text-red-500 text-sm mt-3 font-bold ml-2 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div x-show="showModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
            x-transition.opacity>
            <div class="absolute inset-0" @click="showModal = false"></div>

            @if (isset($laporan))
                <div id="modal-content-laporan"
                    class="bg-white rounded-[2rem] shadow-2xl max-w-3xl w-full relative z-10 flex flex-col max-h-full overflow-hidden transform transition-all"
                    x-transition.scale>
                    <button @click="showModal = false"
                        class="absolute top-4 right-4 z-50 p-2 bg-black/20 hover:bg-black/40 text-white rounded-full backdrop-blur-md transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="bg-gradient-to-r from-[#800000] to-red-900 p-6 sm:p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative shrink-0">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl">
                        </div>
                        <div class="absolute bottom-0 right-20 w-16 h-16 rounded-full bg-white opacity-10 blur-xl"></div>
                        <div class="relative z-10">
                            <span
                                class="text-red-100 text-xs font-bold uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>
                                </svg>
                                Kartu Tiket Laporan
                            </span>
                            <h3 class="text-3xl font-black tracking-wider drop-shadow-md">{{ $laporan->kode_tiket }}</h3>
                        </div>
                        @php
                            $statusStyles = [
                                'Menunggu Verifikasi' => 'bg-yellow-400 text-yellow-900 border-yellow-300',
                                'Sedang Diproses' => 'bg-blue-400 text-blue-900 border-blue-300',
                                'Selesai' => 'bg-green-400 text-green-900 border-green-300',
                                'Ditolak' => 'bg-gray-200 text-gray-800 border-gray-300',
                            ];
                            $badgeStyle = $statusStyles[$laporan->status] ?? 'bg-white text-gray-800';
                        @endphp
                        <div
                            class="relative z-10 px-5 py-2.5 rounded-xl border-2 font-extrabold text-sm shadow-lg {{ $badgeStyle }}">
                            {{ $laporan->status }}
                        </div>
                    </div>

                    <div class="overflow-y-auto custom-scroll p-0 relative">
                        <div class="px-6 sm:px-10 pt-10 pb-6 border-b border-gray-100 bg-gray-50/50">
                            <h4 class="text-center font-extrabold text-gray-400 mb-8 uppercase tracking-widest text-xs">
                                Jalur Penanganan Laporan</h4>
                            <div class="relative flex items-center justify-between w-full max-w-lg mx-auto">
                                <div
                                    class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1.5 bg-gray-200 rounded-full">
                                </div>
                                @php
                                    $progressWidth = '0%';
                                    if ($laporan->status == 'Sedang Diproses') {
                                        $progressWidth = '50%';
                                    }
                                    if ($laporan->status == 'Selesai' || $laporan->status == 'Ditolak') {
                                        $progressWidth = '100%';
                                    }
                                    $progressColor = $laporan->status == 'Ditolak' ? 'bg-red-500' : 'bg-[#800000]';
                                @endphp
                                <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1.5 {{ $progressColor }} rounded-full transition-all duration-1000"
                                    style="width: {{ $progressWidth }}"></div>

                                <div class="relative z-10 flex flex-col items-center group">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors bg-[#800000] border-red-200 text-white shadow-md">
                                        1</div>
                                    <p
                                        class="absolute top-12 text-[11px] font-bold text-[#800000] whitespace-nowrap text-center">
                                        Menunggu<br>Verifikasi</p>
                                </div>

                                @php
                                    $step2Active = in_array($laporan->status, [
                                        'Sedang Diproses',
                                        'Selesai',
                                        'Ditolak',
                                    ]);
                                    $step2Bg = $step2Active
                                        ? 'bg-[#800000] border-red-200 text-white shadow-md'
                                        : 'bg-white border-gray-300 text-gray-400';
                                    $step2Text = $step2Active ? 'text-[#800000]' : 'text-gray-400';
                                @endphp
                                <div class="relative z-10 flex flex-col items-center group">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors {{ $step2Bg }}">
                                        2</div>
                                    <p
                                        class="absolute top-12 text-[11px] font-bold {{ $step2Text }} whitespace-nowrap text-center">
                                        Sedang<br>Diproses</p>
                                </div>

                                @php
                                    $step3Active = in_array($laporan->status, ['Selesai', 'Ditolak']);
                                    if ($step3Active && $laporan->status == 'Ditolak') {
                                        $step3Bg = 'bg-red-500 border-red-200 text-white shadow-md';
                                        $step3Text = 'text-red-600';
                                        $icon = 'X';
                                    } elseif ($step3Active && $laporan->status == 'Selesai') {
                                        $step3Bg = 'bg-green-500 border-green-200 text-white shadow-md';
                                        $step3Text = 'text-green-600';
                                        $icon = '3';
                                    } else {
                                        $step3Bg = 'bg-white border-gray-300 text-gray-400';
                                        $step3Text = 'text-gray-400';
                                        $icon = '3';
                                    }
                                @endphp
                                <div class="relative z-10 flex flex-col items-center group">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold border-4 transition-colors {{ $step3Bg }}">
                                        {{ $icon }}</div>
                                    <p
                                        class="absolute top-12 text-[11px] font-bold {{ $step3Text }} whitespace-nowrap text-center">
                                        {{ $laporan->status == 'Ditolak' ? 'Ditolak' : 'Selesai' }}</p>
                                </div>
                            </div>
                            <div class="h-10"></div>
                        </div>

                        <div class="px-6 sm:px-10 py-8">
                            <h4 class="font-extrabold text-gray-800 text-lg mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#800000]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Rincian Data Formulir
                            </h4>
                            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <table class="w-full text-sm text-left text-gray-600">
                                    <tbody class="divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="w-1/3 px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Waktu
                                                Pelaporan</th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($laporan->created_at)->format('d F Y - H:i') }}
                                                WIB</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Judul Pengaduan
                                            </th>
                                            <td class="px-6 py-4 font-black text-gray-900 text-base">
                                                {{ $laporan->judul_lapor }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Jenis Kekerasan
                                            </th>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold rounded-md text-xs border border-purple-200">{{ strtoupper($laporan->jenis_kasus) }}</span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Nama Pelapor/Korban
                                            </th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                @if ($laporan->is_anonim)
                                                    <span class="italic text-gray-500">Anonim (Dirahasiakan)</span>
                                                @else
                                                    {{ $laporan->nama_korban }}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Status di Kampus
                                            </th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ ucfirst($laporan->status_korban) }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Nomor Handphone
                                            </th>
                                            <td class="px-6 py-4 font-medium text-gray-900">{{ $laporan->no_hp_korban }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Jenis Kelamin</th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $laporan->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Disabilitas</th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ ucfirst($laporan->disabilitas) }}</td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Tanggal Kejadian
                                            </th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d F Y') }}
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <th class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700">Lokasi Kejadian
                                            </th>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $laporan->lokasi_kejadian }}</td>
                                        </tr>
                                        <tr>
                                            <th
                                                class="px-6 py-4 bg-gray-50/50 font-bold text-gray-700 border-b-0 align-top">
                                                Deskripsi & Kronologi</th>
                                            <td class="px-6 py-4 font-medium text-gray-700">
                                                {{ $laporan->deskripsi }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div
                            class="px-6 sm:px-10 pb-8 flex flex-col sm:flex-row justify-center gap-3 border-t border-white pt-6">
                            @if ($laporan->bukti)
                                @if (request()->ajax())
                                    <a href="{{ asset($laporan->bukti) }}" target="_blank"
                                        class="px-8 py-3.5 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors focus:outline-none text-center">
                                        Buka Gambar Bukti Laporan
                                    </a>
                                @else
                                    <button @click="showDetail = false; setTimeout(() => showBukti = true, 300)"
                                        class="px-8 py-3.5 bg-blue-50 text-blue-700 font-bold rounded-xl border border-blue-100 hover:bg-blue-100 transition-colors focus:outline-none text-center">
                                        Lihat Gambar Bukti Laporan
                                    </button>
                                @endif
                            @endif

                            <button @click="showModal = false"
                                class="px-8 py-3.5 bg-[#800000] text-white hover:bg-red-200 font-bold rounded-xl transition-colors text-center">
                                Tutup Kartu Laporan
                            </button>
                        </div>
                    </div>
                </div>
            @elseif (isset($error))
                <div id="modal-content-error"
                    class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full relative z-10 flex flex-col overflow-hidden transform transition-all text-center p-8"
                    x-transition.scale>
                    <button @click="showModal = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div
                        class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-red-100">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 mb-3">Laporan Tidak Ditemukan</h3>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed mb-8">
                        Mohon periksa kembali Kode Tiket Anda. Pastikan kode diketik dengan benar menggunakan format <strong
                            class="text-red-600">PPKS-XXX</strong>.
                    </p>
                    <button @click="showModal = false"
                        class="w-full py-3.5 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition-colors shadow-lg shadow-red-900/20 active:scale-95">
                        Coba Lagi
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if (!request()->ajax())
    @endsection
@endif
