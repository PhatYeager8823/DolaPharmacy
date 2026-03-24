@extends('layouts.admin')
@section('title', 'Sửa bài viết')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Bài viết /</span> Cập nhật</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold text-muted">Tiêu đề bài viết <span class="text-danger">*</span></label>
                                <input type="text" name="tieu_de" class="form-control" value="{{ $post->tieu_de }}" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold text-muted">Mô tả ngắn <span class="text-danger">*</span></label>
                                <textarea name="mo_ta_ngan" class="form-control" rows="3" required>{{ $post->mo_ta_ngan }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-uppercase small fw-bold text-muted">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea name="noi_dung" class="form-control" rows="15" required>{{ $post->noi_dung }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label text-uppercase small fw-bold text-muted">Hình ảnh đại diện</label>
                                        @if($post->hinh_anh)
                                            <div class="mb-2">
                                                <img src="{{ asset('images/news/' . $post->hinh_anh) }}" class="img-fluid rounded border">
                                            </div>
                                        @endif
                                        <input type="file" name="hinh_anh" class="form-control" accept="image/*" />
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $post->is_active ? 'checked' : '' }} />
                                            <label class="form-check-label" for="activeSwitch">Hiển thị</label>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
