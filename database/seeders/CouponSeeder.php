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

        // 4. Thêm dữ liệu (CHAO10K và CHAO20K như yêu cầu)
        DB::table('coupons')->insert([
            [
                'code' => 'CHAO10K',
                'type' => 'fixed',
                'value' => 10000,
                'image' => 'voucher_10k.webp',
                'expiry_date' => '2030-12-31 23:59:59',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CHAO20K',
                'type' => 'fixed',
                'value' => 20000,
                'image' => 'voucher_20k.webp',
                'expiry_date' => '2030-12-31 23:59:59',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
