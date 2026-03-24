@extends('layouts.admin')
@section('title', 'Viết bài mới')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Bài viết /</span> Thêm mới</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label text-uppercase small fw-bold text-muted">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" name="tieu_de" class="form-control" required />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase small fw-bold text-muted">Mô tả ngắn <span class="text-danger">*</span></label>
                                    <textarea name="mo_ta_ngan" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase small fw-bold text-muted">Nội dung <span class="text-danger">*</span></label>
                                    <textarea name="noi_dung" class="form-control" rows="10" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-uppercase small fw-bold text-muted">Hình ảnh</label>
                                    <input type="file" name="hinh_anh" class="form-control" accept="image/*" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked />
                                        <label class="form-check-label" for="activeSwitch">Hiển thị</label>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">Đăng bài</button>
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Hủy</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
