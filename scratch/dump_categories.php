<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DanhMuc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// Thử kết nối tới 127.0.0.1 nếu 'db' không hoạt động
try {
    DB::connection()->getPdo();
} catch (\Exception $e) {
    Config::set('database.connections.mysql.host', '127.0.0.1');
    DB::purge('mysql');
}

$all = DanhMuc::all();
echo "--- CATEGORIES DUMP ---\n";
foreach($all as $d) {
    echo "ID: " . $d->id . " | Name: [" . $d->ten_danh_muc . "] | ParentID: " . ($d->danh_muc_cha_id ?? 'NULL') . "\n";
}
echo "--- END DUMP ---\n";
