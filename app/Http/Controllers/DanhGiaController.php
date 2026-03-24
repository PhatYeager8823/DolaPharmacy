<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhGia;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DanhGiaController extends Controller
{
    public function store(Request $request, $thuoc_id)
    {
        $request->validate([
            'so_sao' => 'required|integer|min:1|max:5',
            'noi_dung' => 'required|string|max:1000',
        ], [
            'so_sao.required' => 'Vui lòng chọn số sao.',
            'noi_dung.required' => 'Vui lòng nhập nội dung đánh giá.',
            'noi_dung.max' => 'Nội dung đánh giá không được vượt quá 1000 ký tự.'
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để đánh giá sản phẩm.');
        }

        // Tùy chọn 1: Chỉ cho phép người đã từng mua mới được đánh giá
        $hasBought = Order::where('nguoi_dung_id', Auth::id())
                          ->whereHas('items', function($q) use ($thuoc_id) {
                              $q->where('thuoc_id', $thuoc_id);
                          })
                          ->where('trang_thai', 'da_giao') // Bắt buộc phải nhận hàng xong mới cho review
                          ->exists();

        if (!$hasBought) {
            // Kiểm tra xem có đơn hàng nào nhưng chưa giao không để báo chi tiết hơn
            $anyOrder = Order::where('nguoi_dung_id', Auth::id())
                          ->whereHas('items', function($q) use ($thuoc_id) {
                              $q->where('thuoc_id', $thuoc_id);
                          })->first();
            
            if ($anyOrder) {
                return back()->with('error', 'Đơn hàng của bạn đang được xử lý. Vui lòng nhận hàng thành công trước khi để lại đánh giá.');
            }

            return back()->with('error', 'Bạn chỉ được đánh giá những sản phẩm đã mua tại hệ thống.');
        }

        // Tùy chọn 2: Mỗi người chỉ được đánh giá 1 lần cho 1 sản phẩm
        $exists = DanhGia::where('thuoc_id', $thuoc_id)
                         ->where('nguoi_dung_id', Auth::id())
                         ->first();
        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi. Cảm ơn bạn!');
        }

        DanhGia::create([
            'thuoc_id' => $thuoc_id,
            'nguoi_dung_id' => Auth::id(),
            'so_sao' => $request->so_sao,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 1 // Cho hiển thị luôn (Có thể đổi thành 0 nếu muốn admin duyệt)
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công! Cảm ơn bạn đã đóng góp ý kiến.');
    }
}
