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
            $table->text('keluhan')->nullable()->after('status'); // Isi keluhan dari pelapor (HTML Summernote)
            $table->boolean('keluhan_dibaca')->default(false)->after('keluhan'); // Status apakah admin sudah membaca
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['keluhan', 'keluhan_dibaca']);
        });
    }
};
