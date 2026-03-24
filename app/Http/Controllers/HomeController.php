<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thuoc;
use App\Models\Slider;
use App\Models\Video;   // Nhớ import Model Video
use App\Models\BaiViet;

class HomeController extends Controller
{
    public function index()
    {
        // 1. SLIDER (Giữ nguyên)
        $sliders = Slider::where('is_active', 1)->orderBy('thu_tu')->get();

        // =========================================================
        // LOGIC MỚI: CHỈ LẤY ĐÚNG SẢN PHẨM ĐƯỢC TÍCH DẤU (FLAG)
        // =========================================================

        // 2. SẢN PHẨM MỚI (Dựa vào cột is_new)
        // Chỉ hiện những thuốc có tích 'New' trong admin
        $newProducts = Thuoc::where('is_active', 1) // Phải đang bật
                            ->where('ke_don', 0)    // Không kê đơn
                            ->where('so_luong_ton', '>', 0) // <--- CHỈ LẤY CÒN HÀNG
                            ->where('is_new', 1)    // <--- CỘT QUAN TRỌNG
                            ->orderBy('created_at', 'desc')
                            ->take(8) // Lấy tối đa 8, nếu chỉ tích 2 thì hiện 2
                            ->get();

        // 3. ƯU ĐÃI ĐỘC QUYỀN (Dựa vào cột is_exclusive)
        $exclusiveProducts = Thuoc::where('is_active', 1)
                                  ->where('ke_don', 0)
                                  ->where('so_luong_ton', '>', 0) // <--- CHỈ LẤY CÒN HÀNG
                                  ->where('is_exclusive', 1) // <--- CỘT QUAN TRỌNG
                                  ->orderBy('created_at', 'desc')
                                  ->take(6)
                                  ->get();

        // 4. GỢI Ý HÔM NAY (Dựa vào cột is_suggested)
        $suggestProducts = Thuoc::where('is_active', 1)
                                ->where('ke_don', 0)
                                ->where('so_luong_ton', '>', 0) // <--- CHỈ LẤY CÒN HÀNG
                                ->where('is_suggested', 1) // <--- CỘT QUAN TRỌNG
                                ->orderBy('created_at', 'desc')
                                ->take(6)
                                ->get();

        // 5. SẢN PHẨM NỔI BẬT (Dựa vào cột is_featured)
        // (Bạn nhớ tạo cột này trong database nhé: ALTER TABLE thuocs ADD COLUMN is_featured TINYINT(1) DEFAULT 0;)
        $featuredProducts = Thuoc::where('is_active', 1)
                                 ->where('ke_don', 0)
                                 ->where('so_luong_ton', '>', 0) // <--- CHỈ LẤY CÒN HÀNG
                                 ->where('is_featured', 1) // <--- CỘT QUAN TRỌNG
                                 ->orderBy('created_at', 'desc')
                                 ->take(8)
                                 ->get();

        // 6. KHUYẾN MÃI HOT (Tự động dựa trên giá: Có giảm giá là hiện)
        // Mục này thường tự động là tốt nhất, không cần tích tay
        $hotDeals = Thuoc::where('is_active', 1)
                         ->where('ke_don', 0)
                         ->where('so_luong_ton', '>', 0) // <--- CHỈ LẤY CÒN HÀNG
                         ->where('gia_cu', '>', 'gia_ban') // Giá cũ > Giá bán
                         ->orderBy('created_at', 'desc')
                         ->take(8)
                         ->get();

        // 1. LẤY DỮ LIỆU VIDEO (Lấy những video đang active, mới nhất)
        $videos = Video::where('is_active', 1)->latest()->take(10)->get();

        // 2. LẤY DỮ LIỆU TIN TỨC / BÀI VIẾT
        // Giả sử bảng của bạn là 'bai_viets'
        $allBlogs = BaiViet::where('is_active', 1)->latest()->take(6)->get();

        // Chia tin tức làm 2 phần:
        // - 2 tin mới nhất nằm to bên trái
        $mainBlogs = $allBlogs->take(2);
        // - 4 tin tiếp theo nằm danh sách bên phải
        $sideBlogs = $allBlogs->skip(2);

        return view('home', compact(
            'videos',
            'mainBlogs',
            'sideBlogs',
            'sliders',
            'newProducts',
            'featuredProducts',
            'hotDeals',
            'exclusiveProducts',
            'suggestProducts'
        ));
    }

    public function about()
    {
        return view('pages.about');
    }
}
