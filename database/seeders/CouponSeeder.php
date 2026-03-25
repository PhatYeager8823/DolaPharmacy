<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <--- NHỚ DÒNG NÀY

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dùng hàm chuẩn của Laravel để tắt khóa ngoại
        Schema::disableForeignKeyConstraints();

        // 2. Xóa sạch bảng (Giờ thì được phép xóa rồi)
        DB::table('coupons')->truncate();

        // 3. Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();

        // 4. Thêm dữ liệu
        DB::table('coupons')->insert([
            [
                'code' => 'BANMOI',
                'type' => 'fixed',
                'value' => 50000,
                'image' => 'voucher_50k.webp',
                'expiry_date' => '2030-12-31 23:59:59',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SALE10',
                'type' => 'percent',
                'value' => 10, // 10%
                'image' => 'voucher_10percent.webp',
                'expiry_date' => '2030-12-31 23:59:59',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
