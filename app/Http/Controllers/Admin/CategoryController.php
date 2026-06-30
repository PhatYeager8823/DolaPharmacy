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
        \Illuminate\Support\Facades\Session::put('category_back_url', request()->fullUrl());
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Form thêm mới
    public function create()
    {
        // Lấy tất cả danh mục để tạo cấu trúc cây
        $parents = DanhMuc::all();
        return view('admin.categories.create', compact('parents'));
    }

    // 3. Lưu dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục không được để trống',
        ]);

        $data = $request->all();
        // Tạo slug duy nhất
        $slug = Str::slug($request->ten_danh_muc);
        $count = 1;
        while (DanhMuc::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->ten_danh_muc) . '-' . $count;
            $count++;
        }
        $data['slug'] = $slug;

        DanhMuc::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công');
    }

    // 4. Form sửa
    public function edit(string $id)
    {
        $category = DanhMuc::findOrFail($id);
        // Lấy tất cả trừ chính nó để tránh lặp vô hạn
        $parents = DanhMuc::where('id', '!=', $id)->get();
        $backUrl = \Illuminate\Support\Facades\Session::get('category_back_url', route('admin.categories.index'));
        return view('admin.categories.edit', compact('category', 'parents', 'backUrl'));
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

        $redirectUrl = $request->input('redirect_url', \Illuminate\Support\Facades\Session::get('category_back_url', route('admin.categories.index')));
        return redirect($redirectUrl)->with('success', 'Cập nhật thành công');
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
