@extends('layouts.admin')
@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý /</span> Danh sách thuốc</h4>


    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tất cả sản phẩm</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Thêm thuốc mới
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            {{-- THANH CÔNG CỤ TÌM KIẾM & LỌC --}}
            <div class="card-body border-bottom">
                <form action="{{ route('admin.products.index') }}" method="GET">
                    <div class="row g-3">

                        {{-- 1. Tìm từ khóa --}}
                        <div class="col-md-4">
                            <label class="form-label">Tìm kiếm</label>
                            <div class="input-group position-relative" id="product-search-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" name="keyword" id="product-search-input"
                                    value="{{ request('keyword') }}"
                                    placeholder="Tên thuốc, Mã SP..."
                                    autocomplete="off">
                                {{-- Khung gợi ý Cyber-Glass --}}
                                <div id="product-suggestions" class="search-results-container scrollbar-hidden" style="top: 100%; width: 100%;"></div>
                            </div>
                        </div>

                        {{-- 2. Lọc theo Danh mục --}}
                        <div class="col-md-3">
                            <label class="form-label">Danh mục</label>
                            <select name="danh_muc_id" class="form-select">
                                <option value="">-- Tất cả danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('danh_muc_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Lọc Thuốc Kê đơn / Thường --}}
                        <div class="col-md-2">
                            <label class="form-label">Loại thuốc</label>
                            <select name="ke_don" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('ke_don') === '1' ? 'selected' : '' }}>💊 Thuốc Kê Đơn</option>
                                <option value="0" {{ request('ke_don') === '0' ? 'selected' : '' }}>🌿 Thuốc Thường</option>
                            </select>
                        </div>

                        {{-- 4. Lọc Trạng thái --}}
                        <div class="col-md-2">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="1" {{ request('trang_thai') === '1' ? 'selected' : '' }}>Đang bán</option>
                                <option value="0" {{ request('trang_thai') === '0' ? 'selected' : '' }}>Đang ẩn</option>
                            </select>
                        </div>

                        {{-- 5. Nút bấm --}}
                        <div class="col-md-1 d-flex align-items-end">
                            <div class="d-grid w-100">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </div>
                        </div>

                        {{-- Nút Reset (Nếu đang lọc mới hiện) --}}
                        @if(request()->hasAny(['keyword', 'danh_muc_id', 'ke_don', 'trang_thai']))
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bx bx-refresh"></i> Xóa bộ lọc
                            </a>
                        </div>
                        @endif

                    </div>
                </form>
            </div>
            {{-- KẾT THÚC THANH CÔNG CỤ --}}
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên thuốc</th>
                        <th>Giá bán</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>
                            <img src="{{ $p->hinh_anh ? asset('images/images_san_pham/'.$p->hinh_anh) : 'https://via.placeholder.com/50' }}"
                                 width="50" height="50" class="rounded" style="object-fit: contain; border: 1px solid #eee;">
                        </td>
                        <td>
                            <strong>{{ $p->ten_thuoc }}</strong><br>
                            <small class="text-muted">{{ $p->ma_san_pham }}</small>
                        </td>
                        <td>
                            <span class="text-primary fw-bold">{{ number_format($p->gia_ban) }}đ</span>
                            @if($p->gia_cu > $p->gia_ban)
                                <br><small class="text-decoration-line-through text-muted">{{ number_format($p->gia_cu) }}đ</small>
                            @endif
                        </td>
                        <td>{{ $p->danhMuc->ten_danh_muc ?? '---' }}</td>
                        <td>
                            @if($p->is_active)
                                <span class="badge bg-label-success">Đang bán</span>
                            @else
                                <span class="badge bg-label-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.products.edit', $p->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger delete-confirm-btn" data-message="Bạn có chắc chắn muốn xóa thuốc này không?">
                                            <i class="bx bx-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const $input = $('#product-search-input');
    const $suggestions = $('#product-suggestions');
    let debounceTimer;

    $input.on('input', function() {
        clearTimeout(debounceTimer);
        const keyword = $(this).val().trim();

        if (keyword.length < 2) {
            $suggestions.hide();
            return;
        }

        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.products.suggest') }}",
                data: { keyword: keyword },
                success: function(products) {
                    if (products.length > 0) {
                        let html = '';
                        products.forEach(p => {
                            const imgUrl = p.hinh_anh ? `{{ asset('images/images_san_pham') }}/${p.hinh_anh}` : 'https://via.placeholder.com/40';
                            html += `
                                <div class="search-result-item py-2 px-3 border-bottom cursor-pointer" style="border-color: rgba(255,255,255,0.05) !important;" data-name="${p.ten_thuoc}">
                                    <div class="d-flex align-items-center">
                                        <img src="${imgUrl}" class="rounded me-2" width="35" height="35" style="object-fit: contain; background: rgba(255,255,255,0.1);">
                                        <div class="flex-grow-1 overflow-hidden text-start">
                                            <div class="fw-bold text-truncate" style="font-size: 0.9rem; color: #fff;">${p.ten_thuoc}</div>
                                            <small class="text-info d-block text-truncate" style="font-size: 0.75rem;">${p.ma_san_pham}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $suggestions.html(html).fadeIn(200);
                    } else {
                        $suggestions.hide();
                    }
                }
            });
        }, 300);
    });

    // Khi click vào một kết quả
    $(document).on('click', '.search-result-item', function() {
        const name = $(this).data('name');
        $input.val(name);
        $suggestions.hide();
        $input.closest('form').submit();
    });

    // Đóng khi click ngoài
    $(document).on('mousedown', function(e) {
        if (!$(e.target).closest('#product-search-group').length) {
            $suggestions.hide();
        }
    });

    // Hiển thị lại khi focus
    $input.on('focus', function() {
        if ($(this).val().trim().length >= 2 && $suggestions.children().length > 0) {
            $suggestions.fadeIn(200);
        }
    });
});
</script>

<style>
    #product-suggestions {
        position: absolute;
        z-index: 1000;
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(25px) saturate(180%);
        border: 1px solid rgba(0, 242, 254, 0.4) !important;
        border-radius: 12px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.8);
        max-height: 400px;
        overflow-y: auto;
        display: none;
        margin-top: 5px;
    }
    .cursor-pointer { cursor: pointer; }
    
    /* Hover effect đồng bộ Cyber Theme */
    .search-result-item:hover {
        background: rgba(0, 242, 254, 0.12) !important;
        transition: all 0.2s ease;
    }
</style>
@endpush
