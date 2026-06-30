@extends('layouts.admin')
@section('title', 'Báo Cáo Tổng Hợp')

@push('styles')
<style>
    .stat-card { border-radius: 16px !important; padding: 1.25rem !important; }
    .stat-icon { width: 52px; height: 52px; border-radius: 14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; }
    .growth-up   { color: #4ade80 !important; }
    .growth-down { color: #ff6b6b !important; }
    .section-title {
        font-size: 1rem; font-weight: 700; letter-spacing: 0.5px;
        color: #3ad4ff !important; text-transform: uppercase;
        border-left: 4px solid #3ad4ff; padding-left: 10px; margin-bottom: 1rem;
    }
    .report-table th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .progress { background: rgba(255,255,255,0.1) !important; border-radius: 20px; }
    .progress-bar { border-radius: 20px; }
    .rank-badge { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:0.75rem; }

    /* Custom Tabs */
    .nav-tabs .nav-link {
        color: rgba(255, 255, 255, 0.7);
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover { color: #fff; }
    .nav-tabs .nav-link.active {
        color: #3ad4ff !important;
        background: transparent !important;
        border-bottom: 3px solid #3ad4ff;
    }
    .tab-content { padding-top: 1.5rem; }

    /* ===== TỐI ƯU HÓA BẢN IN (PRINT - CHUẨN VĂN BẢN) ===== */
    @media print {
        /* 1. Ẩn toàn bộ UI thừa thãi */
        .layout-menu, .layout-navbar, .navbar-detached, .nav-tabs, form, .btn, footer, .app-brand, .d-flex.flex-wrap.justify-content-between.align-items-center.mb-4.gap-3 {
            display: none !important;
        }
        
        /* 2. Đưa nội dung ra full màn hình */
        body, html, .layout-wrapper, .layout-page {
            background: #fff !important; 
            padding: 0 !important;
            margin: 0 !important;
        }
        .container-p-y { padding: 0 !important; }

        /* 3. Hiển thị Print Header (Quốc hiệu) */
        .print-header { display: block !important; text-align: center; margin-bottom: 30px; }
        .print-header h3 { font-size: 14pt; font-weight: bold; margin: 0; }
        .print-header h4 { font-size: 13pt; font-weight: normal; margin: 5px 0 15px 0; }
        .print-header .line { width: 150px; height: 1px; background: #000; margin: 0 auto 20px auto; }
        .print-header h1 { font-size: 18pt; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }

        /* 4. HIỂN THỊ TẤT CẢ TABS CÙNG LÚC */
        .tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            page-break-inside: auto;
            margin-bottom: 2rem !important;
        }

        /* 5. Phá vỡ Grid (Row/Col) -> Thành danh sách tuyến tính dọc (Văn bản) */
        .row { display: block !important; margin: 0 !important; }
        .col-6, .col-md-3, .col-lg-8, .col-lg-4, .col-lg-7, .col-lg-5 {
            width: 100% !important;
            max-width: 100% !important;
            display: block !important;
            margin-bottom: 15px !important;
            padding: 0 !important;
        }

        /* 6. Biến Card thành Text thường */
        .card { border: none !important; box-shadow: none !important; margin-bottom: 20px !important; }
        .card-header { background: transparent !important; border-bottom: 1px solid #000 !important; padding: 0 0 5px 0 !important; margin-bottom: 10px !important; }
        .card-header h5 { font-size: 12pt !important; font-weight: bold !important; color: #000 !important; text-transform: uppercase !important; }
        .card-body { padding: 0 !important; }

        /* 7. Định dạng lại Thẻ Số Liệu (Chỉ hiện text, bỏ icon/màu/shadow) */
        .stat-card { display: block !important; padding: 0 !important; border: none !important; margin-bottom: 5px !important; }
        .stat-card .d-flex { display: block !important; }
        .stat-card p { display: inline-block !important; font-weight: bold !important; font-size: 12pt !important; color: #000 !important; width: 200px; margin: 0 !important; text-transform: none !important; }
        .stat-card h4 { display: inline-block !important; font-size: 12pt !important; font-weight: normal !important; color: #000 !important; margin: 0 !important; }
        .stat-card small, .stat-card .stat-icon, .stat-card .mt-2 { display: none !important; }

        /* 8. Bảng đen trắng chuẩn Word */
        .table { width: 100% !important; border-collapse: collapse !important; border: 1px solid #000 !important; margin-bottom: 20px !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 6px !important; color: #000 !important; font-size: 11pt !important; }
        .table thead th { background: #fff !important; color: #000 !important; border-bottom: 2px solid #000 !important; }
        .rank-badge { border: none !important; background: transparent !important; color: #000 !important; }
        .progress { display: none !important; } /* Ẩn thanh chạy */

        canvas { max-width: 100% !important; height: auto !important; page-break-inside: avoid; margin-bottom: 20px; }
        @page { size: A4 portrait; margin: 2cm; }
        
        h1, h2, h3, h4, h5, h6, p, label, span, td, th, div { color: #000 !important; text-shadow: none !important; }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- HEADER CHỈ DÀNH CHO BẢN IN (VĂN BẢN) --}}
    <div class="print-header" style="display: none;">
        <h3>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</h3>
        <h4>Độc lập - Tự do - Hạnh phúc</h4>
        <div class="line"></div>
        <h1>BÁO CÁO KẾT QUẢ HOẠT ĐỘNG KINH DOANH</h1>
        <p>Kỳ báo cáo: Tháng {{ $month }} năm {{ $year }}</p>
        <p>Đơn vị: Nhà thuốc Dola Pharmacy</p>
    </div>

    {{-- ===== TIÊU ĐỀ & BỘ LỌC THÁNG (Web) ===== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">📊 Báo Cáo Tổng Hợp</h4>
            <p class="mb-0" style="color:rgba(255,255,255,0.7); font-size:0.85rem;">
                Kỳ báo cáo: <strong>Tháng {{ $month }}/{{ $year }}</strong>
                &nbsp;({{ $startDate->format('d/m/Y') }} – {{ $endDate->format('d/m/Y') }})
            </p>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="d-flex gap-2">
                <select name="month" class="form-select form-select-sm" style="width:130px;">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>Tháng {{ $m }}</option>
                    @endfor
                </select>
                <select name="year" class="form-select form-select-sm" style="width:100px;">
                    @for($y = now()->year; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bx bx-filter-alt me-1"></i> Lọc
                </button>
            </form>
            
            {{-- Nút Export Excel/CSV --}}
            <a href="{{ route('admin.reports.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-success btn-sm px-3" title="Xuất báo cáo chuẩn định dạng Excel/CSV">
                <i class="bx bx-download me-1"></i> Xuất CSV
            </a>
            <button onclick="window.print()" class="btn btn-outline-info btn-sm px-3">
                <i class="bx bx-printer me-1"></i> In
            </button>
        </div>
    </div>

    {{-- ===== TABS NAVIGATION ===== --}}
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="finance-tab" data-bs-toggle="tab" data-bs-target="#finance" type="button" role="tab" aria-controls="finance" aria-selected="true">
                💰 Tài Chính & Doanh Thu
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory" aria-selected="false">
                📦 Hàng Hóa & Kho
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="customer-tab" data-bs-toggle="tab" data-bs-target="#customer" type="button" role="tab" aria-controls="customer" aria-selected="false">
                👥 Khách Hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">
        
        {{-- =============================================== --}}
        {{-- TAB 1: TÀI CHÍNH & DOANH THU --}}
        {{-- =============================================== --}}
        <div class="tab-pane fade show active" id="finance" role="tabpanel" aria-labelledby="finance-tab">
            {{-- I. THẺ SỐ LIỆU TỔNG QUAN --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <p class="mb-1" style="font-size:0.75rem; color:rgba(255,255,255,0.65); text-transform:uppercase; letter-spacing:0.5px;">Doanh thu</p>
                                <h4 class="mb-0 fw-bold" style="color:#4ade80 !important; text-shadow: 0 0 12px rgba(74,222,128,0.5) !important;">{{ number_format($revenue,0,',','.') }}đ</h4>
                                <small class="{{ $revenueGrowth >= 0 ? 'growth-up' : 'growth-down' }}">
                                    <i class="bx {{ $revenueGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                    {{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% so tháng trước
                                </small>
                            </div>
                            <div class="stat-icon" style="background:rgba(74,222,128,0.15);">
                                <i class="bx bx-money" style="color:#4ade80;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <p class="mb-1" style="font-size:0.75rem; color:rgba(255,255,255,0.65); text-transform:uppercase; letter-spacing:0.5px;">Lợi nhuận (ước)</p>
                                <h4 class="mb-0 fw-bold" style="color:{{ $estimatedProfit >= 0 ? '#3ad4ff' : '#ff6b6b' }} !important; text-shadow: 0 0 12px rgba(58,212,255,0.5) !important;">
                                    {{ number_format($estimatedProfit,0,',','.') }}đ
                                </h4>
                                <small style="color:rgba(255,255,255,0.6); font-size:0.75rem;">Nhập kho: {{ number_format($importCost,0,',','.') }}đ</small>
                            </div>
                            <div class="stat-icon" style="background:rgba(58,212,255,0.15);">
                                <i class="bx bx-trending-up" style="color:#3ad4ff;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card h-100">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <p class="mb-1" style="font-size:0.75rem; color:rgba(255,255,255,0.65); text-transform:uppercase; letter-spacing:0.5px;">Đơn hàng</p>
                                <h4 class="mb-0 fw-bold">{{ $totalOrders }}</h4>
                                <small class="{{ $ordersGrowth >= 0 ? 'growth-up' : 'growth-down' }}">
                                    <i class="bx {{ $ordersGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                    {{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}% so tháng trước
                                </small>
                            </div>
                            <div class="stat-icon" style="background:rgba(251,191,36,0.15);">
                                <i class="bx bx-receipt" style="color:#fbbf24;"></i>
                            </div>
                        </div>
                        <div class="mt-2 d-flex gap-2 flex-wrap" style="font-size:0.75rem;">
                            <span style="color:#4ade80;">✓ {{ $deliveredOrders }} thành công</span>
                            <span style="color:#ff6b6b;">✗ {{ $cancelledOrders }} hủy</span>
                            <span style="color:#fbbf24;">⏳ {{ $pendingOrders }} chờ</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card mb-3 h-100">
                        <div class="card-header border-bottom-0 pb-2">
                            <h5 class="mb-0 fs-6">💳 PTTT</h5>
                        </div>
                        <div class="card-body pt-0 px-3">
                            @forelse($revenueByPayment as $pay)
                            @php
                                $label = match($pay->phuong_thuc_thanh_toan) {
                                    'cod'     => 'COD',
                                    'banking' => 'Chuyển khoản QR',
                                    'bank'    => 'Chuyển khoản',
                                    'momo'    => 'MoMo',
                                    default   => ucfirst($pay->phuong_thuc_thanh_toan)
                                };
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-1 pb-1" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                                <span style="font-size:0.8rem; font-weight:600;">{{ $label }}</span>
                                <span style="color:#3ad4ff; font-weight:700; font-size:0.8rem;">{{ number_format($pay->tong_tien,0,',','.') }}đ</span>
                            </div>
                            @empty
                            <p class="text-center py-1 mb-0" style="color:rgba(255,255,255,0.5); font-size: 0.8rem;">Không có dữ liệu</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Biểu đồ doanh thu theo ngày --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📈 Doanh Thu Theo Ngày - Tháng {{ $month }}/{{ $year }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 2: HÀNG HÓA & KHO --}}
        {{-- =============================================== --}}
        <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="mb-0">🏆 Top 10 Sản Phẩm Bán Chạy Tháng {{ $month }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table report-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">#</th>
                                            <th>Tên thuốc</th>
                                            <th class="text-center">SL bán</th>
                                            <th class="text-end pe-3">Doanh thu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topProducts as $i => $item)
                                        @php
                                            $maxQty = $topProducts->max('tong_so_luong') ?: 1;
                                            $pct = round(($item->tong_so_luong / $maxQty) * 100);
                                            $rankColor = $i === 0 ? '#fbbf24' : ($i === 1 ? '#94a3b8' : ($i === 2 ? '#cd7c2f' : 'rgba(255,255,255,0.3)'));
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <span class="rank-badge" style="background:{{ $rankColor }}20; color:{{ $rankColor }}; border:1px solid {{ $rankColor }}50;">{{ $i+1 }}</span>
                                            </td>
                                            <td>
                                                <div style="font-size:0.85rem; font-weight:600;">{{ $item->thuoc->ten_thuoc ?? 'N/A' }}</div>
                                                <div class="progress mt-1" style="height:4px;">
                                                    <div class="progress-bar" style="width:{{ $pct }}%; background:linear-gradient(90deg, #3ad4ff, #696cff);"></div>
                                                </div>
                                            </td>
                                            <td class="text-center"><strong>{{ number_format($item->tong_so_luong) }}</strong></td>
                                            <td class="text-end pe-3" style="color:#4ade80; font-weight:700;">{{ number_format($item->tong_doanh_thu,0,',','.') }}đ</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center py-4" style="color:rgba(255,255,255,0.5);">Không có dữ liệu trong tháng này</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    {{-- Biểu đồ trạng thái đơn --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">🥧 Trạng Thái Đơn Hàng</h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <canvas id="statusChart" style="max-height:180px;"></canvas>
                            <div class="mt-3 w-100" style="font-size:0.8rem;">
                                <div class="d-flex justify-content-between mb-1"><span>⏳ Chờ xác nhận</span><strong>{{ $orderStatusData[0] }}</strong></div>
                                <div class="d-flex justify-content-between mb-1"><span>🚚 Đang giao</span><strong>{{ $orderStatusData[1] }}</strong></div>
                                <div class="d-flex justify-content-between mb-1"><span>✅ Đã giao</span><strong>{{ $orderStatusData[2] }}</strong></div>
                                <div class="d-flex justify-content-between"><span>❌ Đã hủy</span><strong>{{ $orderStatusData[3] }}</strong></div>
                            </div>
                        </div>
                    </div>

                    {{-- Cảnh báo tồn kho --}}
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">⚠️ Tồn Kho Thấp (&lt;15)</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table report-table mb-0">
                                    <thead><tr><th class="ps-3">Tên thuốc</th><th class="text-center">Tồn</th></tr></thead>
                                    <tbody>
                                        @forelse($lowStockProducts as $p)
                                        <tr>
                                            <td class="ps-3" style="font-size:0.82rem;">{{ $p->ten_thuoc }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $p->so_luong_ton <= 5 ? 'bg-danger' : 'bg-warning' }}">
                                                    {{ $p->so_luong_ton }} {{ $p->don_vi_tinh }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="2" class="text-center py-2" style="color:rgba(255,255,255,0.5);">✅ Kho đang đầy đủ</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Lịch sử nhập --}}
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">📦 Nhập Kho Gần Đây</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table report-table mb-0">
                                    <thead><tr><th class="ps-3">Mã phiếu</th><th class="text-end pe-3">Tổng tiền</th></tr></thead>
                                    <tbody>
                                        @forelse($importHistory->take(4) as $phieu)
                                        <tr>
                                            <td class="ps-3" style="font-size:0.82rem; color:#3ad4ff; font-weight:700;">{{ $phieu->ma_phieu ?? '#'.$phieu->id }}</td>
                                            <td class="text-end pe-3" style="color:#4ade80; font-weight:700; font-size:0.82rem;">{{ number_format($phieu->tong_tien,0,',','.') }}đ</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="2" class="text-center py-2" style="color:rgba(255,255,255,0.5);">Không có phiếu nhập</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- TAB 3: KHÁCH HÀNG --}}
        {{-- =============================================== --}}
        <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👥 Khách Hàng Mới Tháng {{ $month }}</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.75rem;">Quản lý KH</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table report-table mb-0">
                            <thead><tr><th class="ps-3">Họ tên</th><th>SĐT / Email</th><th class="text-end pe-3">Ngày đăng ký</th></tr></thead>
                            <tbody>
                                @forelse($newUsersThisMonth as $u)
                                <tr>
                                    <td class="ps-3" style="font-size:0.82rem; font-weight:600;">{{ $u->ten }}</td>
                                    <td style="font-size:0.78rem; color:rgba(255,255,255,0.7);">{{ $u->sdt ?? $u->email ?? 'N/A' }}</td>
                                    <td class="text-end pe-3" style="font-size:0.78rem; color:rgba(255,255,255,0.6);">{{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4" style="color:rgba(255,255,255,0.5);">Chưa có khách hàng mới trong tháng</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartDefaults = {
        color: 'rgba(255,255,255,0.85)',
        grid: { color: 'rgba(255,255,255,0.06)', drawBorder: false },
        tick: { color: 'rgba(255,255,255,0.6)', font: { size: 11 } }
    };

    // Khởi tạo biểu đồ đường Doanh thu
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($daysLabel),
                datasets: [{
                    label: 'Doanh thu (đ)',
                    data: @json($dailyRevenue),
                    backgroundColor: 'rgba(58,212,255,0.2)',
                    borderColor: '#3ad4ff',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#0b1120',
                    pointBorderColor: '#3ad4ff',
                    pointHoverBackgroundColor: '#3ad4ff',
                    pointHoverBorderColor: '#fff',
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + 'đ'
                        }
                    }
                },
                scales: {
                    x: { grid: chartDefaults.grid, ticks: chartDefaults.tick },
                    y: {
                        grid: chartDefaults.grid,
                        ticks: {
                            ...chartDefaults.tick,
                            callback: (v) => (v >= 1e6 ? (v/1e6).toFixed(1)+'M' : v.toLocaleString('vi-VN'))
                        }
                    }
                }
            }
        });
    }

    // Khởi tạo biểu đồ tròn Trạng thái đơn (Chỉ vẽ khi click tab Inventory nếu muốn tối ưu, nhưng ở đây vẽ luôn)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Chờ xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'],
                datasets: [{
                    data: @json($orderStatusData),
                    backgroundColor: ['#fbbf24','#3ad4ff','#4ade80','#ff6b6b'],
                    borderWidth: 2,
                    borderColor: 'rgba(0,0,0,0.2)',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (ctx) => ' ' + ctx.label + ': ' + ctx.raw } }
                }
            }
        });
    }
});
</script>
@endpush
