@extends('layouts.admin')
@section('title', 'Cập nhật sản phẩm')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Sản phẩm /</span> Cập nhật
    </h4>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Quan trọng: Để Laravel biết đây là lệnh Sửa --}}

        <input type="hidden" name="redirect_url" value="{{ old('redirect_url', $backUrl) }}">

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <h5 class="card-header">Thông tin chung</h5>
                    <div class="card-body">

                        {{-- Tên thuốc --}}
                        <div class="mb-3">
                            <label class="form-label">Tên thuốc <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_thuoc" value="{{ old('ten_thuoc', $product->ten_thuoc) }}" required />
                            @error('ten_thuoc') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            {{-- Mã SP --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã sản phẩm (SKU)</label>
                                <input type="text" class="form-control" name="ma_san_pham" value="{{ old('ma_san_pham', $product->ma_san_pham) }}" required />
                            </div>
                            {{-- Số đăng ký --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số đăng ký</label>
                                <input type="text" class="form-control" name="so_dang_ky" value="{{ old('so_dang_ky', $product->so_dang_ky) }}" />
                            </div>
                        </div>

                        <div class="row">
                            {{-- Giá bán --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán (VND)</label>
                                <input type="number" class="form-control" name="gia_ban" value="{{ old('gia_ban', $product->gia_ban) }}" required />
                            </div>
                            {{-- Giá cũ --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá cũ (Niêm yết)</label>
                                <input type="number" class="form-control" name="gia_cu" value="{{ old('gia_cu', $product->gia_cu) }}" />
                            </div>
                        </div>

                        <div class="row">
                            {{-- Đơn vị tính --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Đơn vị tính</label>
                                <input type="text" class="form-control" name="don_vi_tinh" value="{{ old('don_vi_tinh', $product->don_vi_tinh) }}" />
                            </div>
                            {{-- Quy cách --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quy cách đóng gói</label>
                                <input type="text" class="form-control" name="quy_cach" value="{{ old('quy_cach', $product->quy_cach) }}" />
                            </div>
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-3">
                            <label class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control" name="mo_ta_ngan" rows="3">{{ old('mo_ta_ngan', $product->mo_ta_ngan) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- CARD: THÔNG TIN CHI TIẾT --}}
                <div class="card mb-4">
                    <h5 class="card-header">Thông tin chi tiết</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Công dụng</label>
                            <textarea name="cong_dung" id="editor1">{{ old('cong_dung', $product->cong_dung) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cách dùng & Liều dùng</label>
                            <textarea name="cach_dung" id="editor2">{{ old('cach_dung', $product->cach_dung) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thành phần</label>
                            <textarea name="thanh_phan" class="form-control" rows="3">{{ old('thanh_phan', $product->thanh_phan) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tác dụng phụ</label>
                            <textarea name="tac_dung_phu" class="form-control" rows="2">{{ old('tac_dung_phu', $product->tac_dung_phu) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: PHÂN LOẠI & CẤU HÌNH --}}
            <div class="col-md-4">

                {{-- CẤU HÌNH HIỂN THỊ --}}
                <div class="card mb-4">
                    <h5 class="card-header">Cấu hình hiển thị</h5>
                    <div class="card-body">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success">Đang bán (Hiển thị)</label>
                        </div>

                        <hr class="my-3">

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="ke_don" value="1" {{ $product->ke_don ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-bold">Thuốc kê đơn</label>
                        </div>

                        <hr class="my-3">

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label text-primary">Sản phẩm nổi bật</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_new" value="1" {{ $product->is_new ? 'checked' : '' }}>
                            <label class="form-check-label text-info">Sản phẩm mới</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_exclusive" value="1" {{ $product->is_exclusive ? 'checked' : '' }}>
                            <label class="form-check-label text-warning">Ưu đãi độc quyền</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_suggested" value="1" {{ $product->is_suggested ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary">Gợi ý hôm nay</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_hot_deal" value="1" {{ $product->is_hot_deal ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-bold">Khuyến mãi hấp dẫn (Hot Deal)</label>
                        </div>
                    </div>
                </div>

                {{-- PHÂN LOẠI --}}
                <div class="card mb-4">
                    <h5 class="card-header">Phân loại</h5>
                    <div class="card-body">
                        {{-- Danh mục --}}
                        <div class="mb-3">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select" name="danh_muc_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->danh_muc_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select class="form-select" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nhà cung cấp --}}
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-select" name="nha_cung_cap_id">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ $product->nha_cung_cap_id == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- HÌNH ẢNH --}}
                <div class="card mb-4">
                    <h5 class="card-header">Hình ảnh</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Thay ảnh mới (nếu muốn)</label>
                            <input class="form-control" type="file" name="hinh_anh" onchange="previewImage(this)">
                        </div>
                        <div class="text-center border rounded p-2" style="min-height: 150px; background: #f8f9fa;">
                            {{-- Hiện ảnh cũ nếu có --}}
                            <img id="imgPreview"
                                 src="{{ $product->hinh_anh ? asset('images/images_san_pham/' . $product->hinh_anh) : 'https://via.placeholder.com/150x150?text=No+Image' }}"
                                 class="img-fluid" style="max-height: 200px;">
                        </div>
                    </div>
                </div>

                {{-- KHO HÀNG --}}
                <div class="card mb-4">
                    <h5 class="card-header">Kho hàng</h5>
                    <div class="card-body">
                        {{-- Trong file edit.blade.php --}}
                        <div class="mb-3">
                            <label class="form-label">Số lượng tồn kho hiện tại</label>
                            {{-- Hiển thị dữ liệu thật từ DB ($product->so_luong_ton) --}}
                            <input type="text" class="form-control bg-light" value="{{ $product->so_luong_ton }}" disabled readonly>
                            <div class="form-text text-muted">Để thay đổi số lượng, vui lòng vào Quản lý kho -> Nhập hàng.</div>
                        </div>
                    </div>
                </div>

                {{-- NÚT HÀNH ĐỘNG --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning btn-lg">Cập nhật sản phẩm</button>

                    {{-- Sửa dòng này: Dùng biến $backUrl thay vì route cố định --}}
                    <a href="{{ $backUrl }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Kích hoạt CKEditor 5 (Giống trang Create)
    ClassicEditor.create(document.querySelector('#editor1')).catch(error => console.error(error));
    ClassicEditor.create(document.querySelector('#editor2')).catch(error => console.error(error));

    // Script Preview ảnh
    function previewImage(input) {
        const preview = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<style>
    .ck-editor__editable_inline { min-height: 150px; }
</style>
@endpush
