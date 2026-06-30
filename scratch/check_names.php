<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DanhMuc;

$all = DanhMuc::all();
echo "--- DANH SACH DANH MUC ---\n";
foreach($all as $d) {
    echo "ID: " . $d->id . " | Ten: [" . $d->ten_danh_muc . "] | Cha ID: " . ($d->danh_muc_cha_id ?? 'None') . "\n";
}
echo "--- HET ---\n";
