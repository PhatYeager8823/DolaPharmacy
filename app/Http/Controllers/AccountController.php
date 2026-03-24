<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\Thuoc;
use App\Models\NguoiDung;
use App\Mail\OtpPasswordMail;
use App\Mail\OtpVerifyEmail;

class AccountController extends Controller
{
    // 1. Trang thông tin tài khoản (Đã thêm Xếp hạng thành viên)
    public function index()
    {
        $user = Auth::user();

        // Gọi hàm tính hạng (Nếu model NguoiDung chưa có hàm này thì sẽ lỗi, nhớ cập nhật Model trước nhé)
        $membership = $user->getMembershipData();

        // Truyền thêm biến $membership sang View
        return view('account.profile', compact('user', 'membership'));
    }

    // 2. Trang danh sách đơn hàng
    public function orders()
    {
        $user = Auth::user();

        // [THÊM MỚI] Gọi hàm tính hạng thành viên để sidebar có dữ liệu hiển thị
        $membership = $user->getMembershipData();

        $orders = Order::where('nguoi_dung_id', $user->id)
                       ->with('items.thuoc')
                       ->latest()
                       ->get();

        // [SỬA LẠI] Thêm 'membership' vào compact
        return view('account.orders', compact('user', 'orders', 'membership'));
    }

    // 3. Cập nhật thông tin cơ bản (Tên, SĐT, Avatar...)
    public function update(Request $request)
    {
        $request->validate([
            'ten'       => 'required|string|max:255',
            'sdt'       => 'nullable|string|max:20',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|integer',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        /** @var \App\Models\NguoiDung $user */
        $user = Auth::user();

        $user->ten       = $request->ten;
        $user->sdt       = $request->sdt;
        $user->ngay_sinh = $request->ngay_sinh;
        $user->gioi_tinh = $request->gioi_tinh;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();
        return redirect()->route('account.index')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    // ============================================================
    // QUẢN LÝ ĐỔI MẬT KHẨU (Quy trình: Nhập Pass mới -> OTP -> Lưu)
    // ============================================================

    // BƯỚC 1: Nhận yêu cầu đổi mật khẩu và Gửi OTP
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // Validate đầu vào
        $request->validate([
            'current_password' => 'nullable', // Có thể null nếu user chưa từng có pass
            'new_password'     => 'required|min:6|confirmed',
        ], [
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'new_password.min' => 'Mật khẩu phải từ 6 ký tự.'
        ]);

        // Nếu user đã có mật khẩu cũ, bắt buộc phải nhập đúng
        if ($user->mat_khau && !Hash::check($request->current_password, $user->mat_khau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        // Kiểm tra xem user có email để nhận OTP không
        if (!$user->email) {
            return back()->with('error', 'Bạn cần cập nhật Email trước để nhận mã OTP đổi mật khẩu.');
        }

        // Tạo OTP và lưu tạm vào Session
        $otp = rand(100000, 999999);

        Session::put('change_pass_data', [
            'new_password' => Hash::make($request->new_password), // Hash luôn để bảo mật session
            'otp'          => $otp,
            'expires_at'   => now()->addMinutes(5) // Hết hạn sau 5 phút
        ]);

        // Gửi Email
        Mail::to($user->email)->send(new OtpPasswordMail($otp));

        // Chuyển hướng sang trang nhập OTP
        return redirect()->route('account.verify_change_password_form');
    }

    // BƯỚC 2: Hiển thị Form nhập OTP Đổi mật khẩu
    public function verifyChangePasswordForm()
    {
        if (!Session::has('change_pass_data')) {
            return redirect()->route('account.index')->with('error', 'Yêu cầu đã hết hạn, vui lòng thực hiện lại.');
        }
        return view('auth.verify_password_otp');
    }

    // BƯỚC 3: Xử lý xác thực OTP và Cập nhật Mật khẩu
    public function verifyChangePassword(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $data = Session::get('change_pass_data');

        // Kiểm tra session tồn tại và thời gian
        if (!$data || now()->greaterThan($data['expires_at'])) {
            Session::forget('change_pass_data');
            return redirect()->route('account.index')->with('error', 'Mã OTP đã hết hạn.');
        }

        // Kiểm tra mã OTP
        if ($request->otp != $data['otp']) {
            return back()->withErrors(['otp' => 'Mã OTP không chính xác']);
        }

        // Cập nhật Database
        $user = NguoiDung::find(Auth::id());
        $user->mat_khau = $data['new_password'];
        $user->is_guest = 0; // Nếu là khách thì giờ thành thành viên chính thức
        $user->save();

        // Xóa Session
        Session::forget('change_pass_data');

        return redirect()->route('account.index')->with('success', 'Đổi mật khẩu thành công!');
    }

    // Hàm thiết lập mật khẩu (dành cho user chưa có pass) - Logic đơn giản không cần OTP email nếu muốn
    // Nhưng nếu muốn đồng bộ, hãy dùng hàm changePassword ở trên.
    // Giữ lại hàm này nếu bạn muốn user mới tạo pass mà KHÔNG cần xác thực email.
    public function createPassword(Request $request)
    {
        return $this->changePassword($request); // Tái sử dụng logic trên
    }


    // ============================================================
    // QUẢN LÝ ĐỔI EMAIL (Quy trình: Nhập Email mới -> OTP -> Lưu)
    // ============================================================

    // BƯỚC 1: Nhận yêu cầu đổi Email và Gửi OTP
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:nguoi_dungs,email,' . Auth::id(),
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
        ]);

        $otp = rand(100000, 999999);
        $newEmail = $request->email;

        // Lưu thông tin tạm vào Session
        Session::put('update_email_data', [
            'email'      => $newEmail,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(5)
        ]);

        // Gửi OTP đến EMAIL MỚI (để xác minh họ sở hữu email đó)
        Mail::to($newEmail)->send(new OtpVerifyEmail($otp));

        return redirect()->route('account.verify_email_form');
    }

    // BƯỚC 2: Hiển thị Form nhập OTP Email
    public function verifyEmailForm()
    {
        if (!Session::has('update_email_data')) {
            return redirect()->route('account.index')->with('error', 'Yêu cầu hết hạn.');
        }

        $email = Session::get('update_email_data')['email'];
        return view('auth.verify_email_otp', compact('email'));
    }

    // BƯỚC 3: Xử lý xác thực OTP và Cập nhật Email
    public function verifyEmail(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $data = Session::get('update_email_data');

        if (!$data || now()->greaterThan($data['expires_at'])) {
            Session::forget('update_email_data');
            return redirect()->route('account.index')->with('error', 'Mã OTP đã hết hạn.');
        }

        if ($request->otp != $data['otp']) {
            return back()->withErrors(['otp' => 'Mã OTP không chính xác']);
        }

        // Cập nhật Database
        $user = NguoiDung::find(Auth::id());
        $user->email = $data['email'];
        $user->save();

        Session::forget('update_email_data');

        return redirect()->route('account.index')->with('success', 'Cập nhật Email thành công!');
    }

    public function coupons()
    {
        $user = Auth::user();

        // [QUAN TRỌNG] Thêm dòng này để tính hạng cho Sidebar
        $membership = $user->getMembershipData();

        // Code cũ của bạn (Lấy danh sách coupon)
        $usedCouponIds = \Illuminate\Support\Facades\DB::table('coupon_user')
            ->where('user_id', $user->id)
            ->whereNotNull('used_at')
            ->pluck('coupon_id')
            ->toArray();

        $coupons = \App\Models\Coupon::where('is_active', 1)
            ->where('expiry_date', '>=', now())
            ->whereNotIn('id', $usedCouponIds)
            ->get();

        // [QUAN TRỌNG] Nhớ truyền thêm 'membership' vào compact
        return view('account.coupons', compact('user', 'coupons', 'membership'));
    }

    // Xem chi tiết một đơn hàng cụ thể
    public function showOrder($id)
    {
        $user = Auth::user();

        // [QUAN TRỌNG] Thêm dòng này
        $membership = $user->getMembershipData();

        $order = Order::where('id', $id)
                      ->where('nguoi_dung_id', $user->id)
                      ->with(['items.thuoc', 'nguoiDung'])
                      ->firstOrFail();

        // [QUAN TRỌNG] Truyền thêm 'membership'
        return view('account.order_detail', compact('order', 'membership'));
    }

    public function cancelOrder($id)
    {
        // 1. Tìm đơn hàng của chính user đó
        $order = Order::where('id', $id)
                      ->where('nguoi_dung_id', Auth::id())
                      ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        // 2. Chỉ cho phép hủy khi đơn còn ở trạng thái "Chờ xác nhận"
        if ($order->trang_thai == 'cho_xac_nhan') {

            // A. Đổi trạng thái
            $order->trang_thai = 'da_huy';
            $order->save();

            // B. Hoàn lại số lượng tồn kho (Restock)
            foreach ($order->items as $item) {
                $product = Thuoc::find($item->thuoc_id);
                if ($product) {
                    $product->so_luong_ton += $item->so_luong;
                    $product->save();
                }
            }

            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
        } else {
            return redirect()->back()->with('error', 'Đơn hàng đang được vận chuyển, không thể hủy lúc này!');
        }
    }
}
