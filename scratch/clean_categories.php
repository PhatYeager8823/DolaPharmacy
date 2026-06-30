<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DanhMuc;

$root = DanhMuc::where('ten_danh_muc', 'Thuốc')->first();

if ($root) {
    // Tìm các mục là con của Thuốc nhưng KHÔNG PHẢI là "Thuốc kê đơn" hoặc "Thuốc không kê đơn"
    $orphans = DanhMuc::where('danh_muc_cha_id', $root->id)
        ->whereNotIn('ten_danh_muc', ['Thuốc kê đơn', 'Thuốc không kê đơn'])
        ->get();

    foreach ($orphans as $orphan) {
        $orphan->update([
            'danh_muc_cha_id' => null, // Gỡ bỏ khỏi mục Thuốc
            'slug' => 'old-' . $orphan->slug // Đổi slug để ẩn/tránh trùng
        ]);
    }
    echo "Đã dọn dẹp xong " . $orphans->count() . " danh mục cũ dưới mục Thuốc.\n";
} else {
    echo "Không tìm thấy danh mục gốc 'Thuốc'.\n";
}
