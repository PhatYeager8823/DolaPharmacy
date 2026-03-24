<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ThongBao;
use Carbon\Carbon;

class AutoUpdateOrderStatus extends Command
{
    // Tên lệnh để gọi (ví dụ: php artisan order:simulate)
    protected $signature = 'order:simulate';

    // Mô tả lệnh
    protected $description = 'Mô phỏng quy trình giao hàng tự động';

    public function handle()
    {
        $this->info('Bắt đầu quét đơn hàng để cập nhật trạng thái...');

        // -----------------------------------------------------------
        // 1. TỰ ĐỘNG XÁC NHẬN & GIAO HÀNG (Sau khi đặt 1 phút)
        // -----------------------------------------------------------
        // Tìm các đơn "Chờ xác nhận" đã tạo quá 1 phút trước
        $ordersToShip = Order::where('trang_thai', 'cho_xac_nhan')
                             ->where('created_at', '<=', Carbon::now()->subMinutes(1))
                             ->get();

        foreach ($ordersToShip as $order) {
            $order->update(['trang_thai' => 'dang_giao']);

            // Gửi thông báo
            $this->sendNotification($order, "Đơn hàng #{$order->ma_don_hang} đã được xác nhận và đang trên đường giao 🚚");

            $this->info("Đã chuyển đơn #{$order->ma_don_hang} sang Đang giao.");
        }

        // -----------------------------------------------------------
        // 2. TỰ ĐỘNG HOÀN THÀNH (Sau khi giao 2 phút)
        // -----------------------------------------------------------
        // Tìm các đơn "Đang giao" đã cập nhật (updated_at) quá 2 phút trước
        $ordersToComplete = Order::where('trang_thai', 'dang_giao')
                                 ->where('updated_at', '<=', Carbon::now()->subMinutes(2))
                                 ->get();

        foreach ($ordersToComplete as $order) {
            $order->update(['trang_thai' => 'da_giao']); // Hoặc 'hoan_thanh' tùy quy trình của bạn

            // Gửi thông báo
            $this->sendNotification($order, "Shipper báo đã giao đơn hàng #{$order->ma_don_hang} thành công ✅");

            $this->info("Đã chuyển đơn #{$order->ma_don_hang} sang Đã giao.");
        }

        $this->info('Hoàn tất quá trình mô phỏng.');
    }

    // Hàm phụ để tạo thông báo (Tránh lặp code)
    private function sendNotification($order, $content)
    {
        ThongBao::create([
            'nguoi_dung_id' => $order->nguoi_dung_id,
            'tieu_de'       => 'Cập nhật hành trình đơn hàng 🚀',
            'noi_dung'      => $content,
            'loai'          => 'don_hang',
            'duong_dan'     => route('account.orders'), // Lưu ý: route() có thể lỗi trong console, nên hardcode url hoặc check
            'da_xem'        => 0
        ]);
    }
}
