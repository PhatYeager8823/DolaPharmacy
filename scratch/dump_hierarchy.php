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

$root = DanhMuc::where('ten_danh_muc', 'like', '%Thuốc%')->whereNull('danh_muc_cha_id')->first();

if (!$root) {
    echo "LỖI: Không tìm thấy danh mục gốc là 'Thuốc'.\n";
} else {
    echo "GỐC: [" . $root->ten_danh_muc . "] (ID: " . $root->id . ")\n";
    $mids = DanhMuc::where('danh_muc_cha_id', $root->id)->get();
    foreach ($mids as $m) {
        echo "  - TẦNG TRUNG GIAN: [" . $m->ten_danh_muc . "] (ID: " . $m->id . ")\n";
        $leaves = DanhMuc::where('danh_muc_cha_id', $m->id)->get();
        foreach ($leaves as $l) {
            echo "    -- MỤC CON: [" . $l->ten_danh_muc . "] (ID: " . $l->id . ")\n";
        }
    }
}
