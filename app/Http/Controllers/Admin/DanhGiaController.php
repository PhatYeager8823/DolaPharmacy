<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhGia;

class DanhGiaController extends Controller
{
    // Hiển thị danh sách tất cả đánh giá
    public function index()
    {
        // Lấy tất cả đánh giá, xếp mới nhất lên đầu, lấy kèm thông tin Người Dùng và Thuốc
        $danhGias = DanhGia::with(['nguoiDung', 'thuoc'])->latest()->paginate(15);
        return view('admin.danhgias.index', compact('danhGias'));
    }

    // Bật/Tắt hiển thị đánh giá (Duyệt/Ẩn)
    public function toggleStatus($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->trang_thai = !$danhGia->trang_thai;
        $danhGia->save();

        $message = $danhGia->trang_thai ? 'Đã HIỂN THỊ lại bình luận thành công!' : 'Đã ẨN bình luận thành công!';
        return back()->with('success', $message);
    }

    // Xóa vĩnh viễn đánh giá
    public function destroy($id)
    {
        $danhGia = DanhGia::findOrFail($id);
        $danhGia->delete();

        return back()->with('success', 'Đã xóa bình luận vĩnh viễn!');
    }
}
