<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (!Schema::hasColumn('laporans', 'status_terlapor')) {
                $table->string('status_terlapor')->nullable()->after('status_korban');
            }
            if (!Schema::hasColumn('laporans', 'link_video')) {
                $table->string('link_video')->nullable()->after('deskripsi');
            }
        });
    }

    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['status_terlapor', 'link_video']);
        });
    }
};
