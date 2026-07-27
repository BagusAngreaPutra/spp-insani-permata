<?php 
require_once 'bootstrap/app.php';

$count = DB::table('riwayat_kelulusan')->whereNull('sekolah_id')->count();
echo "Jumlah data dengan sekolah_id NULL: " . $count . "\n";

$datas = DB::table('riwayat_kelulusan')->whereNull('sekolah_id')->get();
foreach($datas as $data) {
    echo "ID: " . $data->id . ", Siswa ID: " . $data->siswa_id . ", Sekolah ID: " . $data->sekolah_id . "\n";
}