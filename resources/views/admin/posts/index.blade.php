@extends('layouts.admin')
@section('title', 'Quản lý Bài viết')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Nội dung /</span> Tin tức & Y khoa</h4>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Viết bài mới
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Danh sách bài viết</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th width="100">Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($posts as $post)
                    <tr>
                        <td><strong>#{{ $post->id }}</strong></td>
                        <td>
                            <img src="{{ asset('images/news/' . $post->hinh_anh) }}" alt="Ảnh" class="rounded" width="60" height="40" style="object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-primary text-truncate" style="max-width: 300px;" title="{{ $post->tieu_de }}">{{ $post->tieu_de }}</div>
                            <small class="text-muted">{{ Str::limit($post->mo_ta_ngan, 50) }}</small>
                        </td>
                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($post->is_active)
                                <span class="badge bg-label-success">Hiển thị</span>
                            @else
                                <span class="badge bg-label-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.posts.edit', $post->id) }}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger delete-confirm-btn" data-message="Bạn có chắc muốn xóa bài viết này?">
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
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
