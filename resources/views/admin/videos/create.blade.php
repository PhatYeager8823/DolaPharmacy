@extends('layouts.admin')
@section('title', 'Thêm Video')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Video /</span> Thêm mới</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Thông tin video</h5>
            <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Tiêu đề Video <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Link Youtube <span class="text-danger">*</span></label>
                    <input type="url" name="youtube_id" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required />
                </div>
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Ảnh bìa</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*" />
                    {{-- Thêm dòng chú thích này --}}
                    <small class="text-muted fst-italic">* Để trống để tự động lấy ảnh từ Youtube</small>
                </div>
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked />
                        <label class="form-check-label" for="activeSwitch">Hiển thị ngay</label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Lưu lại</button>
                    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
