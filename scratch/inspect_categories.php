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
    $allRoots = DanhMuc::whereNull('danh_muc_cha_id')->get();
    echo "Các danh mục gốc hiện có:\n";
    foreach($allRoots as $r) echo "- " . $r->ten_danh_muc . " (ID: " . $r->id . ")\n";
} else {
    echo "DANH MỤC GỐC: [" . $root->ten_danh_muc . "] (ID: " . $root->id . ")\n";
    $children = DanhMuc::where('danh_muc_cha_id', $root->id)->get();
    if ($children->isEmpty()) {
        echo "CẢNH BÁO: Mục 'Thuốc' không có mục con nào trực tiếp.\n";
    } else {
        foreach ($children as $c) {
            echo "  - Cấp 2: [" . $c->ten_danh_muc . "] (ID: " . $c->id . ")\n";
            $gChildren = DanhMuc::where('danh_muc_cha_id', $c->id)->get();
            foreach ($gChildren as $gc) {
                echo "    -- Cấp 3: [" . $gc->ten_danh_muc . "]\n";
            }
        }
    }
}
