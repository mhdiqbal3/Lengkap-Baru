<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            // user_id boleh kosong (nullable) jika sistem memperbolehkan lapor tanpa login, 
            // tapi karena kita buat fitur registrasi, ini ditautkan ke tabel users
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('kode_tiket')->unique();
            $table->string('judul_lapor');
            $table->string('jenis_kasus');
            $table->boolean('is_anonim')->default(false);
            $table->string('nama_korban')->nullable();
            $table->string('no_hp_korban');
            $table->string('status_korban');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('disabilitas', ['ya', 'tidak']);
            $table->date('tanggal_kejadian');
            $table->string('lokasi_kejadian');
            $table->text('deskripsi');
            $table->string('bukti')->nullable();
            $table->enum('status', ['Menunggu Verifikasi', 'Sedang Diproses', 'Selesai', 'Ditolak'])->default('Menunggu Verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
