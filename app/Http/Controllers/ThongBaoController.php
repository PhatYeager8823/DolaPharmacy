<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThongBao;
use Illuminate\Support\Facades\Auth;

class ThongBaoController extends Controller
{
    // Trang danh sách thông báo (Nằm trong khu vực Tài khoản của Khách)
    public function index()
    {
        $user = Auth::user();

        // [THÊM MỚI] Dòng này là bắt buộc để Sidebar hoạt động
        $membership = $user->getMembershipData();

        // Lấy thông báo (Code cũ giữ nguyên)
        $notifications = ThongBao::where('nguoi_dung_id', $user->id)
                                 ->latest()
                                 ->paginate(10);

        // Đánh dấu đã đọc (Code cũ giữ nguyên)
        ThongBao::where('nguoi_dung_id', $user->id)
                ->where('da_xem', 0)
                ->update(['da_xem' => 1]);

        // [QUAN TRỌNG] Thêm 'user' và 'membership' vào đây
        return view('account.notifications', compact('notifications', 'user', 'membership'));
    }
}
