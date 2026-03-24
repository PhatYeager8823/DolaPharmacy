<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('brands')->insert([
            ['id' => 1, 'ten' => 'GSK', 'slug' => 'gsk', 'xuat_xu' => 'Anh', 'created_at' => now()],
            ['id' => 2, 'ten' => 'UPSA', 'slug' => 'upsa', 'xuat_xu' => 'Pháp', 'created_at' => now()],
            ['id' => 3, 'ten' => 'Dược Hậu Giang', 'slug' => 'duoc-hau-giang', 'xuat_xu' => 'Việt Nam', 'created_at' => now()],
            ['id' => 4, 'ten' => 'Johnson & Johnson', 'slug' => 'johnson-johnson', 'xuat_xu' => 'Mỹ', 'created_at' => now()],
            ['id' => 5, 'ten' => 'United Pharma', 'slug' => 'united-pharma', 'xuat_xu' => 'Việt Nam', 'created_at' => now()],
            ['id' => 6, 'ten' => 'Thai Nakorn Patana', 'slug' => 'thai-nakorn-patana', 'xuat_xu' => 'Thái Lan', 'created_at' => now()],
            ['id' => 7, 'ten' => 'Hisamitsu', 'slug' => 'hisamitsu', 'xuat_xu' => 'Nhật Bản', 'created_at' => now()],
            ['id' => 8, 'ten' => 'Engelhard', 'slug' => 'engelhard', 'xuat_xu' => 'Đức', 'created_at' => now()],
            ['id' => 9, 'ten' => 'Ikeadamohando', 'slug' => 'ikeadamohando', 'xuat_xu' => 'Nhật Bản', 'created_at' => now()],
            ['id' => 10, 'ten' => 'Nam Dược', 'slug' => 'nam-duoc', 'xuat_xu' => 'Việt Nam', 'created_at' => now()],
            ['id' => 11, 'ten' => 'Ipsen', 'slug' => 'ipsen', 'xuat_xu' => 'Pháp', 'created_at' => now()],
            ['id' => 12, 'ten' => 'OPC', 'slug' => 'opc', 'xuat_xu' => 'Việt Nam', 'created_at' => now()],
            ['id' => 13, 'ten' => 'Yuhan', 'slug' => 'yuhan', 'xuat_xu' => 'Hàn Quốc', 'created_at' => now()],
            ['id' => 14, 'ten' => 'Sanofi', 'slug' => 'sanofi', 'xuat_xu' => 'Pháp', 'created_at' => now()],
            ['id' => 15, 'ten' => 'Borden', 'slug' => 'borden', 'xuat_xu' => 'Singapore', 'created_at' => now()],
            ['id' => 16, 'ten' => 'Bayer', 'slug' => 'bayer', 'xuat_xu' => 'Đức', 'created_at' => now()],
            ['id' => 17, 'ten' => 'Gamma Chemicals', 'slug' => 'gamma-chemicals', 'xuat_xu' => 'Việt Nam', 'created_at' => now()],
            ['id' => 18, 'ten' => 'Leo Pharma', 'slug' => 'leo-pharma', 'xuat_xu' => 'Đan Mạch', 'created_at' => now()],
            ['id' => 19, 'ten' => 'Stada', 'slug' => 'stada', 'xuat_xu' => 'Đức', 'created_at' => now()],
        ]);
    }
}
