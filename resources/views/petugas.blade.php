@extends('layouts.app')

@section('header_title', 'Manajemen Petugas Satgas')

@section('content')
    {{-- Tambahkan state showEditModal dan editData untuk AlpineJS --}}
    <div class="max-w-[100%] mx-auto pb-10" x-data="{ showModal: false, showEditModal: false, editFormAction: '', editData: { name: '', username: '' } }">
        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Manajemen Petugas </h2>
                <p class="text-gray-500 text-sm mt-1.5 font-medium">Kelola daftar akun anggota petugas yang bertugas di
                    sistem.</p>
            </div>
            <button @click="showModal = true"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-[#800000] text-white font-bold rounded-xl hover:bg-red-900 transition shadow-lg active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Satgas
            </button>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex justify-between items-center"
                x-data="{ show: true }" x-show="show" x-transition>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Tabel Daftar Petugas --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col p-6">
            <div class="overflow-x-auto custom-scroll flex-1 relative w-full">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead
                        class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-20">No</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            {{-- Urutan Email dan Username ditukar di sini --}}
                            <th class="px-6 py-4 text-center">Email</th>
                            <th class="px-6 py-4 text-center">Username</th>
                            <th class="px-6 py-4 text-center">Password</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($petugas as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ reveal: false }">
                                <td class="px-6 py-4 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-800">{{ $item->name }}</td>

                                {{-- Kolom Email sekarang ada di sebelum Username --}}
                                <td class="px-6 py-4 text-center text-gray-500">{{ $item->email }}</td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="px-3 py-1 bg-gray-100 rounded-lg text-gray-600 font-medium">{{ $item->username }}</span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div
                                        class="inline-flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                                        <span
                                            class="font-mono font-bold {{ $item->password_plain ? 'text-gray-700' : 'text-red-400 text-xs italic' }}"
                                            x-text="reveal ? '{{ $item->password_plain ?? 'Tidak direkam' }}' : '••••••'"></span>
                                        <button @click="reveal = !reveal"
                                            class="text-gray-400 hover:text-[#800000] focus:outline-none ml-1">
                                            <svg x-show="!reveal" class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path>
                                                <path
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    stroke-width="2"></path>
                                            </svg>
                                            <svg x-show="reveal" style="display: none;" class="w-4 h-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.97 9.97 0 015.71-2.29c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                                    stroke-width="2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Tombol Edit --}}
                                        <button
                                            @click="showEditModal = true; editFormAction = '{{ route('petugas.update', $item->id) }}'; editData.name = '{{ $item->name }}'; editData.username = '{{ $item->username }}';"
                                            class="p-2 text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm border border-blue-100"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        {{-- Form Hapus --}}
                                        <form action="{{ route('petugas.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus akun satgas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-600 bg-red-50 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm border border-red-100"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada petugas
                                    Satgas yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL POPUP REGISTRASI SATGAS --}}
        <template x-teleport="body">
            <div x-show="showModal" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
                x-transition.opacity>
                <div @click.away="showModal = false"
                    class="bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                    x-transition.scale>
                    <div class="bg-[#800000] p-10 text-white relative">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black tracking-tight leading-none mb-1">Registrasi SATGAS</h3>
                                <p class="text-red-200 text-sm font-bold uppercase tracking-widest">Buat Akses Petugas Baru
                                </p>
                            </div>
                        </div>
                        <button @click="showModal = false"
                            class="absolute top-8 right-8 text-white/40 hover:text-white transition focus:outline-none">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('petugas.store') }}" method="POST" class="p-10 space-y-6 bg-white">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama
                                Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan Nama Lengkap"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-[#800000] font-bold text-gray-700 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                                <input type="text" name="username" required placeholder="Username"
                                    class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-[#800000] font-bold text-gray-700 text-sm transition-all">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                                <input type="email" name="email" required placeholder="Email"
                                    class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-[#800000] font-bold text-gray-700 text-sm transition-all">
                            </div>
                        </div>
                        <div x-data="{ show: false }">
                            <label
                                class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" required minlength="6"
                                    placeholder="Minimal 6 Karakter"
                                    class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 pr-14 outline-none focus:border-[#800000] font-bold text-gray-700 transition-all">
                                <button type="button" @click="show = !show"
                                    class="absolute right-5 top-4 text-gray-400 hover:text-[#800000] transition-colors focus:outline-none">
                                    <svg x-show="!show" class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path>
                                        <path
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            stroke-width="2"></path>
                                    </svg>
                                    <svg x-show="show" class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.97 9.97 0 015.71-2.29c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                            stroke-width="2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-4 bg-[#800000] text-white font-black rounded-2xl hover:bg-red-900 transition shadow-xl shadow-red-900/20 active:scale-[0.98] uppercase tracking-widest text-sm">Registrasi
                            Petugas</button>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL POPUP EDIT SATGAS --}}
        <template x-teleport="body">
            <div x-show="showEditModal" style="display: none;"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-sm px-4"
                x-transition.opacity>
                <div @click.away="showEditModal = false"
                    class="bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all"
                    x-transition.scale>

                    {{-- Header --}}
                    <div class="bg-blue-600 p-10 text-white relative">
                        <div class="flex items-center gap-5">
                            <div
                                class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black tracking-tight leading-none mb-1">Edit SATGAS</h3>
                                <p class="text-blue-200 text-sm font-bold uppercase tracking-widest">Perbarui Data Petugas
                                </p>
                            </div>
                        </div>
                        <button @click="showEditModal = false"
                            class="absolute top-8 right-8 text-white/40 hover:text-white transition focus:outline-none">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12" stroke-width="3" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form :action="editFormAction" method="POST" class="p-10 space-y-6 bg-white">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama
                                Lengkap</label>
                            <input type="text" name="name" x-model="editData.name" required
                                placeholder="Masukkan Nama Lengkap"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-blue-600 font-bold text-gray-700 transition-all">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                            <input type="text" name="username" x-model="editData.username" required
                                placeholder="Username"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-blue-600 font-bold text-gray-700 text-sm transition-all">
                        </div>
                        <div x-data="{ show: false }">
                            <label
                                class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password
                                Baru <span class="text-gray-400 text-[10px] lowercase normal-case">(Opsional, isi jika
                                    ingin mengubah password)</span></label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" minlength="6"
                                    placeholder="Biarkan kosong jika tidak diubah"
                                    class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 pr-14 outline-none focus:border-blue-600 font-bold text-gray-700 transition-all">
                                <button type="button" @click="show = !show"
                                    class="absolute right-5 top-4 text-gray-400 hover:text-blue-600 transition-colors focus:outline-none">
                                    <svg x-show="!show" class="w-6 h-6" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path>
                                        <path
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                            stroke-width="2"></path>
                                    </svg>
                                    <svg x-show="show" style="display: none;" class="w-6 h-6" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a9.97 9.97 0 015.71-2.29c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                            stroke-width="2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-900/20 active:scale-[0.98] uppercase tracking-widest text-sm">Simpan
                            Perubahan</button>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection
