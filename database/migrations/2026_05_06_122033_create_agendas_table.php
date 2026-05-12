<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis')->nullable()->after('judul');
            $table->string('slug')->unique();
            $table->longText('konten');
            $table->string('thumbnail')->nullable();
            $table->date('tanggal');
            $table->string('status')->default('publikasi'); // publikasi / draft
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropColumn('penulis');
        });
    }
};
