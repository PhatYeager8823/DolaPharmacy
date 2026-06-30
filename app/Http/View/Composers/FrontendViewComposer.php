<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\DanhMuc;
use App\Models\ThongBao;
use App\Models\YeuThich;
use App\Models\GioHang;
use App\Models\GioHangChiTiet;
use App\Models\CauHinhWebsite;
use Illuminate\Support\Facades\Auth;

class FrontendViewComposer
{
    public function compose(View $view)
    {
        // 1. Dữ liệu Mega Menu (Eager load children)
        $megaCategories = DanhMuc::whereNull('danh_muc_cha_id')
                                ->with('children')
                                ->get();


        // 2. Thông báo & Yêu thích & Giỏ hàng
        $unreadCount = 0;
        $wishlistCount = 0;
        $cartCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $unreadCount = ThongBao::where('nguoi_dung_id', $user->id)->where('da_xem', 0)->count();
            $wishlistCount = YeuThich::where('nguoi_dung_id', $user->id)->count();
            
            $userCart = GioHang::where('nguoi_dung_id', $user->id)->first();
            if ($userCart) {
                $cartCount = GioHangChiTiet::where('gio_hang_id', $userCart->id)
                                           ->where('trang_thai', 0)
                                           ->sum('so_luong');
            }
        } else {
            $cartSession = session()->get('cart', []);
            $cartCount = array_sum(array_column($cartSession, 'quantity'));
        }

        // Truyền dữ liệu ra tất cả các view sử dụng composer này
        $view->with(compact('megaCategories', 'unreadCount', 'wishlistCount', 'cartCount'));
    }
}
