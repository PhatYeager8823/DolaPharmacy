<header class="pharmacy-header {{ $global_setting->is_promo_active ? 'has-promo' : '' }}">
    @if($global_setting->is_promo_active)
    {{-- Banner khuyến mãi trên cùng --}}
    <div class="pharmacy-promo">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="promo-text">
                <span>{{ $global_setting->promo_text ?? 'CHÀO MỪNG BẠN MỚI' }}</span>
                <strong>TƯNG BỪNG ƯU ĐÃI</strong>
            </div>
            <div class="promo-code">
                @if($global_setting->promo_code)
                    <span>NHẬP MÃ "<strong>{{ $global_setting->promo_code }}</strong>"</span>
                    <br>
                @endif
                <span>NHẬN QUÀ HẤP DẪN</span>
                <p class="promo-note mb-0">(Áp dụng cho đơn hàng online đầu tiên từ 399K)</p>
            </div>
            <div class="promo-date-btn d-flex align-items-center">
                <div class="promo-date me-3">
                    @if($global_setting->promo_end_date)
                        <strong>Kết thúc: {{ date('d/m/Y', strtotime($global_setting->promo_end_date)) }}</strong>
                    @else
                        <strong>01.07 - 31.07.2023</strong>
                    @endif
                </div>
                <a href="#" class="btn btn-promo">XEM CHI TIẾT</a>
            </div>
        </div>
    </div>
    @endif

    {{-- Header chính --}}
    <div class="pharmacy-header-main">
        <div class="container">

            {{-- TOP BAR: ĐĂNG NHẬP & HOTLINE --}}
            <div class="d-none d-lg-flex justify-content-end py-2">
                <div class="header-top-links d-flex align-items-center">

                    <ul class="nav d-flex align-items-center">
                        {{-- KIỂM TRA ĐĂNG NHẬP --}}
                        @guest
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link text-white fw-bold px-2 py-0" href="{{ route('login') }}">Đăng nhập</a>
                                <span class="text-white mx-1 fw-bold">/</span>
                                <a class="nav-link text-white fw-bold px-2 py-0" href="{{ route('register') }}">Đăng ký</a>
                            </li>
                        @else
                            {{-- ĐÃ ĐĂNG NHẬP --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Xin chào, <strong>{{ Auth::user()->ten }}</strong>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.index') }}">
                                            <i class="fas fa-user-circle me-2"></i> Tài khoản của tôi
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('account.orders') }}">
                                            <i class="fas fa-shopping-bag me-2"></i> Đơn mua
                                        </a>
                                    </li>
                                    @if(Auth::user()->vai_tro === 'admin')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i> Trang quản trị
                                            </a>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                        </a>
                                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    {{-- Hotline --}}
                    <span class="mx-2 text-white">|</span>
                    <span class="text-white link-auth">Hotline đặt hàng:</span>
                    <span class="hotline-pill ms-2 text-white">
                        <i class="fa fa-phone-alt me-1"></i>
                        {{ $global_setting->hotline ?? '0123.456.789' }}
                    </span>
                </div>
            </div>

            {{-- MAIN BAR --}}
            <div class="d-flex align-items-center flex-wrap gap-2 gap-lg-3 py-3">

                {{-- Nút danh mục (Mobile) --}}
                <div class="pharmacy-category-btn d-lg-none order-1">
                    <button class="btn btn-category d-flex align-items-center"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#mobileMenuOffcanvas">
                        <span class="category-icon">
                            <span></span><span></span><span></span>
                        </span>
                    </button>
                </div>

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="pharmacy-logo d-flex align-items-center order-2 order-lg-1">
                    @if($global_setting->logo)
                        <img src="{{ asset('images/settings/' . $global_setting->logo) }}" alt="{{ $global_setting->ten_website }}">
                    @else
                        <img src="{{ asset('images/logos/logo-nha-thuoc.webp') }}" alt="Dola Pharmacy">
                    @endif
                </a>

                {{-- Nút danh mục (Desktop) --}}
                <div class="pharmacy-category-btn d-none d-lg-block order-lg-2">
                    <button class="btn btn-category d-flex align-items-center"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#categoryMegaMenu">
                        <span class="category-icon">
                            <span></span><span></span><span></span>
                        </span>
                        <span class="ms-2">Danh mục</span>
                    </button>
                </div>

                <div class="pharmacy-search flex-grow-1 header-search-wrapper mt-2 mt-lg-0 order-5 order-lg-3">
                    <form action="{{ route('thuoc.index') }}" method="GET" class="header-search-form m-0">
                        <button class="header-search-btn" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                        <input type="text"
                            name="keyword"
                            class="header-search-input"
                            placeholder="Bạn đang tìm gì hôm nay..."
                            value="{{ request('keyword') }}"
                            required>
                    </form>
                </div>

                {{-- Các Icon chức năng --}}
                <div class="header-icons d-flex align-items-center gap-2 gap-lg-3 order-3 order-lg-4 ms-auto ms-lg-0">
                    <a href="{{ route('contact.index') }}" class="icon-circle position-relative" title="Hệ thống nhà thuốc">
                        <i class="fa fa-map-marker-alt"></i>
                    </a>

                    @php
                        $unreadCount = 0;
                        if(Auth::check()) {
                            $unreadCount = \App\Models\ThongBao::where('nguoi_dung_id', Auth::id())->where('da_xem', 0)->count();
                        }
                    @endphp
                    <a href="{{ route('notifications.index') }}" class="icon-circle position-relative" title="Thông báo">
                        <i class="fa fa-bell"></i>
                        @if($unreadCount > 0)
                            <span class="badge-count">{{ $unreadCount }}</span>
                        @endif
                    </a>

                    @php
                        $wishlistCount = 0;
                        if(Auth::check()) {
                            $wishlistCount = \App\Models\YeuThich::where('nguoi_dung_id', Auth::id())->count();
                        }
                    @endphp
                    <a href="{{ route('wishlist.index') }}" class="icon-circle position-relative" title="Sản phẩm yêu thích">
                        <i class="far fa-heart"></i>
                        <span class="badge-count wishlist-count-badge" style="{{ $wishlistCount > 0 ? '' : 'display: none;' }}">
                            {{ $wishlistCount }}
                        </span>
                    </a>
                </div>

                {{-- Giỏ hàng --}}
                <div class="header-cart-wrapper order-4 order-lg-5">
                    @php
                        $cartCount = 0;
                        if (Auth::check()) {
                            $userCart = \App\Models\GioHang::where('nguoi_dung_id', Auth::id())->first();
                            if ($userCart) {
                                $cartCount = \App\Models\GioHangChiTiet::where('gio_hang_id', $userCart->id)
                                                                       ->where('trang_thai', 0)
                                                                       ->sum('so_luong');
                            }
                        } else {
                            $cartSession = session()->get('cart', []);
                            $cartCount = array_sum(array_column($cartSession, 'quantity'));
                        }
                    @endphp

                    <a href="{{ route('cart.index') }}" class="btn btn-cart d-flex align-items-center">
                        <span class="me-2"><i class="fa fa-shopping-cart"></i></span>
                        <span class="d-none d-lg-inline">Giỏ hàng</span>
                        <span class="cart-count ms-2" style="{{ $cartCount > 0 ? '' : 'display: none;' }}">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>
            </div>

            {{-- MENU NGANG --}}
            <nav class="pharmacy-nav">
                <ul class="nav nav-main justify-content-center">
                    <li class="nav-item mega-dropdown d-none d-lg-block">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') ?? url('/') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a>
                    </li>

                    {{-- MEGA MENU (ĐÃ SỬA LỖI) --}}
                    <li class="nav-item mega-dropdown">
                        <a class="nav-link {{ (request()->routeIs('thuoc.*') || request()->routeIs('danhmuc.*')) && !request()->routeIs('promotion.index') ? 'active' : '' }}" href="{{ route('thuoc.index') }}">
                            Sản phẩm <i class="fa fa-chevron-down dropdown-arrow"></i>
                        </a>
                        <div class="mega-menu">
                            <div class="container">
                                {{-- Thêm class mega-row để CSS hoạt động --}}
                                <div class="row py-3 mega-row">
                                    @if(isset($megaCategories) && $megaCategories->count() > 0)
                                        @foreach($megaCategories as $parent)
                                            {{-- [QUAN TRỌNG] Thêm class 'mega-col' vào đây --}}
                                            <div class="col-md-3 mb-3 mega-col">

                                                {{-- Tên danh mục cha --}}
                                                <h5 class="fw-bold mb-2">
                                                    <a href="{{ route('danhmuc.show', $parent->slug) }}" class="text-decoration-none text-primary">
                                                        {{ $parent->ten_danh_muc }}
                                                    </a>
                                                </h5>

                                                {{-- Danh sách con --}}
                                                <ul class="list-unstyled">
                                                    @foreach($parent->children as $child)
                                                        <li class="mb-1">
                                                            {{-- [QUAN TRỌNG] Đã xóa style font-size cứng --}}
                                                            <a href="{{ route('danhmuc.show', $child->slug) }}" class="hover-link">
                                                                {{ $child->ten_danh_muc }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center text-muted">Đang cập nhật danh mục...</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('promotion.index') ? 'active' : '' }}" href="{{ route('promotion.index') }}">Sản phẩm khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('baiviet.*') ? 'active' : '' }}" href="{{ route('baiviet.index') }}">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('video.index') ? 'active' : '' }}" href="{{ route('video.index') }}">Video</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('faq.index') ? 'active' : '' }}" href="{{ route('faq.index') }}">Câu hỏi thường gặp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">Liên hệ</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

{{-- MENU MEGA DROPDOWN (Dữ liệu thật từ AppServiceProvider) --}}
<div class="collapse" id="categoryMegaMenu">
    <div class="category-mega-menu">
        <div class="container">
            <div class="row">
                {{-- CỘT TRÁI --}}
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills category-menu-nav" id="v-pills-tab" role="tablist">
                        @foreach($megaCategories as $index => $parent)
                            <button class="nav-link {{ $index == 0 ? 'active' : '' }} d-flex justify-content-between align-items-center"
                                    id="v-pills-{{ $parent->id }}-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#v-pills-{{ $parent->id }}"
                                    type="button" role="tab">
                                <span>{{ $parent->ten_danh_muc }}</span>
                                <i class="fa fa-chevron-right small" style="font-size: 10px;"></i>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- CỘT PHẢI --}}
                <div class="col-md-9">
                    <div class="tab-content category-menu-content ps-3 pt-2" id="v-pills-tabContent" style="min-height: 250px;">
                        @foreach($megaCategories as $index => $parent)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="v-pills-{{ $parent->id }}" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <h5 class="fw-bold text-primary mb-0">{{ $parent->ten_danh_muc }}</h5>
                                    <a href="{{ route('danhmuc.show', $parent->slug) }}" class="text-decoration-none small fw-bold">Xem tất cả <i class="fa fa-angle-right"></i></a>
                                </div>
                                @if($parent->children->count() > 0)
                                    <div class="row g-3">
                                        @foreach($parent->children as $child)
                                            <div class="col-md-4">
                                                <a href="{{ route('danhmuc.show', $child->slug) }}" class="d-flex align-items-center text-decoration-none text-dark p-2 rounded hover-bg-light">
                                                    <div class="me-3">
                                                        <img src="{{ $child->hinh_anh ? asset('images/images_danh_muc/' . $child->hinh_anh) : 'https://via.placeholder.com/40' }}"
                                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    </div>
                                                    <div><div class="fw-bold" style="font-size: 14px;">{{ $child->ten_danh_muc }}</div></div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Đang cập nhật danh mục con...</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MOBILE MENU OFFCANVAS --}}
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileMenuOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-white">
            {{ strtoupper($global_setting->ten_website ?? 'Dola Pharmacy') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body pt-0">
        <div class="mobile-top-links py-3">
            @guest
                <div class="mobile-auth-links d-flex align-items-center gap-2">
                    <i class="fa fa-user-circle text-secondary fs-4"></i>
                    <div>
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-white">Đăng nhập</a>
                        <span class="text-white-50 mx-1">/</span>
                        {{-- SỬA ROUTE TẠI ĐÂY --}}
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-white">Đăng ký</a>
                    </div>
                </div>
            @else
                <a href="#mobileUserMenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="mobileUserMenu" class="d-flex align-items-center gap-3 mobile-user-info mb-3 text-decoration-none w-100">
                    <div class="avatar-circle bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 24px; flex-shrink: 0;">
                        {{ substr(Auth::user()->ten ?? 'U', 0, 1) }}
                    </div>
                    <div class="d-flex flex-column text-white flex-grow-1">
                        <span class="d-block small text-white-50 mb-1">Xin chào,</span>
                        <span class="fw-bold fs-5 text-white m-0 p-0 text-truncate" style="max-width: 200px; line-height: 1;">{{ Auth::user()->ten }}</span>
                    </div>
                    <i class="fa fa-chevron-down text-white-50 ms-auto me-2"></i>
                </a>

                {{-- BỔ SUNG CÁC NÚT TÍNH NĂNG TÀI KHOẢN TRÊN MOBILE CHỖ NÀY --}}
                <div class="collapse" id="mobileUserMenu">
                    <div class="mobile-user-actions px-3 py-3 mx-2 mb-3 bg-white bg-opacity-10 rounded">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li>
                                <a href="{{ route('account.index') }}" class="text-white text-decoration-none d-flex align-items-center gap-3">
                                    <i class="fas fa-user-circle fs-5" style="width: 24px; text-align: center;"></i> 
                                    <span style="font-size: 15px;">Tài khoản của tôi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.orders') }}" class="text-white text-decoration-none d-flex align-items-center gap-3">
                                    <i class="fas fa-shopping-bag fs-5" style="width: 24px; text-align: center;"></i> 
                                    <span style="font-size: 15px;">Đơn mua hàng</span>
                                </a>
                            </li>
                            @if(Auth::user()->vai_tro === 'admin')
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="text-warning text-decoration-none d-flex align-items-center gap-3 fw-bold">
                                    <i class="fas fa-tachometer-alt fs-5" style="width: 24px; text-align: center;"></i> 
                                    <span style="font-size: 15px;">Trang quản trị (Admin)</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

            @endguest
            <hr class="my-3">
            <div class="hotline d-flex align-items-center justify-content-between bg-primary bg-opacity-10 p-2 rounded">
                <span class="fw-bold text-primary"><i class="fa fa-phone-alt me-2"></i>Hotline:</span>
                <a href="tel:{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0123.456.789') }}" class="fw-bold text-danger text-decoration-none">{{ $global_setting->hotline ?? '0123.456.789' }}</a>
            </div>
        </div>

        <nav class="mobile-nav">
            <ul class="nav flex-column gap-1">
                <li class="nav-item border-bottom"><a class="nav-link text-dark py-3" href="{{ route('about') }}"><i class="fa fa-info-circle me-2 text-secondary"></i> Giới thiệu</a></li>

                @php $mobileParents = \App\Models\DanhMuc::whereNull('danh_muc_cha_id')->with('children')->get(); @endphp
                <li class="nav-item border-bottom">
                    <a class="nav-link text-dark py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileProducts">
                        <span><i class="fa fa-pills me-2 text-secondary"></i> Sản phẩm</span><i class="fa fa-chevron-down small"></i>
                    </a>
                    <div class="collapse" id="mobileProducts">
                        <ul class="nav flex-column ms-2 border-start border-2 ps-3 mb-3">
                            @foreach($mobileParents as $parent)
                                <li class="nav-item mt-2"><a href="{{ route('danhmuc.show', $parent->slug) }}" class="fw-bold text-primary text-decoration-none d-block py-1">{{ $parent->ten_danh_muc }}</a></li>
                                @foreach($parent->children as $child)
                                    <li class="nav-item"><a class="nav-link py-1 text-secondary ps-0" href="{{ route('danhmuc.show', $child->slug) }}" style="font-size: 14px;">- {{ $child->ten_danh_muc }}</a></li>
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </li>

                <li class="nav-item border-bottom"><a class="nav-link text-dark py-3" href="{{ route('promotion.index') }}"><i class="fa fa-tags me-2 text-secondary"></i> Khuyến mãi</a></li>
                <li class="nav-item border-bottom"><a class="nav-link text-dark py-3" href="{{ route('baiviet.index') }}"><i class="fa fa-newspaper me-2 text-secondary"></i> Tin tức</a></li>
                <li class="nav-item border-bottom"><a class="nav-link text-dark py-3" href="{{ route('video.index') }}"><i class="fa fa-video me-2 text-secondary"></i> Video</a></li>
                <li class="nav-item"><a class="nav-link text-dark py-3" href="{{ route('contact.index') }}"><i class="fa fa-phone me-2 text-secondary"></i> Liên hệ</a></li>

                @auth
                <li class="nav-item mt-3">
                     <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100"><i class="fa fa-sign-out-alt me-2"></i> Đăng xuất</button>
                    </form>
                </li>
                @endauth
            </ul>
        </nav>
    </div>
</div>
