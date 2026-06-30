<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tắt khóa ngoại và làm sạch bảng
        Schema::disableForeignKeyConstraints();
        DB::table('sliders')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Thêm dữ liệu mẫu dựa trên các ảnh đã có trong public/images/sliders
        DB::table('sliders')->insert([
            [
                'tieu_de' => 'Chăm sóc sức khỏe gia đình',
                'hinh_anh' => 'slider1.webp',
                'link' => '/san-pham',
                'mo_ta' => 'Cung cấp các loại thuốc thiết yếu cho gia đình bạn.',
                'thu_tu' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tieu_de' => 'Thực phẩm chức năng chính hãng',
                'hinh_anh' => 'slider2.webp',
                'link' => '/danh-muc/thuc-pham-chuc-nang',
                'mo_ta' => 'Nâng cao sức đề kháng với các sản phẩm từ thiên nhiên.',
                'thu_tu' => 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tieu_de' => 'Dược mỹ phẩm cao cấp',
                'hinh_anh' => 'slider3.webp',
                'link' => '/danh-muc/duoc-my-pham',
                'mo_ta' => 'Chăm sóc làn da toàn diện với các thương hiệu nổi tiếng.',
                'thu_tu' => 3,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tieu_de' => 'Tư vấn tận tâm 24/7',
                'hinh_anh' => 'slider4.webp',
                'link' => '/lien-he',
                'mo_ta' => 'Đội ngũ dược sĩ giàu kinh nghiệm luôn sẵn sàng hỗ trợ.',
                'thu_tu' => 4,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
