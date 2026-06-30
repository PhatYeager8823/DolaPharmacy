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
                    ->where('so_luong_ton', '>', 0)
                    ->with('brand');

        // 2. Xử lý TÌM KIẾM
        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', '%' . $keyword . '%')
                  ->orWhere('hoat_chat', 'like', '%' . $keyword . '%')
                  ->orWhere('cong_dung', 'like', '%' . $keyword . '%');
            });
        }
        
        // 3. LỌC THEO THƯƠNG HIỆU
        if ($request->has('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        // 4. Lấy dữ liệu cho Sidebar (Cấu trúc 2 tầng chuẩn)
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();

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
        $allBrands = \App\Models\Brand::all();
        $danhMuc = null;
        $popularSubCategories = $allParents; 

        return view('thuoc.index', compact(
            'products',
            'allParents',
            'allBrands',
            'danhMuc',
            'popularSubCategories'
        ));
    }

    public function show($slug)
    {
        $thuoc = Thuoc::where('slug', $slug)
                    ->where('is_active', 1)
                    ->with(['brand', 'nhaCungCap', 'danhMuc', 'danhGias' => function($q) {
                        $q->where('trang_thai', 1)->latest()->with('nguoiDung');
                    }])
                    ->firstOrFail();

        $danhGias = $thuoc->danhGias;
        $avgRating = $danhGias->count() > 0 ? round($danhGias->avg('so_sao'), 1) : 0;

        // Cấu trúc Sidebar chuẩn cho trang Show
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();
        
        $danhMuc = $thuoc->danhMuc;
        // ... (phần code liên quan giữ nguyên)
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
                        ->where('so_luong_ton', '>', 0)
                        ->whereColumn('gia_cu', '>', 'gia_ban')
                        ->latest()
                        ->paginate(12);

        // 2. Lấy dữ liệu cho Sidebar đa tầng (Sử dụng cấu trúc chuẩn đồng bộ với trang TẤT CẢ SẢN PHẨM)
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();
        
        $allBrands = Brand::all();

        // 3. === SỬA ĐOẠN NÀY: Lấy danh mục cha để hiện ô tròn ===
        // Thay vì collect([]), ta lấy các danh mục gốc (cấp 1)
        $popularSubCategories = DanhMuc::whereNull('danh_muc_cha_id')->get();

        // 4. Tiêu đề & View
        $title = "Sản phẩm khuyến mãi hot";
        return view('thuoc.index', compact('products', 'title', 'allParents', 'allBrands', 'popularSubCategories'));
    }

    /**
     * Tìm kiếm nhanh bằng AJAX
     */
    public function quickSearch(Request $request)
    {
        $keyword = $request->get('keyword');
        
        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $products = Thuoc::where('is_active', 1)
            ->where('so_luong_ton', '>', 0)
            ->where(function($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', '%' . $keyword . '%')
                  ->orWhere('slug', 'like', '%' . $keyword . '%')
                  ->orWhere('hoat_chat', 'like', '%' . $keyword . '%');
            })
            ->select('id', 'ten_thuoc', 'slug', 'gia_ban', 'gia_cu', 'hinh_anh', 'don_vi_tinh')
            ->limit(8)
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->ten_thuoc,
                    'slug' => $p->slug,
                    'price' => number_format($p->gia_ban) . 'đ',
                    'old_price' => $p->gia_cu > $p->gia_ban ? number_format($p->gia_cu) . 'đ' : null,
                    'image' => $p->hinh_anh ? asset('images/images_san_pham/' . $p->hinh_anh) : asset('images/no-image.png'),
                    'url' => route('thuoc.show', $p->slug),
                    'unit' => $p->don_vi_tinh ?? 'Hộp'
                ];
            });

        return response()->json($products);
    }

    /**
     * Lấy thông tin nhanh sản phẩm (Quick View) cho Modal
     */
    public function getQuickView($id)
    {
        $product = Thuoc::where('id', $id)
            ->where('is_active', 1)
            ->with(['brand', 'danhMuc'])
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->ten_thuoc,
                'price' => number_format($product->gia_ban) . 'đ',
                'raw_price' => $product->gia_ban,
                'old_price' => $product->gia_cu > $product->gia_ban ? number_format($product->gia_cu) . 'đ' : null,
                'image' => $product->hinh_anh ? asset('images/images_san_pham/' . $product->hinh_anh) : asset('images/no-image.png'),
                'unit' => $product->don_vi_tinh ?? 'Hộp',
                'description' => $product->mo_ta_ngan ?? '',
                'category_name' => $product->danhMuc->ten_danh_muc ?? '',
                'is_prescription' => $product->ke_don == 1
            ]
        ]);
    }
}
