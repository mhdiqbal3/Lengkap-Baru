<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - SATGAS PPKS USN Kolaka</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#800000] flex min-h-screen items-center justify-center p-4 font-sans">
    <div class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl p-8 border border-gray-100 text-center">

        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-3 rounded-xl text-xs font-bold border border-green-200 mb-6">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-2">Verifikasi OTP</h1>
        <p class="text-xs font-medium text-gray-500 mb-6">Masukkan 6 digit kode OTP yang baru saja kami kirimkan ke email
            <br><b class="text-gray-800">{{ session('otp_email') }}</b></p>

        <form method="POST" action="{{ route('password.verify.post') }}" class="space-y-4">
            @csrf

            @if ($errors->any())
                <div
                    class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-medium border border-red-100 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-1.5 text-left">
                <label class="text-xs font-bold text-gray-700">Kode OTP (6 Angka)</label>
                <input type="text" name="otp" required maxlength="6"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-center text-2xl tracking-[0.5em] font-black rounded-xl focus:ring-2 focus:ring-[#800000]/20 focus:border-[#800000] outline-none transition-all"
                    placeholder="------">
            </div>

            <button type="submit"
                class="w-full py-3 mt-4 bg-[#800000] text-white text-sm font-bold rounded-xl shadow-lg hover:bg-[#600000] transition-all active:scale-[0.98]">
                Validasi OTP
            </button>
        </form>

        <div class="text-center mt-6 border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-500">Tidak menerima email? <a href="{{ route('password.request') }}"
                    class="font-bold text-[#800000]">Coba ulangi</a></p>
        </div>
    </div>
</body>

</html>
