<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            // Menambahkan kolom baru
            $table->string('status_terlapor')->nullable()->after('status_korban');
            $table->string('link_video')->nullable()->after('deskripsi');

            // Opsional: Jika kamu ingin benar-benar menghapus is_anonim dari database
            // $table->dropColumn('is_anonim'); 
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['status_terlapor', 'link_video']);
        });
    }
};
