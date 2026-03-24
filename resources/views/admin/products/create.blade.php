@extends('layouts.admin')
@section('title', 'Thêm thuốc mới')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sản phẩm /</span> Thêm mới</h4>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- CỘT TRÁI: THÔNG TIN CHÍNH --}}
            <div class="col-md-8">
                <div class="card mb-4">
                    <h5 class="card-header">Thông tin chung</h5>
                    <div class="card-body">

                        {{-- Tên thuốc --}}
                        <div class="mb-3">
                            <label class="form-label">Tên thuốc <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_thuoc" value="{{ old('ten_thuoc') }}" placeholder="Ví dụ: Panadol Extra" required />
                            @error('ten_thuoc') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            {{-- Mã SP --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mã sản phẩm (SKU) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ma_san_pham" value="{{ old('ma_san_pham') }}" required />
                                @error('ma_san_pham') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            {{-- Số đăng ký --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số đăng ký</label>
                                <input type="text" class="form-control" name="so_dang_ky" value="{{ old('so_dang_ky') }}" placeholder="VD: VD-1234-20" />
                            </div>
                        </div>

                        <div class="row">
                            {{-- Giá bán --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán (VND) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="gia_ban" value="{{ old('gia_ban') }}" required />
                            </div>
                            {{-- Giá cũ (để làm giảm giá) --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá niêm yết (Giá cũ)</label>
                                <input type="number" class="form-control" name="gia_cu" value="{{ old('gia_cu') }}" placeholder="Để trống nếu không giảm giá" />
                            </div>
                        </div>

                        <div class="row">
                            {{-- Đơn vị tính --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Đơn vị tính</label>
                                <input type="text" class="form-control" name="don_vi_tinh" value="{{ old('don_vi_tinh') }}" placeholder="Hộp, Chai, Vỉ..." />
                            </div>
                            {{-- Quy cách --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quy cách đóng gói</label>
                                <input type="text" class="form-control" name="quy_cach" value="{{ old('quy_cach') }}" placeholder="Hộp 10 vỉ x 10 viên" />
                            </div>
                        </div>

                        {{-- Mô tả ngắn --}}
                        <div class="mb-3">
                            <label class="form-label">Mô tả ngắn (SEO)</label>
                            <textarea class="form-control" name="mo_ta_ngan" rows="3">{{ old('mo_ta_ngan') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- CARD: THÔNG TIN CHI TIẾT (Dùng CKEditor) --}}
                <div class="card mb-4">
                    <h5 class="card-header">Thông tin chi tiết</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Công dụng</label>
                            <textarea name="cong_dung" id="editor1">{{ old('cong_dung') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cách dùng & Liều dùng</label>
                            <textarea name="cach_dung" id="editor2">{{ old('cach_dung') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thành phần</label>
                            <textarea name="thanh_phan" class="form-control" rows="3">{{ old('thanh_phan') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tác dụng phụ (Lưu ý)</label>
                            <textarea name="tac_dung_phu" class="form-control" rows="2">{{ old('tac_dung_phu') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: PHÂN LOẠI & ẢNH --}}
            <div class="col-md-4">

                {{-- TRẠNG THÁI --}}
                <div class="card mb-4">
                    <h5 class="card-header">Cấu hình hiển thị</h5>
                    <div class="card-body">

                        {{-- 1. Trạng thái chung --}}
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label fw-bold text-success">Đang bán (Hiển thị)</label>
                        </div>

                        <hr class="my-3">

                        {{-- 2. Loại thuốc --}}
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="ke_don" value="1">
                            <label class="form-check-label text-danger fw-bold">Thuốc kê đơn (Cần dược sĩ)</label>
                        </div>

                        <hr class="my-3">

                        {{-- 3. Vị trí hiển thị trang chủ --}}
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1">
                            <label class="form-check-label text-primary">Sản phẩm nổi bật</label>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_new" value="1">
                            <label class="form-check-label text-info">Sản phẩm mới</label>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_exclusive" value="1">
                            <label class="form-check-label text-warning">Ưu đãi độc quyền</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_suggested" value="1">
                            <label class="form-check-label text-secondary">Gợi ý hôm nay</label>
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
                                    <option value="{{ $cat->id }}">{{ $cat->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select class="form-select" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nhà cung cấp --}}
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-select" name="nha_cung_cap_id">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->ten }}</option>
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
                            <label class="form-label">Ảnh đại diện</label>
                            <input class="form-control" type="file" name="hinh_anh" onchange="previewImage(this)">
                        </div>
                        <div class="text-center border rounded p-2" style="min-height: 150px; background: #f8f9fa;">
                            <img id="imgPreview" src="https://via.placeholder.com/150x150?text=No+Image"
                                 class="img-fluid" style="max-height: 200px; display: none;">
                            <span id="placeholderText" class="text-muted mt-5 d-block">Chưa chọn ảnh</span>
                        </div>
                    </div>
                </div>

                {{-- KHO HÀNG
                <div class="card mb-4">
                    <h5 class="card-header">Kho hàng</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Số lượng tồn</label>
                            <input type="number" class="form-control" name="so_luong_ton" value="0">
                        </div>
                    </div>
                </div> --}}

                {{-- NÚT HÀNH ĐỘNG --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Lưu sản phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                </div>

            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // 1. Kích hoạt CKEditor 5 (Cho ô Công dụng)
    ClassicEditor
        .create(document.querySelector('#editor1'), {
            // Tùy chỉnh thanh công cụ cho gọn (nếu muốn)
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });

    // 2. Kích hoạt CKEditor 5 (Cho ô Cách dùng)
    ClassicEditor
        .create(document.querySelector('#editor2'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });

    // 3. Script xem trước ảnh (Giữ nguyên code cũ của bạn)
    function previewImage(input) {
        const preview = document.getElementById('imgPreview');
        const placeholder = document.getElementById('placeholderText');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'inline-block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Lấy 2 phần tử cần thao tác
        const checkboxKeDon = document.querySelector('input[name="ke_don"]');
        const inputGiaCu = document.querySelector('input[name="gia_cu"]');

        // Hàm xử lý logic
        function toggleGiaCu() {
            if (checkboxKeDon.checked) {
                // Nếu là thuốc KÊ ĐƠN
                inputGiaCu.value = '';        // Xóa trắng giá cũ
                inputGiaCu.disabled = true;   // Khóa ô nhập không cho gõ
                inputGiaCu.placeholder = 'Thuốc kê đơn không được giảm giá';
            } else {
                // Nếu là thuốc THƯỜNG
                inputGiaCu.disabled = false;  // Mở khóa cho nhập
                inputGiaCu.placeholder = 'Nhập giá gốc (nếu có giảm giá)';
            }
        }

        // 2. Chạy hàm ngay khi load trang (để check trường hợp đang Edit)
        if(checkboxKeDon && inputGiaCu) {
            toggleGiaCu();

            // 3. Lắng nghe sự kiện khi click vào checkbox
            checkboxKeDon.addEventListener('change', toggleGiaCu);
        }
    });
</script>

{{-- Thêm chút CSS để khung soạn thảo cao hơn cho dễ viết --}}
<style>
    .ck-editor__editable_inline {
        min-height: 150px;
    }
</style>
@endpush
