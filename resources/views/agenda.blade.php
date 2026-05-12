@extends('layouts.app')
@section('header_title', 'Manajemen Agenda & Berita')
@section('content')

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        /* Mengatasi konflik z-index pada dialog gambar/link Summernote saat berada di dalam Modal Alpine */
        .note-modal-backdrop {
            z-index: 10001 !important;
            display: none !important;
        }

        .note-modal {
            z-index: 10002 !important;
        }

        .note-dropdown-menu {
            z-index: 10005 !important;
        }

        .note-editor.note-frame .note-editing-area .note-editable {
            background-color: #ffffff;
        }
    </style>

    <div class="max-w-[100%] mx-auto pb-10" x-data="{ showTambahModal: {{ $errors->any() ? 'true' : 'false' }} }">

        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Daftar Agenda & Berita</h2>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Kelola informasi kegiatan Satgas PPKS di sini.</p>
            </div>
            <button @click="showTambahModal = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 transition-all shadow-md active:scale-95 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Agenda Baru
            </button>
        </div>

        @if (session('success'))
            <div
                class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 font-bold text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($agendas as $index => $item)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col overflow-hidden hover:shadow-lg transition-all duration-300"
                    x-data="{ showEdit: false, showDelete: false }">

                    <div class="relative h-48 bg-gray-100 shrink-0 border-b border-gray-100">
                        @if ($item->thumbnail)
                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->judul }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span
                                class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg shadow-sm border {{ $item->status == 'publikasi' ? 'bg-green-500 text-white border-green-600' : 'bg-gray-100 text-gray-600 border-gray-300' }}">
                                {{ $item->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div
                            class="text-[10px] font-bold text-gray-400 flex items-center gap-1.5 mb-2 uppercase tracking-widest">
                            <svg class="w-3.5 h-3.5 text-[#800000]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                        </div>
                        <h3 class="text-base font-black text-gray-800 leading-tight mb-2 line-clamp-2 h-[2.5rem]">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-[11px] text-gray-500 line-clamp-2 mb-4 leading-relaxed font-medium">
                            {{ strip_tags($item->konten) }}
                        </p>
                    </div>

                    <div class="p-4 border-t border-gray-50 bg-gray-50/50 flex items-center justify-between shrink-0">
                        <a href="{{ route('agenda.show', $item->slug) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 text-[11px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors border border-blue-100 uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat
                        </a>

                        <div class="flex items-center gap-2">
                            <button @click="showEdit = true"
                                class="p-2 text-yellow-600 hover:text-white bg-yellow-50 hover:bg-yellow-500 rounded-lg transition-colors border border-yellow-100 shadow-sm"
                                title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                            <button @click="showDelete = true"
                                class="p-2 text-red-600 hover:text-white bg-red-50 hover:bg-red-600 rounded-lg transition-colors border border-red-100 shadow-sm"
                                title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <template x-teleport="body">
                        <div x-show="showEdit" style="display: none;"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                            x-transition.opacity>
                            <div @click.away="showEdit = false"
                                class="bg-white rounded-[2rem] shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden text-left"
                                x-transition.scale>
                                <div
                                    class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </div>
                                        Edit Agenda & Berita
                                    </h2>
                                    <button type="button" @click="showEdit = false"
                                        class="text-gray-400 hover:text-red-500 transition p-2 rounded-xl hover:bg-red-50"><svg
                                            class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg></button>
                                </div>
                                <div class="p-8 overflow-y-auto custom-scroll">
                                    <form action="{{ route('agenda.update', $item->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="mb-6">
                                            <label class="block mb-2 text-sm font-bold text-gray-700">Judul Agenda <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" name="judul" value="{{ $item->judul }}" required
                                                class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-700">Nama Penulis
                                                    <span class="text-red-500">*</span></label>
                                                <input type="text" name="penulis"
                                                    value="{{ $item->penulis ?? Auth::user()->name }}" required
                                                    class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-700">Tanggal Agenda
                                                    <span class="text-red-500">*</span></label>
                                                <input type="date" name="tanggal"
                                                    value="{{ \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') }}"
                                                    required
                                                    class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-700">Dokumentasi
                                                    (Gambar)
                                                </label>
                                                <input type="file" name="thumbnail" accept="image/*"
                                                    class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-1.5 focus:outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white cursor-pointer hover:file:bg-red-900 transition">
                                                <p class="text-[10px] text-gray-500 mt-1">Biarkan kosong jika tidak ingin
                                                    mengubah gambar sampul.</p>
                                            </div>
                                            <div>
                                                <label class="block mb-2 text-sm font-bold text-gray-700">Status Publikasi
                                                    <span class="text-red-500">*</span></label>
                                                <select required name="status"
                                                    class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                                    <option value="publikasi"
                                                        {{ $item->status == 'publikasi' ? 'selected' : '' }}>Publikasi Umum
                                                    </option>
                                                    <option value="draft"
                                                        {{ $item->status == 'draft' ? 'selected' : '' }}>Simpan sebagai
                                                        Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-6">
                                            <label class="block mb-2 text-sm font-bold text-gray-700">Tulisan Agenda <span
                                                    class="text-red-500">*</span></label>
                                            <textarea name="konten" required x-init="$(function() {
                                                $($el).summernote({
                                                    height: 250,
                                                    dialogsInBody: true,
                                                    toolbar: [
                                                        ['style', ['style']],
                                                        ['font', ['bold', 'italic', 'underline', 'clear']],
                                                        ['color', ['color']],
                                                        ['para', ['ul', 'ol', 'paragraph']],
                                                        ['table', ['table']],
                                                        ['insert', ['link', 'picture', 'video']],
                                                        ['view', ['fullscreen', 'codeview']]
                                                    ]
                                                });
                                            });">{!! $item->konten !!}</textarea>
                                        </div>
                                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                            <button type="button" @click="showEdit = false"
                                                class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                                            <button type="submit"
                                                class="px-6 py-3 bg-[#f7b500] text-white font-bold rounded-xl hover:bg-yellow-600 transition shadow-md">Simpan
                                                Pembaruan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-teleport="body">
                        <div x-show="showDelete" style="display: none;"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
                            x-transition.opacity>
                            <div @click.away="showDelete = false"
                                class="bg-white rounded-3xl shadow-2xl max-w-sm w-full text-center p-8 transform transition-all"
                                x-transition.scale>
                                <div
                                    class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900 mb-2">Hapus Agenda?</h3>
                                <p class="text-gray-500 text-sm mb-8 font-medium">Berita <strong
                                        class="text-gray-800">{{ $item->judul }}</strong> akan dihapus permanen.</p>
                                <div class="flex justify-center gap-3">
                                    <button @click="showDelete = false"
                                        class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 w-full transition">Batal</button>
                                    <form action="{{ route('agenda.destroy', $item->id) }}" method="POST"
                                        class="w-full m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md w-full transition">Ya,
                                            Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-gray-200 border-dashed">
                    <div
                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Agenda</h3>
                    <p class="text-gray-500 text-sm font-medium">Klik tombol "Tambah Agenda Baru" untuk mulai menulis.</p>
                </div>
            @endforelse
        </div>

        <template x-teleport="body">
            <div x-show="showTambahModal" style="display: none;"
                class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4 py-6"
                x-transition.opacity>
                <div @click.away="showTambahModal = false"
                    class="bg-white rounded-[2rem] shadow-2xl max-w-5xl w-full max-h-[90vh] flex flex-col overflow-hidden text-left"
                    x-transition.scale>
                    <div
                        class="px-8 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 shrink-0">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-red-100 text-[#800000] rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            Tambah Agenda Baru
                        </h2>
                        <button type="button" @click="showTambahModal = false"
                            class="text-gray-400 hover:text-red-500 transition p-2 rounded-xl hover:bg-red-50"><svg
                                class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></button>
                    </div>
                    <div class="p-8 overflow-y-auto custom-scroll">
                        <form action="{{ route('agenda.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-bold text-gray-700">Judul Agenda <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="judul" required
                                    class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]"
                                    placeholder="Contoh: Sosialisasi PPKS di Fakultas Teknik">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Nama Penulis <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="penulis"
                                        value="{{ Auth::user()->name ?? 'Admin PPKS' }}" required
                                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Tanggal Agenda <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Dokumentasi (Gambar) <span
                                            class="text-red-500">*</span></label>
                                    <input type="file" name="thumbnail" accept="image/*" required
                                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-1.5 focus:outline-none file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#800000] file:text-white cursor-pointer hover:file:bg-red-900 transition">
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-bold text-gray-700">Status Publikasi <span
                                            class="text-red-500">*</span></label>
                                    <select required name="status"
                                        class="w-full bg-gray-50 border border-gray-300 text-sm rounded-xl p-3 focus:outline-none focus:border-[#800000]">
                                        <option value="publikasi">Publikasi Umum</option>
                                        <option value="draft">Simpan sebagai Draft</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-bold text-gray-700">Tulisan Agenda <span
                                        class="text-red-500">*</span></label>
                                <textarea name="konten" required x-init="$(function() {
                                    $($el).summernote({
                                        placeholder: 'Tulis isi konten agenda atau berita di sini...',
                                        height: 250,
                                        dialogsInBody: true,
                                        toolbar: [
                                            ['style', ['style']],
                                            ['font', ['bold', 'italic', 'underline', 'clear']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['fullscreen', 'codeview']]
                                        ]
                                    });
                                });"></textarea>
                            </div>
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <button type="button" @click="showTambahModal = false"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                                <button type="submit"
                                    class="px-6 py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition shadow-md">Simpan
                                    & Terbitkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endpush
