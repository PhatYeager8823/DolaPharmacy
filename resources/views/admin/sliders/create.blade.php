@extends('layouts.admin')
@section('title', 'Thêm Slider Banner')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Giao diện /</span> Thêm Banner</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Thông tin Banner</h5>

            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Hàng 1: Tiêu đề & Link --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tiêu đề (Không bắt buộc)</label>
                        <input type="text" name="tieu_de" class="form-control" placeholder="VD: Khuyến mãi hè..." />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Link liên kết</label>
                        <input type="url" name="link" class="form-control" placeholder="https://..." />
                    </div>
                </div>

                {{-- Hàng 2: CHỌN ẢNH (QUAN TRỌNG) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase text-primary">Hình ảnh Banner <span class="text-danger">*</span></label>

                    {{-- Tabs chuyển đổi --}}
                    <ul class="nav nav-tabs" id="imageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button" role="tab">
                                <i class="bx bx-upload me-1"></i> Upload ảnh mới
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="library-tab" data-bs-toggle="tab" data-bs-target="#library" type="button" role="tab">
                                <i class="bx bx-images me-1"></i> Chọn từ thư viện ({{ count($files) }} ảnh)
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content border border-top-0 p-3 rounded-bottom" id="imageTabsContent">

                        {{-- TAB 1: UPLOAD MỚI --}}
                        <div class="tab-pane fade show active" id="upload" role="tabpanel">
                            <input type="file" name="hinh_anh" class="form-control" accept="image/*" />
                            <div class="form-text mt-2">Chọn ảnh từ máy tính của bạn.</div>
                        </div>

                        {{-- TAB 2: CHỌN TỪ THƯ VIỆN --}}
                        <div class="tab-pane fade" id="library" role="tabpanel">
                            @if(count($files) > 0)
                                <div class="row g-3" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($files as $file)
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <label class="image-select-item position-relative d-block cursor-pointer">
                                                <input type="radio" name="chon_hinh_anh_cu" value="{{ $file }}" class="d-none peer">

                                                {{-- Khung ảnh --}}
                                                <div class="border rounded p-1 item-box transition-all">
                                                    <img src="{{ asset('images/sliders/' . $file) }}" class="w-100 object-cover rounded" style="height: 80px; object-fit: cover;">
                                                    <div class="small text-truncate mt-1 text-center text-muted" style="font-size: 10px;">{{ $file }}</div>
                                                </div>

                                                {{-- Dấu tích xanh khi chọn --}}
                                                <div class="check-icon position-absolute top-0 end-0 m-1 text-success d-none">
                                                    <i class="bx bxs-check-circle fs-4 bg-white rounded-circle"></i>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3 text-muted">Chưa có ảnh nào trong thư viện.</div>
                            @endif
                        </div>
                    </div>
                    @error('hinh_anh') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Hàng 3: Thứ tự & Trạng thái --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="thu_tu" class="form-control" value="0" min="0" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Trạng thái</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch" checked />
                            <label class="form-check-label" for="activeSwitch">Hiển thị ngay</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary me-2">Lưu lại</button>
                    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSS & JS TÙY CHỈNH CHO PHẦN CHỌN ẢNH --}}
<style>
    .cursor-pointer { cursor: pointer; }
    /* Khi radio được check thì đổi màu border khung ảnh */
    input[type="radio"]:checked + .item-box {
        border-color: #696cff !important; /* Màu xanh Sneat */
        border-width: 2px !important;
        background-color: #f0f2ff;
    }
    /* Hiện dấu tích khi check */
    input[type="radio"]:checked ~ .check-icon {
        display: block !important;
    }
</style>

<script>
    // Script nhỏ để khi chọn ảnh thư viện thì xóa value của file upload (để tránh gửi cả 2)
    document.querySelectorAll('input[name="chon_hinh_anh_cu"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelector('input[name="hinh_anh"]').value = ''; // Reset file input
        });
    });

    // Ngược lại, khi chọn file upload thì bỏ chọn radio
    document.querySelector('input[name="hinh_anh"]').addEventListener('change', function() {
        document.querySelectorAll('input[name="chon_hinh_anh_cu"]').forEach(radio => {
            radio.checked = false;
        });
    });
</script>
@endsection
