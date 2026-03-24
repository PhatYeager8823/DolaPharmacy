<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DanhMucSeeder extends Seeder
{
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại để truncate bảng cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('danh_mucs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ================================================================
        // 1. NHÓM THUỐC (Chia theo bệnh lý)
        // ================================================================
        $thuocId = DB::table('danh_mucs')->insertGetId([
            'ten_danh_muc' => 'Thuốc',
            'slug' => 'thuoc',
            'hinh_anh' => 'thuoc.png',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $thuocSubs = [
            ['ten' => 'Thuốc tiêu hóa, Gan mật', 'slug' => 'tieu-hoa-gan-mat', 'img' => 'tieu-hoa.png'],
            ['ten' => 'Thuốc thần kinh, Não bộ', 'slug' => 'than-kinh-nao-bo', 'img' => 'than-kinh.png'],
            ['ten' => 'Thuốc cơ xương khớp, Gout', 'slug' => 'co-xuong-khop-gout', 'img' => 'co-xuong-khop.png'], // Mới
            ['ten' => 'Thuốc tim mạch, Huyết áp', 'slug' => 'tim-mach-huyet-ap', 'img' => 'tim-mach.png'], // Mới
            ['ten' => 'Thuốc giảm đau, Hạ sốt', 'slug' => 'giam-dau-ha-sot', 'img' => 'giam-dau-ha-sot.png'],
            ['ten' => 'Hô hấp, Ho, Cảm cúm', 'slug' => 'ho-hap-cam-cum', 'img' => 'ho-cam-lanh.png'],
            ['ten' => 'Da liễu, Dị ứng', 'slug' => 'da-lieu-di-ung', 'img' => 'ngoai-da.png'],
            ['ten' => 'Mắt - Tai - Mũi - Họng', 'slug' => 'mat-tai-mui-hong', 'img' => 'mat-tay-mui-hong.png'],
            ['ten' => 'Dầu gió, Cao xoa', 'slug' => 'dau-gio-cao-xoa', 'img' => 'dau-gio-cao-xoa.png'],
        ];

        foreach ($thuocSubs as $sub) {
            DB::table('danh_mucs')->insert([
                'ten_danh_muc' => $sub['ten'],
                'slug' => $sub['slug'],
                'danh_muc_cha_id' => $thuocId,
                'hinh_anh' => $sub['img'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // ================================================================
        // 2. THỰC PHẨM CHỨC NĂNG
        // ================================================================
        $tpcnId = DB::table('danh_mucs')->insertGetId([
            'ten_danh_muc' => 'Thực phẩm chức năng',
            'slug' => 'thuc-pham-chuc-nang',
            'hinh_anh' => 'thuc-pham-chuc-nang.png',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $tpcnSubs = [
            ['ten' => 'Vitamin & Khoáng chất', 'slug' => 'vitamin-khoang-chat', 'img' => 'vitamin-khoang-chat.png'],
            ['ten' => 'Hỗ trợ làm đẹp', 'slug' => 'ho-tro-lam-dep', 'img' => 'lam-dep.png'],
            ['ten' => 'Sức khỏe tim mạch', 'slug' => 'suc-khoe-tim-mach', 'img' => 'sk-tim-mach.png'],
            ['ten' => 'Dinh dưỡng & Mẹ bé', 'slug' => 'dinh-duong-me-be', 'img' => 'dd-me-be.png'],
        ];

        foreach ($tpcnSubs as $sub) {
            DB::table('danh_mucs')->insert([
                'ten_danh_muc' => $sub['ten'],
                'slug' => $sub['slug'],
                'danh_muc_cha_id' => $tpcnId,
                'hinh_anh' => $sub['img'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // ================================================================
        // 3. THIẾT BỊ Y TẾ
        // ================================================================
        $tbytId = DB::table('danh_mucs')->insertGetId([
            'ten_danh_muc' => 'Thiết bị y tế',
            'slug' => 'thiet-bi-y-te',
            'hinh_anh' => 'dung-cu-y-te.png',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $tbytSubs = [
            ['ten' => 'Máy đo (Huyết áp, Đường huyết)', 'slug' => 'may-do-y-te', 'img' => 'may-do.png'],
            ['ten' => 'Nhiệt kế', 'slug' => 'nhiet-ke', 'img' => 'nhiet-ke.png'],
            ['ten' => 'Khẩu trang y tế', 'slug' => 'khau-trang-y-te', 'img' => 'khau-trang.png'],
            ['ten' => 'Dụng cụ sơ cứu', 'slug' => 'dung-cu-so-cuu', 'img' => 'dung-cu-so-cuu.png'],
        ];

        foreach ($tbytSubs as $sub) {
            DB::table('danh_mucs')->insert([
                'ten_danh_muc' => $sub['ten'],
                'slug' => $sub['slug'],
                'danh_muc_cha_id' => $tbytId,
                'hinh_anh' => $sub['img'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        // ================================================================
        // 4. CHĂM SÓC CÁ NHÂN
        // ================================================================
        $cscnId = DB::table('danh_mucs')->insertGetId([
            'ten_danh_muc' => 'Chăm sóc cá nhân',
            'slug' => 'cham-soc-ca-nhan',
            'hinh_anh' => 'cham-soc-ca-nhan.png',
            'created_at' => now(), 'updated_at' => now()
        ]);

        $cscnSubs = [
            ['ten' => 'Chăm sóc cơ thể', 'slug' => 'cham-soc-co-the', 'img' => 'cham-soc-co-the.png'],
            ['ten' => 'Chăm sóc da mặt', 'slug' => 'cham-soc-da-mat', 'img' => 'cham-soc-da-mat.png'],
            ['ten' => 'Vệ sinh phụ nữ', 'slug' => 've-sinh-phu-nu', 'img' => 've-sinh-phu-nu.png'],
            ['ten' => 'Chăm sóc răng miệng', 'slug' => 'cham-soc-rang-mieng', 'img' => 'cham-soc-rang-mieng.png'],
        ];

        foreach ($cscnSubs as $sub) {
            DB::table('danh_mucs')->insert([
                'ten_danh_muc' => $sub['ten'],
                'slug' => $sub['slug'],
                'danh_muc_cha_id' => $cscnId,
                'hinh_anh' => $sub['img'],
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}
