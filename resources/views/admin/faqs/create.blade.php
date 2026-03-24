@extends('layouts.admin')
@section('title', 'Thêm FAQ')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">FAQ /</span> Thêm mới</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Nội dung câu hỏi</h5>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf

                {{-- Câu hỏi --}}
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Câu hỏi <span class="text-danger">*</span></label>
                    <input type="text" name="cau_hoi" class="form-control" placeholder="VD: Phí vận chuyển được tính như thế nào?" required />
                </div>

                {{-- Câu trả lời --}}
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Câu trả lời <span class="text-danger">*</span></label>
                    <textarea name="tra_loi" class="form-control" rows="4" placeholder="Nhập nội dung giải đáp..." required></textarea>
                </div>

                {{-- Trạng thái --}}
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked />
                        <label class="form-check-label" for="activeSwitch">Hiển thị ngay</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Lưu lại</button>
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
