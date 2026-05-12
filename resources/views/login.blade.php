<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SATGAS PPKS USN Kolaka</title>
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
</head>

<body class="bg-[#800000] flex h-screen items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-4xl rounded-[2rem] shadow-2xl flex flex-col md:flex-row overflow-hidden border border-gray-100 min-h-[480px] max-h-[90vh]">

        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center bg-white relative overflow-y-auto">

            <div class="max-w-sm w-full mx-auto mt-6">
                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-4">
                        <img src="{{ asset('assets/image/logo.png') }}" alt="Logo USN Kolaka"
                            class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-1">Selamat Datang</h1>
                    <p class="text-xs font-medium text-gray-500">Silakan masukkan detail akun Anda.</p>
                </div>

                <form method="POST" action="/login" class="space-y-4">
                    @csrf

                    @if ($errors->any())
                        <div
                            class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100 text-center">
                            Username atau password Anda salah.
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="username" required
                                class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="Masukkan username">
                        </div>
                    </div>

                    <div class="space-y-1.5" x-data="{ show: false }">
                        <label class="text-xs font-bold text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input :type="show ? 'text' : 'password'" name="password" required
                                class="w-full pl-9 pr-10 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-ppks-maroon/20 focus:border-ppks-maroon focus:bg-white outline-none transition-all"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg x-show="show" style="display:none;" class="w-4 h-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.188-1.583 8.32 8.32 0 013.89.981L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-3.5 h-3.5 text-ppks-maroon border-gray-300 rounded focus:ring-ppks-maroon focus:ring-2">
                            <span class="text-[11px] font-semibold text-gray-600">Ingat Saya</span>
                        </label>
                        <a href="#"
                            class="text-[11px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Lupa
                            Password?</a>
                    </div>

                    <button type="submit"
                        class="w-full mt-4 py-3 bg-ppks-maroon text-white text-sm font-bold rounded-xl shadow-lg shadow-ppks-maroon/30 hover:bg-ppks-red transition-all active:scale-[0.98] flex justify-center items-center gap-2">
                        Masuk Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-center space-x-4">
                    <span class="h-px bg-gray-200 w-1/4"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Register</span>
                    <span class="h-px bg-gray-200 w-1/4"></span>
                </div>

                <p class="text-center mt-4 text-xs text-gray-500 font-medium">
                    Belum Punya Akun? <a href="{{ url('/register') }}"
                        class="text-ppks-maroon font-bold hover:underline">Daftar Disini</a>
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
                        Portal Satgas
                    </span>
                    <h2 class="text-3xl font-black text-white leading-[1.15] mb-4">
                        Mari Ciptakan<br>Kampus yang<br>Aman & Inklusif.
                    </h2>
                </div>

                <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-xl">
                    <p class="text-red-50 text-xs leading-relaxed font-medium">
                        "Setiap laporan yang masuk akan kami tangani dengan mengedepankan prinsip kerahasiaan, keadilan,
                        dan tanpa penghakiman kepada korban."
                    </p>
                    <div class="mt-3 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-[#800000]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-white text-[10px] font-bold uppercase tracking-wider">Satgas PPKPT USN
                            Kolaka</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
