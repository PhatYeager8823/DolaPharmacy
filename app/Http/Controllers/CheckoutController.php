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
use App\Services\MomoService;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // ====================================================
    // 1. HIỂN THỊ TRANG THANH TOÁN
    // ====================================================
    public function index(Request $request)
    {
        $cartItems = [];
        $total = 0;
        $user = Auth::user();
        $addresses = [];

        // --- A. XỬ LÝ MUA NGAY (Ưu tiên cao nhất) ---
        if ($request->has('buy_now_id')) {
            $id = $request->buy_now_id;
            $qty = $request->qty ?? 1;
            $thuoc = Thuoc::find($id);

            if ($thuoc) {
                $cartItems[] = [
                    'thuoc_id' => $thuoc->id,
                    'name'     => $thuoc->ten_thuoc,
                    'price'    => $thuoc->gia_ban,
                    'quantity' => $qty,
                    'image'    => $thuoc->hinh_anh,
                    'unit'     => $thuoc->don_vi_tinh
                ];
                $total = $thuoc->gia_ban * $qty;
                
                if ($user) {
                    $addresses = DiaChi::where('nguoi_dung_id', $user->id)->get();
                }
            } else {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại!');
            }
        } 
        // --- B. LẤY DỮ LIỆU GIỎ HÀNG CHUNG (Nếu không phải mua ngay) ---
        else if ($user) {
            $gioHang = GioHang::where('nguoi_dung_id', $user->id)->first();
            if ($gioHang) {
                $dbItems = $gioHang->chiTiets()->where('trang_thai', 0)->with('thuoc')->get();
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
        $finalCartItems = [];
        $totalMoney = 0;

        // --- A. KIỂM TRA NẾU LÀ MUA NGAY ---
        if ($request->has('is_buy_now') && $request->is_buy_now == 1) {
            $thuoc = Thuoc::find($request->buy_now_id);
            if ($thuoc) {
                $finalCartItems[] = [
                    'thuoc_id'  => $thuoc->id,
                    'ten_thuoc' => $thuoc->ten_thuoc,
                    'so_luong'  => $request->buy_now_qty ?? 1,
                    'gia_ban'   => $thuoc->gia_ban,
                    'thanh_tien'=> $thuoc->gia_ban * ($request->buy_now_qty ?? 1)
                ];
                $totalMoney = $thuoc->gia_ban * ($request->buy_now_qty ?? 1);
            }
        } 
        // --- B. NẾU LÀ THANH TOÁN GIỎ HÀNG CHUNG ---
        else {
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
            } else if (Auth::check()) {
                $gh = GioHang::where('nguoi_dung_id', Auth::id())->first();
                if ($gh) {
                    foreach ($gh->chiTiets()->where('trang_thai', 0)->get() as $ct) {
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
                        'loai_giao_dich' => 'xuat',
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

            // 5. XỬ LÝ LÀM SẠCH GIỎ HÀNG (CHỈ KHI THANH TOÁN TOÀN BỘ GIỎ)
            if (!$request->has('is_buy_now') || $request->is_buy_now != 1) {
                session()->forget('cart'); // Xóa session cho cả khách và user (để tránh trùng)

                if($user) {
                     // Với User: Chuyển sang trạng thái "Đã đặt" (trang_thai = 1) thay vì xóa hẳn
                     $gioHang = \App\Models\GioHang::where('nguoi_dung_id', $user->id)->first();
                     if ($gioHang) {
                         $gioHang->chiTiets()->where('trang_thai', 0)->update(['trang_thai' => 1]);
                     }
                }
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

            // Nếu chọn MoMo: Redirect sang cổng thanh toán MoMo
            if ($request->payment_method === 'momo') {
                DB::commit();
                return $this->createMomoPayment($order);
            }

            return redirect()->route('checkout.success', ['id' => $order->id])
                             ->with('success', 'Bạn đã đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }


    // ====================================================
    // 4. TẠO PAYMENT MOMO
    // ====================================================
    public function createMomoPayment(Order $order)
    {
        $momo = new MomoService();

        $amount    = (int) $order->tong_tien;  // MoMo dùng VND nguyên (không nhân 100 như VNPay)
        $orderId   = $order->ma_don_hang;
        $orderInfo = 'Thanh toan don hang ' . $order->ma_don_hang . ' - Dola Pharmacy';

        $result = $momo->createPayment($orderId, $amount, $orderInfo);

        if ($result['success']) {
            return redirect()->away($result['payUrl']);
        }

        // Nếu tạo MoMo thất bại → Vẫn giữ đơn, báo lỗi và cho về trang success (Đơn đã tạo rồi)
        return redirect()->route('checkout.success', $order->id)
            ->with('warning', 'Không thể kết nối MoMo: ' . $result['message'] . '. Vui lòng thanh toán khi nhận hàng.');
    }


    // ====================================================
    // 5. MOMO RETURN (CALLBACK SAU KHI THANH TOÁN)
    // ====================================================
    public function momoReturn(Request $request)
    {
        $data = $request->all();

        // Tìm đơn hàng theo mã
        $order = Order::where('ma_don_hang', $data['orderId'] ?? '')->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Xác thực chữ ký từ MoMo
        $momo = new MomoService();
        $isValid = $momo->verifySignature($data);

        if ($isValid && ($data['resultCode'] ?? -1) == 0) {
            // ✅ THANH TOÁN THÀNH CÔNG
            $order->ghi_chu = '[MoMo: Đã thanh toán thành công - TransID: ' . ($data['transId'] ?? 'N/A') . '] ' . $order->ghi_chu;
            $order->save();
            return redirect()->route('checkout.success', $order->id)
                ->with('success', '🎉 Thanh toán MoMo thành công!');
        }

        // ❌ THANH TOÁN THẤT BẠI / BỊ HỦY
        $errorMsg = $data['message'] ?? 'Giao dịch bị hủy.';
        $order->ghi_chu = '[MoMo: Thất bại - ' . $errorMsg . '] ' . $order->ghi_chu;
        $order->trang_thai = 'da_huy';
        $order->save();

        return redirect()->route('home')
            ->with('error', 'Thanh toán MoMo thất bại: ' . $errorMsg);
    }


    // ====================================================
    // 6. MOMO IPN (WEBHOOK - MoMo gọi phía server)
    // ====================================================
    public function momoNotify(Request $request)
    {
        // Endpoint này MoMo gọi server-to-server để xác nhận giao dịch
        // Trong môi trường local/sandbox không nhận được IPN thật nên chỉ log lại
        \Log::info('[MoMo IPN]', $request->all());
        return response()->json(['message' => 'ok'], 200);
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
