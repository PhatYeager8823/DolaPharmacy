@extends('layouts.admin')
@section('title', 'Quản lý Mã giảm giá')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Bán hàng /</span> Mã giảm giá
        </h4>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Tạo mã mới
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Danh sách Voucher</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Code</th>
                        <th>Loại giảm</th>
                        <th>Giá trị</th>
                        <th>Hạn sử dụng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>
                            <strong class="text-primary text-uppercase">{{ $coupon->code }}</strong>
                        </td>
                        <td>
                            @if($coupon->type == 'fixed')
                                <span class="badge bg-label-info">Tiền mặt</span>
                            @else
                                <span class="badge bg-label-warning">Phần trăm</span>
                            @endif
                        </td>
                        <td class="fw-bold">
                            @if($coupon->type == 'fixed')
                                -{{ number_format($coupon->value) }}đ
                            @else
                                -{{ $coupon->value }}%
                            @endif
                        </td>
                        <td>
                            @if($coupon->expiry_date)
                                {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y') }}
                                @if(\Carbon\Carbon::now()->gt($coupon->expiry_date))
                                    <small class="text-danger d-block">(Hết hạn)</small>
                                @endif
                            @else
                                <span class="text-muted">Vô thời hạn</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->is_active)
                                <span class="badge bg-label-success">Kích hoạt</span>
                            @else
                                <span class="badge bg-label-secondary">Khóa</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Xóa mã này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $coupons->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
