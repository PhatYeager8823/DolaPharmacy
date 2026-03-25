<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('videos')->truncate();

        DB::table('videos')->insert([
            [
                'tieu_de' => 'Hội chứng khó đọc là gì? Dấu hiệu nhận biết',
                'slug' => 'hoi-chung-kho-doc',
                'thumbnail' => 'video_1.webp',
                'youtube_id' => 'zaP_K4LM2TU', // ID video Youtube (ví dụ)
                'mo_ta_ngan' => 'Tìm hiểu về hội chứng khó đọc và cách hỗ trợ trẻ...',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Hệ tiêu hóa hoạt động như thế nào?',
                'slug' => 'he-tieu-hoa-hoat-dong-nhu-the-nao',
                'thumbnail' => 'video_2.webp',
                'youtube_id' => 'Og5xAdC8EUI',
                'mo_ta_ngan' => 'Hành trình của thức ăn trong cơ thể bạn diễn ra ra sao?',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Vitamin hoạt động trong cơ thể ra sao?',
                'slug' => 'vitamin-hoat-dong-nhu-the-nao',
                'thumbnail' => 'video_3.webp',
                'youtube_id' => 'ISZLTJH5lYg',
                'mo_ta_ngan' => 'Tại sao chúng ta cần Vitamin mỗi ngày?',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Tại sao ngồi nhiều lại có hại cho sức khỏe?',
                'slug' => 'tai-sao-ngoi-nhieu-co-hai',
                'thumbnail' => 'video_4.webp',
                'youtube_id' => 'wUEl8KrMz14',
                'mo_ta_ngan' => 'Những tác hại khôn lường của việc ngồi quá lâu một chỗ.',
                'created_at' => now(),
            ],
        ]);
    }
}
