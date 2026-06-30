<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\NguoiDung;
use App\Models\Thuoc;
use App\Models\PhieuNhap;
use App\Models\ChiTietPhieuNhap;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // --- Lấy tháng/năm từ filter, mặc định là tháng hiện tại ---
        $month = $request->input('month', now()->month);
        $year  = $request->input('year',  now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        // ============================================================
        // I. THẺ SỐ LIỆU TỔNG QUAN (tháng được chọn)
        // ============================================================
        $revenue = Order::where('trang_thai', 'da_giao')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('tong_tien');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $deliveredOrders  = Order::where('trang_thai', 'da_giao')->whereBetween('created_at', [$startDate, $endDate])->count();
        $cancelledOrders  = Order::where('trang_thai', 'da_huy')->whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingOrders    = Order::whereIn('trang_thai', ['cho_xac_nhan', 'dang_giao'])->whereBetween('created_at', [$startDate, $endDate])->count();

        $newUsers = NguoiDung::where('vai_tro', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Tổng chi phí nhập hàng trong tháng
        $importCost = PhieuNhap::whereBetween('created_at', [$startDate, $endDate])->sum('tong_tien');

        // Lợi nhuận ước tính = Doanh thu - Chi phí nhập
        $estimatedProfit = $revenue - $importCost;

        // ============================================================
        // II. DOANH THU THEO NGÀY TRONG THÁNG (Biểu đồ)
        // ============================================================
        $revenueByDay = Order::select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(tong_tien) as total')
            )
            ->where('trang_thai', 'da_giao')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = $endDate->day;
        $dailyRevenue = [];
        $daysLabel = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dailyRevenue[] = (float)($revenueByDay[$i] ?? 0);
            $daysLabel[] = 'Ngày ' . $i;
        }

        // ============================================================
        // III. TOP 10 SẢN PHẨM BÁN CHẠY TRONG THÁNG
        // ============================================================
        $topProducts = OrderItem::select(
                'thuoc_id',
                DB::raw('SUM(so_luong) as tong_so_luong'),
                DB::raw('SUM(thanh_tien) as tong_doanh_thu')
            )
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('trang_thai', 'da_giao')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('thuoc:id,ten_thuoc,ma_san_pham,hinh_anh,gia_ban')
            ->groupBy('thuoc_id')
            ->orderByDesc('tong_so_luong')
            ->limit(10)
            ->get();

        // ============================================================
        // IV. TRẠNG THÁI ĐƠN HÀNG TRONG THÁNG (Biểu đồ tròn)
        // ============================================================
        $orderStatusCounts = Order::select('trang_thai', DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('trang_thai')
            ->pluck('total', 'trang_thai')
            ->toArray();

        $orderStatusData = [
            $orderStatusCounts['cho_xac_nhan'] ?? 0,
            $orderStatusCounts['dang_giao']    ?? 0,
            $orderStatusCounts['da_giao']      ?? 0,
            $orderStatusCounts['da_huy']       ?? 0,
        ];

        // ============================================================
        // V. DOANH THU THEO PHƯƠNG THỨC THANH TOÁN
        // ============================================================
        $revenueByPayment = Order::select(
                'phuong_thuc_thanh_toan',
                DB::raw('COUNT(*) as so_don'),
                DB::raw('SUM(tong_tien) as tong_tien')
            )
            ->where('trang_thai', 'da_giao')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('phuong_thuc_thanh_toan')
            ->get();

        // ============================================================
        // VI. THUỐC SẮP HẾT HÀNG (< 15 đơn vị)
        // ============================================================
        $lowStockProducts = Thuoc::where('so_luong_ton', '<', 15)
            ->orderBy('so_luong_ton', 'asc')
            ->limit(10)
            ->get(['id', 'ten_thuoc', 'ma_san_pham', 'so_luong_ton', 'don_vi_tinh']);

        // ============================================================
        // VII. LỊCH SỬ NHẬP HÀNG TRONG THÁNG
        // ============================================================
        $importHistory = PhieuNhap::with('nhaCungCap')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ============================================================
        // VIII. KHÁCH HÀNG MỚI TRONG THÁNG
        // ============================================================
        $newUsersThisMonth = NguoiDung::where('vai_tro', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'ten', 'sdt', 'email', 'created_at']);

        // ============================================================
        // IX. SO SÁNH VỚI THÁNG TRƯỚC
        // ============================================================
        $prevMonth = Carbon::create($year, $month, 1)->subMonth();
        $prevRevenue = Order::where('trang_thai', 'da_giao')
            ->whereMonth('created_at', $prevMonth->month)
            ->whereYear('created_at', $prevMonth->year)
            ->sum('tong_tien');
        $prevOrders = Order::whereMonth('created_at', $prevMonth->month)
            ->whereYear('created_at', $prevMonth->year)
            ->count();

        $revenueGrowth = $prevRevenue > 0 ? round((($revenue - $prevRevenue) / $prevRevenue) * 100, 1) : ($revenue > 0 ? 100 : 0);
        $ordersGrowth  = $prevOrders  > 0 ? round((($totalOrders - $prevOrders)   / $prevOrders)   * 100, 1) : ($totalOrders > 0 ? 100 : 0);

        return view('admin.reports.index', compact(
            'month', 'year',
            'startDate', 'endDate',
            'revenue', 'totalOrders', 'deliveredOrders', 'cancelledOrders', 'pendingOrders',
            'newUsers', 'importCost', 'estimatedProfit',
            'dailyRevenue', 'daysLabel',
            'topProducts',
            'orderStatusData',
            'revenueByPayment',
            'lowStockProducts',
            'importHistory',
            'newUsersThisMonth',
            'revenueGrowth', 'ordersGrowth', 'prevRevenue', 'prevOrders'
        ));
    }

    public function export(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year',  now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate   = Carbon::create($year, $month, 1)->endOfMonth();

        $fileName = "bao-cao-dola-pharmacy-thang-{$month}-{$year}.csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Lấy dữ liệu cơ bản
        $revenue = Order::where('trang_thai', 'da_giao')->whereBetween('created_at', [$startDate, $endDate])->sum('tong_tien');
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $importCost = PhieuNhap::whereBetween('created_at', [$startDate, $endDate])->sum('tong_tien');
        $estimatedProfit = $revenue - $importCost;

        $topProducts = OrderItem::select('thuoc_id', DB::raw('SUM(so_luong) as tong_so_luong'), DB::raw('SUM(thanh_tien) as tong_doanh_thu'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('trang_thai', 'da_giao')->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('thuoc:id,ten_thuoc,ma_san_pham,don_vi_tinh')
            ->groupBy('thuoc_id')
            ->orderByDesc('tong_so_luong')
            ->get();

        $callback = function() use($month, $year, $revenue, $totalOrders, $importCost, $estimatedProfit, $topProducts) {
            $file = fopen('php://output', 'w');
            
            // Ghi BOM (Byte Order Mark) để Excel nhận diện chuẩn UTF-8 Tiếng Việt
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Header Báo Cáo
            fputcsv($file, ['NHÀ THUỐC DOLA PHARMACY']);
            fputcsv($file, ['BÁO CÁO THỐNG KÊ DOANH THU & HOẠT ĐỘNG']);
            fputcsv($file, ['Kỳ báo cáo:', "Tháng $month / $year"]);
            fputcsv($file, []);

            // Phần I: Tổng quan
            fputcsv($file, ['I. TỔNG QUAN TÀI CHÍNH']);
            fputcsv($file, ['Chỉ tiêu', 'Giá trị (VNĐ / SL)']);
            fputcsv($file, ['Tổng đơn hàng phát sinh', $totalOrders]);
            fputcsv($file, ['Tổng doanh thu', number_format($revenue, 0, ',', '.')]);
            fputcsv($file, ['Tổng chi phí nhập kho', number_format($importCost, 0, ',', '.')]);
            fputcsv($file, ['Lợi nhuận ước tính', number_format($estimatedProfit, 0, ',', '.')]);
            fputcsv($file, []);

            // Phần II: Top sản phẩm bán chạy
            fputcsv($file, ['II. CHI TIẾT SẢN PHẨM BÁN RA']);
            fputcsv($file, ['Mã SP', 'Tên thuốc', 'Đơn vị tính', 'Số lượng bán', 'Doanh thu (VNĐ)']);

            foreach ($topProducts as $item) {
                fputcsv($file, [
                    $item->thuoc->ma_san_pham ?? 'N/A',
                    $item->thuoc->ten_thuoc ?? 'N/A',
                    $item->thuoc->don_vi_tinh ?? 'N/A',
                    $item->tong_so_luong,
                    number_format($item->tong_doanh_thu, 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
