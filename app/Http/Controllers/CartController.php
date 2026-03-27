<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thuoc;
use App\Models\GioHang;
use App\Models\GioHangChiTiet;
use App\Models\Coupon; // <--- Nhớ import Model Coupon
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <--- Nhớ import Carbon để xử lý ngày tháng
use App\Models\Order; // <--- Thêm dòng này

class CartController extends Controller
{
    // ====================================================
    // 1. XEM GIỎ HÀNG & TÍNH TOÁN COUPON
    // ====================================================
    public function index()
    {
        $cartItems = [];
        $orderedItems = [];
        $total = 0;

        // --- A. LẤY DỮ LIỆU GIỎ HÀNG (Có phân tách trạng thái) ---
        if (Auth::check()) {
            // Đã đăng nhập: Lấy từ DB
            $user = Auth::user();
            $gioHang = GioHang::firstOrCreate(['nguoi_dung_id' => $user->id]);
            $dbItems = $gioHang->chiTiets()->with('thuoc')->get();

            foreach ($dbItems as $item) {
                if ($item->thuoc) {
                    $data = [
                        'cart_item_id' => $item->id, // Lưu ID của bảng chi tiết để xử lý riêng
                        'id'           => $item->thuoc_id,
                        'name'         => $item->thuoc->ten_thuoc,
                        'price'        => $item->thuoc->gia_ban,
                        'image'        => $item->thuoc->hinh_anh,
                        'quantity'     => $item->so_luong,
                        'slug'         => $item->thuoc->slug,
                        'unit'         => $item->thuoc->don_vi_tinh
                    ];

                    if ($item->trang_thai == 0) {
                        $cartItems[$item->thuoc_id] = $data;
                        $total += $item->thuoc->gia_ban * $item->so_luong;
                    } else {
                        // Sản phẩm đã đặt trong quá khứ
                        $orderedItems[$item->id] = $data;
                    }
                }
            }
        } else {
            // Chưa đăng nhập: Lấy từ Session (Toàn bộ là Active)
            $cartItems = session()->get('cart', []);
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        // --- B. XỬ LÝ MÃ GIẢM GIÁ (Logic mới) ---
        $discountAmount = 0;

        if (session()->has('coupon')) {
            $coupon = session()->get('coupon');

            // Tính số tiền giảm
            if ($coupon['type'] == 'fixed') {
                $discountAmount = $coupon['value'];
            } else {
                $discountAmount = ($total * $coupon['value']) / 100;
            }

            // Kiểm tra: Không được giảm quá tổng tiền (tránh âm tiền)
            if ($discountAmount > $total) {
                $discountAmount = $total;
            }
        }

        $finalTotal = $total - $discountAmount;

        // --- C. LẤY DANH SÁCH MÃ CỦA USER (Để hiện lên Popup chọn) ---
        $userCoupons = [];
        // --- C. LẤY DANH SÁCH MÃ KHẢ DỤNG CHO USER ---
        $userCoupons = [];
        if (Auth::check()) {
            $user = Auth::user();

            // Lấy ID các mã đã dùng
            $usedCouponIds = \Illuminate\Support\Facades\DB::table('coupon_user')
                ->where('user_id', $user->id)
                ->whereNotNull('used_at')
                ->pluck('coupon_id')
                ->toArray();

            // Lấy mã active, chưa hết hạn, chưa dùng
            $userCoupons = Coupon::where('is_active', 1)
                ->where('expiry_date', '>=', now())
                ->whereNotIn('id', $usedCouponIds)
                ->get();
        }

        // Truyền hết dữ liệu sang View
        return view('cart.index', [
            'cart'           => $cartItems,
            'orderedItems'   => $orderedItems, // <--- Truyền thêm danh sách đã đặt
            'total'          => $total,
            'discountAmount' => $discountAmount,
            'finalTotal'     => $finalTotal,
            'userCoupons'    => $userCoupons
        ]);
    }

    /**
     * Chuyển một sản phẩm đã đặt quay lại giỏ hàng chủ động
     */
    public function repurchase(Request $request)
    {
        if (!Auth::check()) return response()->json(['success' => false]);

        $cartItemId = $request->id;
        $oldItem = GioHangChiTiet::find($cartItemId);

        if (!$oldItem) return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);

        $userCart = GioHang::firstOrCreate(['nguoi_dung_id' => Auth::id()]);

        // Tìm xem đã có item này đang ACTIVE (0) chưa
        $activeItem = GioHangChiTiet::where('gio_hang_id', $userCart->id)
                                    ->where('thuoc_id', $oldItem->thuoc_id)
                                    ->where('trang_thai', 0)
                                    ->first();

        if ($activeItem) {
            $activeItem->so_luong += $oldItem->so_luong;
            $activeItem->save();
        } else {
            // Tạo mới active item
            GioHangChiTiet::create([
                'gio_hang_id' => $userCart->id,
                'thuoc_id'    => $oldItem->thuoc_id,
                'so_luong'    => $oldItem->so_luong,
                'trang_thai'  => 0
            ]);
        }

        return response()->json(['success' => true]);
    }

    // ====================================================
    // 2. ÁP DỤNG MÃ GIẢM GIÁ (MỚI)
    // ====================================================
    public function applyCoupon(Request $request)
    {
        $code = $request->coupon_code;

        // 1. Tìm mã trong bảng Coupons
        $coupon = Coupon::where('code', $code)->where('is_active', 1)->first();

        if (!$coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại hoặc đã bị khóa!');
        }

        // 2. Kiểm tra hạn sử dụng
        if ($coupon->expiry_date && Carbon::now()->gt($coupon->expiry_date)) {
            return back()->with('error', 'Mã giảm giá này đã hết hạn!');
        }

        // 3. Kiểm tra xem User đã dùng mã này chưa (Query trực tiếp bảng trung gian)
        if (Auth::check()) {
            $user = Auth::user();

            $hasUsed = \Illuminate\Support\Facades\DB::table('coupon_user')
                ->where('user_id', $user->id)
                ->where('coupon_id', $coupon->id)
                ->whereNotNull('used_at') // Đã có ngày dùng
                ->exists();

            if ($hasUsed) {
                return back()->with('error', 'Bạn đã sử dụng mã giảm giá này rồi!');
            }
        }

        // 4. Lưu mã hợp lệ vào Session
        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
        ]);

        if ($request->has('redirect')) {
            return redirect()->route($request->redirect)->with('success', 'Áp dụng mã giảm giá thành công!');
        }

        return back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    // ====================================================
    // 3. GỠ BỎ MÃ GIẢM GIÁ (MỚI)
    // ====================================================
    public function removeCoupon()
    {
        session()->forget('coupon');
        return back()->with('success', 'Đã gỡ bỏ mã giảm giá.');
    }

    // ====================================================
    // 4. THÊM VÀO GIỎ (Giữ nguyên)
    // ====================================================
    public function addToCart(Request $request, $id)
    {
        $product = Thuoc::find($id);
        if (!$product) return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại']);

        $qty = $request->input('so_luong', 1);
        $totalCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $gioHang = GioHang::firstOrCreate(['nguoi_dung_id' => $user->id]);

            $chiTiet = GioHangChiTiet::where('gio_hang_id', $gioHang->id)
                                     ->where('thuoc_id', $id)
                                     ->where('trang_thai', 0) // <--- CHỈ TÌM SẢN PHẨM ĐANG ACTIVE
                                     ->first();

            if ($chiTiet) {
                $chiTiet->so_luong += $qty;
                $chiTiet->save();
            } else {
                GioHangChiTiet::create([
                    'gio_hang_id' => $gioHang->id,
                    'thuoc_id'    => $id,
                    'so_luong'    => $qty,
                    'trang_thai'  => 0 // <--- MẶC ĐỊNH LÀ ACTIVE
                ]);
            }
            $totalCount = $gioHang->chiTiets()->where('trang_thai', 0)->sum('so_luong');

        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $qty;
            } else {
                $cart[$id] = [
                    "name" => $product->ten_thuoc,
                    "quantity" => $qty,
                    "price" => $product->gia_ban,
                    "image" => $product->hinh_anh,
                    "unit" => $product->don_vi_tinh,
                    "slug" => $product->slug
                ];
            }
            session()->put('cart', $cart);
            $totalCount = array_sum(array_column($cart, 'quantity'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm ' . $product->ten_thuoc . ' vào giỏ!',
            'cartCount' => $totalCount
        ]);
    }

    // ====================================================
    // 5. CẬP NHẬT SỐ LƯỢNG (Giữ nguyên)
    // ====================================================
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $total = 0;
            $itemSubtotal = 0;

            if (Auth::check()) {
                $user = Auth::user();
                $gioHang = GioHang::where('nguoi_dung_id', $user->id)->first();

                if ($gioHang) {
                    $chiTiet = GioHangChiTiet::where('gio_hang_id', $gioHang->id)
                                             ->where('thuoc_id', $request->id)
                                             ->where('trang_thai', 0) // Chỉ cập nhật item đang active
                                             ->first();
                    if ($chiTiet) {
                        $chiTiet->so_luong = $request->quantity;
                        $chiTiet->save();
                        $itemSubtotal = $chiTiet->so_luong * $chiTiet->thuoc->gia_ban;
                    }
                    foreach ($gioHang->chiTiets()->where('trang_thai', 0)->get() as $item) {
                        $total += $item->so_luong * $item->thuoc->gia_ban;
                    }
                }
            } else {
                $cart = session()->get('cart');
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);

                $itemSubtotal = $cart[$request->id]["quantity"] * $cart[$request->id]["price"];
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }

            return response()->json([
                'success' => true,
                'subtotal' => number_format($itemSubtotal) . ' đ',
                'total' => number_format($total) . ' đ'
            ]);
        }
    }

    // ====================================================
    // 6. XÓA SẢN PHẨM (Giữ nguyên)
    // ====================================================
    public function remove(Request $request)
    {
        if ($request->id) {
            $total = 0;
            $totalCount = 0;

            if (Auth::check()) {
                $user = Auth::user();
                $gioHang = GioHang::where('nguoi_dung_id', $user->id)->first();

                if ($gioHang) {
                    GioHangChiTiet::where('gio_hang_id', $gioHang->id)
                                  ->where('thuoc_id', $request->id)
                                  ->where('trang_thai', 0) // Chỉ xóa item đang active
                                  ->delete();

                    foreach ($gioHang->chiTiets()->where('trang_thai', 0)->get() as $item) {
                        $total += $item->so_luong * $item->thuoc->gia_ban;
                    }
                    $totalCount = $gioHang->chiTiets()->where('trang_thai', 0)->sum('so_luong');
                }
            } else {
                $cart = session()->get('cart');
                if (isset($cart[$request->id])) {
                    unset($cart[$request->id]);
                    session()->put('cart', $cart);
                }

                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                $totalCount = array_sum(array_column($cart, 'quantity'));
            }

            return response()->json([
                'success' => true,
                'total' => number_format($total) . ' đ',
                'cartCount' => $totalCount,
                'message' => 'Đã xóa sản phẩm'
            ]);
        }
    }

    // Chức năng Mua lại (Re-order)
    public function reOrder($id)
    {
        // 1. Lấy thông tin đơn hàng cũ
        $order = Order::with('items')->where('id', $id)->where('nguoi_dung_id', Auth::id())->first();

        if (!$order) {
            return back()->with('error', 'Đơn hàng không tồn tại.');
        }

        // 2. Lấy (hoặc tạo) giỏ hàng hiện tại của User
        $gioHang = GioHang::firstOrCreate(['nguoi_dung_id' => Auth::id()]);

        // 3. Duyệt qua từng sản phẩm trong đơn cũ và thêm vào giỏ
        foreach ($order->items as $item) {
            // Kiểm tra xem thuốc đó còn tồn tại trong hệ thống không
            $product = Thuoc::find($item->thuoc_id);

            if ($product) {
                // Kiểm tra xem trong giỏ đã có thuốc này chưa
                $cartItem = GioHangChiTiet::where('gio_hang_id', $gioHang->id)
                                         ->where('thuoc_id', $item->thuoc_id)
                                         ->first();

                if ($cartItem) {
                    // Nếu có rồi thì cộng thêm số lượng
                    $cartItem->so_luong += $item->so_luong;
                    $cartItem->trang_thai = 0; // Đảm bảo nó là ACTIVE
                    $cartItem->save();
                } else {
                    // Nếu chưa có thì tạo mới
                    GioHangChiTiet::create([
                        'gio_hang_id' => $gioHang->id,
                        'thuoc_id'    => $item->thuoc_id,
                        'so_luong'    => $item->so_luong,
                        'trang_thai'  => 0 // MẶC ĐỊNH LÀ ACTIVE
                    ]);
                }
            }
        }

        // 4. Chuyển hướng về trang giỏ hàng
        return redirect()->route('cart.index')->with('success', 'Đã thêm các sản phẩm vào giỏ hàng!');
    }
}
