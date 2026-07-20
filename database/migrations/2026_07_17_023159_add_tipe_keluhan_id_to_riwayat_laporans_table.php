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
        Schema::table('riwayat_laporans', function (Blueprint $table) {
            // Tipe riwayat: 'status', 'keluhan', 'tanggapan_keluhan'
            $table->string('tipe')->default('status')->after('catatan');
            // Relasi opsional ke tabel keluhans
            $table->unsignedBigInteger('keluhan_id')->nullable()->after('tipe');
            $table->foreign('keluhan_id')->references('id')->on('keluhans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_laporans', function (Blueprint $table) {
            $table->dropForeign(['keluhan_id']);
            $table->dropColumn(['tipe', 'keluhan_id']);
        });
    }
};
