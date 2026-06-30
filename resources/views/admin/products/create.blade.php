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
                            <input type="text" id="ten_thuoc" class="form-control" name="ten_thuoc" value="{{ old('ten_thuoc') }}" placeholder="Ví dụ: Panadol Extra" required />
                            @error('ten_thuoc') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        {{-- URL Slug (Tự động tạo) --}}
                        <div class="mb-3">
                            <label class="form-label">Đường dẫn SEO (Slug) <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="input-group-text rounded-3">/san-pham/</span>
                                <input type="text" id="slug" class="form-control rounded-3" name="slug" value="{{ old('slug') }}" placeholder="panadol-extra" required />
                            </div>
                            @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
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

                        {{-- BỘ NHẬP & ĐẾM KÝ TỰ MÔ TẢ NGẮN (SEO) --}}
                        <div class="mb-3">
                            <label class="form-label d-flex justify-content-between">
                                <span>Mô tả ngắn (SEO)</span>
                                <small id="seo_counter" class="text-muted">0 / 160 ký tự</small>
                            </label>
                            <textarea class="form-control" id="mo_ta_ngan" name="mo_ta_ngan" rows="3">{{ old('mo_ta_ngan') }}</textarea>
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
                        <div class="form-check form-switch py-2 mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label fw-bold text-success ms-2">Đang bán (Hiển thị)</label>
                        </div>

                        <hr class="my-2 opacity-50">

                        {{-- 2. Loại thuốc --}}
                        <div class="form-check form-switch py-2 mb-2">
                            <input class="form-check-input" type="checkbox" name="ke_don" value="1">
                            <label class="form-check-label text-danger fw-bold ms-2">Thuốc kê đơn (Cần dược sĩ)</label>
                        </div>

                        <hr class="my-2 opacity-50">

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

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_suggested" value="1">
                            <label class="form-check-label text-secondary">Gợi ý hôm nay</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_hot_deal" value="1">
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
                            <select class="form-select select2" name="danh_muc_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('danh_muc_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->full_hierarchy }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="mb-3">
                            <label class="form-label">Thương hiệu</label>
                            <select class="form-select select2" name="brand_id">
                                <option value="">-- Chọn thương hiệu --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->ten }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nhà cung cấp --}}
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-select select2" name="nha_cung_cap_id">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->ten }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- HÌNH ẢNH (GIAO DIỆN KÉO THẢ MỚI) --}}
                <div class="card mb-4">
                    <h5 class="card-header">Hình ảnh đại diện</h5>
                    <div class="card-body">
                        <div class="upload-zone border-dashed rounded-3 text-center position-relative overflow-hidden" 
                             style="border: 2px dashed rgba(255,255,255,0.3); background: rgba(255,255,255,0.02); cursor: pointer; min-height: 200px; transition: all 0.3s ease;"
                             onclick="document.getElementById('upload_image').click()">
                            
                            <input id="upload_image" type="file" name="hinh_anh" onchange="previewImage(this)" class="d-none" accept="image/*">
                            
                            <div class="p-4 d-flex flex-column align-items-center justify-content-center h-100" id="upload_placeholder" style="min-height: 200px;">
                                <div class="bg-primary rounded-circle p-3 mb-3" style="background-color: rgba(56, 189, 248, 0.1) !important;">
                                    <i class="bx bx-cloud-upload fs-1 text-info"></i>
                                </div>
                                <h6 class="mb-1 fw-bold">Click để tải ảnh lên</h6>
                                <small class="text-muted">JPG, PNG, WEBP. Tối đa 2MB.</small>
                            </div>
                            
                            <img id="imgPreview" src="#" class="w-100 h-100 object-fit-cover rounded-3" 
                                 style="display: none; position: absolute; top: 0; left: 0; z-index: 1;">
                            
                            {{-- Lớp phủ tối khi hover ảnh đã up --}}
                            <div id="imgOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center rounded-3" 
                                 style="opacity: 0; z-index: 2; transition: 0.3s; display: none !important;">
                                <span class="text-white fw-bold"><i class="bx bx-edit fs-4 me-1"></i> Đổi ảnh khác</span>
                            </div>
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
        .then(editor => {
            // Tắt gạch chân đỏ (Spellcheck)
            editor.editing.view.change(writer => {
                writer.setAttribute('spellcheck', 'false', editor.editing.view.document.getRoot());
            });
        })
        .catch(error => {
            console.error(error);
        });

    // 2. Kích hoạt CKEditor 5 (Cho ô Cách dùng)
    ClassicEditor
        .create(document.querySelector('#editor2'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
        })
        .then(editor => {
            // Tắt gạch chân đỏ (Spellcheck)
            editor.editing.view.change(writer => {
                writer.setAttribute('spellcheck', 'false', editor.editing.view.document.getRoot());
            });
        })
        .catch(error => {
            console.error(error);
        });

    // 3. Script xem trước ảnh (Kéo thả)
    function previewImage(input) {
        const preview = document.getElementById('imgPreview');
        const placeholder = document.getElementById('upload_placeholder');
        const overlay = document.getElementById('imgOverlay');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                if(placeholder) {
                    placeholder.classList.remove('d-flex');
                    placeholder.style.display = 'none';
                }
                if(overlay) overlay.style.setProperty('display', 'flex', 'important');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            if(placeholder) {
                placeholder.classList.add('d-flex');
                placeholder.style.display = 'flex';
            }
            if(overlay) overlay.style.setProperty('display', 'none', 'important');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Phục vụ Checkbox Kê Đơn - Giá Cũ
        const checkboxKeDon = document.querySelector('input[name="ke_don"]');
        const inputGiaCu = document.querySelector('input[name="gia_cu"]');

        function toggleGiaCu() {
            if (checkboxKeDon.checked) {
                inputGiaCu.value = '';        
                inputGiaCu.disabled = true;   
                inputGiaCu.placeholder = 'Thuốc kê đơn không được giảm giá';
            } else {
                inputGiaCu.disabled = false;  
                inputGiaCu.placeholder = 'Nhập giá gốc (nếu có giảm giá)';
            }
        }

        if(checkboxKeDon && inputGiaCu) {
            toggleGiaCu();
            checkboxKeDon.addEventListener('change', toggleGiaCu);
        }

        // 2. Tự động sinh Slug từ Tên Thuốc
        const inputTenThuoc = document.getElementById('ten_thuoc');
        const inputSlug = document.getElementById('slug');

        function ChangeToSlug(text) {
            let slug = text.toLowerCase();
            // Đổi ký tự có dấu thành không dấu
            slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
            slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
            slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
            slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
            slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
            slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
            slug = slug.replace(/đ/gi, 'd');
            // Xóa các ký tự đặc biệt
            slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
            // Đổi khoảng trắng thành ký tự gạch ngang
            slug = slug.replace(/ /gi, "-");
            // Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
            slug = slug.replace(/\-\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-\-/gi, '-');
            slug = slug.replace(/\-\-\-/gi, '-');
            slug = slug.replace(/\-\-/gi, '-');
            // Xóa các ký tự gạch ngang ở đầu và cuối
            slug = '@' + slug + '@';
            slug = slug.replace(/\@\-|\-\@|\@/gi, '');
            return slug;
        }

        if(inputTenThuoc && inputSlug) {
            inputTenThuoc.addEventListener('keyup', function() {
                inputSlug.value = ChangeToSlug(this.value);
            });
        }

        // 3. Bộ đếm ký tự SEO
        const inputMoTa = document.getElementById('mo_ta_ngan');
        const seoCounter = document.getElementById('seo_counter');

        function updateSeoCounter() {
            if(!inputMoTa || !seoCounter) return;
            const currentLength = inputMoTa.value.length;
            seoCounter.textContent = currentLength + ' / 160 ký tự';
            
            if (currentLength > 160) {
                seoCounter.classList.remove('text-muted', 'text-success');
                seoCounter.classList.add('text-danger', 'fw-bold');
            } else if (currentLength > 120) {
                seoCounter.classList.remove('text-muted', 'text-danger');
                seoCounter.classList.add('text-success', 'fw-bold');
            } else {
                seoCounter.classList.remove('text-danger', 'text-success', 'fw-bold');
                seoCounter.classList.add('text-muted');
            }
        }

        if(inputMoTa) {
            updateSeoCounter(); // init
            inputMoTa.addEventListener('input', updateSeoCounter);
        }
    });
</script>

{{-- Thêm chút CSS để khung soạn thảo cao hơn cho dễ viết --}}
<style>
    .ck-editor__editable_inline {
        min-height: 150px;
    }
    
    .upload-zone:hover {
        background-color: rgba(56, 189, 248, 0.05) !important;
        border-color: rgba(56, 189, 248, 0.5) !important;
    }
    .upload-zone:hover #imgOverlay {
        opacity: 1 !important;
    }
</style>
@endpush
