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
        Schema::table('laporans', function (Blueprint $table) {
            // Kolom saksi disimpan sebagai JSON (nama, pekerjaan, telepon, alamat)
            $table->json('saksi')->nullable()->after('lokasi_kejadian');
            // Keterangan tambahan jika status_korban = 'lainnya'
            $table->string('status_korban_lainnya')->nullable()->after('status_korban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['saksi', 'status_korban_lainnya']);
        });
    }
};
