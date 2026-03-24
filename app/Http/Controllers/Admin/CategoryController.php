<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. Danh sách
    public function index()
    {
        // Lấy danh sách, phân trang 10 dòng
        $categories = DanhMuc::with('parent')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Form thêm mới
    public function create()
    {
        // Lấy danh mục cha để chọn (nếu tạo danh mục con)
        $parents = DanhMuc::whereNull('danh_muc_cha_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    // 3. Lưu dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required|unique:danh_mucs,ten_danh_muc',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống',
            'ten_danh_muc.unique' => 'Tên danh mục đã tồn tại'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_danh_muc); // Tự tạo slug từ tên

        // Xử lý ảnh (Nếu có) - Phần này làm sau hoặc để null tạm

        DanhMuc::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công');
    }

    // 4. Form sửa
    public function edit(string $id)
    {
        $category = DanhMuc::findOrFail($id);
        $parents = DanhMuc::whereNull('danh_muc_cha_id')->where('id', '!=', $id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    // 5. Cập nhật
    public function update(Request $request, string $id)
    {
        $category = DanhMuc::findOrFail($id);

        $request->validate([
            'ten_danh_muc' => 'required|unique:danh_mucs,ten_danh_muc,'.$id,
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->ten_danh_muc);

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công');
    }

    // 6. Xóa
    public function destroy(string $id)
    {
        $category = DanhMuc::findOrFail($id);

        // Kiểm tra nếu có danh mục con hoặc có thuốc thì không cho xóa (để an toàn)
        if($category->children()->count() > 0 || $category->thuocs()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang chứa dữ liệu con');
        }

        $category->delete();
        return back()->with('success', 'Đã xóa danh mục');
    }
}
