<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiVietSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('bai_viets')->truncate();

        DB::table('bai_viets')->insert([
            [
                'tieu_de' => 'Thai 13 tuần uống nước dừa được không? Lợi ích với mẹ bầu',
                'slug' => 'thai-13-tuan-uong-nuoc-dua',
                'hinh_anh' => 'blog_nuoc_dua.png',
                'mo_ta_ngan' => 'Nước dừa là thức uống rất phổ biến ở Việt Nam, có thể dễ dàng tìm mua và giàu giá trị dinh dưỡng...',
                'noi_dung' => '<p>Nội dung chi tiết bài viết về nước dừa...</p><p>Nước dừa chứa nhiều kali, magie...</p>',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Uống sắt đi ngoài màu đen có nguy hiểm không?',
                'slug' => 'uong-sat-di-ngoai-mau-den',
                'hinh_anh' => 'blog_satt_den.png',
                'mo_ta_ngan' => 'Uống sắt đi ngoài màu đen liệu có gây ảnh hưởng xấu gì đến sức khỏe hay không và làm thế nào để hạn chế...',
                'noi_dung' => '<p>Đây là hiện tượng bình thường khi cơ thể đào thải lượng sắt thừa...</p>',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Soda sữa hột gà có tác dụng gì cho sức khỏe?',
                'slug' => 'soda-sua-hot-ga',
                'hinh_anh' => 'blog_sua_hot_ga.png',
                'mo_ta_ngan' => 'Soda sữa hột gà giúp tăng cân cho người gầy, bổ sung protein và năng lượng tức thì...',
                'noi_dung' => '<p>Cách làm soda sữa hột gà ngon tại nhà...</p>',
                'created_at' => now(),
            ],
            [
                'tieu_de' => 'Trẻ uống kẽm có bị táo bón không? Lưu ý khi dùng',
                'slug' => 'tre-uong-kem-co-tao-bon-khong',
                'hinh_anh' => 'blog_keo_kem.png',
                'mo_ta_ngan' => 'Tại Việt Nam, tỷ lệ trẻ em bị thiếu kẽm lên đến 40% khiến bé chậm lớn, biếng ăn...',
                'noi_dung' => '<p>Kẽm là vi chất quan trọng...</p>',
                'created_at' => now(),
            ],
        ]);
    }
}
