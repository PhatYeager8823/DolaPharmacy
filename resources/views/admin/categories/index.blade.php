@extends('layouts.admin')
@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý /</span> Danh mục sản phẩm</h4>

    {{-- Thông báo thành công/lỗi --}}
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
            <h5 class="mb-0">Danh sách danh mục</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Thêm mới
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug (Đường dẫn)</th>
                        <th>Danh mục cha</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($categories as $cat)
                        <tr>
                            <td><strong>{{ $cat->id }}</strong></td>
                            <td><span class="fw-bold text-primary">{{ $cat->ten_danh_muc }}</span></td>
                            <td>{{ $cat->slug }}</td>
                            <td>
                                @if($cat->parent)
                                    <span class="badge bg-label-info">{{ $cat->parent->ten_danh_muc }}</span>
                                @else
                                    <span class="badge bg-label-secondary">Gốc (Cấp 1)</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        {{-- SỬA --}}
                                        <a class="dropdown-item" href="{{ route('admin.categories.edit', $cat->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Sửa
                                        </a>

                                        {{-- XÓA (Dùng Form) --}}
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger delete-confirm-btn" data-message="Bạn có chắc muốn xóa danh mục này?">
                                                <i class="bx bx-trash me-1"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">Chưa có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang (Pagination) --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
