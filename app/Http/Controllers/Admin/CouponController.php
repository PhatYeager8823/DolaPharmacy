<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\File; // Dùng để xóa ảnh cũ

class CouponController extends Controller
{
    // Danh sách mã
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        \Illuminate\Support\Facades\Session::put('coupon_back_url', request()->fullUrl());
        return view('admin.coupons.index', compact('coupons'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Xử lý lưu mới
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'value' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate ảnh
        ]);

        $data = $request->all();

        // 2. Xử lý checkbox is_active
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // 3. Xử lý upload ảnh (QUAN TRỌNG)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Lưu vào thư mục public/images/coupons
            $file->move(public_path('images/coupons'), $filename);

            // Gán tên file vào dữ liệu để lưu DB
            $data['image'] = $filename;
        }

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Thêm mã giảm giá thành công');
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $backUrl = \Illuminate\Support\Facades\Session::get('coupon_back_url', route('admin.coupons.index'));
        return view('admin.coupons.edit', compact('coupon', 'backUrl'));
    }

    // Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'value' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {
            // 1. Xóa ảnh cũ nếu có
            if ($coupon->image && \Illuminate\Support\Facades\File::exists(public_path('images/coupons/' . $coupon->image))) {
                \Illuminate\Support\Facades\File::delete(public_path('images/coupons/' . $coupon->image));
            }

            // 2. Lưu ảnh mới
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/coupons'), $filename);

            $data['image'] = $filename;
        }

        $coupon->update($data);

        $redirectUrl = $request->input('redirect_url', \Illuminate\Support\Facades\Session::get('coupon_back_url', route('admin.coupons.index')));
        return redirect($redirectUrl)->with('success', 'Cập nhật mã giảm giá thành công');
    }

    // Xóa mã
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        // Xóa ảnh khi xóa mã cho sạch server
        if ($coupon->image && File::exists(public_path('images/coupons/' . $coupon->image))) {
            File::delete(public_path('images/coupons/' . $coupon->image));
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã xóa mã giảm giá');
    }
}
