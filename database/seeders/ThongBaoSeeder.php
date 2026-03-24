<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ThongBao;
use App\Models\NguoiDung;

class ThongBaoSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user đầu tiên để tạo thông báo
        $user = NguoiDung::first();

        if ($user) {
            ThongBao::insert([
                [
                    'nguoi_dung_id' => $user->id,
                    'tieu_de' => 'Chào mừng bạn mới!',
                    'noi_dung' => 'Cảm ơn bạn đã đăng ký thành viên tại Dola Pharmacy. Tặng bạn mã GIAM10K cho đơn đầu tiên.',
                    'loai' => 'he_thong',
                    'da_xem' => 0, // Chưa xem
                    'created_at' => now(),
                ],
                [
                    'nguoi_dung_id' => $user->id,
                    'tieu_de' => 'Đơn hàng #DH001 đã được xác nhận',
                    'noi_dung' => 'Đơn hàng của bạn đang được đóng gói và sẽ sớm được giao cho đơn vị vận chuyển.',
                    'loai' => 'don_hang',
                    'da_xem' => 0, // Chưa xem
                    'created_at' => now()->subHours(2),
                ],
                [
                    'nguoi_dung_id' => $user->id,
                    'tieu_de' => 'Khuyến mãi sốc tháng 11',
                    'noi_dung' => 'Giảm giá lên đến 50% các sản phẩm thực phẩm chức năng.',
                    'loai' => 'khuyen_mai',
                    'da_xem' => 1, // Đã xem
                    'created_at' => now()->subDays(1),
                ]
            ]);
        }
    }
}
