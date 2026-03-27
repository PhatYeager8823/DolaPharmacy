@extends('layouts.admin')
@section('title', 'Sửa Mã giảm giá')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Mã giảm giá /</span> Cập nhật</h4>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Chỉnh sửa voucher</h5>

        {{-- QUAN TRỌNG: Phải có enctype="multipart/form-data" --}}
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="redirect_url" value="{{ $backUrl }}">

            {{-- KHU VỰC CHỌN ẢNH --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Hình ảnh Banner</label>
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    {{-- Hiển thị ảnh cũ nếu có, không thì hiện ảnh no-image --}}
                    <img src="{{ $coupon->image ? asset('images/coupons/' . $coupon->image) : asset('images/no-image.png') }}"
                         alt="coupon-img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" style="object-fit: cover; border: 1px solid #d9dee3;" />

                    <div class="button-wrapper">
                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Thay đổi ảnh</span>
                            <i class="bx bx-upload d-block d-sm-none"></i>
                            <input type="file" id="upload" class="account-file-input" hidden name="image" accept="image/png, image/jpeg" />
                        </label>
                        <p class="text-muted mb-0 small">Để trống nếu không muốn đổi ảnh.</p>
                    </div>
                </div>
            </div>

            {{-- CÁC TRƯỜNG CŨ --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Mã Code <span class="text-danger">*</span></label>
                <input type="text" name="code" class="form-control" value="{{ $coupon->code }}" style="text-transform: uppercase;" required />
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Loại giảm giá</label>
                    <select name="type" class="form-select">
                        <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Giảm tiền mặt (VNĐ)</option>
                        <option value="percent" {{ $coupon->type == 'percent' ? 'selected' : '' }}>Giảm phần trăm (%)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="value" class="form-control" value="{{ $coupon->value }}" required />
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Hạn sử dụng</label>
                <input type="datetime-local" name="expiry_date" class="form-control"
                       value="{{ $coupon->expiry_date ? \Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d\TH:i') : '' }}" />
            </div>

            <div class="mb-4">
                <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="statusSwitch" {{ $coupon->is_active ? 'checked' : '' }} />
                    <label class="form-check-label" for="statusSwitch">Kích hoạt</label>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function (e) {
        const imgInput = document.getElementById('upload');
        const imgPreview = document.getElementById('uploadedAvatar');
        if (imgInput) {
            imgInput.onchange = () => {
                if (imgInput.files[0]) {
                    imgPreview.src = window.URL.createObjectURL(imgInput.files[0]);
                }
            };
        }
    });
</script>
@endsection
