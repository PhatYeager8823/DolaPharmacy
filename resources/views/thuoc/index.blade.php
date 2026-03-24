@extends('layouts.app')

@section('title', isset($danhMuc) ? $danhMuc->ten_danh_muc : 'Tất cả sản phẩm')

@section('content')

    {{-- Breadcrumb --}}
    <div class="bg-light py-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 14px;">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('thuoc.index') }}" class="text-decoration-none">Sản phẩm</a>
                    </li>
                    @if(isset($danhMuc))
                        @if($danhMuc->parent)
                             <li class="breadcrumb-item">
                                <a href="{{ route('danhmuc.show', $danhMuc->parent->slug) }}" class="text-decoration-none">{{ $danhMuc->parent->ten_danh_muc }}</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $danhMuc->ten_danh_muc }}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>

    {{-- HIỂN THỊ TỪ KHÓA ĐANG TÌM --}}
    @if(request('keyword'))
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="fa fa-search me-2"></i>
            <span>
                Kết quả tìm kiếm cho: <strong>"{{ request('keyword') }}"</strong>
                ({{ $products->total() }} sản phẩm)
            </span>
            <a href="{{ route('thuoc.index') }}" class="btn-close ms-auto" title="Xóa tìm kiếm"></a>
        </div>
    @endif

    <div class="container pb-5">
        <div class="row">

            {{-- ================= CỘT TRÁI: SIDEBAR BỘ LỌC ================= --}}
            <div class="col-lg-3 mb-4 mb-lg-0">
                <div class="d-lg-none mb-3">
                    <button class="btn btn-outline-primary w-100 fw-bold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilterCollapse" aria-expanded="false" aria-controls="mobileFilterCollapse">
                        <i class="fa fa-filter me-2"></i> Lọc & Danh mục
                    </button>
                </div>
                
                <div class="collapse d-lg-block" id="mobileFilterCollapse">
                    <form action="{{ url()->current() }}" method="GET">

                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <div class="filter-sidebar">
                        {{-- 1. DANH MỤC --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Danh mục</h6>
                            <ul class="list-unstyled filter-list">
                                @foreach($allParents as $parent)
                                    <li>
                                        <a href="{{ route('danhmuc.show', $parent->slug) }}"
                                           class="fw-bold {{ (isset($danhMuc) && ($danhMuc->id == $parent->id || ($danhMuc->parent && $danhMuc->parent->id == $parent->id))) ? 'text-primary' : 'text-dark' }}">
                                            {{ $parent->ten_danh_muc }}
                                        </a>
                                    </li>
                                    @foreach($parent->children as $child)
                                        <li class="ps-3">
                                            <a href="{{ route('danhmuc.show', $child->slug) }}"
                                               class="{{ (isset($danhMuc) && $danhMuc->id == $child->id) ? 'text-primary fw-bold' : '' }}">
                                                {{ $child->ten_danh_muc }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="mb-2"></li>
                                @endforeach
                            </ul>
                        </div>

                        <hr class="my-3">

                        {{-- 2. LỌC GIÁ --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Chọn mức giá</h6>
                            <div class="filter-price-options">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="p1" value="duoi_100k" {{ request('price_range') == 'duoi_100k' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="p1">Dưới 100.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="p2" value="100k_300k" {{ request('price_range') == '100k_300k' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="p2">100.000đ - 300.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="p3" value="300k_500k" {{ request('price_range') == '300k_500k' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="p3">300.000đ - 500.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="p4" value="tren_500k" {{ request('price_range') == 'tren_500k' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="p4">Trên 500.000đ</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 align-items-center">
                                <input type="number" name="price_min" class="form-control form-control-sm" placeholder="Min" value="{{ request('price_min') }}">
                                <span>-</span>
                                <input type="number" name="price_max" class="form-control form-control-sm" placeholder="Max" value="{{ request('price_max') }}">
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- 3. LỌC THƯƠNG HIỆU --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Thương hiệu</h6>
                            <div class="brand-list" style="max-height: 200px; overflow-y: auto;">
                                @if(isset($allBrands))
                                    @foreach($allBrands as $brand)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="brands[]" id="b_{{ $brand->id }}" value="{{ $brand->id }}"
                                                {{ (is_array(request('brands')) && in_array($brand->id, request('brands'))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="b_{{ $brand->id }}">
                                                {{ $brand->ten }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Áp dụng bộ lọc</button>

                        @if(request()->hasAny(['price_range', 'brands', 'price_min', 'price_max']))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">Bỏ lọc</a>
                        @endif

                    </div>
                </form>
                </div> <!-- End collapse -->
            </div>

            {{-- ================= CỘT PHẢI: NỘI DUNG CHÍNH ================= --}}
            <div class="col-lg-9">

                {{-- ID BAO QUANH ĐỂ AJAX UPDATE --}}
                <div id="main-ajax-wrapper" style="transition: opacity 0.3s ease;">

                    <h1 class="h3 fw-bold mb-4">
                        {{ $title ?? (isset($danhMuc) ? $danhMuc->ten_danh_muc : 'Danh sách sản phẩm') }}
                    </h1>
                    {{-- 1. VÒNG TRÒN DANH MỤC --}}
                    @if($popularSubCategories && $popularSubCategories->count() > 0)
                    <div class="mb-5">

                        <div class="subcategory-wrapper">
                            <div class="subcategory-scroll">
                                @foreach($popularSubCategories as $sub)
                                <a href="{{ route('danhmuc.show', $sub->slug) }}" class="subcategory-item text-center d-block text-decoration-none ajax-link">
                                    <div class="sub-img-wrap rounded-circle border mx-auto mb-2 shadow-sm"
                                         style="width: 100px; height: 100px; overflow: hidden; position: relative; background: #f8f9fa;">
                                        <img src="{{ $sub->hinh_anh ? asset('images/images_danh_muc/' . $sub->hinh_anh) : 'https://via.placeholder.com/100' }}"
                                             alt="{{ $sub->ten_danh_muc }}"
                                             class="img-fluid"
                                             width="100" height="100"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="sub-title text-dark fw-semibold" style="font-size: 14px;">
                                        {{ $sub->ten_danh_muc }}
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- 2. THANH SẮP XẾP --}}
                    <div class="bg-light p-3 rounded-3 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3 shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <span class="fw-bold text-dark d-none d-md-inline"><i class="fa fa-filter me-1 text-secondary"></i> Bộ lọc:</span>
                            @if(request()->hasAny(['price_range', 'brands', 'price_min', 'price_max', 'sort']))
                                <a href="{{ url()->current() }}" class="text-danger text-decoration-none small fw-bold"><i class="fa fa-times-circle"></i> Xóa hết lọc</a>
                            @else
                                <span class="text-muted small">Mặc định</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary me-2 d-none d-md-inline" style="font-size: 14px;">Sắp xếp:</span>
                            <div class="d-flex gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="ajax-link btn btn-sm px-3 fw-semibold {{ !request('sort') || request('sort') == 'newest' ? 'btn-primary text-white shadow-sm' : 'btn-white border bg-white text-dark' }}">Mới nhất</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="ajax-link btn btn-sm px-3 fw-semibold {{ request('sort') == 'price_asc' ? 'btn-primary text-white shadow-sm' : 'btn-white border bg-white text-dark' }}">Giá thấp</a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="ajax-link btn btn-sm px-3 fw-semibold {{ request('sort') == 'price_desc' ? 'btn-primary text-white shadow-sm' : 'btn-white border bg-white text-dark' }}">Giá cao</a>
                            </div>
                        </div>
                    </div>

                    {{-- 3. DANH SÁCH SẢN PHẨM --}}
                    @include('partials.product-list', ['products' => $products])

                </div> {{-- Kết thúc #main-ajax-wrapper --}}

            </div>
        </div>
    </div>

@endsection

{{-- SCRIPT AJAX --}}
{{-- @push('scripts') Nếu layout không có @stack('scripts') thì bỏ dòng này và @endpush đi --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function fetchContent(url) {
            const container = document.getElementById('main-ajax-wrapper');

            // Hiệu ứng loading
            if(container) {
                container.style.opacity = '0.4';
                container.style.pointerEvents = 'none';
            }

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Lấy nội dung mới
                    const newWrapper = doc.getElementById('main-ajax-wrapper');

                    if(newWrapper && container) {
                        container.innerHTML = newWrapper.innerHTML;

                        // Cập nhật URL
                        window.history.pushState(null, '', url);

                        // Hiệu ứng xong
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';

                        // Scroll nhẹ lên đầu nếu cần
                        // document.querySelector('.col-lg-9').scrollIntoView({ behavior: 'smooth' });
                    } else {
                        console.error('Không tìm thấy #main-ajax-wrapper trong phản hồi');
                        window.location.href = url;
                    }
                })
                .catch(error => {
                    console.error('Lỗi AJAX:', error);
                    window.location.href = url;
                });
        }

        // Bắt sự kiện Click
        document.addEventListener('click', function(e) {
            // Link AJAX (Danh mục tròn, Sắp xếp)
            const ajaxLink = e.target.closest('.ajax-link');
            if (ajaxLink) {
                e.preventDefault();
                fetchContent(ajaxLink.href);
                return;
            }
            // Phân trang
            const pageLink = e.target.closest('.pagination a');
            if (pageLink) {
                e.preventDefault();
                fetchContent(pageLink.href);
                return;
            }
        });

        // Bắt sự kiện Submit Form Lọc
        const filterForm = document.querySelector('.filter-sidebar') ? document.querySelector('.filter-sidebar').closest('form') : null;
        if(filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const url = this.action + '?' + new URLSearchParams(new FormData(this)).toString();
                fetchContent(url);
            });
        }

        // Bắt sự kiện nút Back trình duyệt
        window.addEventListener('popstate', function() {
            fetchContent(window.location.href);
        });
    });
</script>
{{-- @endpush --}}
