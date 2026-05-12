<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin (Tim Satgas)
        User::create([
            'name'     => 'Tim Satgas PPKS',
            'username' => 'admin_satgas',
            'email'    => 'satgasppks@usn.ac.id',
            'no_hp'    => '081200001111',
            'role'     => 'admin',
            'password' => Hash::make('satgas123'), // Password untuk admin
        ]);

        // 2. Buat Akun Pelapor (Contoh: Mahasiswa)
        User::create([
            'name'     => 'Andi Budi (Pelapor)',
            'username' => '19000123', // Menggunakan NIM sebagai username
            'email'    => 'andi.budi@usn.ac.id',
            'no_hp'    => '085200002222',
            'role'     => 'user', // Ganti menjadi 'pelapor' jika di file migrasi Anda tertulis 'pelapor'
            'password' => Hash::make('mahasiswa123'), // Password untuk pelapor
        ]);
    }
}
