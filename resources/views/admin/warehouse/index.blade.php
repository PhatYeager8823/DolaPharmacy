@extends('layouts.admin')
@section('title', 'Quản lý Kho - Lịch sử nhập hàng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Kho /</span> Lịch sử nhập hàng</h4>
        <a href="{{ route('admin.warehouse.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Nhập hàng mới
        </a>
    </div>

    {{-- Hiển thị thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Danh sách phiếu nhập</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã Phiếu</th>
                        <th>Nhà cung cấp</th>
                        <th>Người nhập</th>
                        <th>Ngày nhập</th>
                        <th>Tổng tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($phieuNhaps as $phieu)
                    <tr>
                        <td>
                            <a href="{{ route('admin.warehouse.show', $phieu->id) }}">
                                <strong>{{ $phieu->ma_phieu }}</strong>
                            </a>
                        </td>
                        <td>{{ $phieu->nhaCungCap->ten_nha_cung_cap ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xs me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ substr($phieu->nguoiNhap->ten ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <span>{{ $phieu->nguoiNhap->ten ?? 'Admin' }}</span>
                            </div>
                        </td>
                        <td>{{ $phieu->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-primary fw-bold">{{ number_format($phieu->tong_tien) }} đ</td>
                        <td>
                            <a href="{{ route('admin.warehouse.show', $phieu->id) }}" class="btn btn-sm btn-outline-info">
                                <i class="bx bx-show me-1"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Chưa có phiếu nhập hàng nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $phieuNhaps->links() }}
        </div>
    </div>
</div>
@endsection
