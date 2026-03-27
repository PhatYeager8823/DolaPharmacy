@extends('layouts.admin')
@section('title', 'Quản lý Video')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Nội dung /</span> Video</h4>
        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Thêm mới
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Danh sách Video</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Link Video</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($videos as $vid)
                    <tr>
                        <td><strong>#{{ $vid->id }}</strong></td>
                        <td>
                            <div class="fw-bold text-primary">{{ $vid->tieu_de }}</div>
                            @if($vid->hinh_anh)
                                <img src="{{ asset('images/videos/' . $vid->thumbnail) }}" width="60" class="mt-1 rounded">
                            @endif
                        </td>
                        <td>
                            <a href="{{ $vid->youtube_id }}" target="_blank" class="text-muted">
                                <i class="bx bxl-youtube text-danger me-1"></i> Xem link
                            </a>
                        </td>
                        <td>
                            @if($vid->is_active)
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
                                    <a class="dropdown-item" href="{{ route('admin.videos.edit', $vid->id) }}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                                    <form action="{{ route('admin.videos.destroy', $vid->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger delete-confirm-btn" data-message="Bạn có chắc muốn xóa video này?">
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
            {{ $videos->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
