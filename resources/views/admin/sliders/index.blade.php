@extends('layouts.admin')
@section('title', 'Quản lý Banner Slider')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Giao diện /</span> Slider</h4>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Thêm Banner
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Danh sách Banner trang chủ</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề / Link</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach($sliders as $slider)
                    <tr>
                        <td>
                            <img src="{{ asset('images/sliders/' . $slider->hinh_anh) }}" width="150" class="rounded border">
                        </td>
                        <td>
                            <strong>{{ $slider->tieu_de ?? '---' }}</strong><br>
                            <small class="text-muted">{{ Str::limit($slider->link, 30) }}</small>
                        </td>
                        <td>{{ $slider->thu_tu }}</td>
                        <td>
                            @if($slider->is_active)
                                <span class="badge bg-label-success">Hiện</span>
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
                                    <a class="dropdown-item" href="{{ route('admin.sliders.edit', $slider->id) }}"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
                                    <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Xóa banner này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-1"></i> Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
