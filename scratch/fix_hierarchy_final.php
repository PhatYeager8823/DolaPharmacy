<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DanhMuc;

$root = DanhMuc::where('ten_danh_muc', 'Thuốc')->first();
$etc = DanhMuc::where('ten_danh_muc', 'Thuốc kê đơn')->first();
$otc = DanhMuc::where('ten_danh_muc', 'Thuốc không kê đơn')->first();

if (!$root || !$etc || !$otc) {
    die("Lỗi: Không tìm thấy các danh mục gốc cần thiết.\n");
}

// BƯỚC 1: Dọn dẹp con của Thuốc
$orphans = DanhMuc::where('danh_muc_cha_id', $root->id)
    ->whereNotIn('id', [$etc->id, $otc->id])
    ->get();

foreach ($orphans as $orphan) {
    $orphan->update(['danh_muc_cha_id' => null, 'slug' => 'hidden-' . $orphan->id . '-' . time()]);
}

echo "1. Đã dọn dẹp " . $orphans->count() . " danh mục rác dưới mục Thuốc.\n";

// BƯỚC 2: Kiểm tra cấu trúc cây
$rootCount = $root->children->count();
echo "2. Số lượng danh mục con hiện tại của mục Thuốc: " . $rootCount . "\n";
foreach($root->children as $child) {
    echo "   - " . $child->ten_danh_muc . " (L1) có " . $child->children->count() . " danh mục nhỏ (L2)\n";
}

if ($rootCount == 2) {
    echo "SUCCESS: Cấu trúc 3 tầng đã hoàn tất.\n";
} else {
    echo "WARNING: Cấu trúc vẫn chưa chuẩn, cần kiểm tra lại.\n";
}
