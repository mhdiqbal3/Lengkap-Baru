<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SATGAS PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'ppks-maroon': '#800000',
                        'ppks-red': '#b30000',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Styling Scrollbar khusus untuk form jika layarnya kecil */
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-[#800000] flex h-screen items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-5xl rounded-[2rem] shadow-2xl flex flex-col md:flex-row overflow-hidden border border-gray-100 min-h-[550px] max-h-[95vh]">

        <div
            class="w-full md:w-1/2 p-8 lg:px-12 py-8 flex flex-col justify-center bg-white relative overflow-y-auto custom-scroll">

            <div class="max-w-md w-full mx-auto mt-8 md:mt-4">
                <div class="text-center mb-6">
                    <div
                        class="w-14 h-14 bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-3">
                        <img src="{{ asset('assets/image/logo.png') }}" alt="Logo USN Kolaka"
                            class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-1">Buat Akun Baru</h1>
                    <p class="text-xs font-medium text-gray-500">Lengkapi data diri anda.</p>
                </div>

                <form method="POST" action="/register" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Nama Lengkap <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="Masukkan nama">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Username <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="username" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="Contoh: 19000123">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Alamat Email <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="email@usn.ac.id">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-700">Nomor WhatsApp <span
                                    class="text-red-500">*</span></label>
                            <input type="tel" name="no_hp" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ show1: false, show2: false }">
                        <div class="space-y-1.5 relative">
                            <label class="text-xs font-bold text-gray-700">Password <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input :type="show1 ? 'text' : 'password'" name="password" required minlength="8"
                                    class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                    placeholder="Min. 8 Karakter">
                                <button type="button" @click="show1 = !show1"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show1" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg x-show="show1" style="display:none;" class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583 8.32 8.32 0 013.89.981L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5 relative">
                            <label class="text-xs font-bold text-gray-700">Konfirmasi Password <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input :type="show2 ? 'text' : 'password'" name="password_confirmation" required
                                    minlength="8"
                                    class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                    placeholder="Ketik ulang sandi">
                                <button type="button" @click="show2 = !show2"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg x-show="!show2" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg x-show="show2" style="display:none;" class="w-4 h-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583 8.32 8.32 0 013.89.981L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full mt-6 py-3.5 bg-ppks-maroon text-white text-sm font-bold rounded-xl shadow-lg shadow-ppks-maroon/30 hover:bg-ppks-red transition-all active:scale-[0.98] flex justify-center items-center gap-2">
                        Daftar Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-center space-x-4">
                    <span class="h-px bg-gray-200 w-1/4"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Masuk</span>
                    <span class="h-px bg-gray-200 w-1/4"></span>
                </div>

                <p class="text-center mt-4 text-xs text-gray-500 font-medium">
                    Sudah Punya Akun? <a href="{{ url('/login') }}"
                        class="text-ppks-maroon font-bold hover:underline">Masuk Disini</a>
                </p>
            </div>
        </div>

        <div class="hidden md:block w-1/2 p-2.5">
            <div
                class="w-full h-full rounded-[1.5rem] bg-gradient-to-br from-[#4a0000] via-[#800000] to-[#2b0000] relative overflow-hidden flex flex-col justify-between p-10">

                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white opacity-5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 -left-10 w-64 h-64 bg-[#ff4d4d] opacity-10 rounded-full blur-3xl"></div>

                <svg class="absolute top-1/4 left-1/4 w-full h-full text-white/5 transform scale-150 -rotate-12"
                    fill="currentColor" viewBox="0 0 100 100">
                    <path d="M50 0L100 50L50 100L0 50L50 0Z"></path>
                </svg>

                <div class="relative z-10">
                    <span
                        class="inline-block py-1.5 px-3 rounded-full bg-white/10 text-white/90 text-[9px] font-bold tracking-widest uppercase mb-3 border border-white/20 backdrop-blur-sm">
                        Registrasi Pelapor
                    </span>
                    <h2 class="text-3xl font-black text-white leading-[1.15] mb-4">
                        Akses Cepat<br>Pemantauan<br>Status Laporan.
                    </h2>
                </div>

                <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-xl">
                    <p class="text-red-50 text-xs leading-relaxed font-medium">
                        "Dengan mendaftar, Anda dapat melacak progres laporan Anda dan berkomunikasi
                        secara aman dengan tim Satgas."
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[#800000]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-white text-[10px] font-bold uppercase tracking-wider">Satgas PPKS USN
                            Kolaka</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
