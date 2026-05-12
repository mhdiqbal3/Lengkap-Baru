<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        // Mengambil user dengan role satgas
        $petugas = User::where('role', 'satgas')->orderBy('created_at', 'desc')->get();
        return view('petugas', compact('petugas'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Untuk Login (Tersandi)
        $user->password_plain = $request->password;     // Untuk Ditampilkan (Teks Biasa)
        $user->role = 'satgas';
        $user->save();

        return redirect()->back()->with('success', 'Petugas Satgas baru berhasil didaftarkan.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $user = User::findOrFail($id);
        if ($user->role === 'satgas') {
            $user->delete();
            return redirect()->back()->with('success', 'Akun petugas berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6', // Password opsional, hanya jika diisi
        ]);

        $user->name = $request->name;
        $user->username = $request->username;

        // Perbarui password hanya jika input password diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->password_plain = $request->password;
        }

        $user->save();

        return redirect()->back()->with('success', 'Data petugas berhasil diperbarui.');
    }
}
