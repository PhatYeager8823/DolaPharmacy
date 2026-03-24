@extends('layouts.admin')
@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Quản lý Đơn hàng</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h5 class="card-header">Danh sách đơn hàng</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <strong>#{{ $order->ma_don_hang }}</strong>
                            <div class="mt-1">
                                @if(isset($order->phuong_thuc_thanh_toan))
                                    @if($order->phuong_thuc_thanh_toan == 'banking')
                                        <span class="badge bg-warning text-dark border border-warning shadow-sm"><i class='bx bx-credit-card me-1'></i>Chuyển khoản</span>
                                    @elseif($order->phuong_thuc_thanh_toan == 'cod')
                                        <span class="badge bg-success shadow-sm"><i class='bx bx-money me-1'></i>Tiền mặt (COD)</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td>
                            {{-- 1. Tên người nhận (Lưu cứng trong đơn hàng) --}}
                            <span class="fw-bold">{{ $order->ten_nguoi_nhan }}</span>
                            <br>
                            <small class="text-muted">{{ $order->sdt_nguoi_nhan }}</small>

                            {{-- 2. Tên tài khoản (Lấy từ bảng User qua quan hệ nguoiDung) --}}
                            <div class="mt-1 border-top pt-1" style="font-size: 0.85em;">
                                @if($order->nguoiDung)
                                    {{-- Nếu có tài khoản --}}
                                    <span class="text-primary">
                                        <i class="bx bx-user-circle"></i> TK: {{ $order->nguoiDung->ten }}
                                    </span>
                                @else
                                    {{-- Nếu không có tài khoản (đã bị xóa hoặc khách vãng lai) --}}
                                    <span class="text-secondary">
                                        <i class="bx bx-user-x"></i> Khách vãng lai
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-primary fw-bold">{{ number_format($order->tong_tien) }} đ</td>
                        <td>
                            @php
                                $badgeClass = match($order->trang_thai) {
                                    'cho_xac_nhan' => 'bg-label-secondary',
                                    'dang_giao' => 'bg-label-info',
                                    'da_giao' => 'bg-label-success',
                                    'da_huy' => 'bg-label-danger',
                                    default => 'bg-label-primary'
                                };
                                $statusText = match($order->trang_thai) {
                                    'cho_xac_nhan' => 'Chờ xác nhận',
                                    'dang_giao' => 'Đang giao',
                                    'da_giao' => 'Đã giao',
                                    'da_huy' => 'Đã hủy',
                                    default => $order->trang_thai
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-show me-1"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
