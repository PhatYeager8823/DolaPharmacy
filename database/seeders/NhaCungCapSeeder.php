<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NhaCungCapSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa dữ liệu cũ nếu cần (hoặc dùng migrate:fresh)
        // DB::table('nha_cung_caps')->truncate();

        DB::table('nha_cung_caps')->insert([
            ['id' => 1, 'ten' => 'Zuellig Pharma Việt Nam', 'email' => 'orders@zuelligpharma.com', 'sdt' => '02839102650', 'dia_chi' => 'KCN Tân Tạo, Q. Bình Tân, TP.HCM', 'created_at' => now()],
            ['id' => 2, 'ten' => 'Codupha (Dược phẩm TW2)', 'email' => 'cskh@codupha.com.vn', 'sdt' => '02838652327', 'dia_chi' => '334 Tô Hiến Thành, P.14, Q.10, TP.HCM', 'created_at' => now()],
            ['id' => 3, 'ten' => 'Sapharco (Dược Sài Gòn)', 'email' => 'info@sapharco.com', 'sdt' => '02838354670', 'dia_chi' => '18 Nguyễn Tất Thành, Q.4, TP.HCM', 'created_at' => now()],
            ['id' => 4, 'ten' => 'Vimedimex', 'email' => 'contact@vimedimex.vn', 'sdt' => '02839304620', 'dia_chi' => '246 Cống Quỳnh, Q.1, TP.HCM', 'created_at' => now()],
        ]);
    }
}
