{{-- Áp dụng Glassmorphism thay vì bg-dark cứng --}}
<aside id="layout-menu" class="layout-menu menu-vertical menu" style="overflow-y: auto; max-height: 100vh;">
  {{-- 1. LOGO THƯƠNG HIỆU --}}
  <div class="app-brand demo" style="height: 80px;"> {{-- Tăng chiều cao Header lên xíu cho thoáng --}}

    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">

      {{-- 3. Chỉ giữ lại thẻ IMG và set kích thước to lên --}}
      <img src="{{ asset('images/logos/logo.webp') }}"
           alt="Dola Pharmacy"
           style="width: 170px; height: auto; object-fit: contain;">
           {{-- width: 170px là kích thước chuẩn đẹp cho sidebar --}}

    </a>

    {{-- Nút mũi tên thu nhỏ menu (Giữ nguyên) --}}
    <a href="javascript:void(0);" onclick="document.documentElement.classList.remove('layout-menu-expanded');" class="menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <div class="menu-inner-shadow"></div>

  {{-- 2. MENU CHÍNH --}}
  <ul class="menu-inner py-1">

    {{-- DASHBOARD --}}
    <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <a href="{{ route('admin.dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics">Dashboard</div>
      </a>
    </li>

    {{-- NHÓM QUẢN LÝ BÁN HÀNG --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Bán hàng</span>
    </li>

    {{-- Đơn hàng --}}
    <li class="menu-item {{ request()->is('quan-tri/orders*') ? 'active' : '' }}">
        <a href="{{ route('admin.orders.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-receipt"></i>
            <div data-i18n="Orders">Đơn hàng</div>
        </a>
    </li>

    {{-- Sản phẩm --}}
    <li class="menu-item {{ request()->is('quan-tri/products*') ? 'active' : '' }}">
        <a href="{{ route('admin.products.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <div data-i18n="Products">Sản phẩm (Thuốc)</div>
        </a>
    </li>


    {{-- KHO HÀNG & TỒN KHO (Đã tách ra ngoài để không bị lỗi Dropdown) --}}

    {{-- KHO HÀNG (Đã tách mục để tránh lỗi JS) --}}

    {{-- 1. NHẬP HÀNG --}}
    <li class="menu-item {{ request()->routeIs('admin.warehouse.index') || request()->routeIs('admin.warehouse.create') ? 'active' : '' }}">
        <a href="{{ route('admin.warehouse.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-download"></i>
            <div>Nhập hàng</div>
        </a>
    </li>

    {{-- 2. BÁO CÁO TỒN KHO --}}
    <li class="menu-item {{ request()->routeIs('admin.warehouse.inventory') ? 'active' : '' }}">
        <a href="{{ route('admin.warehouse.inventory') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <div>Báo cáo tồn kho</div>
        </a>
    </li>

    {{-- Danh mục --}}
    <li class="menu-item {{ request()->is('quan-tri/categories*') ? 'active' : '' }}">
        <a href="{{ route('admin.categories.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-category"></i>
            <div data-i18n="Categories">Danh mục</div>
        </a>
    </li>

    {{-- Mục Mã giảm giá --}}
    <li class="menu-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
        <a href="{{ route('admin.coupons.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-gift"></i> {{-- Icon hộp quà --}}
            <div data-i18n="Coupons">Mã giảm giá</div>
        </a>
    </li>

    {{-- 1. Thương hiệu --}}
    <li class="menu-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
        <a href="{{ route('admin.brands.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-store"></i> {{-- Icon Cửa hàng --}}
            <div data-i18n="Brand List">Thương hiệu</div>
        </a>
    </li>

    {{-- 2. Nhà cung cấp --}}
    <li class="menu-item {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
        <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-buildings"></i> {{-- Icon Tòa nhà --}}
            <div data-i18n="Supplier List">Nhà cung cấp</div>
        </a>
    </li>

    {{-- NHÓM NỘI DUNG & KHÁCH HÀNG --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Nội dung & Người dùng</span>
    </li>

    {{-- Khách hàng (Sửa lại link route) --}}
    <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a href="{{ route('admin.users.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Users">Khách hàng</div>
        </a>
    </li>

    {{-- Đánh giá & Nhận xét --}}
    <li class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
        <a href="{{ route('admin.reviews.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-message-rounded-dots text-warning"></i>
            <div data-i18n="Reviews">Đánh giá & Nhận xét</div>
        </a>
    </li>

    {{-- Mục Bài viết --}}
    <li class="menu-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <a href="{{ route('admin.posts.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-news"></i>
            <div data-i18n="Posts">Tin tức & Y khoa</div>
        </a>
    </li>

    {{-- Mục Video --}}
    <li class="menu-item {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
        <a href="{{ route('admin.videos.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-video"></i>
            <div data-i18n="Videos">Video</div>
        </a>
    </li>

    {{-- Mục FAQ --}}
    <li class="menu-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
        <a href="{{ route('admin.faqs.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-question-mark"></i>
            <div data-i18n="FAQs">Câu hỏi thường gặp</div>
        </a>
    </li>

    {{-- Mục Liên hệ --}}
    <li class="menu-item {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
        <a href="{{ route('admin.contacts.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-envelope"></i>
            <div data-i18n="Contacts">Liên hệ</div>
        </a>
    </li>

    {{-- NHÓM CẤU HÌNH --}}
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Hệ thống</span>
    </li>

    {{-- Banner / Slide --}}
    <li class="menu-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
        <a href="{{ route('admin.sliders.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-carousel"></i>
            <div data-i18n="Sliders">Banner Quảng cáo</div>
        </a>
    </li>

    {{-- Cài đặt chung --}}
    <li class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <a href="{{ route('admin.settings.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div data-i18n="Settings">Cài đặt chung</div>
        </a>
    </li>

  </ul>
</aside>
