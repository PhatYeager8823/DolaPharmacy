<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // <--- 1. Thêm dòng này lên đầu file

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('thu_tu', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        // Lấy tất cả các file trong thư mục public/images/sliders
        $files = [];
        $path = public_path('images/sliders');

        if (File::exists($path)) {
            // Lấy danh sách file và chỉ lấy tên file
            $files = File::files($path);
            $files = array_map(function ($file) {
                return $file->getFilename();
            }, $files);
        }

        return view('admin.sliders.create', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Không bắt buộc upload file nếu đã chọn ảnh cũ
            'hinh_anh' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // XỬ LÝ ẢNH
        // Ưu tiên 1: Nếu có upload ảnh mới
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/sliders'), $filename);
            $data['hinh_anh'] = $filename;
        }
        // Ưu tiên 2: Nếu chọn ảnh cũ từ thư viện
        elseif ($request->filled('chon_hinh_anh_cu')) {
            $data['hinh_anh'] = $request->chon_hinh_anh_cu;
        }
        // Nếu không chọn gì cả -> Báo lỗi
        else {
            return back()->withErrors(['hinh_anh' => 'Vui lòng upload ảnh mới hoặc chọn ảnh có sẵn.'])->withInput();
        }

        Slider::create($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Thêm Slider thành công!');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ
            if ($slider->hinh_anh && file_exists(public_path('images/sliders/' . $slider->hinh_anh))) {
                unlink(public_path('images/sliders/' . $slider->hinh_anh));
            }
            $file = $request->file('hinh_anh');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/sliders'), $filename);
            $data['hinh_anh'] = $filename;
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Cập nhật Slider thành công!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->hinh_anh && file_exists(public_path('images/sliders/' . $slider->hinh_anh))) {
            unlink(public_path('images/sliders/' . $slider->hinh_anh));
        }
        $slider->delete();
        return back()->with('success', 'Đã xóa Slider.');
    }
}
