<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\NguoiDung;
use App\Models\Thuoc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. CÁC SỐ LIỆU TỔNG QUAN (CARDS)
        $totalRevenue = Order::where('trang_thai', 'da_giao')->sum('tong_tien'); // Tổng doanh thu thực tế
        $totalOrders = Order::count(); // Tổng số đơn
        $totalProducts = Thuoc::count(); // Tổng số thuốc
        $totalUsers = NguoiDung::where('vai_tro', 'customer')->count();

        // 2. BIỂU ĐỒ DOANH THU 12 THÁNG (BAR CHART)
        // Lấy doanh thu của các đơn hàng "da_giao" trong năm nay
        $revenueData = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(tong_tien) as total')
        )
        ->where('trang_thai', 'da_giao')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Chuẩn hóa dữ liệu cho đủ 12 tháng (Tháng nào không có doanh thu thì = 0)
        $monthlyRevenue = [];
        for ($i = 1; $i <= 12; $i++) {
            // [QUAN TRỌNG] Thêm (float) vào trước để ép kiểu thành số
            $monthlyRevenue[] = (float)($revenueData[$i] ?? 0);
        }

        // 3. BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG (DOUGHNUT CHART)
        $statusCounts = Order::select('trang_thai', DB::raw('count(*) as total'))
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();

        // Sắp xếp theo thứ tự hiển thị mong muốn
        $orderStats = [
            $statusCounts['cho_xac_nhan'] ?? 0,
            $statusCounts['dang_giao'] ?? 0,
            $statusCounts['da_giao'] ?? 0,
            $statusCounts['da_huy'] ?? 0,
        ];

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'monthlyRevenue',
            'orderStats'
        ));
    }
}
