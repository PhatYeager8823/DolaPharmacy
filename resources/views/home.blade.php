@extends('layouts.app')

@section('title', 'Trang Chủ - Nhà Thuốc')

@section('content')

    <section class="hero-section py-4">
        <div class="container">
            <div class="row g-3">

                {{-- CỘT TRÁI: SLIDER ĐỘNG --}}
                <div class="col-lg-8">
                    <div class="swiper hero-swiper rounded overflow-hidden">
                        <div class="swiper-wrapper">

                            @if($sliders && $sliders->count() > 0)
                                @foreach($sliders as $slider)
                                    <div class="swiper-slide">
                                        <a href="{{ $slider->link ?? '#' }}" class="d-block w-100">
                                            {{-- QUAN TRỌNG: Ép chiều cao cố định và dùng object-fit để cắt ảnh thừa --}}
                                            <img src="{{ asset('images/sliders/' . $slider->hinh_anh) }}"
                                                 class="d-block w-100 hero-img"
                                                 alt="{{ $slider->tieu_de }}">
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                {{-- Slide mặc định (Đủ 4 ảnh như yêu cầu) --}}
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/sliders/slider1.webp') }}"
                                         class="d-block w-100 hero-img" alt="Slider 1">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/sliders/slider2.webp') }}"
                                         class="d-block w-100 hero-img" alt="Slider 2">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/sliders/slider3.webp') }}"
                                         class="d-block w-100 hero-img" alt="Slider 3">
                                </div>
                                <div class="swiper-slide">
                                    <img src="{{ asset('images/sliders/slider4.webp') }}"
                                         class="d-block w-100 hero-img" alt="Slider 4">
                                </div>
                            @endif

                        </div>

                        {{-- Nút tròn phân trang --}}
                        <div class="swiper-pagination"></div>
                    </div>
                </div>

                {{-- CỘT PHẢI: 2 BANNER TĨNH --}}
                <div class="col-lg-4 d-flex flex-column gap-3">
                    {{-- Banner nhỏ 1 --}}
                    <div class="hero-banner-small rounded overflow-hidden flex-grow-1">
                        <a href="#" class="h-100 d-block">
                            <img src="{{ asset('images/banners/banner_hdtc.webp') }}"
                                 class="w-100 h-100"
                                 style="object-fit: cover; min-height: 190px;"
                                 alt="Banner 1">
                        </a>
                    </div>
                    {{-- Banner nhỏ 2 --}}
                    <div class="hero-banner-small rounded overflow-hidden flex-grow-1">
                        <a href="#" class="h-100 d-block">
                            <img src="{{ asset('images/banners/banner_kt.webp') }}"
                                 class="w-100 h-100"
                                 style="object-fit: cover; min-height: 190px;"
                                 alt="Banner 2">
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- danh mục nổi bật --}}
    <section class="featured-categories-section py-3 py-lg-4">
        <div class="container">
            <h2 class="featured-title mb-4">Danh mục nổi bật</h2>

            <div class="featured-categories-wrapper position-relative">

                <div class="swiper featured-categories-swiper">
                    <div class="swiper-wrapper">

                        {{-- 1. Vitamin & Khoáng Chất --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-vitamin-khoang-chat.webp') }}" alt="">
                                </div>
                                <div class="title">Vitamin &amp;<br>Khoáng Chất</div>
                            </a>
                        </div>

                        {{-- 2. Vitamin Cho U50+ --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-u50.webp') }}" alt="">
                                </div>
                                <div class="title">Vitamin<br>Cho U50+</div>
                            </a>
                        </div>

                        {{-- 3. Vitamin cho mẹ --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-me-bau.webp') }}" alt="">
                                </div>
                                <div class="title">Vitamin Cho Mẹ</div>
                            </a>
                        </div>

                        {{-- 4. Dưỡng trắng da --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-duong-trang-da.webp') }}" alt="">
                                </div>
                                <div class="title">Dưỡng Trắng Da</div>
                            </a>
                        </div>

                        {{-- 5. Thực phẩm chức năng --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-tpcn.webp') }}" alt="">
                                </div>
                                <div class="title">Thực Phẩm<br>Chức Năng</div>
                            </a>
                        </div>

                        {{-- 6. Tăng cân --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-tang-can.webp') }}" alt="">
                                </div>
                                <div class="title">Tăng Cân</div>
                            </a>
                        </div>

                        {{-- 7. Giảm cân --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-giam-can.webp') }}" alt="">
                                </div>
                                <div class="title">Giảm Cân</div>
                            </a>
                        </div>

                        {{-- 8. Quà tặng sức khỏe --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-qua-tang.webp') }}" alt="">
                                </div>
                                <div class="title">Quà Tặng<br>Sức Khỏe</div>
                            </a>
                        </div>

                        {{-- 9. Thiết bị y tế --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-thiet-bi-y-te.webp') }}" alt="">
                                </div>
                                <div class="title">Thiết Bị Y Tế</div>
                            </a>
                        </div>

                        {{-- 10. Khuyến mãi hot --}}
                        <div class="swiper-slide">
                            <a href="#" class="featured-category-item">
                                <div class="icon-wrap">
                                    <img src="{{ asset('images/icons/cat-khuyen-mai-hot.webp') }}" alt="">
                                </div>
                                <div class="title">Khuyến mãi hot</div>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Nút điều hướng --}}
                <div class="swiper-button-prev featured-cat-prev"></div>
                <div class="swiper-button-next featured-cat-next"></div>
            </div>
        </div>
    </section>

    {{-- khuyến mãi hấp dẫn --}}
    @if($global_setting->is_promo_active)
    <section class="hot-deals-section py-4 py-lg-5">
        <div class="container">

            {{-- Tiêu đề + countdown --}}
            <div class="d-flex justify-content-between align-items-center mb-3 mb-lg-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="hot-deals-icon">⚡</span>
                    <h2 class="hot-deals-title mb-0">Khuyến mãi hấp dẫn</h2>
                </div>

                {{-- Countdown - Hiển thị dựa trên Admin --}}
                @if($global_setting->promo_end_date)
                <div id="promo-countdown" class="hot-deals-countdown d-flex align-items-center gap-2">
                    <div class="count-box" data-unit="days">
                        <div class="value">0</div>
                        <div class="label">Ngày</div>
                    </div>
                    <span class="dot">:</span>
                    <div class="count-box" data-unit="hours">
                        <div class="value">0</div>
                        <div class="label">Giờ</div>
                    </div>
                    <span class="dot">:</span>
                    <div class="count-box" data-unit="minutes">
                        <div class="value">0</div>
                        <div class="label">Phút</div>
                    </div>
                    <span class="dot">:</span>
                    <div class="count-box" data-unit="seconds">
                        <div class="value">0</div>
                        <div class="label">Giây</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="hot-deals-wrapper position-relative">
                {{-- Slider sản phẩm khuyến mãi --}}
                <div class="swiper hot-deals-swiper">
                    <div class="swiper-wrapper">
                        @foreach($hotDeals as $product)
                            <div class="swiper-slide">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Nút prev/next riêng cho section này --}}
                <div class="swiper-button-prev hot-deals-prev"></div>
                <div class="swiper-button-next hot-deals-next"></div>
            </div>

            {{-- Nút xem tất cả --}}
            <div class="text-center mt-4">
                <a href="{{ route('promotion.index') }}" class="btn btn-hot-deals-all">
                    Xem tất cả
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- sản phẩm mới --}}
    <section class="new-products-section py-4 py-lg-5">
        <div class="container position-relative">

            <h2 class="new-products-title mb-3">Sản phẩm mới</h2>

            <div class="new-products-wrapper position-relative">
                <div class="swiper new-products-swiper">
                    <div class="swiper-wrapper">
                        @foreach($newProducts as $product)
                            <div class="swiper-slide">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Nút điều hướng --}}
                <div class="swiper-button-prev new-products-prev"></div>
                <div class="swiper-button-next new-products-next"></div>
            </div>

            {{-- Nút xem tất cả --}}
            <div class="text-center mt-4">
                <a href="{{ route('thuoc.index') }}" class="btn btn-hot-deals-all">
                    Xem tất cả
                </a>
            </div>

        </div>
    </section>

    {{-- banner sp mới --}}
    <section class="sub-banner-section py-3 py-lg-4">
        <div class="container">

            <div class="sub-banner-wrapper">
                <div class="row g-3 g-lg-4">

                    <div class="col-12 col-md-6">
                        <a href="#" class="sub-banner-card">
                            <img src="{{ asset('images/banners/banner_spm1.webp') }}" alt="Banner 1">
                        </a>
                    </div>

                    <div class="col-12 col-md-6">
                        <a href="#" class="sub-banner-card">
                            <img src="{{ asset('images/banners/banner_spm2.webp') }}" alt="Banner 2">
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>

    {{-- SẢN PHẨM NỔI BẬT --}}
    <section class="featured-products-section py-4 py-lg-5">
        <div class="container">

            <h2 class="featured-products-title mb-4">Sản phẩm nổi bật</h2>

            {{-- ====== HÀNG 1: Banner + 3 SP đầu tiên ====== --}}
            <div class="row g-3 g-lg-4">

                {{-- Banner lớn bên trái (Giữ nguyên) --}}
                <div class="col-12 col-lg-3 shine-effect">
                    <a href="#" class="featured-banner-card">
                        <img src="{{ asset('images/banners/banner_spnb1.webp') }}" alt="Banner nổi bật" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                    </a>
                </div>

                {{-- 3 sản phẩm đầu tiên --}}
                <div class="col-12 col-lg-9">
                    <div class="row g-3 g-lg-4">
                        @foreach($featuredProducts->take(3) as $product)
                            <div class="col-6 col-md-4">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ====== HÀNG 2: Các sản phẩm tiếp theo ====== --}}
            <div class="row g-3 g-lg-4 mt-2">
                @foreach($featuredProducts->skip(3)->take(4) as $product)
                    <div class="col-6 col-md-3 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            {{-- Nút xem tất cả --}}
            <div class="text-center mt-4">
                <a href="{{ route('thuoc.index') }}" class="btn btn-hot-deals-all">
                    Xem tất cả
                </a>
            </div>
        </div>
    </section>

    {{-- giới thiệu --}}
    <section class="about-section py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">

                {{-- Ảnh bên trái --}}
                <div class="col-12 col-lg-5 d-flex">
                    <div class="about-image-card shine-effect flex-fill">
                        <img src="{{ asset('images/banners/gioithieu_doctor.webp') }}" alt="Giới thiệu Dola Pharmacy">
                    </div>
                </div>

                {{-- Nội dung bên phải --}}
                <div class="col-12 col-lg-7">
                    <div class="about-content">

                        <h2 class="about-title">
                            Giới thiệu về Dola Pharmacy
                        </h2>

                        <p>
                            Dola Pharmacy là một nhà thuốc hàng đầu, cung cấp cho khách hàng những dịch vụ chăm sóc sức
                            khỏe chất lượng, chuyên nghiệp và đáng tin cậy nhất. Với nhiều năm kinh nghiệm trong ngành
                            dược phẩm, chúng tôi tự tin là địa điểm mà bạn có thể tin tưởng tìm kiếm sự hỗ trợ về sức
                            khỏe và tìm mua các loại thuốc cần thiết.
                        </p>

                        <p>
                            Tại Dola Pharmacy, chúng tôi cam kết luôn mang đến cho khách hàng sự phục vụ tận tâm và
                            thân thiện. Đội ngũ nhân viên chuyên gia của chúng tôi có kiến thức sâu rộng về ngành dược
                            phẩm và luôn sẵn lòng tư vấn giúp đỡ khách hàng. Bên cạnh đó, chúng tôi cung cấp thông tin
                            chi tiết, minh bạch về các loại thuốc và sản phẩm mà chúng tôi cung cấp, giúp khách hàng
                            hiểu rõ và lựa chọn được những sản phẩm phù hợp nhất cho nhu cầu cụ thể của mình.
                        </p>

                        <h3 class="about-subtitle mt-4">
                            Tại sao chọn chúng tôi
                        </h3>

                        <div class="row g-3 mt-2">

                            {{-- Lý do 1 --}}
                            <div class="col-12 col-md-6">
                                <div class="why-item">
                                    <div class="why-icon">
                                        <div class="why-icon-inner">
                                            <i class="fa fa-shield-alt"></i>
                                        </div>
                                    </div>
                                    <div class="why-text">
                                        <h4>An toàn</h4>
                                        <p>
                                            Sản phẩm được kiểm định rõ ràng, nguồn gốc minh bạch, luôn tuân thủ các
                                            tiêu chuẩn an toàn dược phẩm.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Lý do 2 --}}
                            <div class="col-12 col-md-6">
                                <div class="why-item">
                                    <div class="why-icon">
                                        <div class="why-icon-inner">
                                            <i class="fa fa-user-md"></i>
                                        </div>
                                    </div>
                                    <div class="why-text">
                                        <h4>Đội ngũ giàu kinh nghiệm</h4>
                                        <p>
                                            Đội ngũ dược sĩ, chuyên gia tư vấn tận tâm, luôn sẵn sàng hỗ trợ bạn lựa chọn
                                            sản phẩm phù hợp.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Lý do 3 --}}
                            <div class="col-12 col-md-6">
                                <div class="why-item">
                                    <div class="why-icon">
                                        <div class="why-icon-inner">
                                            <i class="fa fa-hands-helping"></i>
                                        </div>
                                    </div>
                                    <div class="why-text">
                                        <h4>Đồng hành và hỗ trợ</h4>
                                        <p>
                                            Luôn lắng nghe, theo dõi quá trình sử dụng và đưa ra những lời khuyên hữu ích
                                            cho sức khỏe của bạn.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Lý do 4 --}}
                            <div class="col-12 col-md-6">
                                <div class="why-item">
                                    <div class="why-icon">
                                        <div class="why-icon-inner">
                                            <i class="fa fa-percent"></i>
                                        </div>
                                    </div>
                                    <div class="why-text">
                                        <h4>Ưu đãi và giảm giá</h4>
                                        <p>
                                            Nhiều chương trình khuyến mãi hấp dẫn, giúp bạn tiết kiệm chi phí mà vẫn đảm
                                            bảo chất lượng sản phẩm.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div> {{-- row lý do --}}

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Ưu đãi độc quyền --}}
    <section class="exclusive-deals-section py-5">
        <div class="container">

            <h2 class="section-title mb-4">Ưu đãi độc quyền</h2>

            <div class="row g-4 align-items-stretch">

                {{-- Cột trái: Banner dọc (Giữ nguyên) --}}
                <div class="col-12 col-lg-3 d-flex">
                    <div class="exclusive-left-banners flex-fill d-flex flex-column gap-3">
                        <div class="exclusive-banner-card shine-effect">
                            <img src="{{ asset('images/banners/tranhxabenhtat.webp') }}" alt="Banner 1" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="exclusive-banner-card shine-effect">
                            <img src="{{ asset('images/banners/uudaidocquyen.webp') }}" alt="Banner 2" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Danh sách sản phẩm chung --}}
                <div class="col-12 col-lg-9 d-flex">
                    <div class="exclusive-right flex-fill d-flex flex-column">

                        {{-- KHÔNG CÒN TABS MENU NỮA --}}

                        {{-- Hiển thị danh sách sản phẩm (Lấy từ biến $exclusiveProducts) --}}
                        <div class="row g-3 g-lg-4">
                            @if(isset($exclusiveProducts))
                                @foreach($exclusiveProducts as $product)
                                    <div class="col-6 col-md-4 d-flex align-items-stretch">
                                        @include('partials.product-card', ['product' => $product])
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Phần Tags và Xem tất cả --}}
                        <div class="exclusive-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between mt-3">
                            <div class="exclusive-tags-wrapper mb-2 mb-md-0">
                                <span class="exclusive-tags-label">Tìm kiếm nhiều nhất:</span>
                                <button class="exclusive-tag-btn"><i class="fa fa-star me-1"></i> Làm đẹp</button>
                                <button class="exclusive-tag-btn"><i class="fa fa-heart me-1"></i> Sức khỏe</button>
                            </div>
                            <div class="exclusive-viewall-wrapper text-md-end text-start">
                                <a href="{{ route('thuoc.index') }}" class="btn exclusive-viewall-btn">Xem tất cả</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Gợi ý hôm nay --}}
    <section class="exclusive-deals-section py-5">
        <div class="container">

            <h2 class="section-title mb-4">Gợi ý hôm nay</h2>

            <div class="row g-4 align-items-stretch">

                {{-- Cột trái: Banner dọc --}}
                <div class="col-12 col-lg-3 d-flex">
                    <div class="exclusive-left-banners flex-fill d-flex flex-column gap-3">
                        <div class="exclusive-banner-card shine-effect">
                            <img src="{{ asset('images/banners/lamsachlanda.webp') }}" alt="Gợi ý 1" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="exclusive-banner-card shine-effect">
                            <img src="{{ asset('images/banners/xithongkeoong.webp') }}" alt="Gợi ý 2" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Danh sách sản phẩm chung --}}
                <div class="col-12 col-lg-9 d-flex">
                    <div class="exclusive-right flex-fill d-flex flex-column">

                        {{-- KHÔNG CÒN TABS MENU NỮA --}}

                        {{-- Hiển thị danh sách sản phẩm (Lấy từ biến $suggestProducts) --}}
                        <div class="row g-3 g-lg-4">
                            @if(isset($suggestProducts))
                                @foreach($suggestProducts as $product)
                                    <div class="col-6 col-md-4 d-flex align-items-stretch">
                                        @include('partials.product-card', ['product' => $product])
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Phần Tags và Xem tất cả --}}
                        <div class="exclusive-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between mt-3">
                            <div class="exclusive-tags-wrapper mb-2 mb-md-0">
                                <span class="exclusive-tags-label">Xu hướng:</span>
                                <button class="exclusive-tag-btn"><i class="fa fa-star me-1"></i> Vitamin</button>
                                <button class="exclusive-tag-btn"><i class="fa fa-heart me-1"></i> Collagen</button>
                            </div>
                            <div class="exclusive-viewall-wrapper text-md-end text-start">
                                <a href="{{ route('thuoc.index') }}" class="btn exclusive-viewall-btn">Xem tất cả</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Góc dinh dưỡng & Góc khỏe đẹp --}}
    <section class="blog-section py-5">
        <div class="container">
            <div class="row g-5">

                {{-- CỘT TRÁI: GÓC DINH DƯỠNG (2 BÀI MỚI NHẤT) --}}
                <div class="col-12 col-lg-8">
                    <h2 class="blog-title mb-4">Góc dinh dưỡng</h2>

                    <div class="row g-4">
                        @forelse($mainBlogs as $blog)
                            <div class="col-12 col-md-6">
                                <article class="blog-main-card h-100">
                                    {{-- Link bài viết (Bạn sửa route cho đúng) --}}
                                    <a href="{{ route('baiviet.show', $blog->slug ?? '#') }}" class="blog-main-thumb">
                                        {{-- Ảnh bài viết --}}
                                        <img src="{{ asset('images/news/' . $blog->hinh_anh) }}"
                                            alt="{{ $blog->tieu_de }}"
                                            onerror="this.src='{{ asset('images/no-image.png') }}'">
                                    </a>
                                    <div class="blog-main-body">
                                        <a href="{{ route('baiviet.show', $blog->slug ?? '#') }}" class="blog-main-heading">
                                            {{ $blog->tieu_de }}
                                        </a>
                                        <div class="blog-main-date">
                                            {{-- Format ngày tháng --}}
                                            {{ $blog->created_at ? $blog->created_at->format('d/m/Y') : '' }}
                                        </div>
                                        <p class="blog-main-excerpt">
                                            {{-- Cắt ngắn mô tả --}}
                                            {{ Str::limit($blog->mo_ta_ngan ?? '', 100) }}
                                        </p>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <p class="text-muted">Chưa có bài viết nào.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('baiviet.index') }}" class="btn blog-viewall-btn">Xem tất cả</a>
                    </div>
                </div>

                {{-- CỘT PHẢI: GÓC KHỎE ĐẸP (LIST BÀI CŨ HƠN) --}}
                <div class="col-12 col-lg-4">
                    <h2 class="blog-title mb-4">Góc khỏe đẹp</h2>

                    <div class="blog-side-list">
                        @foreach($sideBlogs as $blog)
                            <article class="blog-side-item">
                                <a href="{{ route('baiviet.show', $blog->slug ?? '#') }}" class="blog-side-thumb">
                                    <img src="{{ asset('images/news/' . $blog->hinh_anh) }}"
                                        alt="{{ $blog->tieu_de }}"
                                        onerror="this.src='{{ asset('images/no-image.png') }}'">
                                </a>
                                <div class="blog-side-body">
                                    <a href="{{ route('baiviet.show', $blog->slug ?? '#') }}" class="blog-side-heading">
                                        {{ $blog->tieu_de }}
                                    </a>
                                    <p class="blog-side-excerpt">
                                        {{ Str::limit($blog->mo_ta_ngan ?? '', 60) }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-4 text-lg-left">
                        <a href="{{ route('baiviet.index') }}" class="btn blog-viewall-btn">Xem tất cả</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- video --}}
    <section class="video-section py-5">
        <div class="container">

            <h2 class="section-title mb-4">Video Sức Khỏe</h2>

            <div class="position-relative video-wrapper">
                <div class="swiper video-swiper">
                    <div class="swiper-wrapper">

                        @forelse($videos as $video)
                            <div class="swiper-slide">
                                <article class="video-card">
                                    {{--
                                        QUAN TRỌNG:
                                        1. data-video-url: Phải nối chuỗi để tạo link embed youtube
                                        2. src ảnh: Trỏ vào thư mục images/videos mà ta đã upload
                                    --}}
                                    <a href="javascript:void(0)"
                                    class="video-thumb"
                                    data-video-url="https://www.youtube.com/embed/{{ $video->youtube_id }}">

                                        <img src="{{ asset('images/videos/' . $video->thumbnail) }}"
                                            alt="{{ $video->tieu_de }}"
                                            style="object-fit: cover; aspect-ratio: 16/9;">

                                        <span class="video-play-btn"><i class="fa fa-play"></i></span>
                                    </a>

                                    <h3 class="video-title mt-2">
                                        {{ $video->tieu_de }}
                                    </h3>
                                </article>
                            </div>
                        @empty
                            <div class="text-center w-100">Đang cập nhật video...</div>
                        @endforelse

                    </div>
                </div>

                <div class="video-swiper-button-prev"></div>
                <div class="video-swiper-button-next"></div>
            </div>

            <div class="text-center mt-4">
                <a href="#" class="btn blog-viewall-btn">Xem tất cả</a>
            </div>

        </div>
    </section>

    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-modal-close">&times;</span>
            <iframe id="videoFrame" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    {{-- chính sách và lợi ích --}}
    <section class="policy-section py-5">
        <div class="container">

            <div class="row g-4 justify-content-between">

                <!-- 1. Miễn phí vận chuyển -->
                <div class="col-12 col-md-3 d-flex">
                    <div class="policy-item d-flex">
                        <div class="policy-icon">
                            <img src="{{ asset('images/icons/free-ship.webp') }}" alt="">
                        </div>
                        <div class="policy-text">
                            <h4>Miễn phí vận chuyển</h4>
                            <p>Cho tất cả đơn hàng trong nội thành Hồ Chí Minh</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Miễn phí đổi trả -->
                <div class="col-12 col-md-3 d-flex">
                    <div class="policy-item d-flex">
                        <div class="policy-icon">
                            <img src="{{ asset('images/icons/return.webp') }}" alt="">
                        </div>
                        <div class="policy-text">
                            <h4>Miễn phí đổi – trả</h4>
                            <p>Đối với sản phẩm lỗi sản xuất hoặc vận chuyển</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Hỗ trợ nhanh chóng -->
                <div class="col-12 col-md-3 d-flex">
                    <div class="policy-item d-flex">
                        <div class="policy-icon">
                            <img src="{{ asset('images/icons/support.webp') }}" alt="">
                        </div>
                        <div class="policy-text">
                            <h4>Hỗ trợ nhanh chóng</h4>
                            <p>Gọi Hotline: {{ $global_setting->hotline ?? '0123.456.789' }} để được hỗ trợ ngay lập tức</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Ưu đãi thành viên -->
                <div class="col-12 col-md-3 d-flex">
                    <div class="policy-item d-flex">
                        <div class="policy-icon">
                            <img src="{{ asset('images/icons/member.webp') }}" alt="">
                        </div>
                        <div class="policy-text">
                            <h4>Ưu đãi thành viên</h4>
                            <p>Đăng ký thành viên để được nhận nhiều ưu đãi hấp dẫn</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

@push('scripts')
<script>
    (function() {
        console.log('HOT DEALS TIMER START');
        
        @if($global_setting->promo_end_date)
            const targetDate = new Date('{{ $global_setting->promo_end_date }}');
        @else
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 3);
            targetDate.setHours(23, 59, 59, 0);
        @endif

        function update() {
            const countdown = document.getElementById('promo-countdown');
            if (!countdown) return;

            const now = new Date().getTime();
            const diff = targetDate.getTime() - now;

            if (diff <= 0) {
                countdown.querySelectorAll('.value').forEach(v => v.textContent = '00');
                return;
            }

            const d = Math.floor(diff / (1000 * 60 * 60 * 24));
            const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);

            const dv = countdown.querySelector('[data-unit="days"] .value');
            const hv = countdown.querySelector('[data-unit="hours"] .value');
            const mv = countdown.querySelector('[data-unit="minutes"] .value');
            const sv = countdown.querySelector('[data-unit="seconds"] .value');

            if (dv) dv.textContent = d < 10 ? '0' + d : d;
            if (hv) hv.textContent = h < 10 ? '0' + h : h;
            if (mv) mv.textContent = m < 10 ? '0' + m : m;
            if (sv) sv.textContent = s < 10 ? '0' + s : s;
        }

        setInterval(update, 1000);
        update();
    })();
</script>
@endpush
