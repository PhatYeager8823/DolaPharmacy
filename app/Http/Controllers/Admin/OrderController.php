<?php

namespace App\Http\Controllers\Admin; // <--- Namespace phải là Admin

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ThongBao; // <--- Nhớ import ThongBao
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 1. Danh sách đơn hàng
    public function index()
    {
        $orders = Order::with(['items', 'nguoiDung'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // 2. Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with(['items.thuoc', 'nguoiDung'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // 3. Cập nhật trạng thái đơn hàng (Có gửi thông báo)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $oldStatus = $order->trang_thai;

        if ($order->trang_thai == 'da_giao' || $order->trang_thai == 'da_huy') {
            return redirect()->back()->with('error', 'Đơn hàng này đã hoàn tất hoặc đã hủy. Không thể thay đổi trạng thái!');
        }

        // Cập nhật trạng thái
        $order->trang_thai = $request->trang_thai;
        $order->save();

        // Gửi thông báo nếu trạng thái thay đổi
        // Gửi thông báo nếu trạng thái thay đổi
        if ($oldStatus != $request->trang_thai) {

            $msg = "";
            switch ($request->trang_thai) {
                case 'cho_xac_nhan':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đang chờ xác nhận.";
                    break;
                case 'xac_nhan':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã được cửa hàng xác nhận và đang đóng gói 📦";
                    break;
                case 'dang_giao':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đang trên đường giao đến bạn 🚚";
                    break;

                // === [THÊM CASE NÀY ĐỂ SỬA LỖI] ===
                case 'da_giao':
                    $msg = "Shipper báo đã giao đơn hàng #{$order->ma_don_hang} thành công ✅";
                    break;
                // ===================================

                case 'hoan_thanh':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã hoàn thành. Cảm ơn bạn đã mua sắm! ❤️";
                    break;
                case 'da_huy':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã bị hủy.";
                    break;
                default:
                    // Nếu gặp trạng thái lạ, tự động làm đẹp (VD: 'tra_hang' -> 'Tra hang')
                    $niceName = ucfirst(str_replace('_', ' ', $request->trang_thai));
                    $msg = "Đơn hàng #{$order->ma_don_hang} cập nhật trạng thái: $niceName";
            }

            ThongBao::create([
                'nguoi_dung_id' => $order->nguoi_dung_id,
                'tieu_de'       => 'Cập nhật đơn hàng 🔔',
                'noi_dung'      => $msg,
                'loai'          => 'don_hang',
                'duong_dan'     => route('account.orders'),
                'da_xem'        => 0
            ]);
        }

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
