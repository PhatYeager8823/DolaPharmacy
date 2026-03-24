<?php

namespace App\Http\Controllers;

use App\Models\Thuoc;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use App\Models\Brand;

class ThuocController extends Controller
{
    // ===============================
    // HIỂN THỊ DANH SÁCH TẤT CẢ SẢN PHẨM
    // ===============================
    public function index(Request $request)
    {
        // 1. Khởi tạo Query
        $query = Thuoc::where('is_active', 1)
                    ->where('so_luong_ton', '>', 0) // <--- THÊM VÀO ĐÂY
                    ->with('brand');

        // 2. Xử lý TÌM KIẾM (Thêm đoạn này)
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                // Tìm theo tên thuốc
                $q->where('ten_thuoc', 'like', '%' . $keyword . '%')
                  // Tìm theo hoạt chất (Rất hữu ích cho nhà thuốc)
                  ->orWhere('hoat_chat', 'like', '%' . $keyword . '%')
                  // Tìm theo công dụng
                  ->orWhere('cong_dung', 'like', '%' . $keyword . '%');
            });
        }
        
        // 3. LỌC THEO THƯƠNG HIỆU (Nhận mảng brand_ids từ form)
        if ($request->has('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        // 4. LỌC THEO MỨC GIÁ
        if ($request->has('price_range')) {
            switch ($request->price_range) {
                case 'duoi_100k':
                    $query->where('gia_ban', '<', 100000);
                    break;
                case '100k_300k':
                    $query->whereBetween('gia_ban', [100000, 300000]);
                    break;
                case '300k_500k':
                    $query->whereBetween('gia_ban', [300000, 500000]);
                    break;
                case 'tren_500k':
                    $query->where('gia_ban', '>', 500000);
                    break;
            }
        }
        // Lọc theo giá tự nhập (Min - Max)
        if ($request->price_min) {
            $query->where('gia_ban', '>=', $request->price_min);
        }
        if ($request->price_max) {
            $query->where('gia_ban', '<=', $request->price_max);
        }

        // 5. SẮP XẾP (SORT)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc': // Giá thấp -> cao
                    $query->orderBy('gia_ban', 'asc');
                    break;
                case 'price_desc': // Giá cao -> thấp
                    $query->orderBy('gia_ban', 'desc');
                    break;
                case 'name_asc': // Tên A-Z
                    $query->orderBy('ten_thuoc', 'asc');
                    break;
                case 'newest': // Mới nhất
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Mặc định là mới nhất
        }

        // 6. Thực thi Query và Phân trang
        $products = $query->paginate(12);

        // --- Dữ liệu phụ cho Sidebar ---
        // Lấy danh mục cha
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();

        // Lấy danh sách tất cả thương hiệu để hiện checkbox
        $allBrands = \App\Models\Brand::all();

        $danhMuc = null;
        $children = collect();
        $popularSubCategories = $allParents; // Ở trang chủ thì hiện danh mục cha

        return view('thuoc.index', compact(
            'products',
            'allParents',
            'allBrands', // Truyền thêm biến này sang view
            'danhMuc',
            'children',
            'popularSubCategories'
        ));
    }

    public function show($slug)
    {
        // Giữ nguyên như cũ (code ở câu trả lời trước)
        $thuoc = Thuoc::where('slug', $slug)
                    ->where('is_active', 1)
                    ->with(['brand', 'nhaCungCap', 'danhMuc', 'danhGias' => function($q) {
                        $q->where('trang_thai', 1)->latest()->with('nguoiDung');
                    }])
                    ->firstOrFail();

        // Tính số sao trung bình
        $danhGias = $thuoc->danhGias;
        $avgRating = $danhGias->count() > 0 ? round($danhGias->avg('so_sao'), 1) : 0;

        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();
        $danhMuc = $thuoc->danhMuc;
        $relatedProducts = Thuoc::where('danh_muc_id', $thuoc->danh_muc_id)
                                ->where('id', '!=', $thuoc->id)
                                ->where('is_active', 1)
                                ->where('so_luong_ton', '>', 0) // <--- THÊM VÀO ĐÂY
                                ->with('brand')
                                ->inRandomOrder()
                                ->limit(4)->get();

        return view('thuoc.show', compact('thuoc', 'allParents', 'danhMuc', 'relatedProducts', 'danhGias', 'avgRating'));
    }

    public function promotion()
    {
        // 1. Lấy sản phẩm khuyến mãi
        $products = Thuoc::where('is_active', 1)
                        ->where('so_luong_ton', '>', 0) // <--- THÊM VÀO ĐÂY
                        ->whereColumn('gia_cu', '>', 'gia_ban')
                        ->latest()
                        ->paginate(12);

        // 2. Lấy dữ liệu cho Sidebar (Bộ lọc)
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();
        $allBrands  = Brand::all();

        // 3. === SỬA ĐOẠN NÀY: Lấy danh mục cha để hiện ô tròn ===
        // Thay vì collect([]), ta lấy các danh mục gốc (cấp 1)
        $popularSubCategories = DanhMuc::whereNull('danh_muc_cha_id')->get();

        // 4. Tiêu đề & View
        $title = "Sản phẩm khuyến mãi hot";
        return view('thuoc.index', compact('products', 'title', 'allParents', 'allBrands', 'popularSubCategories'));
    }
}
