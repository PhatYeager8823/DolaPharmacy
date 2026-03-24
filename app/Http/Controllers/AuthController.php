<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\GioHang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // 1. Hiện trang đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ====================================================
    // PHẦN 1: ĐĂNG NHẬP BẰNG SĐT + OTP
    // ====================================================

    public function sendOtp(Request $request)
    {
        $request->validate([
            'sdt' => 'required|numeric|min_digits:10',
        ], ['sdt.required' => 'Vui lòng nhập số điện thoại']);

        $sdt = $request->sdt;
        // Tạo OTP ngẫu nhiên
        $otp = rand(100000, 999999);

        // Lưu OTP và SĐT vào session tạm thời
        session(['auth_otp' => $otp, 'auth_sdt' => $sdt]);

        // Ghi log để test (Thực tế nên gửi SMS ở đây)
        Log::info("Mã OTP đăng nhập cho SĐT $sdt là: $otp");

        return redirect()->route('auth.verify');
    }

    public function showVerifyForm()
    {
        // Nếu không có SĐT trong session thì quay về login
        if (!session('auth_sdt')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp-phone');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        if ($request->otp == session('auth_otp')) {
            $sdt = session('auth_sdt');

            // Tìm user theo SĐT
            $user = NguoiDung::where('sdt', $sdt)->first();

            // === [BỔ SUNG ĐOẠN NÀY] ===
            if ($user && $user->trang_thai == 0) {
                // Nếu user tồn tại NHƯNG trạng thái là 0 -> Chặn luôn
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa do vi phạm chính sách. Vui lòng liên hệ Admin.');
            }

            if (!$user) {
                // === TẠO USER MỚI NẾU CHƯA CÓ ===
                $user = NguoiDung::create([
                    'ten'        => 'Khách hàng ' . substr($sdt, -4),
                    'sdt'        => $sdt,
                    'email'      => null,
                    'mat_khau'   => null,
                    'vai_tro'    => 'customer',
                    'trang_thai' => 1,
                    'is_guest'   => 1, // Đánh dấu là khách vãng lai
                ]);

                // Tạo giỏ hàng cho user mới
                GioHang::create(['nguoi_dung_id' => $user->id]);

                // ========================================================
                // [MỚI] TỰ ĐỘNG TẶNG 2 MÃ GIẢM GIÁ CHO THÀNH VIÊN MỚI
                // ========================================================
                $defaultCoupons = \App\Models\Coupon::whereIn('code', ['BANMOI', 'SALE10'])->get();

                foreach ($defaultCoupons as $coupon) {
                    // Thêm vào bảng trung gian coupon_user
                    \Illuminate\Support\Facades\DB::table('coupon_user')->insert([
                        'user_id' => $user->id,
                        'coupon_id' => $coupon->id,
                        'used_at' => null, // Chưa dùng
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // ========================================================
            }

            // Đăng nhập user
            Auth::login($user);

            // Xóa session OTP
            session()->forget(['auth_otp', 'auth_sdt']);

            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        } else {
            return back()->with('error', 'Mã OTP không đúng');
        }
    }

    // ====================================================
    // PHẦN 2: ĐĂNG NHẬP BẰNG EMAIL & MẬT KHẨU
    // ====================================================

    public function loginWithEmail(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        // 1. Thử đăng nhập bằng Email & Password (chưa quan tâm khóa hay không)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // 2. Kiểm tra trạng thái tài khoản
            if (auth()->user()->trang_thai == 0) {
                // Nếu bị khóa: Đăng xuất ngay lập tức
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Trả về thông báo lỗi cụ thể
                return back()->with('error', 'Tài khoản này đang bị khóa. Vui lòng liên hệ Admin để mở lại.');
            }

            // 3. Nếu không bị khóa: Đăng nhập thành công hoàn toàn
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        // 4. Trường hợp sai Email hoặc Mật khẩu
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    // Hiện form Đăng ký (Thực chất dùng chung luồng OTP SĐT)
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
