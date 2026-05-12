<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            // Menautkan arsip dengan admin yang menginputnya
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul_kegiatan');
            $table->string('jenis_kegiatan');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('status_publikasi');
            $table->text('deskripsi');
            $table->string('dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
