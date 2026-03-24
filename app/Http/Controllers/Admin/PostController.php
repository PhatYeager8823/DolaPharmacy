<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BaiViet;
use Illuminate\Support\Str; // Dùng để tạo slug
use Illuminate\Support\Facades\File; // Dùng để xóa ảnh cũ

class PostController extends Controller
{
    // 1. Danh sách bài viết
    public function index()
    {
        // Lấy bài viết mới nhất trước, phân trang 10 bài
        $posts = BaiViet::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    // 2. Form thêm mới
    public function create()
    {
        return view('admin.posts.create');
    }

    // 3. Xử lý lưu bài viết (FIX LỖI _token TẠI ĐÂY)
    public function store(Request $request)
    {
        $request->validate([
            'tieu_de' => 'required|max:255',
            'mo_ta_ngan' => 'required',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Loại bỏ _token ra khỏi dữ liệu
        $data = $request->except('_token');

        // Tạo slug từ tiêu đề (VD: Tieu De Bai Viet -> tieu-de-bai-viet)
        $data['slug'] = Str::slug($request->tieu_de);

        // Xử lý Checkbox (Nếu không tích thì trả về 0)
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý Upload ảnh
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Lưu vào public/images/news
            $file->move(public_path('images/news'), $filename);
            $data['hinh_anh'] = $filename;
        }

        BaiViet::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công');
    }

    // 4. Form sửa bài viết
    public function edit($id)
    {
        $post = BaiViet::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // 5. Xử lý cập nhật
    public function update(Request $request, $id)
    {
        $post = BaiViet::findOrFail($id);

        $request->validate([
            'tieu_de' => 'required|max:255',
            'mo_ta_ngan' => 'required',
            'noi_dung' => 'required',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('_token'); // Loại bỏ _token
        $data['slug'] = Str::slug($request->tieu_de);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // Xử lý ảnh mới
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có
            if ($post->hinh_anh && File::exists(public_path('images/news/' . $post->hinh_anh))) {
                File::delete(public_path('images/news/' . $post->hinh_anh));
            }

            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/news'), $filename);
            $data['hinh_anh'] = $filename;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

    // 6. Xóa bài viết
    public function destroy($id)
    {
        $post = BaiViet::findOrFail($id);

        // Xóa ảnh trước khi xóa bài
        if ($post->hinh_anh && File::exists(public_path('images/news/' . $post->hinh_anh))) {
            File::delete(public_path('images/news/' . $post->hinh_anh));
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết');
    }
}
