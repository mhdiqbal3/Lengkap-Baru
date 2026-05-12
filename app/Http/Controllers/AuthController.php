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
            // Arahkan ke dashboard jika sukses login
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
        // 1. Validasi data yang diinput
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

        // 2. Simpan ke Database
        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'no_hp'    => $request->no_hp,
            'password' => Hash::make($request->password),
            'role'     => 'user', 
            
            // PERBAIKAN: Secara eksplisit mengatur foto menjadi null saat mendaftar
            // Sistem akan otomatis menampilkan inisial nama karena nilainya kosong
            'foto'     => null, 
        ]);

        // 3. Arahkan kembali ke halaman login dengan pesan sukses
        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk dengan akun Anda.');
    }
}