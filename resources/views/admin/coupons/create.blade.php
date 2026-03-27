@extends('layouts.admin')
@section('title', 'Tạo Mã giảm giá')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mã giảm giá /</span> Thêm mới</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Thông tin khuyến mãi</h5>

            {{-- QUAN TRỌNG: Phải có enctype="multipart/form-data" mới gửi được ảnh --}}
            <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- KHU VỰC CHỌN ẢNH --}}
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Hình ảnh Banner</label>
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        {{-- Ảnh xem trước mặc định --}}
                        <img src="{{ asset('images/no-image.png') }}" alt="preview" class="d-block rounded" height="100" width="100" id="uploadedAvatar" style="object-fit: cover; border: 1px solid #d9dee3;" />

                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                <span class="d-none d-sm-block">Tải ảnh lên</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                {{-- Input chọn file --}}
                                <input type="file" id="upload" class="account-file-input" hidden name="image" accept="image/png, image/jpeg" />
                            </label>

                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Đặt lại</span>
                            </button>
                            <p class="text-muted mb-0 small">Chấp nhận JPG, PNG.</p>
                        </div>
                    </div>
                </div>

                {{-- CÁC TRƯỜNG CŨ GIỮ NGUYÊN --}}
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Mã Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" placeholder="VD: BANMOI" style="text-transform: uppercase;" required />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Loại giảm giá</label>
                        <select name="type" class="form-select">
                            <option value="fixed">Giảm tiền mặt (VNĐ)</option>
                            <option value="percent">Giảm phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Giá trị giảm <span class="text-danger">*</span></label>
                        <input type="number" name="value" class="form-control" placeholder="VD: 50000" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Hạn sử dụng</label>
                    <input type="datetime-local" name="expiry_date" class="form-control" />
                </div>

                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" checked />
                        <label class="form-check-label" for="statusSwitch">Kích hoạt ngay</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Lưu lại</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT XEM TRƯỚC ẢNH --}}
<script>
    document.addEventListener('DOMContentLoaded', function (e) {
        const imgInput = document.getElementById('upload');
        const imgPreview = document.getElementById('uploadedAvatar');
        const resetButton = document.querySelector('.account-image-reset');

        if (imgInput) {
            imgInput.onchange = () => {
                if (imgInput.files[0]) {
                    imgPreview.src = window.URL.createObjectURL(imgInput.files[0]);
                }
            };
        }
        if (resetButton) {
            resetButton.onclick = () => {
                imgInput.value = '';
                imgPreview.src = "{{ asset('images/no-image.png') }}";
            };
        }
    });
</script>
@endsection
