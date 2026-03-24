<?php

namespace App\Http\Controllers;

use App\Models\DanhMuc;
use App\Models\Thuoc;
use App\Models\Brand; // <--- Nhớ thêm dòng này
use Illuminate\Http\Request;

class DanhMucController extends Controller
{
    public function show(Request $request, $slug) // Thêm Request $request để xử lý lọc sau này
    {
        $danhMuc = DanhMuc::where('slug', $slug)->firstOrFail();
        $allParents = DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get();

        // Lấy danh sách thương hiệu để hiển thị bộ lọc
        $allBrands = Brand::all(); // <--- THÊM DÒNG NÀY

        $children = $danhMuc->children()->get();

        if ($children->count() > 0) {
            $popularSubCategories = $children;
        } else {
            $popularSubCategories = collect();
        }

        // Logic lấy sản phẩm (đệ quy)
        $listId = [$danhMuc->id];
        foreach ($children as $child) {
            $listId[] = $child->id;
        }

        // Khởi tạo Query
        $query = Thuoc::whereIn('danh_muc_id', $listId)
            ->where('is_active', 1)
            ->with('brand');

        // --- THÊM LOGIC LỌC (Giống ThuocController) ---

        // 1. Lọc theo Thương hiệu
        if ($request->has('brands')) {
            $query->whereIn('brand_id', $request->brands);
        }

        // 2. Lọc theo Giá
        if ($request->has('price_range')) {
            switch ($request->price_range) {
                case 'duoi_100k': $query->where('gia_ban', '<', 100000); break;
                case '100k_300k': $query->whereBetween('gia_ban', [100000, 300000]); break;
                case '300k_500k': $query->whereBetween('gia_ban', [300000, 500000]); break;
                case 'tren_500k': $query->where('gia_ban', '>', 500000); break;
            }
        }
        if ($request->price_min) $query->where('gia_ban', '>=', $request->price_min);
        if ($request->price_max) $query->where('gia_ban', '<=', $request->price_max);

        // 3. Sắp xếp
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc': $query->orderBy('gia_ban', 'asc'); break;
                case 'price_desc': $query->orderBy('gia_ban', 'desc'); break;
                case 'name_asc': $query->orderBy('ten_thuoc', 'asc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        return view('thuoc.index', compact(
            'danhMuc',
            'allParents',
            'allBrands', // <--- THÊM BIẾN NÀY VÀO COMPACT
            'children',
            'popularSubCategories',
            'products'
        ));
    }
}
