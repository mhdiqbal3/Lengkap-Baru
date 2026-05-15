@extends('layouts.app')

@section('header_title', 'Pengaturan Akun')

@section('content')
    <div class="max-w-6xl mx-auto pb-20" x-data="{ tabAktif: 'profil' }">

        <div
            class="bg-gradient-to-r from-[#800000] to-red-900 rounded-[2rem] p-8 md:p-10 mb-8 shadow-lg text-white flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-black tracking-tight mb-2">Pengaturan Akun</h1>
                <p class="text-red-100/80 text-sm font-medium">Kelola informasi profil personal dan preferensi keamanan Anda.
                </p>
            </div>
            <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-20 -mt-20"></div>
        </div>

        <div class="flex flex-col md:flex-row gap-8 items-start">
            {{-- Sisi Kiri: Navigasi Menu --}}
            <div class="w-full md:w-64 shrink-0">
                <nav class="flex flex-col gap-2">
                    <button @click="tabAktif = 'profil'"
                        :class="tabAktif === 'profil' ? 'bg-white text-[#800000] font-bold shadow-sm border border-gray-200' :
                            'text-gray-500 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
                        class="text-left px-5 py-3.5 rounded-xl transition-all flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profil Akun
                    </button>
                    <button @click="tabAktif = 'keamanan'"
                        :class="tabAktif === 'keamanan' ? 'bg-white text-[#800000] font-bold shadow-sm border border-gray-200' :
                            'text-gray-500 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
                        class="text-left px-5 py-3.5 rounded-xl transition-all flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                        Keamanan Sandi
                    </button>
                </nav>
            </div>

            {{-- Sisi Kanan: Konten --}}
            <div class="flex-1 w-full bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-10">
                {{-- TAB PROFIL --}}
                <div x-show="tabAktif === 'profil'" x-transition.opacity.duration.300ms>
                    <div class="mb-8 pb-6 border-b border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Profil Publik</h3>
                    </div>

                    <form action="{{ route('pengaturan.profil') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-8" x-data="{ photoPreview: null, removePhoto: false }">
                        @csrf
                        {{-- Input tersembunyi untuk menandai hapus foto --}}
                        <input type="hidden" name="remove_foto" :value="removePhoto ? '1' : '0'">

                        <div class="flex items-center gap-6">
                            <div class="relative shrink-0">
                                <div
                                    class="w-24 h-24 rounded-full border border-gray-200 shadow-sm overflow-hidden bg-gray-50">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!photoPreview && !removePhoto">
                                        @if (Auth::user()->foto)
                                            <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center text-3xl font-black text-[#800000] bg-red-50">
                                                {{ substr(Auth::user()->name, 0, 1) }}</div>
                                        @endif
                                    </template>
                                    <template x-if="removePhoto && !photoPreview">
                                        <div
                                            class="w-full h-full flex items-center justify-center text-3xl font-black text-[#800000] bg-red-50">
                                            {{ substr(Auth::user()->name, 0, 1) }}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-2">
                                    <input type="file" name="foto" class="hidden" x-ref="fotoInput" accept="image/*"
                                        @change="const file = $event.target.files[0]; if (file) { removePhoto = false; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <button type="button" @click="$refs.fotoInput.click()"
                                        class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition-all">Ganti
                                        avatar</button>

                                    @if (Auth::user()->foto)
                                        <button type="button" x-show="!removePhoto || photoPreview"
                                            @click="removePhoto = true; photoPreview = null; $refs.fotoInput.value = ''"
                                            class="px-5 py-2.5 bg-red-50 border border-red-100 rounded-xl text-sm font-bold text-red-600 hover:bg-red-100 shadow-sm transition-all">Hapus
                                            Foto</button>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 font-medium">Format JPG, GIF atau PNG. Maksimal 2MB.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required
                                    class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white outline-none transition-all text-sm font-semibold shadow-sm">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Username</label>
                                <input type="text" name="username" value="{{ Auth::user()->username }}" required
                                    class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white outline-none transition-all text-sm font-semibold shadow-sm">
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-bold text-gray-700">Alamat Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" required
                                    class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white outline-none transition-all text-sm font-semibold shadow-sm">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 transition-colors shadow-md active:scale-95">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>

                {{-- TAB KEAMANAN --}}
                <div x-show="tabAktif === 'keamanan'" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Keamanan Sandi</h3>
                    </div>

                    <form action="{{ route('pengaturan.password') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-6">
                            {{-- Current Password --}}
                            <div class="space-y-2" x-data="{ show: false }">
                                <label class="text-sm font-bold text-gray-700">Kata Sandi Saat Ini</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="current_password" required
                                        class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] focus:bg-white outline-none transition-all text-sm font-semibold shadow-sm pr-12">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000]">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- New Password --}}
                                <div class="space-y-2" x-data="{ show: false }">
                                    <label class="text-sm font-bold text-gray-700">Kata Sandi Baru</label>
                                    <div class="relative">
                                        <input :type="show ? 'text' : 'password'" name="new_password" required
                                            class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl pr-12 text-sm font-semibold">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                {{-- Confirmation --}}
                                <div class="space-y-2" x-data="{ show: false }">
                                    <label class="text-sm font-bold text-gray-700">Konfirmasi Sandi Baru</label>
                                    <div class="relative">
                                        <input :type="show ? 'text' : 'password'" name="new_password_confirmation" required
                                            class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl pr-12 text-sm font-semibold">
                                        <button type="button" @click="show = !show"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit"
                                class="px-8 py-3.5 bg-[#800000] text-white text-sm font-bold rounded-xl hover:bg-red-900 shadow-md">Perbarui
                                Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
