<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SATGAS PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#800000] flex min-h-screen items-center justify-center p-4 font-sans">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 border border-gray-100 transform transition-all">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">Lupa Password?</h1>
            <p class="text-xs font-medium text-gray-500">Verifikasi identitas Anda untuk mengatur ulang password.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div
                    class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Username --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-700">Username</label>
                <input type="text" name="username" required value="{{ old('username') }}"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all"
                    placeholder="Masukkan Username Anda">
            </div>

            {{-- Nomor HP --}}
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-700">Nomor Handphone Terdaftar</label>
                <input type="text" name="no_hp" required value="{{ old('no_hp') }}"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all"
                    placeholder="Contoh: 081234567890">
            </div>

            <hr class="my-5 border-gray-100">

            {{-- Password Baru (Dengan Tombol Mata) --}}
            <div class="space-y-1.5" x-data="{ show: false }">
                <label class="text-xs font-bold text-gray-700">Password Baru</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all pr-11"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition-colors p-1 rounded-md focus:outline-none focus:ring-1 focus:ring-[#800000]/30">
                        {{-- Icon Mata Terbuka (Show) --}}
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{-- Icon Mata Tertutup (Hide) --}}
                        <svg x-show="show" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583 8.32 8.32 0 013.89.981L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi Password Baru (Sekarang Juga Dengan Tombol Mata) --}}
            <div class="space-y-1.5" x-data="{ show: false }">
                <label class="text-xs font-bold text-gray-700">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all pr-11">
                    <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#800000] transition-colors p-1 rounded-md focus:outline-none focus:ring-1 focus:ring-[#800000]/30">
                        {{-- Icon Mata Terbuka (Show) --}}
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{-- Icon Mata Tertutup (Hide) --}}
                        <svg x-show="show" style="display:none;" class="w-4 h-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583 8.32 8.32 0 013.89.981L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 mt-3 bg-[#800000] text-white text-sm font-bold rounded-xl shadow-lg hover:bg-[#b30000] transition-all active:scale-[0.98]">
                Simpan & Ubah Password
            </button>

            <div class="text-center mt-4 border-t border-gray-100 pt-4">
                <a href="{{ route('login') }}" class="text-xs font-bold text-[#800000] hover:underline">Kembali ke
                    Login</a>
            </div>
        </form>
    </div>
</body>

</html>
