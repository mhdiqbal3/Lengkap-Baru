<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SATGAS PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#800000] flex min-h-screen items-center justify-center p-4 font-sans">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 border border-gray-100">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">Lupa Password?</h1>
            <p class="text-xs font-medium text-gray-500">Masukkan alamat email terdaftar Anda. Kami akan mengirimkan kode
                OTP untuk mengatur ulang password.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div
                    class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-1.5">
                <label class="text-xs font-bold text-gray-700">Alamat Email Terdaftar</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm font-medium rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all"
                    placeholder="Contoh: emailanda@gmail.com">
            </div>

            <button type="submit"
                class="w-full py-3 mt-4 bg-[#800000] text-white text-sm font-bold rounded-xl shadow-lg hover:bg-[#600000] transition-all active:scale-[0.98]">
                Kirim Kode OTP ke Email
            </button>

            <div class="text-center mt-4 border-t border-gray-100 pt-4">
                <a href="{{ route('login') }}"
                    class="text-xs font-bold text-gray-500 hover:text-[#800000] transition-colors">Kembali ke halaman
                    Login</a>
            </div>
        </form>
    </div>
</body>

</html>
