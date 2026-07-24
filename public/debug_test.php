<?php
$laporans = \App\Models\Laporan::all();
$jenis = $laporans->pluck('jenis_kasus')->toArray();
$titles = [];
$kontenDashboard = \App\Models\KontenHalaman::where('halaman', 'dashboard')->first();
if ($kontenDashboard && !empty($kontenDashboard->konten)) {
    $dataDashboard = json_decode($kontenDashboard->konten, true);
    $titles = $dataDashboard['bentuk_item_titles'] ?? [];
}
echo json_encode(['db' => $jenis, 'dashboard' => $titles]);
