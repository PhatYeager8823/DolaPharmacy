<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thuoc;
use App\Models\DanhMuc;
use App\Models\Brand;
use App\Models\NhaCungCap;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // <--- Quan trọng
use Illuminate\Support\Facades\Session; // <--- THÊM DÒNG NÀY

class ThuocController extends Controller
{
    // 1. Danh sách thuốc (Giao diện Admin)
    public function index(Request $request)
    {
        // 1. Khởi tạo Query
        $query = Thuoc::with('danhMuc');

        // 2. Lọc theo Tên hoặc Mã (Keyword)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', "%{$keyword}%")
                  ->orWhere('ma_san_pham', 'like', "%{$keyword}%");
            });
        }

        // 3. Lọc theo Danh mục
        if ($request->filled('danh_muc_id')) {
            $query->where('danh_muc_id', $request->danh_muc_id);
        }

        // 4. Lọc theo Loại thuốc (Kê đơn / Không kê đơn)
        if ($request->filled('ke_don')) {
            // Lưu ý: Giá trị gửi lên là '0' hoặc '1', nên phải dùng filled() chứ không dùng if($request->ke_don)
            $query->where('ke_don', $request->ke_don);
        }

        // 5. Lọc theo Trạng thái (Đang bán / Ẩn)
        if ($request->filled('trang_thai')) {
            $query->where('is_active', $request->trang_thai);
        }

        // 6. Lấy dữ liệu và Phân trang
        // appends($request->all()) giúp giữ lại các bộ lọc khi bấm sang trang 2, 3...
        $products = $query->latest()->paginate(10)->appends($request->all());
        $categories = DanhMuc::all();

        // [MỚI] Lưu URL hiện tại (kèm bộ lọc page=2&danh_muc=...) vào Session
        Session::put('product_back_url', request()->fullUrl());

        return view('admin.products.index', compact('products', 'categories'));
    }

    // 2. Giao diện Thêm mới thuốc
    public function create()
    {
        // Lấy dữ liệu để đổ vào các ô chọn (Select box)
        // Chỉ lấy danh mục con (những danh mục có cha) để gán thuốc
        $categories = DanhMuc::whereNotNull('danh_muc_cha_id')->get();
        $brands = Brand::all();
        $suppliers = NhaCungCap::all();

        return view('admin.products.create', compact('categories', 'brands', 'suppliers'));
    }

    // 3. Xử lý Lưu thuốc vào CSDL
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'ten_thuoc'   => ['required', 'max:255', Rule::unique('thuocs')->withoutTrashed()],
            'ma_san_pham' => ['required', Rule::unique('thuocs')->withoutTrashed()],
            'gia_ban'     => 'required|numeric|min:0',
            'danh_muc_id' => 'required',
            'hinh_anh'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ], [
            'ten_thuoc.required'   => 'Tên thuốc không được để trống',
            'ten_thuoc.unique'     => 'Tên thuốc này đã tồn tại',
            'ma_san_pham.unique'   => 'Mã sản phẩm đã tồn tại',
            'gia_ban.required'     => 'Vui lòng nhập giá bán',
            'hinh_anh.image'       => 'File tải lên phải là hình ảnh'
        ]);

        $data = $request->all();

        $data['so_luong_ton'] = 0; // <--- Mặc định thuốc mới tạo là 0

        // Tự động tạo slug từ tên
        // 1. Tạo slug gốc từ tên
        $slugOriginal = Str::slug($request->ten_thuoc);
        $slug = $slugOriginal;

        // 2. Kiểm tra xem slug này đã tồn tại chưa (Tính cả trong thùng rác - withTrashed)
        $count = 1;
        while (Thuoc::withTrashed()->where('slug', $slug)->exists()) {
            // Nếu trùng thì thêm số vào đuôi: cam-tu-1, cam-tu-2...
            $slug = $slugOriginal . '-' . $count;
            $count++;
        }

        // 3. Gán slug đã xử lý vào data
        $data['slug'] = $slug;

        // Xử lý checkbox (nếu không tích thì trả về null, cần set về 0)
        $data['ke_don'] = $request->has('ke_don') ? 1 : 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['thuoc_uu_tien'] = $request->has('thuoc_uu_tien') ? 1 : 0;

        // === THÊM 3 DÒNG NÀY ===
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['is_exclusive'] = $request->has('is_exclusive') ? 1 : 0;
        $data['is_suggested'] = $request->has('is_suggested') ? 1 : 0;
        $data['is_featured']  = $request->has('is_featured') ? 1 : 0; // <--- THÊM DÒNG NÀY

        // Xử lý upload ảnh (OPT: Resize + Nén với ImageService)
        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            // Gọi ImageService để resize ảnh tự động:
            // - Max 1200x1200px
            // - Nén xuống 75% chất lượng JPEG
            $filename = ImageService::handleUpload($file, 'images/images_san_pham', [
                'max_width' => 1200,
                'max_height' => 1200,
                'quality' => 75
            ]);
            $data['hinh_anh'] = $filename;
        }

        Thuoc::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm thuốc thành công!');
    }

    // 4. Giao diện chỉnh sửa
    public function edit(string $id)
    {
        $product = Thuoc::findOrFail($id);
        $categories = DanhMuc::whereNotNull('danh_muc_cha_id')->get();
        $brands = Brand::all();
        $suppliers = NhaCungCap::all();

        // [MỚI] Lấy link từ Session. Nếu không có thì về trang index mặc định
        $backUrl = Session::get('product_back_url', route('admin.products.index'));

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'suppliers', 'backUrl'));
    }

    // 5. Cập nhật dữ liệu
    public function update(Request $request, string $id)
    {
        $product = Thuoc::findOrFail($id);

        $request->validate([
            // Unique tên thuốc nhưng trừ chính nó ra (,ten_thuoc,'.$id)
            'ten_thuoc'   => [
                'required',
                'max:255',
                Rule::unique('thuocs')->ignore($product->id)->withoutTrashed()
            ],
            'ma_san_pham' => [
                'required',
                Rule::unique('thuocs')->ignore($product->id)->withoutTrashed()
            ],
            'gia_ban'     => 'required|numeric|min:0',
            'danh_muc_id' => 'required',
            'hinh_anh'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data = $request->except(['so_luong_ton']); // <--- Loại bỏ, không cho sửa số lượng ở đây
        $data['slug'] = Str::slug($request->ten_thuoc);

        // Xử lý Checkbox (Nếu không tích -> trả về 0)
        $data['ke_don']        = $request->has('ke_don') ? 1 : 0;
        $data['is_active']     = $request->has('is_active') ? 1 : 0;
        $data['thuoc_uu_tien'] = $request->has('thuoc_uu_tien') ? 1 : 0;
        $data['is_new']        = $request->has('is_new') ? 1 : 0;
        $data['is_exclusive']  = $request->has('is_exclusive') ? 1 : 0;
        $data['is_suggested']  = $request->has('is_suggested') ? 1 : 0;
        $data['is_featured']   = $request->has('is_featured') ? 1 : 0; // <--- THÊM DÒNG NÀY

        // Xử lý ảnh (Nếu có up ảnh mới)
        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu có (sử dụng ImageService)
            ImageService::deleteFile($product->hinh_anh, 'images/images_san_pham');

            // Tải ảnh mới (Resize + Nén với ImageService)
            $file = $request->file('hinh_anh');
            $filename = ImageService::handleUpload($file, 'images/images_san_pham', [
                'max_width' => 1200,
                'max_height' => 1200,
                'quality' => 75
            ]);
            $data['hinh_anh'] = $filename;
        }

        $product->update($data);

        // [MỚI] Lấy link quay về từ form gửi lên, nếu lỗi thì lấy từ Session
        $redirectUrl = $request->input('redirect_url', Session::get('product_back_url', route('admin.products.index')));

        return redirect($redirectUrl)->with('success', 'Cập nhật thuốc thành công!');
    }

    // 6. Xóa sản phẩm
    public function destroy(string $id)
    {
        $thuoc = Thuoc::findOrFail($id);

        // Vì model Thuoc của bạn có dùng 'SoftDeletes',
        // nên lệnh này chỉ "xóa mềm" (ẩn đi) chứ không mất vĩnh viễn khỏi database.
        // Rất an toàn để khôi phục sau này nếu cần.
        $thuoc->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công!');
    }

    // ... (Các hàm Edit, Update, Destroy làm sau)
}
