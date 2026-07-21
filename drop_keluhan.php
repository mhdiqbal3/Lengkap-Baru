<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    if (Schema::hasColumn('riwayat_laporans', 'keluhan_id')) {
        Schema::table('riwayat_laporans', function (Blueprint $table) {
            $table->dropForeign(['keluhan_id']);
            $table->dropColumn(['tipe', 'keluhan_id']);
        });
        echo "Dropped keluhan_id from riwayat_laporans\n";
    }
} catch (\Exception $e) {
    echo "Error dropping keluhan_id: " . $e->getMessage() . "\n";
}

try {
    Schema::dropIfExists('keluhans');
    echo "Dropped keluhans table\n";
} catch (\Exception $e) {
    echo "Error dropping keluhans table: " . $e->getMessage() . "\n";
}

DB::table('migrations')->where('migration', 'like', '%keluhan%')->delete();
echo "Deleted keluhan migrations from DB\n";
