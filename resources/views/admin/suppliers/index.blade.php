@extends('layouts.admin')
@section('title', 'Quản lý Nhà cung cấp')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Tiêu đề và Nút thêm mới nằm cùng 1 hàng giống trang Thương hiệu --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Đối tác /</span> Nhà cung cấp
        </h4>
        <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Thêm mới
        </a>
    </div>

    {{-- Bảng danh sách (Dùng cấu trúc Card của Template) --}}
    <div class="card">
        <h5 class="card-header">Danh sách Nhà cung cấp</h5>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên nhà cung cấp</th>
                        <th>Liên hệ</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($suppliers as $ncc)
                    <tr>
                        <td><strong>#{{ $ncc->id }}</strong></td>

                        {{-- Tên màu xanh đậm giống trang Thương hiệu --}}
                        <td>
                            <strong class="text-primary">{{ $ncc->ten }}</strong>
                        </td>

                        {{-- Thông tin liên hệ --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $ncc->sdt ?? '---' }}</span>
                                <small class="text-muted">{{ $ncc->email }}</small>
                            </div>
                        </td>

                        {{-- Địa chỉ (Cắt ngắn nếu dài) --}}
                        <td title="{{ $ncc->dia_chi }}">
                            {{ \Illuminate\Support\Str::limit($ncc->dia_chi, 30) }}
                        </td>

                        {{-- Trạng thái (Dùng Badge chuẩn của theme) --}}
                        <td>
                            @if($ncc->trang_thai)
                                <span class="badge bg-label-success me-1">Hoạt động</span>
                            @else
                                <span class="badge bg-label-secondary me-1">Ngừng</span>
                            @endif
                        </td>

                        {{-- Nút hành động dạng dấu 3 chấm dọc --}}
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.suppliers.edit', $ncc->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Sửa
                                    </a>

                                    <form action="{{ route('admin.suppliers.destroy', $ncc->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa nhà cung cấp này?');">
                                        @csrf
                                        @method('DELETE')
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

        {{-- Phân trang (Nếu có) --}}
        @if($suppliers->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
