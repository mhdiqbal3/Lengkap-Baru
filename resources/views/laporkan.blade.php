@extends ('layouts.app')
@section('content')
    @php
        $dataPeraturan =
            isset($kontenPeraturan) && !empty($kontenPeraturan->konten)
                ? json_decode($kontenPeraturan->konten, true)
                : [];
        $peraturan_items = $dataPeraturan['peraturan_items'] ?? [
            [
                'nomor' => '30',
                'tahun' => 'Permendikbudristek 2021',
                'judul' => 'Pencegahan dan Penanganan Kekerasan Seksual (PPKS)',
                'deskripsi' =>
                    'Menjamin hak warga kampus atas pendidikan yang aman, penanganan kasus berperspektif korban dan mengutamakan kerahasiaan.',
                'file_url' => 'assets/aturan/TAHUN 2021.pdf',
            ],
            [
                'nomor' => '17',
                'tahun' => 'Permendikbudristek Tahun 2022',
                'judul' => 'Pedoman Lingkungan Inklusif dan Aman',
                'deskripsi' =>
                    'Mengatur komitmen institusi dalam menyelenggarakan pendidikan yang bebas kekerasan, mendorong tindakan proaktif.',
                'file_url' => 'assets/aturan/TAHUN 2022.pdf',
            ],
        ];
    @endphp

    <div class="max-w-5xl mx-auto" x-data="{
        showPengaduan: false,
        isAnonim: false,
        showSuccess: false,
        isLoading: false,
        kodeTiket: '',
        fileName: '',
        submitLaporan(event) {
            this.isLoading = true;
            let formData = new FormData(event.target);
    
            fetch(event.target.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.isLoading = false;
                    if (data.status === 'success') {
                        this.kodeTiket = data.kode_tiket;
                        this.showPengaduan = false;
                        this.showSuccess = true;
                        event.target.reset();
                        this.fileName = '';
                    } else {
                        alert('Gagal mengirim laporan: ' + (data.message || 'Periksa kembali isian Anda.'));
                    }
                })
                .catch(error => {
                    this.isLoading = false;
                    alert('Terjadi kesalahan pada sistem.');
                    console.error(error);
                });
        }
    }">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Layanan Pengaduan
                </h1>
                <p class="text-gray-600">Satgas PPKPT Universitas Sembilanbelas November Kolaka</p>
            </div>
            <button @click="showPengaduan = true"
                class="hidden md:flex px-6 py-2.5 bg-[#800000] text-white font-medium rounded-lg hover:bg-red-900 transition shadow-sm items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Laporan Baru
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <section id="peraturan" class="py-12 lg:py-16 bg-white" x-data="{ showPdfModal: false, pdfUrl: '', pdfTitle: '' }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                    <div class="max-w-2xl">
                        <span class="text-[#800000] text-sm font-black uppercase tracking-widest">Dasar Hukum</span>
                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mt-2 tracking-tight">Peraturan yang
                            Berlaku</h2>
                    </div>
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('informasi.peraturan.edit') }}"
                            class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Peraturan
                        </a>
                    @endif
                </div>

                <!-- Loop Dinamis Peraturan -->
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach ($peraturan_items as $item)
                        <button
                            @click="showPdfModal = true; pdfTitle = '{{ addslashes($item['judul']) }}'; pdfUrl = '{{ asset($item['file_url']) }}'"
                            class="w-full text-left flex flex-col sm:flex-row bg-white border border-gray-200 rounded-[2rem] p-6 shadow-sm hover:shadow-md hover:border-[#800000]/30 transition-all gap-5 items-start focus:outline-none">
                            <div
                                class="w-14 h-14 shrink-0 {{ $loop->iteration % 2 == 0 ? 'bg-[#800000]' : 'bg-gray-900' }} text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-lg {{ $loop->iteration % 2 == 0 ? 'shadow-red-900/20' : '' }}">
                                {{ $item['nomor'] }}
                            </div>
                            <div>
                                <div
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                                    {{ $item['tahun'] }}
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-black text-gray-900 mb-2 leading-snug group-hover:text-[#800000]">
                                    {{ $item['judul'] }}</h3>
                                <p class="text-gray-600 text-sm font-medium leading-relaxed">{{ $item['deskripsi'] }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>

                <div x-show="showPdfModal" style="display: none;"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                    x-transition.opacity>
                    <div class="absolute inset-0" @click="showPdfModal = false"></div>
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col relative z-10 transform transition-all"
                        x-transition.scale>
                        <div
                            class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-100 text-[#800000] rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-800 text-lg" x-text="pdfTitle"></h3>
                            </div>
                            <button @click="showPdfModal = false"
                                class="text-gray-400 hover:text-red-500 bg-white p-1.5 rounded-md shadow-sm border border-gray-200 transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 bg-gray-200 rounded-b-2xl overflow-hidden relative">
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 gap-3">
                                <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Memuat Dokumen...</span>
                            </div>
                            <iframe :src="pdfUrl" class="w-full h-full relative z-10 border-none"
                                title="Dokumen Peraturan"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Form Laporan & Kode Tiket Hasil -->
        <div x-show="showPengaduan" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm"
            x-transition.opacity>
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] flex flex-col overflow-hidden"
                x-transition.scale @click.away="showPengaduan = false">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="text-xl font-bold text-gray-800">Formulir Laporan Pengaduan</h2>
                    <button @click="showPengaduan = false"
                        class="text-gray-400 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto custom-scroll">
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-bold text-blue-800">Privasi Anda Terjamin</h3>
                                <p class="text-sm text-blue-700 mt-1">Identitas pelapor dan korban akan dirahasiakan
                                    sepenuhnya
                                    sesuai mandat undang-undang. Anda juga berhak melapor secara anonim.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('lapor.store') }}" method="POST" enctype="multipart/form-data"
                        @submit.prevent="submitLaporan($event)">
                        @csrf
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Judul Laporan <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="judul_lapor" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none transition"
                                        placeholder="Contoh: Pelecehan di Kantin">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Kasus <span
                                            class="text-red-500">*</span></label>
                                    <select required name="jenis_kasus"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                        <option value="">-- Pilih Jenis Kasus --</option>
                                        <option value="Kekerasan Seksual">Kekerasan Seksual</option>
                                        <option value="Kekerasan Fisik">Kekerasan Fisik</option>
                                        <option value="Kekerasan Psikis">Kekerasan Psikis</option>
                                        <option value="Perundungan">Perundungan</option>
                                        <option value="Diskriminasi dan intoleransi">Diskriminasi dan Intoleransi</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama Korban <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="nama_korban" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none transition"
                                        placeholder="Masukkan nama lengkap korban">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Nomor HP Korban <span
                                            class="text-red-500">*</span></label>
                                    <input type="tel" name="no_hp_korban" required
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none transition"
                                        placeholder="08xx-xxxx-xxxx">
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Status Korban <span
                                            class="text-red-500">*</span></label>
                                    <select required name="status_korban"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="staff">Staff / Tendik</option>
                                        <option value="masyarakat_umum">Masyarakat Umum</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Jenis Kelamin <span
                                            class="text-red-500">*</span></label>
                                    <select required name="jenis_kelamin"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Disabilitas? <span
                                            class="text-red-500">*</span></label>
                                    <select required name="disabilitas"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                        <option value="">-- Pilih --</option>
                                        <option value="tidak">Tidak</option>
                                        <option value="ya">Ya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Status Terlapor (Pelaku)
                                        <span class="text-red-500">*</span></label>
                                    <select required name="status_terlapor"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="staff">Staff / Tendik</option>
                                        <option value="masyarakat_umum">Masyarakat Umum</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="border-gray-100">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Kejadian <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" required name="tanggal_kejadian"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700">Lokasi Kejadian <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" required name="lokasi_kejadian"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none"
                                        placeholder="Contoh: Gedung Rektorat Lt. 2">
                                </div>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi Kejadian <span
                                        class="text-red-500">*</span></label>
                                <textarea rows="4" required name="deskripsi"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-3 outline-none"
                                    placeholder="Ceritakan peristiwa secara detail..."></textarea>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Link Video Kejadian
                                    (Opsional)</label>
                                <input type="url" name="link_video"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#800000] focus:border-[#800000] block w-full p-2.5 outline-none transition"
                                    placeholder="Contoh: https://drive.google.com/... atau https://youtube.com/...">
                                <p class="mt-1 text-xs text-gray-500">Sertakan link Google Drive atau YouTube jika Anda
                                    memiliki bukti berupa video.</p>
                            </div>

                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Unggah Bukti Gambar
                                    (Opsional)</label>
                                <div class="flex items-center justify-center w-full relative">
                                    <label for="dropzone-file"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3-3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="mb-1 text-sm text-gray-500" x-show="!fileName"><span
                                                    class="font-semibold">Klik unggah</span> atau lepas file</p>
                                            <p class="mb-1 text-sm font-bold text-[#800000] truncate max-w-xs"
                                                x-show="fileName" x-text="fileName"></p>
                                            <p class="text-xs text-gray-400">JPEG, PNG, JPG (Max. 5MB)</p>
                                        </div>
                                        <input id="dropzone-file" name="bukti" type="file"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            @change="fileName = $event.target.files.length ? $event.target.files[0].name : ''"
                                            accept="image/jpeg, image/png, image/jpg" />
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-4">
                                <button type="button" @click="showPengaduan = false"
                                    class="px-6 py-2.5 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition shadow-sm text-center">Batal</button>
                                <button type="submit" :disabled="isLoading"
                                    class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-md flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!isLoading" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    <svg x-show="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        style="display: none;">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span x-text="isLoading ? 'Mengirim...' : 'Laporkan'"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="showSuccess" style="display: none;"
            class="fixed inset-0 z-[110] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm" x-transition>
            <div
                class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden transform transition-all text-center">
                <div class="bg-green-500 p-6 flex justify-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Terkirim!</h3>
                    <p class="text-gray-600 text-sm mb-4">Laporan pengaduan Anda berhasil masuk ke sistem Satgas PPKS.
                        Berikut adalah Nomor Tiket Anda:</p>
                    <div class="bg-gray-100 py-3 rounded-xl mb-6">
                        <p class="text-[10px] text-gray-500 mb-1 uppercase tracking-widest font-bold">KODE TIKET</p>
                        <p class="text-2xl font-black text-[#800000]" x-text="kodeTiket"></p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="{{ url('/riwayat') }}"
                            class="w-full px-4 py-2.5 bg-[#800000] text-white font-medium rounded-lg hover:bg-red-900 transition shadow-sm">Lihat
                            Riwayat Laporan</a>
                        <button @click="showSuccess = false" type="button"
                            class="w-full px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
