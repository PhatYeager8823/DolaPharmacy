@extends('layouts.admin')
@section('title', 'Dashboard - Tổng quan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- 1. CÁC THẺ THỐNG KÊ (CARDS) --}}
    <div class="row mb-4">
        {{-- Doanh thu --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted">DOANH THU</span>
                        {{-- Icon Tiền --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cash-stack text-primary" viewBox="0 0 16 16">
                        <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-0">{{ number_format($totalRevenue) }} đ</h4>
                    <small class="text-success fw-bold"><i class='bx bx-up-arrow-alt'></i> Tổng thực thu</small>
                </div>
            </div>
        </div>

        {{-- Đơn hàng --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted">ĐƠN HÀNG</span>
                        {{-- Icon Giỏ hàng --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-cart-check-fill text-warning" viewBox="0 0 16 16">
                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-1.646-7.646-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                    <small class="text-muted">Tổng số đơn phát sinh</small>
                </div>
            </div>
        </div>

        {{-- Khách hàng --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted">KHÁCH HÀNG</span>
                        {{-- Icon Người --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people-fill text-info" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $totalUsers }}</h4>
                    <small class="text-muted">Thành viên đăng ký</small>
                </div>
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-muted">SẢN PHẨM</span>
                        {{-- Icon Hộp hàng --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box-seam-fill text-success" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003 6.97 2.789ZM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461L10.404 2Z"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $totalProducts }}</h4>
                    <small class="text-muted">Đang kinh doanh</small>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. BIỂU ĐỒ (CHARTS) --}}
    <div class="row">
        {{-- Biểu đồ Cột: Doanh thu 12 tháng --}}
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2 fw-bold">Doanh thu năm nay</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Biểu đồ Tròn: Tỷ lệ đơn hàng --}}
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2 fw-bold">Tình trạng đơn hàng</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart" style="max-height: 300px;"></canvas>
                    <div class="mt-3 text-center small text-muted">
                        Tỷ lệ các trạng thái đơn hàng hiện tại
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BIỂU ĐỒ LƯỢN SÓNG (WAVY CHART): SỐ ĐƠN HÀNG --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2 fw-bold text-info">Số lượng đơn hàng hoàn thành</h5>
                </div>
                <div class="card-body">
                    <canvas id="wavyOrdersChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. CẢNH BÁO TỒN KHO HẾT HÀNG --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card h-100" style="border-left: 4px solid #f59e0b !important;">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <h5 class="card-title m-0 me-2 fw-bold text-warning">
                        <i class="bx bx-error-circle me-1"></i> Cảnh báo Thuốc Sắp Hết Hàng
                    </h5>
                    <a href="{{ route('thuoc.index') }}" class="btn btn-sm btn-outline-warning">Nhập Kho Ngay</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless mb-0">
                            <thead class="border-bottom text-muted">
                                <tr>
                                    <th># MÃ SẢN PHẨM</th>
                                    <th>TÊN THUỐC</th>
                                    <th>GIÁ BÁN</th>
                                    <th class="text-end">SỐ LƯỢNG TỒN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $thuoc)
                                    <tr>
                                        <td><strong>{{ $thuoc->ma_san_pham }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($thuoc->hinh_anh)
                                                    <img src="{{ asset('storage/' . $thuoc->hinh_anh) }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bx bx-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <span class="text-truncate fw-bold" style="max-width: 250px;">{{ $thuoc->ten_thuoc }}</span>
                                            </div>
                                        </td>
                                        <td>{{ number_format($thuoc->gia_ban) }} đ</td>
                                        <td class="text-end">
                                            @if($thuoc->so_luong_ton == 0)
                                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">🛑 Hết Hàng (0)</span>
                                            @else
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">⚠️ Sắp hết ({{ $thuoc->so_luong_ton }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-success fw-bold">
                                            <i class="bx bx-check-shield fs-4 mb-2 d-block"></i>
                                            Tuyệt vời! Tất cả thuốc trong kho đều dồi dào số lượng.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. SCRIPT VẼ BIỂU ĐỒ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // --- CẤU HÌNH MÀU SẮC CHUNG CHO TẤT CẢ BIỂU ĐỒ (DÀNH CHO NỀN GLASSMORPHISM TỐI) ---
    Chart.defaults.color = '#cbd5e1'; 
    Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.05)';

    // --- A. BIỂU ĐỒ DOANH THU (Bar Chart) ---
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($monthlyRevenue),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                        }
                    }
                }
            }
        }
    });

    // --- B. BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG (Doughnut Chart) ---
    const ctxWavy = document.getElementById('wavyOrdersChart').getContext('2d');
    
    let gradientWavy = ctxWavy.createLinearGradient(0, 0, 0, 400);
    gradientWavy.addColorStop(0, 'rgba(0, 242, 254, 0.4)'); // Cyan cyber
    gradientWavy.addColorStop(1, 'rgba(0, 242, 254, 0.0)');  // Trong suốt ở đáy

    new Chart(ctxWavy, {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Số đơn hoàn thành',
                data: @json($monthlyOrders),
                backgroundColor: gradientWavy,
                borderColor: '#00f2fe',
                borderWidth: 3,
                tension: 0.4, // CỰC KỲ QUAN TRỌNG: 0.4 tạo độ lượn sóng mềm mại
                fill: true,   // Đổ màu vùng gradient mượt mà bên dưới
                pointBackgroundColor: '#0b1120',
                pointBorderColor: '#00f2fe',
                pointHoverBackgroundColor: '#00f2fe',
                pointHoverBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // --- C. BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG (Doughnut Chart) ---
    const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'],
            datasets: [{
                data: @json($orderStats),
                backgroundColor: [
                    '#6c757d', // Xám (Chờ)
                    '#0dcaf0', // Xanh dương nhạt (Đang giao)
                    '#198754', // Xanh lá (Đã giao)
                    '#dc3545'  // Đỏ (Hủy)
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection
