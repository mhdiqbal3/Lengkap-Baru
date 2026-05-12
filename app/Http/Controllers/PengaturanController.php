<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PengaturanController extends Controller
{
    public function index()
    {
        // BLOKIR SATGAS DARI HALAMAN PENGATURAN
        if (Auth::user()->role === 'satgas') {
            abort(403, 'Akses Ditolak. Anggota Satgas tidak diizinkan mengubah pengaturan akun. Hubungi Administrator.');
        }

        $user = Auth::user();
        return view('pengaturan', compact('user'));
    }

    public function update(Request $request)
    {
        // BLOKIR AKSES UPDATE DATA DARI SATGAS
        if (Auth::user()->role === 'satgas') {
            abort(403, 'Akses Ditolak. Tindakan ini tidak diizinkan untuk peran Anda.');
        }

        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user->name = $request->name;

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru ke folder storage/app/public/profil
            $user->foto = $request->file('foto')->store('profil', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Pengaturan profil Anda berhasil diperbarui.');
    }
}
