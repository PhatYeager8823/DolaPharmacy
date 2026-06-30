<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\DanhMuc;
use App\Models\Setting; // <--- Đảm bảo đã import Model Setting
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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
        // Ép HTTPS khi chạy ngrok để không bị lỗi CSS trên mobile
        if (str_contains(config('app.url'), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }
        // =============================================================
        // 1. GLOBAL DATA COMPOSER (Optimized)
        // =============================================================
        // Use View Composer to share global settings, categories, and counts across all views
        View::composer('*', \App\Http\View\Composers\FrontendViewComposer::class);

        // Share global settings (keeping old variable name for compatibility)
        View::composer('*', function ($view) {
            try {
                $setting = Setting::first();
            } catch (\Exception $e) { $setting = null; }

            if (!$setting) {
                $setting = new Setting();
                $setting->is_promo_active = true;
                $setting->promo_text = "CHÀO MỪNG BẠN MỚI";
                $setting->hotline = "0123.456.789";
            }
            $view->with('global_setting', $setting);
        });

        Paginator::useBootstrapFive();
    }
}
