@extends('layouts.admin')
@section('title', 'Sửa Slider Banner')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Giao diện /</span> Cập nhật Banner</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Chỉnh sửa thông tin</h5>

            <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Hàng 1: Tiêu đề & Link --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Tiêu đề</label>
                        <input type="text" name="tieu_de" class="form-control" value="{{ $slider->tieu_de }}" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Link liên kết</label>
                        <input type="url" name="link" class="form-control" value="{{ $slider->link }}" />
                    </div>
                </div>

                {{-- Hàng 2: Hình ảnh & Thứ tự --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Hình ảnh Banner</label>
                        {{-- Hiển thị ảnh cũ --}}
                        <div class="mb-2">
                            <img src="{{ asset('images/sliders/' . $slider->hinh_anh) }}" class="rounded border" width="200" alt="Ảnh hiện tại">
                        </div>
                        <input type="file" name="hinh_anh" class="form-control" accept="image/*" />
                        <div class="form-text">Chỉ chọn ảnh mới nếu muốn thay đổi.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Thứ tự hiển thị</label>
                        <input type="number" name="thu_tu" class="form-control" value="{{ $slider->thu_tu }}" min="0" />
                    </div>
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Mô tả ngắn</label>
                    <textarea name="mo_ta" class="form-control" rows="2">{{ $slider->mo_ta }}</textarea>
                </div>

                {{-- Trạng thái --}}
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $slider->is_active ? 'checked' : '' }} />
                        <label class="form-check-label" for="activeSwitch">Hiển thị</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                    <a href="{{ $backUrl }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
