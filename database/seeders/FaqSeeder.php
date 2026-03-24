<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        // DB::table('faqs')->truncate();

        DB::table('faqs')->insert([
            [
                'cau_hoi' => 'Tôi có cần toa thuốc của bác sĩ để mua thuốc không?',
                'tra_loi' => 'Đối với các loại thuốc kê đơn (có ký hiệu Rx), bạn bắt buộc phải cung cấp hình ảnh toa thuốc hợp lệ của bác sĩ. Dược sĩ của chúng tôi sẽ kiểm tra và tư vấn trước khi xác nhận đơn hàng. Đối với thực phẩm chức năng và dược mỹ phẩm thì không cần toa.',
                'thu_tu' => 1,
                'is_active' => 1,
            ],
            [
                'cau_hoi' => 'Phí vận chuyển được tính như thế nào?',
                'tra_loi' => 'Dola Pharmacy miễn phí vận chuyển cho đơn hàng từ 300.000đ trong nội thành. Với các đơn hàng khác, phí ship đồng giá 20.000đ nội thành và 30.000đ ngoại thành/tỉnh.',
                'thu_tu' => 2,
                'is_active' => 1,
            ],
            [
                'cau_hoi' => 'Tôi có thể đổi trả hàng nếu mua nhầm không?',
                'tra_loi' => 'Quý khách có thể đổi trả trong vòng 3 ngày kể từ khi nhận hàng nếu sản phẩm còn nguyên tem mác, chưa qua sử dụng và có lỗi từ nhà sản xuất. Lưu ý: Thuốc trữ lạnh và thuốc tiêm không áp dụng đổi trả.',
                'thu_tu' => 3,
                'is_active' => 1,
            ],
            [
                'cau_hoi' => 'Sản phẩm tại Dola Pharmacy có chính hãng không?',
                'tra_loi' => 'Cam kết 100% sản phẩm tại Dola Pharmacy là hàng chính hãng, có hóa đơn đỏ và giấy tờ kiểm định chất lượng đầy đủ. Chúng tôi đền bù 200% nếu phát hiện hàng giả.',
                'thu_tu' => 4,
                'is_active' => 1,
            ],
            [
                'cau_hoi' => 'Thời gian giao hàng bao lâu?',
                'tra_loi' => 'Nội thành TP.HCM: Giao trong ngày hoặc 24h. Các tỉnh thành khác: 2-4 ngày làm việc tùy khu vực.',
                'thu_tu' => 5,
                'is_active' => 1,
            ],
        ]);
    }
}
