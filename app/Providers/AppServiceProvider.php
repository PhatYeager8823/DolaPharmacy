<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\DanhMuc;
use App\Models\Setting; // <--- Đảm bảo đã import Model Setting
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // =============================================================
        // 1. CHIA SẺ DANH MỤC (Code cũ của bạn)
        // =============================================================
        try {
            $megaCategories = DanhMuc::whereNull('danh_muc_cha_id')
                                     ->with('children')
                                     ->orderBy('id', 'asc')
                                     ->get();

            View::share('megaCategories', $megaCategories);
        } catch (\Exception $e) {
            // Tránh lỗi khi chưa chạy migration hoặc bảng chưa tồn tại
            View::share('megaCategories', collect([]));
        }

        // =============================================================
        // 2. CHIA SẺ CẤU HÌNH WEBSITE (Mới thêm)
        // =============================================================
        // Sử dụng View Composer để biến $global_setting tự động có mặt ở TẤT CẢ các view
        View::composer('*', function ($view) {
            try {
                // Lấy dòng cấu hình đầu tiên
                $setting = Setting::first();
            } catch (\Exception $e) {
                $setting = null;
            }

            // Nếu chưa có dữ liệu (hoặc chưa tạo bảng), tạo một object rỗng với các giá trị mặc định để không bị lỗi View
            if (!$setting) {
                $setting = new Setting();
                $setting->is_promo_active = true; // Mặc định hiện promo nếu chưa cấu hình
                $setting->promo_text = "CHÀO MỪNG BẠN MỚI";
                $setting->hotline = "0123.456.789";
            }

            // Truyền biến $global_setting sang view
            $view->with('global_setting', $setting);
        });

        Paginator::useBootstrapFive();
    }
}
