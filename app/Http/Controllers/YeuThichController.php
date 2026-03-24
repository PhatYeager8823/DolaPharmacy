<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YeuThich;
use Illuminate\Support\Facades\Auth;

class YeuThichController extends Controller
{
    // 1. Xem danh sách yêu thích
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem sản phẩm yêu thích.');
        }

        // Lấy danh sách thuốc mà user đã like
        $favorites = YeuThich::where('nguoi_dung_id', Auth::id())
                             ->with('thuoc') // Eager load thuốc
                             ->latest()
                             ->get();

        return view('pages.wishlist', compact('favorites'));
    }

    // 2. Thả tim / Bỏ tim (Toggle)
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'login_required', 'message' => 'Bạn cần đăng nhập để lưu sản phẩm!']);
        }

        $userId = Auth::id();
        $thuocId = $request->id;

        // Kiểm tra xem đã thích chưa
        $exist = YeuThich::where('nguoi_dung_id', $userId)->where('thuoc_id', $thuocId)->first();

        if ($exist) {
            $exist->delete(); // Đã thích rồi thì xóa (Unlike)
            $action = 'removed';
            $message = 'Đã xóa khỏi yêu thích';
        } else {
            YeuThich::create(['nguoi_dung_id' => $userId, 'thuoc_id' => $thuocId]); // Chưa thì thêm (Like)
            $action = 'added';
            $message = 'Đã thêm vào yêu thích';
        }

        // Đếm lại tổng số lượng
        $count = YeuThich::where('nguoi_dung_id', $userId)->count();

        return response()->json([
            'status' => 'success',
            'action' => $action,
            'count' => $count,
            'message' => $message
        ]);
    }
}
