<nav class="mobile-bottom-nav d-lg-none">
    <div class="bottom-nav-container">
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Trang chủ</span>
        </a>
        <a href="javascript:void(0)" class="nav-item" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas">
            <i class="fas fa-th-large"></i>
            <span>Danh mục</span>
        </a>
        <div class="nav-item-center">
            <a href="https://zalo.me/{{ preg_replace('/\D/', '', $global_setting->hotline ?? '0123456789') }}" target="_blank" class="center-raised-btn">
                <i class="fas fa-headset"></i>
            </a>
            <span class="center-label">Tư vấn</span>
        </div>
        <a href="{{ route('account.orders') }}" class="nav-item {{ request()->routeIs('account.orders') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>Đơn hàng</span>
        </a>
        <a href="{{ route('account.index') }}" class="nav-item {{ request()->routeIs('account.index') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span>Tài khoản</span>
        </a>
    </div>
</nav>
