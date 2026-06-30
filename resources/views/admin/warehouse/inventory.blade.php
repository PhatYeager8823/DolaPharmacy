@extends('layouts.admin')
@section('title', 'Báo cáo tồn kho')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kho hàng /</span> Báo cáo tồn kho</h4>

        {{-- 1. THỐNG KÊ TỔNG QUAN --}}
    <div class="row mb-4">
        {{-- Card 1: Tổng sản phẩm tồn --}}
        <div class="col-md-6">
            {{-- SỬA: Bỏ bg-primary, dùng card trắng bình thường --}}
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            {{-- SỬA: Bỏ vòng tròn bao quanh, chỉ để Icon --}}
                            {{-- Icon Hộp (Box) - Màu xanh dương (text-primary) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-box-seam text-primary" viewBox="0 0 16 16">
                              <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                            </svg>
                        </div>
                        <div>
                            {{-- SỬA: Màu chữ tiêu đề xám nhẹ --}}
                            <h5 class="card-title text-muted mb-0">Tổng sản phẩm tồn</h5>
                            {{-- SỬA: Màu số liệu Xanh dương đậm --}}
                            <h3 class="card-text text-primary fw-bold mb-0">{{ number_format($totalStock) }} <span class="fs-6 text-body">đơn vị</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Tổng giá trị tồn --}}
        <div class="col-md-6">
            {{-- SỬA: Bỏ bg-success, dùng card trắng --}}
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar me-3">
                            {{-- SỬA: Bỏ vòng tròn, chỉ để Icon --}}
                            {{-- Icon Tiền (Cash) - Màu xanh lá (text-success) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" fill="currentColor" class="bi bi-cash-stack text-success" viewBox="0 0 16 16">
                                <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1H1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                                <path d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V5zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2H3z"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Tổng giá trị tồn</h5>
                            <h3 class="card-text text-success fw-bold mb-0">{{ number_format($totalValue) }} <span class="fs-6 text-body">đ</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. BỘ LỌC & DANH SÁCH --}}
    <div class="card">
        <div class="card-header border-bottom">
            {{-- Form lọc: Đã chỉnh route chuẩn theo yêu cầu của bạn --}}
            <form action="{{ route('admin.warehouse.inventory') }}" method="GET">
                <div class="row g-3">

                    {{-- Tìm kiếm --}}
                    <div class="col-md-4">
                        <div class="input-group position-relative" id="inventory-search-group">
                            <span class="input-group-text">
                                {{-- SVG Kính lúp --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </span>
                            <input type="text" class="form-control" name="keyword" id="inventory-search-input"
                                   value="{{ request('keyword') }}"
                                   placeholder="Tìm theo tên, mã SP..."
                                   autocomplete="off">
                            {{-- Khung gợi ý Cyber-Glass --}}
                            <div id="inventory-suggestions" class="search-results-container scrollbar-hidden" style="top: 100%; width: 100%;"></div>
                        </div>
                    </div>

                    {{-- Lọc trạng thái --}}
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết (< 10)</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>

                    {{-- Nút Lọc --}}
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                             Lọc
                        </button>
                    </div>

                    {{-- Nút Xóa lọc --}}
                    @if(request()->hasAny(['keyword', 'status']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.warehouse.inventory') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center">
                            {{-- SVG Reset --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise me-1" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                              <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                            Xóa lọc
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- Bảng dữ liệu --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã SP</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Giá trị tồn</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><strong>{{ $product->ma_san_pham }}</strong></td>
                        <td>
                            <img src="{{ $product->hinh_anh ? asset('images/images_san_pham/'.$product->hinh_anh) : 'https://via.placeholder.com/50' }}"
                                 alt="Ảnh" class="rounded" width="40" height="40" style="object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $product->ten_thuoc }}</div>
                            <small class="text-muted">{{ $product->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</small>
                        </td>

                        {{-- Tô màu số lượng tồn --}}
                        <td>
                            @if($product->so_luong_ton == 0)
                                <span class="text-danger fw-bold">{{ $product->so_luong_ton }}</span>
                            @elseif($product->so_luong_ton < 10)
                                <span class="text-warning fw-bold">{{ $product->so_luong_ton }}</span>
                            @else
                                <span class="text-success fw-bold">{{ $product->so_luong_ton }}</span>
                            @endif
                        </td>

                        <td>
                            @if($product->so_luong_ton == 0)
                                <span class="badge bg-label-danger">Hết hàng</span>
                            @elseif($product->so_luong_ton < 10)
                                <span class="badge bg-label-warning">Sắp hết</span>
                            @else
                                <span class="badge bg-label-success">Còn hàng</span>
                            @endif
                        </td>

                        {{-- Tính giá trị tồn của dòng này --}}
                        <td class="fw-bold">
                            {{ number_format($product->so_luong_ton * $product->gia_nhap) }} đ
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Không tìm thấy dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $products->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const $input = $('#inventory-search-input');
    const $suggestions = $('#inventory-suggestions');
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
                url: "{{ route('admin.warehouse.suggest') }}",
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
        if (!$(e.target).closest('#inventory-search-group').length) {
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
    #inventory-suggestions {
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
    #inventory-suggestions .search-result-item:hover {
        background: rgba(0, 242, 254, 0.12) !important;
        transition: all 0.2s ease;
    }
</style>
@endpush
