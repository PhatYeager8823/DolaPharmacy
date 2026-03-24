@extends('layouts.admin')
@section('title', 'Chi tiết Phiếu Nhập')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Kho /</span> Phiếu {{ $phieuNhap->ma_phieu }}
        </h4>
        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> Quay lại
        </a>
    </div>

    <div class="row">
        {{-- Thông tin chung --}}
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="fw-bold text-muted">Nhà cung cấp:</label>
                            <div class="fs-5">{{ $phieuNhap->nhaCungCap->ten_nha_cung_cap ?? '---' }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="fw-bold text-muted">Người nhập:</label>
                            <div>{{ $phieuNhap->nguoiNhap->ten ?? 'Unknown' }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="fw-bold text-muted">Ngày nhập:</label>
                            <div>{{ $phieuNhap->created_at->format('d/m/Y H:i:s') }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="fw-bold text-muted">Ghi chú:</label>
                            <div class="text-muted fst-italic">{{ $phieuNhap->ghi_chu ?? 'Không có' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header border-bottom">Chi tiết hàng hóa</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên thuốc</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Giá nhập</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phieuNhap->chiTiet as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $item->thuoc->ten_thuoc ?? 'Sản phẩm đã xóa' }}</strong>
                                    <br>
                                    <small class="text-muted">Mã: {{ $item->thuoc->ma_thuoc ?? '---' }}</small>
                                </td>
                                <td class="text-center">{{ number_format($item->so_luong) }}</td>
                                <td class="text-end">{{ number_format($item->gia_nhap) }} đ</td>
                                <td class="text-end fw-bold">{{ number_format($item->thanh_tien) }} đ</td>
                            </tr>
                            @endforeach

                            {{-- Dòng tổng cộng --}}
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-bold fs-5">TỔNG CỘNG:</td>
                                <td class="text-end fw-bold fs-5 text-primary">
                                    {{ number_format($phieuNhap->tong_tien) }} đ
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
