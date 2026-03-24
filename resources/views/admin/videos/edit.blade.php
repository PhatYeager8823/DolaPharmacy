@extends('layouts.admin')
@section('title', 'Sửa Video')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Video /</span> Cập nhật</h4>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Chỉnh sửa video</h5>

        <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Tiêu đề --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Tiêu đề Video <span class="text-danger">*</span></label>
                <input type="text" name="tieu_de" class="form-control" value="{{ $video->tieu_de }}" required />
            </div>

            {{-- Link Youtube --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Link Youtube <span class="text-danger">*</span></label>

                {{-- SỬA LẠI DÒNG VALUE DƯỚI ĐÂY: --}}
                <input
                    type="url"
                    name="youtube_id"
                    class="form-control"
                    value="https://www.youtube.com/watch?v={{ $video->youtube_id }}"
                    required
                />
            </div>

            {{-- Hình ảnh (Thumbnail) --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Ảnh bìa (Thumbnail)</label>
                @if($video->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('images/videos/' . $video->thumbnail) }}" class="rounded border" width="100">
                    </div>
                @endif
                <input type="file" name="thumbnail" class="form-control" accept="image/*" />

                {{-- THÊM ĐOẠN NÀY VÀO DƯỚI INPUT FILE --}}
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="refetch_youtube" id="refetchYoutube">
                    <label class="form-check-label text-muted" for="refetchYoutube">
                        <small>Tải lại ảnh thumbnail từ link Youtube trên (Sẽ thay thế ảnh hiện tại)</small>
                    </label>
                </div>
            </div>

            {{-- Trạng thái --}}
            <div class="mb-4">
                <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" {{ $video->is_active ? 'checked' : '' }} />
                    <label class="form-check-label" for="activeSwitch">Hiển thị</label>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                <a href="{{ route('admin.videos.index') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
