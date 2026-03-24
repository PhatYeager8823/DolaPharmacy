<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    // 1. Danh sách
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    // 2. Form thêm
    public function create()
    {
        return view('admin.brands.create');
    }

    // 3. Lưu
    public function store(Request $request)
    {
        $request->validate([
            'ten' => 'required|unique:brands,ten|max:255',
            'xuat_xu' => 'nullable|string|max:100',
        ], [
            'ten.required' => 'Tên thương hiệu không được để trống.',
            'ten.unique' => 'Thương hiệu này đã tồn tại.'
        ]);

        Brand::create([
            'ten' => $request->ten,
            'slug' => Str::slug($request->ten),
            'xuat_xu' => $request->xuat_xu,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu thành công!');
    }

    // 4. Form sửa
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    // 5. Cập nhật
    public function update(Request $request, string $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'ten' => 'required|unique:brands,ten,'.$id,
        ]);

        $brand->update([
            'ten' => $request->ten,
            'slug' => Str::slug($request->ten),
            'xuat_xu' => $request->xuat_xu,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thành công!');
    }

    // 6. Xóa
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);

        // Kiểm tra xem thương hiệu này có đang gắn với thuốc nào không
        if($brand->thuocs()->count() > 0) {
            return back()->with('error', 'Không thể xóa thương hiệu này vì đang có sản phẩm sử dụng!');
        }

        $brand->delete();
        return back()->with('success', 'Đã xóa thương hiệu.');
    }
}
