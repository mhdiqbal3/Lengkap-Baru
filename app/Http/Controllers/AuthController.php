<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================================
    // BAGIAN LOGIN
    // ==========================================
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('index');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // ==========================================
    // BAGIAN REGISTRASI
    // ==========================================
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'no_hp'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Username ini sudah terdaftar.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            'foto'     => null,
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk dengan akun Anda.');
    }

    // ==========================================
    // BAGIAN LUPA PASSWORD (VIA OTP EMAIL)
    // ==========================================

    // 1. Tampilkan form input email
    public function showLupaPasswordForm()
    {
        return view('auth-lupa-password');
    }

    // 2. Proses kirim OTP ke email
    public function kirimOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.exists' => 'Alamat email tidak terdaftar di sistem kami.'
        ]);

        // Generate 6 digit OTP acak
        $otp = rand(100000, 999999);

        // Simpan OTP dan email ke session, berlaku 10 menit
        session([
            'otp' => $otp,
            'otp_email' => $request->email,
            'otp_expires' => now()->addMinutes(10)
        ]);

        try {
            // PERBAIKAN DI SINI: 'emails.otp' diubah menjadi 'otp'
            Mail::send('otp', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Kode OTP Reset Password - Satgas PPKPT USN Kolaka');
            });
        } catch (\Exception $e) {
            // Jika Anda ingin melihat pesan error aslinya untuk debugging, Anda bisa mengaktifkan baris di bawah ini:
            // return back()->withErrors(['email' => 'Error: ' . $e->getMessage()]);
            return back()->withErrors(['email' => 'Gagal mengirim email. Pastikan konfigurasi SMTP (.env) sudah benar.']);
        }

        return redirect()->route('password.verify')->with('success', 'Kode OTP telah dikirim. Silakan periksa kotak masuk (inbox) atau folder spam email Anda.');
    }

    // 3. Tampilkan form verifikasi OTP
    public function showVerifikasiOtpForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('password.request');
        }
        return view('auth-verifikasi-otp');
    }

    // 4. Proses pencocokan OTP
    public function prosesVerifikasiOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        // Cek apakah waktu kedaluwarsa
        if (now()->greaterThan(session('otp_expires'))) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        // Cek apakah OTP cocok
        if ($request->otp != session('otp')) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau salah.']);
        }

        // Jika benar, tandai session sebagai terverifikasi
        session(['otp_verified' => true]);

        return redirect()->route('password.reset')->with('success', 'OTP divalidasi! Silakan buat password baru Anda.');
    }

    // 5. Tampilkan form buat sandi baru
    public function showResetPasswordForm()
    {
        // Pastikan pengguna sudah melewati verifikasi OTP
        if (!session('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth-reset-password');
    }

    // 6. Proses simpan sandi baru ke database
    public function prosesResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        // Ambil email dari session
        $email = session('otp_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Hapus seluruh session OTP demi keamanan
        session()->forget(['otp', 'otp_email', 'otp_expires', 'otp_verified']);

        return redirect('/login')->with('success', 'Password berhasil diubah! Silakan masuk dengan password baru Anda.');
    }
}
