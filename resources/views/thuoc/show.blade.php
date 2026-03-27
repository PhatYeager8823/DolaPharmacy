@extends('layouts.app')

@section('title', $thuoc->ten_thuoc)

@section('content')

    {{-- 1. BREADCRUMB (Điều hướng) --}}
    <div class="bg-light py-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="font-size: 14px;">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Trang chủ</a></li>

                    @if($thuoc->danhMuc)
                        {{-- Nếu có danh mục cha --}}
                        @if($thuoc->danhMuc->parent)
                            <li class="breadcrumb-item">
                                <a href="{{ route('danhmuc.show', $thuoc->danhMuc->parent->slug) }}" class="text-decoration-none">
                                    {{ $thuoc->danhMuc->parent->ten_danh_muc }}
                                </a>
                            </li>
                        @endif
                        {{-- Danh mục hiện tại --}}
                        <li class="breadcrumb-item">
                            <a href="{{ route('danhmuc.show', $thuoc->danhMuc->slug) }}" class="text-decoration-none">
                                {{ $thuoc->danhMuc->ten_danh_muc }}
                            </a>
                        </li>
                    @endif

                    <li class="breadcrumb-item active" aria-current="page">{{ $thuoc->ten_thuoc }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container pb-5">

        {{-- 2. KHU VỰC THÔNG TIN CHÍNH (ẢNH + GIÁ + MUA) --}}
        <div class="row g-4 g-lg-5 mb-5">

            {{-- Cột Trái: Ảnh sản phẩm --}}
            <div class="col-12 col-lg-5">
                {{-- 1. ẢNH LỚN --}}
                <div class="border rounded p-4 text-center bg-white shadow-sm position-relative mb-2">

                    {{-- Badge kê đơn --}}
                    @if($thuoc->ke_don)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 px-3 py-2">Thuốc kê đơn</span>
                    @endif

                    @if($thuoc->gia_cu > $thuoc->gia_ban && $thuoc->ke_don == 0)
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2 fs-6 shadow-sm">
                            -{{ round((($thuoc->gia_cu - $thuoc->gia_ban) / $thuoc->gia_cu) * 100) }}%
                        </span>
                    @endif

                    <img id="mainImage"
                         src="{{ $thuoc->hinh_anh ? asset('images/images_san_pham/' . $thuoc->hinh_anh) : 'https://via.placeholder.com/500x500?text=No+Image' }}"
                         alt="{{ $thuoc->ten_thuoc }}"
                         class="img-fluid"
                         style="max-height: 400px; object-fit: contain;"
                         onerror="this.onerror=null;this.src='https://via.placeholder.com/500x500?text=No+Image';">
                </div>

                {{-- 2. 3 ẢNH NHỎ BÊN DƯỚI --}}
                <div class="row g-2">
                    @for($i = 0; $i < 3; $i++)
                        <div class="col-4">
                            {{--
                                1. height: 80px -> Giảm kích thước xuống cho gọn
                                2. aspect-ratio: 1/1 -> Ép khung thành hình vuông (nếu trình duyệt hỗ trợ)
                                3. p-1 -> Tạo khoảng hở nhỏ để ảnh không dính sát viền
                            --}}
                            <div class="border rounded p-1 text-center bg-white shadow-sm position-relative d-flex align-items-center justify-content-center thumb-container"
                                style="height: 80px; cursor: pointer; width: 100%;">

                                <img src="{{ $thuoc->hinh_anh ? asset('images/images_san_pham/' . $thuoc->hinh_anh) : 'https://via.placeholder.com/150x150?text=No+Image' }}"
                                    alt="Thumbnail {{ $i }}"
                                    class="img-fluid thumb-img"
                                    {{--
                                        QUAN TRỌNG NHẤT: object-fit: contain
                                        -> Giúp ảnh hiển thị TRỌN VẸN, không bị cắt, không bị méo
                                    --}}
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                    onclick="changeImage(this)"
                                    onerror="this.onerror=null;this.src='https://via.placeholder.com/150x150?text=No+Image';">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Cột Phải: Thông tin mua hàng --}}
            <div class="col-12 col-lg-7">
                {{-- Thương hiệu --}}
                @if($thuoc->brand)
                    <div class="mb-2">
                        <span class="text-muted text-uppercase small fw-bold">Thương hiệu: </span>
                        <a href="#" class="text-primary text-decoration-none fw-bold">{{ $thuoc->brand->ten }}</a>
                    </div>
                @endif

                <h1 class="h2 fw-bold mb-3 text-dark">{{ $thuoc->ten_thuoc }}</h1>

                {{-- Đánh giá sao --}}
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-2">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa fa-star {{ $i <= $avgRating ? '' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <span class="small text-secondary fw-bold">{{ $avgRating > 0 ? $avgRating . '/5' : 'Chưa có đánh giá' }} ({{ $danhGias->count() }} lượt)</span>
                    <a href="#danh-gia" class="small text-primary ms-2" onclick="document.getElementById('danh-gia-tab').click();">(Xem nhận xét)</a>
                </div>

                {{-- Mã SP & Quy cách --}}
                <div class="d-flex gap-4 mb-3 text-secondary small">
                    @if($thuoc->ma_san_pham)
                        <span>Mã SP: <span class="text-dark fw-semibold">{{ $thuoc->ma_san_pham }}</span></span>
                    @endif
                    @if($thuoc->quy_cach)
                        <span>Quy cách: <span class="text-dark fw-semibold">{{ $thuoc->quy_cach }}</span></span>
                    @endif
                </div>

                {{-- Giá bán --}}
                <div class="d-flex align-items-end gap-2 mb-4">
                    {{-- Giá to --}}
                    <span class="h3 fw-bold text-primary mb-0">
                        {{ number_format($thuoc->gia_ban) }} đ
                    </span>

                    {{-- Đơn vị tính --}}
                    <span class="h5 fw-bold text-secondary mb-0">
                        / {{ $thuoc->don_vi_tinh ?? 'Hộp' }}
                    </span>

                    {{-- Giá cũ --}}
                    @if($thuoc->gia_cu > $thuoc->gia_ban)
                        <span class="text-decoration-line-through text-muted ms-2 mb-1">
                            {{ number_format($thuoc->gia_cu) }} đ
                        </span>
                    @endif
                </div>

                {{-- Mô tả ngắn --}}
                @if($thuoc->mo_ta_ngan)
                    <div class="alert alert-light border mb-4">
                        <i class="fa fa-info-circle text-primary me-2"></i> {{ $thuoc->mo_ta_ngan }}
                    </div>
                @endif

                {{-- KIỂM TRA: THUỐC KÊ ĐƠN HAY KHÔNG --}}
                @if($thuoc->ke_don == 1)

                    {{-- TRƯỜNG HỢP 1: THUỐC KÊ ĐƠN (Chỉ hiện nút tư vấn) --}}
                    <div class="alert alert-warning border-warning d-flex align-items-center mb-3" role="alert">
                        <i class="fa fa-exclamation-triangle me-2 fs-4"></i>
                        <div>
                            <strong>Thuốc kê đơn:</strong> Sản phẩm này cần dược sĩ tư vấn trước khi mua.
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        {{-- Nút gọi điện --}}
                        <a href="tel:{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0123.456.789') }}" class="btn btn-warning btn-lg flex-grow-1 fw-bold text-white shadow-sm">
                            <i class="fa fa-phone-alt me-2"></i> Gọi dược sĩ
                        </a>
                        {{-- Nút Zalo --}}
                        <a href="https://zalo.me/{{ str_replace(['.', ' '], '', $global_setting->zalo ?? '0123456789') }}" target="_blank" class="btn btn-info btn-lg flex-grow-1 fw-bold text-white shadow-sm">
                            <i class="fa fa-comments me-2"></i> Chat Zalo
                        </a>
                    </div>

                @else

                    {{-- TRƯỜNG HỢP 2: THUỐC THƯỜNG (Hiện form mua hàng của bạn) --}}
                    <div class="d-flex gap-3 align-items-center mb-4">
                        {{-- Input chọn số lượng --}}
                        <div class="input-group" style="width: 130px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                            <input type="number" id="product_qty" class="form-control text-center" value="1" min="1" max="99">
                            <button class="btn btn-outline-secondary" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                        </div>

                        {{-- Nút Thêm vào giỏ (Loại bỏ type="submit", dùng onclick) --}}
                        <button type="button"
                                class="btn btn-primary btn-lg flex-grow-1 fw-bold shadow-sm"
                                onclick="addToCart({{ $thuoc->id }}, this)">
                            <i class="fa fa-cart-plus me-2"></i> Thêm vào giỏ hàng
                        </button>
                    </div>

                @endif

                {{-- Cam kết / Chính sách --}}
                <div class="row g-3 small text-secondary">
                    <div class="col-6"><i class="fa fa-check-circle text-success me-1"></i> 100% Chính hãng</div>
                    <div class="col-6"><i class="fa fa-sync-alt text-success me-1"></i> Đổi trả trong 7 ngày</div>
                    <div class="col-6"><i class="fa fa-truck text-success me-1"></i> Miễn phí vận chuyển nội thành</div>
                    <div class="col-6"><i class="fa fa-file-invoice text-success me-1"></i> Có xuất hóa đơn đỏ</div>
                </div>
            </div>
        </div>

        {{-- 3. CHI TIẾT SẢN PHẨM (TAB) --}}
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <ul class="nav nav-tabs card-header-tabs" id="productTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold text-dark" id="cong-dung-tab" data-bs-toggle="tab" data-bs-target="#cong-dung" type="button">Công dụng</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold text-dark" id="thanh-phan-tab" data-bs-toggle="tab" data-bs-target="#thanh-phan" type="button">Thành phần</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold text-dark" id="cach-dung-tab" data-bs-toggle="tab" data-bs-target="#cach-dung" type="button">Cách dùng</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold text-dark" id="danh-gia-tab" data-bs-toggle="tab" data-bs-target="#danh-gia" type="button">Đánh giá & Nhận xét</button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content" id="productTabContent">

                            {{-- Tab Công Dụng --}}
                            <div class="tab-pane fade show active" id="cong-dung">
                                <h5 class="fw-bold mb-3">Công dụng</h5>
                                <div class="text-secondary" style="line-height: 1.8;">
                                    {{-- SỬA: Bỏ e() và nl2br() để HTML hiển thị đúng --}}
                                    {!! $thuoc->cong_dung ?? 'Đang cập nhật...' !!}
                                </div>
                            </div>

                            {{-- Tab Thành Phần --}}
                            <div class="tab-pane fade" id="thanh-phan">
                                <h5 class="fw-bold mb-3">Thành phần</h5>
                                <div class="text-secondary">
                                    {{-- SỬA: Bỏ e() và nl2br() --}}
                                    {!! $thuoc->thanh_phan ?? 'Đang cập nhật...' !!}

                                    @if($thuoc->hoat_chat)
                                        <div class="mt-3 p-3 bg-light rounded border">
                                            <strong>Hoạt chất chính:</strong> {{ $thuoc->hoat_chat }}
                                            @if($thuoc->ham_luong)
                                                ({{ $thuoc->ham_luong }})
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Tab Cách Dùng --}}
                            <div class="tab-pane fade" id="cach-dung">
                                <h5 class="fw-bold mb-3">Hướng dẫn sử dụng</h5>
                                <div class="text-secondary" style="line-height: 1.8;">
                                    {{-- SỬA: Bỏ e() và nl2br() --}}
                                    {!! $thuoc->cach_dung ?? 'Đang cập nhật...' !!}
                                </div>

                                @if($thuoc->tac_dung_phu)
                                    <h5 class="fw-bold mt-4 mb-2 text-danger">Tác dụng phụ (Lưu ý)</h5>
                                    <div class="text-secondary">
                                        {{-- SỬA: Bỏ e() và nl2br() --}}
                                        {!! $thuoc->tac_dung_phu !!}
                                    </div>
                                @endif
                            </div>

                            {{-- Tab Đánh giá --}}
                            <div class="tab-pane fade" id="danh-gia">
                                <div class="row">
                                    <div class="col-md-7">
                                        <h5 class="fw-bold mb-4">Các đánh giá mới nhất</h5>
                                        @if($danhGias->isEmpty())
                                            <p class="text-muted fst-italic">Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên mua hàng và để lại cái nhìn chân thực nhé!</p>
                                        @else
                                            @foreach($danhGias as $review)
                                                <div class="d-flex mb-4">
                                                    <div class="flex-shrink-0">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 45px; height: 45px;">
                                                            {{ strtoupper(substr($review->nguoiDung->ten, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 fw-bold">{{ $review->nguoiDung->ten }} <span class="text-muted small fw-normal ms-2">{{ $review->created_at->format('d/m/Y H:i') }}</span></h6>
                                                        <div class="text-warning small mb-2">
                                                            @for($i=1; $i<=5; $i++)
                                                                <i class="fa fa-star {{ $i <= $review->so_sao ? '' : 'text-muted' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <p class="mb-0 text-secondary" style="font-size: 0.95rem;">{{ $review->noi_dung }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <div class="bg-light p-4 rounded border">
                                            <h5 class="fw-bold mb-3">Thêm đánh giá của bạn</h5>
                                            @auth
                                                <form action="{{ route('sanpham.danh_gia', $thuoc->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Mức độ hài lòng <span class="text-danger">*</span></label>
                                                        <select name="so_sao" class="form-select border-warning @error('so_sao') is-invalid @enderror" required>
                                                            <option value="5" {{ old('so_sao') == 5 ? 'selected' : '' }}>⭐️⭐️⭐️⭐️⭐️ (5 Sao - Tuyệt vời)</option>
                                                            <option value="4" {{ old('so_sao') == 4 ? 'selected' : '' }}>⭐️⭐️⭐️⭐️ (4 Sao - Tốt)</option>
                                                            <option value="3" {{ old('so_sao') == 3 ? 'selected' : '' }}>⭐️⭐️⭐️ (3 Sao - Bình thường)</option>
                                                            <option value="2" {{ old('so_sao') == 2 ? 'selected' : '' }}>⭐️⭐️ (2 Sao - Tạm được)</option>
                                                            <option value="1" {{ old('so_sao') == 1 ? 'selected' : '' }}>⭐️ (1 Sao - Tệ)</option>
                                                        </select>
                                                        @error('so_sao')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Nhận xét chi tiết <span class="text-danger">*</span></label>
                                                        <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" rows="3" placeholder="Sản phẩm dùng tốt không? Đóng gói thế nào?..." required>{{ old('noi_dung') }}</textarea>
                                                        @error('noi_dung')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa fa-paper-plane me-1"></i> Gửi đánh giá ngay</button>
                                                </form>
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="fa fa-lock text-muted fs-1 mb-3"></i>
                                                    <p class="text-muted mb-3">Vui lòng đăng nhập để gửi nhận xét của bạn</p>
                                                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Đăng nhập ngay</a>
                                                </div>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Thông tin thêm (Có thể để banner quảng cáo hoặc bài viết) --}}
            <div class="col-12 col-lg-3 d-none d-lg-block">
                {{-- Đã chỉnh top: 140px (để tránh đè lên header) và z-index thấp hơn header --}}
                <div class="sticky-top" style="top: 140px; z-index: 99;">
                    <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                        <h6 class="fw-bold mb-3">Sản phẩm này chính hãng?</h6>
                        <p class="small text-secondary text-justify mb-0">
                            Sản phẩm tại Dola Pharmacy được cam kết 100% chính hãng, có hóa đơn đỏ và giấy tờ kiểm định đầy đủ từ nhà sản xuất.
                        </p>
                    </div>
                    {{-- Banner quảng cáo nhỏ --}}
                    <a href="#">
                         <img src="{{ asset('images/logos/Thuong-hieu.webp') }}" class="img-fluid rounded w-100" alt="Banner">
                    </a>
                </div>
            </div>
        </div>

        {{-- 4. SẢN PHẨM LIÊN QUAN --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-5">
                <h3 class="fw-bold mb-4">Sản phẩm cùng danh mục</h3>

                {{-- Tái sử dụng partial product-list nếu bạn đã tạo ở bước trước --}}
                @include('partials.product-list', ['products' => $relatedProducts])
            </div>
        @endif

    </div>

@endsection

{{-- Đặt đoạn này ở dòng cuối cùng của file show.blade.php, SAU @endsection --}}

<script>
    function changeImage(clickedImg) {
        // 2. Tìm ảnh to
        var mainImage = document.getElementById('mainImage');

        // 3. Kiểm tra xem có tìm thấy ảnh to không
        if (mainImage) {
            console.log("Đã tìm thấy ảnh to, đang đổi src...");

            // Đổi ảnh
            mainImage.src = clickedImg.src;

            // Xử lý viền xanh
            document.querySelectorAll('.thumb-img').forEach(img => {
                // Xóa viền ở thẻ cha (div)
                img.parentElement.classList.remove('border-primary', 'border-2');
            });
            // Thêm viền vào thẻ cha của ảnh vừa click
            clickedImg.parentElement.classList.add('border-primary', 'border-2');
        }
    }
</script>
