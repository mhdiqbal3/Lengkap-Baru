<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('konten_halamans', function (Blueprint $table) {
            $table->id();
            $table->string('halaman')->unique(); // Untuk menyimpan nama halaman: 'pencegahan', 'penanganan', dll.
            $table->longText('konten')->nullable(); // Untuk menyimpan isi HTML halamannya
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('konten_halamans');
    }
};
