<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DanhMucController;
use App\Http\Controllers\ThuocController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LienHeController;
use App\Http\Controllers\YeuThichController;
use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\DiaChiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\WarehouseController; // <--- 1. Nhớ import dòng này ở đầu file
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\Client\ChiNhanhController;

// ==========================================
// TRANG CHỦ
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');


// ==========================================
// DANH MỤC & SẢN PHẨM
// ==========================================
Route::get('/danh-muc', [DanhMucController::class, 'index'])->name('danhmuc.index');
Route::get('/danh-muc/{slug}', [DanhMucController::class, 'show'])->name('danhmuc.show');

Route::get('/san-pham', [ThuocController::class, 'index'])->name('thuoc.index');
Route::get('/san-pham/{slug}', [ThuocController::class, 'show'])->name('thuoc.show');
Route::post('/san-pham/{id}/danh-gia', [DanhGiaController::class, 'store'])->name('sanpham.danh_gia')->middleware('auth');

// Route dự phòng trường hợp Lỗi validation back() làm mất Referer bị biến thành GET
Route::get('/san-pham/{id}/danh-gia', function($id) {
    $thuoc = \App\Models\Thuoc::findOrFail($id);
    return redirect()->route('thuoc.show', $thuoc->slug);
});

Route::get('/he-thong-nha-thuoc', [ChiNhanhController::class, 'index'])->name('he-thong-nha-thuoc');


// ==========================================================
// AUTHENTICATION (Đăng nhập/Đăng ký bằng SĐT + OTP)
// ==========================================================

// 1. Form nhập SĐT & Gửi OTP
Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap/gui-otp', [AuthController::class, 'sendOtp'])->name('auth.send_otp');

Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');

// 2. Form nhập OTP xác thực & Xử lý
Route::get('/xac-thuc', [AuthController::class, 'showVerifyForm'])->name('auth.verify');
Route::post('/xac-thuc', [AuthController::class, 'verifyOtp'])->name('auth.verify_submit');

// 3. Đăng nhập bằng Email (Dành cho user cũ/đã update email)
Route::post('/dang-nhap/email', [AuthController::class, 'loginWithEmail'])->name('auth.login_email');

// 4. Đăng xuất
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// GIỎ HÀNG
// ==========================================
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/repurchase', [CartController::class, 'repurchase'])->name('cart.repurchase');


// ==========================================
// TÀI KHOẢN CÁ NHÂN (Yêu cầu đăng nhập)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Trang hồ sơ cá nhân
    Route::get('/tai-khoan', [AccountController::class, 'index'])->name('account.index');

    // Xử lý cập nhật thông tin cơ bản (Tên, SĐT, Ngày sinh...)
    Route::post('/tai-khoan/update', [AccountController::class, 'update'])->name('account.update');

    // Trang đơn hàng
    Route::get('/tai-khoan/don-hang', [AccountController::class, 'orders'])->name('account.orders');

    // Trang thông báo
    Route::get('/tai-khoan/thong-bao', [ThongBaoController::class, 'index'])->name('notifications.index');

    // --- CÁC ROUTE CẬP NHẬT BẢO MẬT (PROFILE) ---

    // 1. Thiết lập mật khẩu (Cho tài khoản tự tạo/chưa có pass)
    Route::post('/tai-khoan/thiet-lap-mat-khau', [AccountController::class, 'createPassword'])->name('account.set_password');

    // 1. Đổi mật khẩu
    // Gửi yêu cầu đổi (Validate + Gửi OTP)
    Route::post('/tai-khoan/doi-mat-khau', [AccountController::class, 'changePassword'])->name('account.change_password');
    // Trang nhập OTP đổi mật khẩu
    Route::get('/tai-khoan/xac-thuc-doi-mat-khau', [AccountController::class, 'verifyChangePasswordForm'])->name('account.verify_change_password_form');
    // Xử lý OTP đổi mật khẩu
    Route::post('/tai-khoan/xac-thuc-doi-mat-khau', [AccountController::class, 'verifyChangePassword'])->name('account.verify_change_password');


    // 2. Cập nhật Email
    // Gửi yêu cầu đổi (Validate + Gửi OTP)
    Route::post('/tai-khoan/doi-email', [AccountController::class, 'updateEmail'])->name('account.update_email');
    // Trang nhập OTP đổi email
    Route::get('/tai-khoan/xac-thuc-email', [AccountController::class, 'verifyEmailForm'])->name('account.verify_email_form');
    // Xử lý OTP đổi email
    Route::post('/tai-khoan/xac-thuc-email', [AccountController::class, 'verifyEmail'])->name('account.verify_email');
    // --- SỔ ĐỊA CHỈ ---
    Route::get('/tai-khoan/so-dia-chi', [DiaChiController::class, 'index'])->name('address.index');
    Route::post('/tai-khoan/so-dia-chi', [DiaChiController::class, 'store'])->name('address.store');
    Route::put('/tai-khoan/so-dia-chi/{id}', [DiaChiController::class, 'update'])->name('address.update');
    Route::delete('/tai-khoan/so-dia-chi/{id}', [DiaChiController::class, 'destroy'])->name('address.destroy');
    Route::get('/tai-khoan/so-dia-chi/mac-dinh/{id}', [DiaChiController::class, 'setDefault'])->name('address.set_default');
    Route::get('/tai-khoan/ma-giam-gia', [AccountController::class, 'coupons'])->name('account.coupons');

    // Xem chi tiết đơn hàng
    Route::get('/account/orders/{id}', [App\Http\Controllers\AccountController::class, 'showOrder'])->name('account.orders.show');

    // Mua lại đơn hàng cũ
    Route::post('/cart/reorder/{id}', [App\Http\Controllers\CartController::class, 'reOrder'])->name('cart.reorder');

    // Route hủy đơn hàng
    Route::post('/account/orders/cancel/{id}', [App\Http\Controllers\AccountController::class, 'cancelOrder'])->name('account.orders.cancel');
});


// ==========================================
// CÁC TRANG NỘI DUNG
// ==========================================

// Trang giới thiệu
Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');

// Sản phẩm khuyến mãi
Route::get('/khuyen-mai', [ThuocController::class, 'promotion'])->name('promotion.index');

// Tin tức
Route::get('/tin-tuc', [BaiVietController::class, 'index'])->name('baiviet.index');
Route::get('/tin-tuc/{slug}', [BaiVietController::class, 'show'])->name('baiviet.show');

// Trang Video
Route::get('/video', [VideoController::class, 'index'])->name('video.index');

// Trang FAQ
Route::get('/cau-hoi-thuong-gap', [FaqController::class, 'index'])->name('faq.index');

// Trang Liên hệ
Route::get('/lien-he', [LienHeController::class, 'index'])->name('contact.index');
Route::post('/lien-he', [LienHeController::class, 'store'])->name('contact.store');

// Yêu thích
Route::get('/yeu-thich', [YeuThichController::class, 'index'])->name('wishlist.index');
Route::post('/yeu-thich/toggle', [YeuThichController::class, 'toggle'])->name('wishlist.toggle');


// ==========================================
// TRANG QUẢN TRỊ (ADMIN)
// ==========================================
Route::prefix('quan-tri')
    ->name('admin.')
    ->middleware(['admin.auth'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', App\Http\Controllers\Admin\ThuocController::class);

        // Quản lý Đơn hàng
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        // Xóa chữ "admin." đi, chỉ để "orders.update_status"
        Route::put('orders/update-status/{id}', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update_status');        // Quản lý Thương hiệu
        Route::resource('brands', App\Http\Controllers\Admin\BrandController::class);

        // Nhà cung cấp
        Route::resource('suppliers', App\Http\Controllers\Admin\SupplierController::class);

        // Giảm giá
        Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);

        // Bài viết
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class);

        // Video
        Route::resource('videos', App\Http\Controllers\Admin\VideoController::class);

        // Faq
        Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class);

        // Contact
        // Mục Liên hệ (Chỉ cần index và destroy)
        Route::resource('contacts', App\Http\Controllers\Admin\ContactController::class)->only(['index', 'destroy']);

        // Khách hàng
        // Quản lý Khách hàng (Chỉ Xem, Khóa, Xóa)
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{id}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('/users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Quản lý Thông báo (Nếu chưa thêm)
        Route::resource('notifications', App\Http\Controllers\Admin\NotificationController::class)->only(['index', 'create', 'store', 'destroy']);

        // Slider
        Route::resource('sliders', App\Http\Controllers\Admin\SliderController::class);

        // Cài đặt chung
        Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Quản lý Kho - Nhập hàng
        // 1. Route Báo cáo tồn kho (THÊM DÒNG NÀY VÀO TRƯỚC RESOURCE)
        Route::get('warehouse/inventory', [WarehouseController::class, 'inventory'])->name('warehouse.inventory');

        // 2. Route Resource Nhập hàng (Cái cũ bạn đã có)
        Route::resource('warehouse', WarehouseController::class);

        // Quản lý Đánh giá / Bình luận
        Route::get('/reviews', [App\Http\Controllers\Admin\DanhGiaController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{id}/toggle', [App\Http\Controllers\Admin\DanhGiaController::class, 'toggleStatus'])->name('reviews.toggle');
        Route::delete('/reviews/{id}', [App\Http\Controllers\Admin\DanhGiaController::class, 'destroy'])->name('reviews.destroy');
});


// ==========================================
// THANH TOÁN (CHECKOUT)
// ==========================================
Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/dat-hang-thanh-cong/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
// Đã gỡ Route VNPAY hoàn toàn
// voucher
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.add');
Route::get('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

// ROUTE CHO CHATBOT (AJAX)
Route::post('/chatbot/reply', [App\Http\Controllers\ChatbotController::class, 'reply'])->name('chatbot.reply');
Route::get('/test-gemini-models', function () {
    $apiKey = env('GEMINI_API_KEY');
    // Gọi API lấy danh sách model
    $response = Http::withoutVerifying()
        ->get("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}");

    return $response->json();
});

Route::get('/check-models', [App\Http\Controllers\ChatbotController::class, 'checkModels']);


