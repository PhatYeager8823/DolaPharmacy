<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\NguoiDung;
use App\Models\Thuoc;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy user đầu tiên để gán đơn hàng
        $user = NguoiDung::first();
        if(!$user) return;

        // Lấy vài thuốc ngẫu nhiên
        $thuocs = Thuoc::inRandomOrder()->take(3)->get();

        // --- ĐƠN HÀNG 1: ĐÃ GIAO ---
        $order1 = Order::create([
            'ma_don_hang' => 'DH' . rand(10000, 99999),
            'nguoi_dung_id' => $user->id,
            'ten_nguoi_nhan' => $user->ten,
            'sdt_nguoi_nhan' => $user->sdt ?? '0909123456',
            'dia_chi_giao_hang' => '123 Nguyễn Văn Linh, Q.7, TP.HCM',
            'tong_tien' => 0, // Tính sau
            'trang_thai' => 'da_giao',
            'created_at' => now()->subDays(5), // Mua cách đây 5 ngày
        ]);

        $total1 = 0;
        foreach($thuocs as $t) {
            $qty = rand(1, 2);
            $subtotal = $qty * $t->gia_ban;

            OrderItem::create([
                'order_id' => $order1->id,
                'thuoc_id' => $t->id,
                'ten_thuoc' => $t->ten_thuoc,
                'so_luong' => $qty,
                'gia_ban' => $t->gia_ban,
                'thanh_tien' => $subtotal
            ]);
            $total1 += $subtotal;
        }
        $order1->update(['tong_tien' => $total1]);

        // --- ĐƠN HÀNG 2: ĐANG GIAO ---
        $order2 = Order::create([
            'ma_don_hang' => 'DH' . rand(10000, 99999),
            'nguoi_dung_id' => $user->id,
            'ten_nguoi_nhan' => $user->ten,
            'sdt_nguoi_nhan' => $user->sdt ?? '0909123456',
            'dia_chi_giao_hang' => '456 Lê Lợi, Q.1, TP.HCM',
            'tong_tien' => 0,
            'trang_thai' => 'dang_giao',
            'created_at' => now()->subDays(1),
        ]);

        // Thêm 1 món cho đơn 2
        $t2 = $thuocs->first();
        OrderItem::create([
            'order_id' => $order2->id,
            'thuoc_id' => $t2->id,
            'ten_thuoc' => $t2->ten_thuoc,
            'so_luong' => 1,
            'gia_ban' => $t2->gia_ban,
            'thanh_tien' => $t2->gia_ban
        ]);
        $order2->update(['tong_tien' => $t2->gia_ban]);
    }
}
