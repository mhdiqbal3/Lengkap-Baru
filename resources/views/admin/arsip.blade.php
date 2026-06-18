@extends('layouts.app')

@section('header_title', 'Arsip Kegiatan')

@section('content')
    <style>
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
    @php
        $promoPath = public_path('assets/image/promo');
        $promos = [];
        if (\Illuminate\Support\Facades\File::exists($promoPath)) {
            $files = \Illuminate\Support\Facades\File::files($promoPath);
            foreach ($files as $file) {
                $promos[] = [
                    'nama' => $file->getFilename(),
                    'url' => asset('assets/image/promo/' . $file->getFilename()),
                ];
            }
        }
    @endphp

    <div class="max-w-[100%] mx-auto pb-10">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Arsip & Publikasi Kegiatan</h2>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Kelola data kegiatan Satgas PPKT.</p>
            </div>
            
            {{-- Tombol Manajemen Promo yang Sudah Diperbaiki --}}
            <button type="button" onclick="bukaModal('modalPromo')" 
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#f7b500] text-white text-sm font-bold rounded-xl hover:bg-yellow-600 transition-all shadow-md active:scale-95 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Poster Pop-up
            </button>
        </div>

        <div id="btnTambahContainer" class="hidden">
            <button onclick="bukaModal('modalTambah')" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 transition-all shadow-md whitespace-nowrap">
                Tambah Kegiatan Baru
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col p-6">
            <div class="overflow-x-auto custom-scroll flex-1 relative w-full">
                {{-- TABEL MURNI TANPA ALPINE JS --}}
                <table id="tableArsip" class="w-full text-sm text-left text-gray-600 min-w-[1200px] mt-4">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center w-16">No</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Judul Kegiatan</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Jenis</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap text-center">Tanggal</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Lokasi</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider whitespace-nowrap">Deskripsi Singkat</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap">Dokumentasi</th>
                            <th scope="col" class="px-6 py-5 font-bold tracking-wider text-center whitespace-nowrap sticky right-0 bg-gray-50 z-30 border-l border-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if (isset($arsips))
                            @foreach ($arsips as $index => $item)
                                <tr class="bg-white hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4 text-center font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-800 min-w-[200px]">{{ $item->judul_kegiatan ?? $item->judul }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ ucfirst($item->jenis_kegiatan ?? $item->jenis) }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap text-gray-600">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $item->lokasi }}</td>
                                    <td class="px-6 py-4 text-gray-500 truncate max-w-xs">
                                        {{-- Bypass Error mb_strimwidth menggunakan strlen & substr bawaan PHP --}}
                                        {{ strlen($item->deskripsi) > 40 ? substr($item->deskripsi, 0, 40) . '...' : $item->deskripsi }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($item->dokumentasi)
                                            <button type="button" 
                                                onclick="lihatBukti('{{ addslashes($item->judul_kegiatan ?? $item->judul) }}', '{{ asset($item->dokumentasi) }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors shadow-sm">
                                                Lihat Gambar
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded border border-gray-100">Tidak ada</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 transition-colors text-center sticky right-0 bg-white group-hover:bg-gray-50 z-20 border-l border-gray-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" 
                                                onclick="editArsip('{{ $item->id }}')"
                                                id="data-edit-{{ $item->id }}"
                                                data-judul="{{ $item->judul_kegiatan ?? $item->judul }}"
                                                data-jenis="{{ $item->jenis_kegiatan ?? $item->jenis }}"
                                                data-tanggal="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                                data-lokasi="{{ $item->lokasi }}"
                                                data-lokasi="{{ $item->lokasi }}"
                                                data-deskripsi="{{ $item->deskripsi }}"
                                                data-dokumentasi="{{ $item->dokumentasi ? asset($item->dokumentasi) : '' }}"
                                                data-update="{{ route('arsip.update', $item->id) }}"
                                                class="p-2 text-yellow-600 bg-yellow-50 hover:bg-yellow-500 hover:text-white rounded-lg transition-colors border border-yellow-100 shadow-sm" title="Edit Arsip">Edit</button>
                                            
                                            <button type="button" 
                                                onclick="hapusArsip('{{ addslashes($item->judul_kegiatan ?? $item->judul) }}', '{{ route('arsip.destroy', $item->id) }}')"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100 shadow-sm" title="Hapus Arsip">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <div id="modalTambah" class="hidden fixed inset-0 z-[9999] bg-gray-900/60 backdrop-blur-sm px-4 items-center justify-center">
        <div class="absolute inset-0" onclick="tutupModal('modalTambah')"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col relative z-10">
            <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                <h2 class="text-xl font-bold text-gray-800">Tambah Arsip Baru</h2>
                <button type="button" onclick="tutupModal('modalTambah')" class="text-gray-400 hover:text-red-500 p-2">X</button>
            </div>
            <div class="p-8 overflow-y-auto custom-scroll">
                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 px-5 py-4 rounded-xl mb-6">
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Judul <span class="text-red-500">*</span></label><input type="text" name="judul_kegiatan" required class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700">Jenis <span class="text-red-500">*</span></label>
                                <select required name="jenis_kegiatan" onchange="
                                    let l = document.getElementById('tambahLainnya'); 
                                    let i = document.getElementById('tambahJenisLainnya');
                                    if(this.value === 'Lainnya') { l.style.display = 'block'; i.setAttribute('required', 'required'); } 
                                    else { l.style.display = 'none'; i.removeAttribute('required'); }
                                " class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none">
                                    <option value="Seminar">Seminar</option>
                                    <option value="Kampanye">Kampanye</option>
                                    <option value="Poster">Poster</option>
                                    <option value="Rapat Koordinasi">Rapat Koordinasi</option>
                                    <option value="MOU">MOU</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div id="tambahLainnya" style="display: none;" class="mt-2">
                                    <input type="text" id="tambahJenisLainnya" name="jenis_kegiatan_lainnya" placeholder="Sebutkan jenis kegiatan..." class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Tanggal <span class="text-red-500">*</span></label><input type="date" required name="tanggal" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Lokasi <span class="text-red-500">*</span></label><input type="text" required name="lokasi" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                        </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Deskripsi <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-400">(Maks. 800 kata)</span></label>
                            <textarea rows="3" required name="deskripsi" maxlength="5000" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Unggah Gambar (Opsional)</label>
                            <input name="dokumentasi" type="file" accept="image/*" class="border border-gray-200 w-full p-2 rounded-xl text-sm bg-gray-50">
                        </div>
                        <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                            <button type="button" onclick="tutupModal('modalTambah')" class="px-6 py-3 bg-gray-100 font-bold rounded-xl">Batal</button>
                            <button type="submit" class="px-6 py-3 bg-[#800000] text-white font-bold rounded-xl">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="modalEdit" class="hidden fixed inset-0 z-[9999] bg-gray-900/60 backdrop-blur-sm px-4 items-center justify-center">
        <div class="absolute inset-0" onclick="tutupModal('modalEdit')"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col relative z-10">
            <div class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                <h2 class="text-xl font-bold text-gray-800">Edit Data Arsip</h2>
                <button type="button" onclick="tutupModal('modalEdit')" class="text-gray-400 hover:text-red-500 p-2">X</button>
            </div>
            <div class="p-8 overflow-y-auto custom-scroll">
                <form id="formEdit" action="" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Judul <span class="text-red-500">*</span></label><input type="text" id="editJudul" name="judul_kegiatan" required class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700">Jenis <span class="text-red-500">*</span></label>
                                <select required id="editJenis" name="jenis_kegiatan" onchange="
                                    let l = document.getElementById('editLainnya'); 
                                    let i = document.getElementById('editJenisLainnya');
                                    if(this.value === 'Lainnya') { l.style.display = 'block'; i.setAttribute('required', 'required'); } 
                                    else { l.style.display = 'none'; i.removeAttribute('required'); }
                                " class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none">
                                    <option value="Seminar">Seminar</option>
                                    <option value="Kampanye">Kampanye</option>
                                    <option value="Poster">Poster</option>
                                    <option value="Rapat Koordinasi">Rapat Koordinasi</option>
                                    <option value="MOU">MOU</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div id="editLainnya" style="display: none;" class="mt-2">
                                    <input type="text" id="editJenisLainnya" name="jenis_kegiatan_lainnya" placeholder="Sebutkan jenis kegiatan..." class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Tanggal <span class="text-red-500">*</span></label><input type="date" id="editTanggal" required name="tanggal" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                            <div><label class="block mb-2 text-sm font-bold text-gray-700">Lokasi <span class="text-red-500">*</span></label><input type="text" id="editLokasi" required name="lokasi" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></div>
                        </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Deskripsi <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-400">(Maks. 800 kata)</span></label>
                            <textarea rows="3" id="editDeskripsi" required name="deskripsi" maxlength="5000" class="border border-gray-200 text-sm rounded-xl w-full p-3 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Ganti Gambar (Opsional)</label>
                            <input name="dokumentasi" type="file" accept="image/*" class="border border-gray-200 w-full p-2 rounded-xl text-sm bg-gray-50">
                            <p class="text-xs text-gray-400 mt-2">Abaikan jika tidak ingin mengubah gambar.</p>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" onclick="tutupModal('modalEdit')" class="px-6 py-3 bg-gray-100 font-bold rounded-xl">Batal</button>
                            <button type="submit" class="px-6 py-3 bg-[#f7b500] text-white font-bold rounded-xl">Simpan Pembaruan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Bukti Gambar --}}
    <div id="modalBukti" class="hidden fixed inset-0 z-[9999] bg-gray-900/80 backdrop-blur-sm px-4 items-center justify-center">
        <div class="absolute inset-0" onclick="tutupModal('modalBukti')"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full p-6 flex flex-col relative z-10 text-center">
            <h3 class="text-lg font-black text-gray-800 mb-4" id="buktiJudul"></h3>
            <div class="bg-gray-100 p-4 rounded-xl flex justify-center mb-4">
                <img id="buktiImg" src="" class="max-h-[50vh] object-contain rounded-lg">
            </div>
            <button type="button" onclick="tutupModal('modalBukti')" class="px-6 py-3 bg-gray-100 font-bold rounded-xl w-full">Tutup</button>
        </div>
    </div>

    {{-- Modal Hapus --}}
    <div id="modalHapus" class="hidden fixed inset-0 z-[9999] bg-gray-900/80 backdrop-blur-sm px-4 items-center justify-center">
        <div class="absolute inset-0" onclick="tutupModal('modalHapus')"></div>
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 relative z-10 text-center">
            <h3 class="text-2xl font-black text-gray-900 mb-2">Hapus Arsip?</h3>
            <p class="text-gray-500 text-sm mb-6" id="hapusJudul"></p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="tutupModal('modalHapus')" class="px-6 py-3 bg-gray-100 font-bold rounded-xl w-full">Batal</button>
                <form id="formHapus" action="" method="POST" class="w-full">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl w-full">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Manajemen Promo (Form Lengkap) --}}
    <div id="modalPromo" class="hidden fixed inset-0 z-[9999] bg-gray-900/80 backdrop-blur-sm p-4 items-center justify-center">
        <div class="absolute inset-0" onclick="tutupModal('modalPromo')"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col relative z-10">
            <div class="px-6 py-4 border-b border-gray-100 bg-yellow-50 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-yellow-800 text-lg">Manajemen Poster Pop-up</h3>
                <button type="button" onclick="tutupModal('modalPromo')" class="text-gray-400 hover:text-red-500 bg-white p-1.5 rounded-lg border border-gray-200">X</button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scroll">
                <form action="{{ route('promo.upload') }}" method="POST" enctype="multipart/form-data" class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200 shadow-sm">
                    @csrf
                    <p class="text-sm font-bold text-gray-800 mb-3">Unggah Poster Pop-up Baru</p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 bg-white border border-gray-300 rounded-lg p-2" required>
                        <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition shadow">Upload</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 font-medium">Poster ini akan langsung muncul sebagai pop-up di Halaman Depan.</p>
                </form>
                
                <p class="text-sm font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Daftar Poster Saat Ini:</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @if (isset($promos) && count($promos) > 0)
                        @foreach ($promos as $index => $promo)
                            <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm aspect-[3/4]">
                                <img src="{{ $promo['url'] }}" class="w-full h-full object-contain bg-gray-100">
                                <form action="{{ route('promo.hapus') }}" method="POST" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    @csrf
                                    <input type="hidden" name="nama_file" value="{{ $promo['nama'] }}">
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus poster ini?')" class="bg-red-500 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-red-600 shadow-md transform hover:scale-105 transition-transform">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-full py-8 text-center text-gray-400 text-sm border-2 border-dashed border-gray-200 rounded-xl">Belum ada poster yang diaktifkan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- SCRIPT UNTUK EXPORT EXCEL DATATABLES --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        // Fungsi Native Buka/Tutup Modal
        function bukaModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }
        function tutupModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        // Fungsi Isi Data Modal
        function lihatBukti(judul, url) {
            document.getElementById('buktiJudul').innerText = judul;
            document.getElementById('buktiImg').src = url;
            bukaModal('modalBukti');
        }

        function hapusArsip(judul, url) {
            document.getElementById('hapusJudul').innerText = "Yakin hapus: " + judul + "?";
            document.getElementById('formHapus').action = url;
            bukaModal('modalHapus');
        }

        function editArsip(id) {
            let el = document.getElementById('data-edit-' + id);
            document.getElementById('formEdit').action = el.getAttribute('data-update');
            document.getElementById('editJudul').value = el.getAttribute('data-judul');
            
            let jenis = el.getAttribute('data-jenis');
            let editJenis = document.getElementById('editJenis');
            let std = ['Seminar', 'Kampanye', 'Poster', 'Rapat Koordinasi', 'MOU'];
            if(std.includes(jenis)) {
                editJenis.value = jenis;
                document.getElementById('editLainnya').style.display = 'none';
                document.getElementById('editJenisLainnya').value = '';
                document.getElementById('editJenisLainnya').removeAttribute('required');
            } else {
                editJenis.value = 'Lainnya';
                document.getElementById('editLainnya').style.display = 'block';
                document.getElementById('editJenisLainnya').value = jenis;
                document.getElementById('editJenisLainnya').setAttribute('required', 'required');
            }
            
            document.getElementById('editTanggal').value = el.getAttribute('data-tanggal');
            document.getElementById('editLokasi').value = el.getAttribute('data-lokasi');
            document.getElementById('editDeskripsi').value = el.getAttribute('data-deskripsi');
            bukaModal('modalEdit');
        }

        $(document).ready(function() {
            var table = $('#tableArsip').DataTable({
                "language": {
                    "search": "Cari Kegiatan:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "emptyTable": "Belum ada arsip kegiatan.",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "paginate": { "previous": "Sebelumnya", "next": "Selanjutnya" }
                },
                "pagingType": "simple_numbers",
                "dom": '<"flex flex-col md:flex-row justify-between items-start gap-4 mb-6"<"left-side flex flex-col gap-3"l><"right-side flex flex-col items-end gap-2"Bf>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"ip>',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: '<svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export Excel',
                    title: 'Data Arsip Kegiatan',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }],
                "pageLength": 10,
                "scrollX": true,
                "order": [[3, "desc"]],
                "columnDefs": [{"orderable": false, "targets": -1}, {"orderable": false, "targets": 6}],
                "initComplete": function() {
                    var tableTitle = '<span class="text-base text-gray-700 font-bold">Tabel Daftar Arsip</span>';
                    $('.left-side').prepend(tableTitle);

                    var btnTambahElem = $('#btnTambahContainer').children().detach();
                    $('.dt-buttons').append(btnTambahElem).addClass('flex items-center gap-2');
                }
            });

            // Buka modal tambah otomatis jika ada error validasi
            @if($errors->any()) bukaModal('modalTambah'); @endif
        });
    </script>
@endpush