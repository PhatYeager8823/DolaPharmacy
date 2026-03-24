<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Import các Model cần thiết
use App\Models\NhaCungCap;
use App\Models\Thuoc;
use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use App\Models\TonKho; // <--- Quan trọng để ghi lịch sử

class WarehouseController extends Controller
{
    // 1. Danh sách phiếu nhập
    public function index()
    {
        // Lấy danh sách phiếu nhập, sắp xếp mới nhất
        $phieuNhaps = PhieuNhap::with(['nhaCungCap', 'nguoiNhap'])
                        ->latest()
                        ->paginate(10);

        return view('admin.warehouse.index', compact('phieuNhaps'));
    }

    // 2. Giao diện tạo phiếu nhập mới
    public function create()
    {
        $providers = NhaCungCap::where('trang_thai', 1)->get();

        // Lấy thêm so_luong_ton để hiển thị
        $products = Thuoc::select('id', 'ten_thuoc', 'ma_thuoc', 'so_luong_ton')
                        ->where('is_active', 1)
                        ->get();

        return view('admin.warehouse.create', compact('providers', 'products'));
    }

    // 3. Xử lý Lưu phiếu & Cộng kho & Ghi log TonKho
    public function store(Request $request)
    {
        $request->validate([
            'nha_cung_cap_id' => 'required|exists:nha_cung_caps,id',
            'product_id'      => 'required|array',
            'product_id.*'    => 'required|exists:thuocs,id',
            'so_luong'        => 'required|array',
            'so_luong.*'      => 'required|integer|min:1',
            'gia_nhap'        => 'required|array',
            'gia_nhap.*'      => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction(); // Bắt đầu giao dịch an toàn

            // A. Tạo Phiếu Nhập (Header)
            $phieuNhap = PhieuNhap::create([
                'ma_phieu'        => 'PN' . date('YmdHis'), // VD: PN20231214120000
                'nha_cung_cap_id' => $request->nha_cung_cap_id,
                'nguoi_nhap_id'   => Auth::id(),
                'ghi_chu'         => $request->ghi_chu,
                'tong_tien'       => 0 // Sẽ cập nhật sau
            ]);

            $tongTien = 0;

            // B. Duyệt từng dòng sản phẩm
            foreach ($request->product_id as $key => $thuocId) {
                $soLuong = (int) $request->so_luong[$key];
                $giaNhap = (float) $request->gia_nhap[$key];
                $thanhTien = $soLuong * $giaNhap;

                if ($soLuong > 0) {
                    // 1. Lưu chi tiết phiếu nhập
                    ChiTietPhieuNhap::create([
                        'phieu_nhap_id' => $phieuNhap->id,
                        'thuoc_id'      => $thuocId,
                        'so_luong'      => $soLuong,
                        'gia_nhap'      => $giaNhap,
                        'thanh_tien'    => $thanhTien
                    ]);

                    // 2. Cập nhật bảng THUỐC (Cộng số lượng + Cập nhật giá vốn mới)
                    $thuoc = Thuoc::lockForUpdate()->find($thuocId); // Khóa dòng để tránh xung đột
                    $thuoc->so_luong_ton += $soLuong;
                    $thuoc->gia_nhap = $giaNhap; // Cập nhật giá nhập mới nhất
                    $thuoc->save();

                    // 3. Ghi lịch sử bảng TỒN KHO (Inventory Log)
                    TonKho::create([
                        'thuoc_id'          => $thuocId,
                        'so_luong_thay_doi' => $soLuong,      // Số dương là nhập thêm
                        'loai_giao_dich'    => 'nhap_hang',   // Đánh dấu loại giao dịch
                        'gia_nhap'          => $giaNhap,
                        'ghi_chu'           => "Nhập hàng từ phiếu " . $phieuNhap->ma_phieu
                    ]);

                    $tongTien += $thanhTien;
                }
            }

            // C. Cập nhật lại tổng tiền cho phiếu
            $phieuNhap->update(['tong_tien' => $tongTien]);

            DB::commit(); // Lưu tất cả vào DB
            return redirect()->route('admin.warehouse.index')->with('success', 'Đã nhập kho thành công!');

        } catch (\Exception $e) {
            DB::rollBack(); // Nếu lỗi thì hủy hết, không lưu gì cả
            return back()->with('error', 'Lỗi nhập hàng: ' . $e->getMessage())->withInput();
        }
    }

    public function inventory(Request $request)
    {
        // 1. Khởi tạo query
        $query = Thuoc::with('danhMuc');

        // 2. Lọc theo từ khóa (Tên hoặc Mã)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_thuoc', 'like', "%{$keyword}%")
                  ->orWhere('ma_san_pham', 'like', "%{$keyword}%");
            });
        }

        // 3. Lọc theo trạng thái tồn kho
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'out_of_stock': // Hết hàng
                    $query->where('so_luong_ton', 0);
                    break;
                case 'low_stock':    // Sắp hết (nhỏ hơn 10)
                    $query->where('so_luong_ton', '>', 0)
                          ->where('so_luong_ton', '<', 10);
                    break;
                case 'in_stock':     // Còn hàng
                    $query->where('so_luong_ton', '>=', 10);
                    break;
            }
        }

        // 4. Lấy dữ liệu phân trang
        $products = $query->latest()->paginate(10)->appends($request->all());

        // 5. Tính toán số liệu tổng quan
        $totalStock = Thuoc::sum('so_luong_ton');

        // Lưu ý: Nếu chưa có cột 'gia_nhap', hãy đổi thành 'gia_ban'
        $totalValue = Thuoc::sum(DB::raw('so_luong_ton * gia_nhap'));

        // 6. Trả về View
        // Đảm bảo file blade nằm đúng chỗ (ví dụ: resources/views/admin/inventory.blade.php)
        return view('admin.warehouse.inventory', compact('products', 'totalStock', 'totalValue'));
    }

    // 4. Xem chi tiết phiếu nhập
    public function show($id)
    {
        $phieuNhap = PhieuNhap::with(['chiTiet.thuoc', 'nhaCungCap', 'nguoiNhap'])->findOrFail($id);
        return view('admin.warehouse.show', compact('phieuNhap'));
    }
}
