<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('keluhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->onDelete('cascade');
            $table->string('kode_tiket');
            $table->enum('kategori', ['belum_dihubungi', 'terlalu_lama', 'kurang_jelas', 'lainnya']);
            $table->text('isi_keluhan')->nullable(); // Hanya diisi jika kategori = 'lainnya'
            $table->enum('status', ['menunggu_tanggapan', 'ditindaklanjuti'])->default('menunggu_tanggapan');
            $table->text('catatan_satgas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhans');
    }
};
