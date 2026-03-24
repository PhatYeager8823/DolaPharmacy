<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\GioHang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DiaChi;
use App\Models\NguoiDung;
use App\Models\Thuoc;
use App\Models\TonKho;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // ====================================================
    // 1. HIỂN THỊ TRANG THANH TOÁN
    // ====================================================
    public function index()
    {
        $cartItems = [];
        $total = 0;
        $user = Auth::user();
        $addresses = [];

        // --- A. LẤY DỮ LIỆU GIỎ HÀNG (Code cũ của bạn) ---
        if ($user) {
            $gioHang = GioHang::where('nguoi_dung_id', $user->id)->first();
            if ($gioHang) {
                $dbItems = $gioHang->chiTiets()->with('thuoc')->get();
                foreach ($dbItems as $item) {
                    if ($item->thuoc) {
                        $cartItems[] = [
                            'thuoc_id' => $item->thuoc_id,
                            'name'     => $item->thuoc->ten_thuoc,
                            'price'    => $item->thuoc->gia_ban,
                            'quantity' => $item->so_luong,
                            'image'    => $item->thuoc->hinh_anh,
                            'unit'     => $item->thuoc->don_vi_tinh
                        ];
                        $total += $item->thuoc->gia_ban * $item->so_luong;
                    }
                }
            }
            $addresses = DiaChi::where('nguoi_dung_id', $user->id)->get();
        } else {
            $cartSession = session()->get('cart', []);
            foreach ($cartSession as $id => $item) {
                $cartItems[] = [
                    'thuoc_id' => $id,
                    'name'     => $item['name'],
                    'price'    => $item['price'],
                    'quantity' => $item['quantity'],
                    'image'    => $item['image'],
                    'unit'     => $item['unit'] ?? 'Hộp'
                ];
                $total += $item['price'] * $item['quantity'];
            }
        }

        if (count($cartItems) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }

        // --- B. [QUAN TRỌNG] TÍNH TOÁN GIẢM GIÁ ---

        // 1. Giữ nguyên tổng tiền hàng ban đầu làm Subtotal
        $subtotal = $total;
        $discount = 0;

        // 2. Kiểm tra mã giảm giá trong Session
        if (session()->has('coupon')) {
            $coupon = session()->get('coupon');

            // Tính số tiền được giảm
            if (isset($coupon['type']) && $coupon['type'] == 'fixed') {
                $discount = $coupon['value'];
            } else {
                // Giảm theo %
                $discount = ($subtotal * $coupon['value']) / 100;
            }

            // Đảm bảo không giảm quá số tiền hiện có
            if ($discount > $subtotal) {
                $discount = $subtotal;
            }
        }

        // 3. Tính tổng cuối cùng
        $finalTotal = $subtotal - $discount;

        // --- C. TRUYỀN BIẾN SANG VIEW ---
        // Lưu ý: Phải truyền đủ 'subtotal', 'discount', 'total'
        $ma_don_hang = 'DH' . strtoupper(uniqid());
        
        return view('checkout.index', [
            'cartItems'   => $cartItems,
            'user'        => $user,
            'addresses'   => $addresses,
            'subtotal'    => $subtotal,    // Giá gốc (30,000)
            'discount'    => $discount,    // Tiền giảm (3,000)
            'total'       => $finalTotal,   // Giá chốt (27,000)
            'ma_don_hang' => $ma_don_hang
        ]);
    }

    // ====================================================
    // 2. XỬ LÝ ĐẶT HÀNG (CORE LOGIC) - ĐÃ CẬP NHẬT TRỪ KHO
    // ====================================================
    // CheckoutController.php

   public function process(Request $request)
    {
        // 1. VALIDATE DỮ LIỆU
        $request->validate([
            'ten_nguoi_nhan'   => 'required',
            'sdt_nguoi_nhan'   => 'required',
            'tinh_thanh'       => 'required',
            'dia_chi_chi_tiet' => 'required',
            'email'            => 'nullable|email',
        ], [
            'ten_nguoi_nhan.required' => 'Vui lòng nhập tên người nhận',
            'sdt_nguoi_nhan.required' => 'Vui lòng nhập số điện thoại',
            'tinh_thanh.required'       => 'Vui lòng chọn Tỉnh/Thành phố',
            'dia_chi_chi_tiet.required' => 'Vui lòng nhập địa chỉ cụ thể',
        ]);

        // [QUAN TRỌNG] LẤY GIỎ HÀNG RA BIẾN RIÊNG NGAY TỪ ĐẦU
        // Để đảm bảo dữ liệu không bị mất dù có chuyện gì xảy ra với Session
        $finalCartItems = [];
        $totalMoney = 0;

        // Ưu tiên lấy từ Session (vì đây là luồng cho khách vãng lai/chưa đăng nhập)
        $cartSession = session()->get('cart', []);

        if (!empty($cartSession)) {
            foreach ($cartSession as $id => $item) {
                $finalCartItems[] = [
                    'thuoc_id'  => $id,
                    'ten_thuoc' => $item['name'],
                    'so_luong'  => $item['quantity'],
                    'gia_ban'   => $item['price'],
                    'thanh_tien'=> $item['price'] * $item['quantity']
                ];
                $totalMoney += $item['price'] * $item['quantity'];
            }
        } else {
            // Backup: Nếu session rỗng thì kiểm tra trong DB (trường hợp user đã login từ trước)
            if (Auth::check()) {
                $gh = GioHang::where('nguoi_dung_id', Auth::id())->first();
                if ($gh) {
                    foreach ($gh->chiTiets as $ct) {
                         $finalCartItems[] = [
                            'thuoc_id'  => $ct->thuoc_id,
                            'ten_thuoc' => $ct->thuoc->ten_thuoc,
                            'so_luong'  => $ct->so_luong,
                            'gia_ban'   => $ct->thuoc->gia_ban,
                            'thanh_tien'=> $ct->thuoc->gia_ban * $ct->so_luong
                        ];
                        $totalMoney += $ct->thuoc->gia_ban * $ct->so_luong;
                    }
                }
            }
        }

        // Chặn nếu không có hàng
        if (empty($finalCartItems)) {
            return back()->with('error', 'Giỏ hàng trống, không thể thanh toán.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user(); // Nếu đã đăng nhập thì dùng luôn
            $autoLoginNewUser = false; // Cờ để đánh dấu có đăng nhập cho khách mới không

            // 2. XỬ LÝ NGƯỜI DÙNG (NẾU CHƯA ĐĂNG NHẬP)
            if (!$user) {
                // Kiểm tra SĐT trong database
                $existingUser = NguoiDung::where('sdt', $request->sdt_nguoi_nhan)->first();

                if ($existingUser) {
                    // === TRƯỜNG HỢP KHÁCH CŨ ===
                    // Gán đơn hàng cho họ, NHƯNG KHÔNG ĐĂNG NHẬP (Để tránh lỗi session & bảo mật)
                    $user = $existingUser;

                    // Cập nhật email nếu họ chưa có
                    if (empty($user->email) && !empty($request->email)) {
                         $user->email = $request->email;
                         $user->save();
                    }
                } else {
                    // === TRƯỜNG HỢP KHÁCH MỚI ===
                    // Tạo tài khoản mới
                    $user = NguoiDung::create([
                        'ten'        => $request->ten_nguoi_nhan,
                        'sdt'        => $request->sdt_nguoi_nhan,
                        'email'      => $request->email,
                        'dia_chi'    => $request->dia_chi_chi_tiet . ', ' . $request->tinh_thanh,
                        'vai_tro'    => 'customer',
                        'trang_thai' => 1,
                        'is_guest'   => 1,
                        'mat_khau'   => null
                    ]);
                    GioHang::create(['nguoi_dung_id' => $user->id]);

                    // Đánh dấu để tí nữa đăng nhập cho khách mới
                    $autoLoginNewUser = true;
                }
            }

            $discountAmount = 0;
            if (session()->has('coupon')) {
                $coupon = session()->get('coupon');

                if (isset($coupon['type']) && $coupon['type'] == 'fixed') {
                    $discountAmount = $coupon['value'];
                } else {
                    $discountAmount = ($totalMoney * $coupon['value']) / 100;
                }

                if ($discountAmount > $totalMoney) {
                    $discountAmount = $totalMoney;
                }
            }

            // 1. TÍNH PHÍ SHIP (LOGIC MỚI)
            $phiShip = 15000; // Mặc định phí ship là 15k (Ngoại tỉnh)

            // Kiểm tra: Nếu là Bạc Liêu thì Free ship
            // Dùng mb_strtolower để không phân biệt hoa thường (bạc liêu == Bạc Liêu)
            if ($request->tinh_thanh && str_contains(mb_strtolower($request->tinh_thanh, 'UTF-8'), 'bạc liêu')) {
                $phiShip = 0;
            }

            // 2. CẬP NHẬT TỔNG TIỀN CUỐI CÙNG
            // Tổng tiền = Tiền hàng - Giảm giá + Phí Ship
            $finalTotal = $totalMoney - $discountAmount + $phiShip;

            // 3. TẠO ĐƠN HÀNG
            // Lấy mã đơn hàng đã được ép sẵn ở giao diện trang checkout (nếu có), nếu không có sinh mới
            $ma_don = $request->input('ma_don_hang', 'DH' . strtoupper(uniqid()));
            
            $order = Order::create([
                'ma_don_hang'      => $ma_don,
                'nguoi_dung_id'    => $user->id,
                'ten_nguoi_nhan'   => $request->ten_nguoi_nhan,
                'sdt_nguoi_nhan'   => $request->sdt_nguoi_nhan,
                'dia_chi_giao_hang'=> $request->dia_chi_chi_tiet . ', ' . $request->tinh_thanh,

                // SỬA DÒNG NÀY: Dùng biến $finalTotal thay vì $totalMoney
                'tong_tien'        => $finalTotal,

                'phuong_thuc_thanh_toan' => $request->payment_method ?? 'cod',
                'trang_thai'       => 'cho_xac_nhan',
                'ghi_chu'          => $request->ghi_chu
            ]);

            // 4. LƯU CHI TIẾT ĐƠN HÀNG
            foreach ($finalCartItems as $item) {
                // Trừ kho
                $thuoc = Thuoc::find($item['thuoc_id']);
                if($thuoc) {
                     $thuoc->decrement('so_luong_ton', $item['so_luong']);
                     TonKho::create([
                        'thuoc_id' => $item['thuoc_id'],
                        'so_luong_thay_doi' => -($item['so_luong']),
                        'loai_giao_dich' => 'xuat_ban_hang',
                        'gia_nhap' => 0,
                        'ghi_chu' => "Bán hàng đơn #" . $order->ma_don_hang
                    ]);
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'thuoc_id'   => $item['thuoc_id'],
                    'ten_thuoc'  => $item['ten_thuoc'],
                    'so_luong'   => $item['so_luong'],
                    'gia_ban'    => $item['gia_ban'],
                    'thanh_tien' => $item['thanh_tien']
                ]);
            }

            // 5. XỬ LÝ COUPON (LƯU LỊCH SỬ DÙNG)
            if (session()->has('coupon')) {
                $couponSession = session()->get('coupon');

                // Tìm lại Coupon trong DB để lấy ID
                $couponDB = \App\Models\Coupon::where('code', $couponSession['code'])->first();

                if ($couponDB && $user) {
                    // Kiểm tra xem đã có dòng nào trong bảng trung gian chưa (đề phòng trùng)
                    // Nếu bạn dùng cơ chế "Mỗi người có sẵn 2 mã" nghĩa là đã có dòng trong coupon_user với used_at = null?
                    // Dưới đây là cách an toàn nhất: Insert hoặc Update

                    \Illuminate\Support\Facades\DB::table('coupon_user')->updateOrInsert(
                        [
                            'user_id'   => $user->id,
                            'coupon_id' => $couponDB->id,
                        ],
                        [
                            'used_at'    => Carbon::now(), // Quan trọng: Đánh dấu thời gian đã dùng
                            // 'order_id' => $order->id,   // Bỏ comment dòng này nếu bảng coupon_user của bạn có cột order_id
                        ]
                    );

                    // Nếu coupon có số lượng giới hạn chung, hãy trừ đi
                    if ($couponDB->quantity > 0) {
                        $couponDB->decrement('quantity');
                    }
                }

                // Xóa khỏi session sau khi đã dùng xong
                session()->forget('coupon');
            }

            // Xóa giỏ hàng
            session()->forget('cart');
            if($user) {
                 GioHang::where('nguoi_dung_id', $user->id)->first()?->chiTiets()->delete();
            }

            DB::commit(); // === LƯU THÀNH CÔNG ===

            // 5. ĐĂNG NHẬP (Chỉ dành cho User MỚI)
            // Khách cũ thì không đăng nhập để tránh lỗi xung đột session
            if ($autoLoginNewUser) {
                Auth::login($user);
            }

            // 5. CHỐT KẾT QUẢ ĐẶT HÀNG THỦ CÔNG
            // Cập nhật phương thức thanh toán vào DB nếu có
            if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'phuong_thuc_thanh_toan')) {
                 $order->phuong_thuc_thanh_toan = $request->payment_method;
                 $order->save();
            }

            return redirect()->route('checkout.success', ['order' => $order->id])
                             ->with('success', 'Bạn đã đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    // ====================================================
    // 4. TAO PAYMENT VNPAY
    // ====================================================
    public function createVnPayPayment($order)
    {
        // Xét múi giờ VN để Checksum không bị lệch giờ (VNPAY bắt lỗi timezone khá gắt)
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Kéo dữ liệu cấu hình từ .env (đã được bọc trim để chống khoảng trắng tự do)
        $vnp_TmnCode = trim(config('vnpay.vnp_TmnCode'));
        $vnp_HashSecret = trim(config('vnpay.vnp_HashSecret'));
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');

        $vnp_TxnRef = $order->ma_don_hang;
        // XÓA HẾT DẤU CÁCH thay bằng dấu gạch dưới để đề phòng urlencode bị biến thành dấu + (plus)
        $vnp_OrderInfo = "Thanh_toan_don_hang_" . $order->ma_don_hang;
        $vnp_OrderType = 'billpayment';
        
        // Ép kiểu (int) để đề phòng $order->tong_tien là số thập phân có dấu chấm (VD: 25000.00)
        $vnp_Amount = (int)($order->tong_tien * 100);
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
    }

    // ====================================================
    // 5. VNPAY RETURN (CALLBACK)
    // ====================================================
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = trim(config('vnpay.vnp_HashSecret'));
        
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $order = Order::where('ma_don_hang', $request->vnp_TxnRef)->first();

        if ($secureHash == $vnp_SecureHash) {
            if ($request->vnp_ResponseCode == '00') {
                if ($order) {
                    $order->ghi_chu = "[VNPAY: Đã thanh toán thành công] " . $order->ghi_chu;
                    $order->save();
                    return redirect()->route('checkout.success', $order->id)->with('success', 'Giao dịch thanh toán VNPAY thành công!');
                }
            } else {
                if ($order) {
                    $order->ghi_chu = "[VNPAY: Giao dịch thất bại / Bị hủy] " . $order->ghi_chu;
                    $order->trang_thai = 'da_huy';
                    $order->save();
                }
                return redirect()->route('home')->with('error', 'Giao dịch thanh toán VNPAY bị hủy hoặc thất bại.');
            }
        }
        
        return redirect()->route('home')->with('error', 'Chữ ký VNPAY không hợp lệ.');
    }


    // ====================================================
    // 3. TRANG THÔNG BÁO THÀNH CÔNG
    // ====================================================
    public function success($id)
    {
        $order = Order::findOrFail($id);

        if (Auth::check() && Auth::id() != $order->nguoi_dung_id) {
             return redirect()->route('home');
        }

        return view('checkout.success', compact('order'));
    }
}
