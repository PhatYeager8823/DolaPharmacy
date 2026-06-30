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
        if ($oldStatus != $request->trang_thai) {

            $msg = "";
            switch ($request->trang_thai) {
                case 'cho_xac_nhan':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đang chờ xác nhận.";
                    break;
                case 'cho_lay_hang':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã được cửa hàng đóng gói và đang chờ ĐVVC đến lấy 📦";
                    break;
                case 'dang_giao':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã được lấy và đang trên đường giao đến bạn 🚚";
                    break;
                case 'da_giao':
                    $msg = "Shipper báo đã giao đơn hàng #{$order->ma_don_hang} thành công ✅";
                    break;
                case 'tra_hang':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã được hoàn trả.";
                    break;
                case 'da_huy':
                    $msg = "Đơn hàng #{$order->ma_don_hang} đã bị hủy.";
                    break;
                default:
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

    /**
     * API kiểm tra đơn hàng mới cho Admin (Real-time polling)
     */
    public function checkNewOrders()
    {
        // Lấy các đơn hàng chưa được thông báo cho admin
        $newOrders = Order::where('is_admin_notified', 0)
            ->latest()
            ->get();

        if ($newOrders->count() > 0) {
            // Đánh dấu ngay là đã thông báo để tránh lặp lại ở lần poll sau
            Order::whereIn('id', $newOrders->pluck('id'))->update(['is_admin_notified' => 1]);
        }

        return response()->json([
            'success' => true,
            'count'   => $newOrders->count(),
            'orders'  => $newOrders
        ]);
    }
}
