@extends('layouts.admin')
@section('title', 'Quản lý Thương hiệu')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Đối tác /</span> Thương hiệu</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách thương hiệu</h5>
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Thêm mới
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu</th>
                        <th>Xuất xứ</th>
                        <th>Slug</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($brands as $brand)
                    <tr>
                        <td><strong>#{{ $brand->id }}</strong></td>
                        <td><span class="fw-bold text-info">{{ $brand->ten }}</span></td>
                        <td>{{ $brand->xuat_xu ?? '---' }}</td>
                        <td><code>{{ $brand->slug }}</code></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.brands.edit', $brand->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger delete-confirm-btn" data-message="Bạn có chắc muốn xóa thương hiệu này?">
                                            <i class="bx bx-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Chưa có thương hiệu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $brands->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
