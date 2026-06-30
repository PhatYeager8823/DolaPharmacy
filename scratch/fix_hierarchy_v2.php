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
    echo "Kết nối thất bại với Host mặc định, đang thử chuyển sang 127.0.0.1...\n";
    Config::set('database.connections.mysql.host', '127.0.0.1');
    DB::purge('mysql');
}

$root = DanhMuc::where('ten_danh_muc', 'Thuốc')->first();
$etc = DanhMuc::where('ten_danh_muc', 'Thuốc kê đơn')->first();
$otc = DanhMuc::where('ten_danh_muc', 'Thuốc không kê đơn')->first();

if (!$root || !$etc || !$otc) {
    die("LỖI: Không tìm thấy danh mục gốc. Hãy chắc chắn bạn đã chạy Seeder.\n");
}

// Dọn dẹp: Các mục con trực tiếp của Thuốc mà không phải là Kê đơn/Không kê đơn
$orphans = DanhMuc::where('danh_muc_cha_id', $root->id)
    ->whereNotIn('id', [$etc->id, $otc->id])
    ->get();

foreach ($orphans as $orphan) {
    $orphan->update(['danh_muc_cha_id' => null, 'slug' => 'hidden-' . $orphan->id]);
}

echo "XONG: Đã dọn dẹp " . $orphans->count() . " danh mục con dư thừa.\n";
echo "Cấu trúc hiện tại của 'Thuốc':\n";
foreach($root->children as $child) {
    echo " - " . $child->ten_danh_muc . " (ID: " . $child->id . ")\n";
}
