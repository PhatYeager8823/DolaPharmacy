<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\Order; // Nhớ import model Order để kiểm tra
use Illuminate\Http\Request;

class UserController extends Controller
{
    // 1. Xem danh sách khách hàng (Có Tìm kiếm)
    public function index(Request $request)
    {
        $query = NguoiDung::where('vai_tro', '!=', 'admin');

        // --- Xử lý Tìm kiếm ---
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('sdt', 'like', "%{$keyword}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    // 2. Khóa / Mở khóa tài khoản
    public function toggleStatus($id)
    {
        $user = NguoiDung::findOrFail($id);

        // Đảo ngược trạng thái
        $user->trang_thai = !$user->trang_thai;
        $user->save();

        $statusMsg = $user->trang_thai ? 'Đã mở khóa tài khoản.' : 'Đã khóa tài khoản.';
        return back()->with('success', $statusMsg);
    }

    // 3. Xóa tài khoản (Có kiểm tra an toàn)
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);

        // --- Kiểm tra an toàn: Có đơn hàng thì KHÔNG cho xóa ---
        // Giả sử bảng orders có cột nguoi_dung_id
        $hasOrders = Order::where('nguoi_dung_id', $user->id)->exists();

        if ($hasOrders) {
            return back()->with('error', 'Không thể xóa! Khách hàng này đã có lịch sử mua hàng. Bạn chỉ có thể KHÓA tài khoản.');
        }

        // Nếu sạch sẽ (chưa mua gì) thì cho xóa
        $user->delete();

        return back()->with('success', 'Đã xóa khách hàng vĩnh viễn.');
    }
}
