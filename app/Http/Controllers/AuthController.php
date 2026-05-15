<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    // BAGIAN LUPA PASSWORD (VIA USERNAME & NO HP)
    // ==========================================
    public function showLupaPasswordForm()
    {
        // Menampilkan view baru kita
        return view('auth-lupa-password');
    }

    public function prosesLupaPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'no_hp'    => 'required',
            'password' => 'required|min:8|confirmed', // Pastikan password baru diisi dan dikonfirmasi
        ]);

        // Cari user yang username DAN no_hp nya cocok
        $user = User::where('username', $request->username)
            ->where('no_hp', $request->no_hp)
            ->first();

        // Jika tidak ketemu (salah ketik atau coba retas)
        if (!$user) {
            return back()->withErrors([
                'username' => 'Maaf, Username atau Nomor HP tidak cocok dengan data kami.'
            ]);
        }

        // Jika cocok, ubah passwordnya langsung
        $user->password = Hash::make($request->password);
        $user->save();

        // Kembali ke login dan beri tahu berhasil
        return redirect('/login')->with('success', 'Password berhasil diatur ulang! Silakan masuk dengan password baru Anda.');
    }
}
