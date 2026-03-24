{{-- resources/views/partials/account-sidebar.blade.php --}}

{{-- 1. Thẻ thành viên VÀNG --}}
<div class="card text-white mb-3 shadow-sm {{ $membership['color'] }}"
     style="border: none; border-radius: 15px; background-image: linear-gradient(45deg, rgba(255,255,255,0.15), transparent);">

    {{-- SỬA LẠI: Đổi p-4 thành p-3 để rộng chỗ hơn --}}
    <div class="card-body p-3">

        <div class="d-flex align-items-center mb-3">
            <div class="avatar p-2 bg-white bg-opacity-25 rounded-circle me-3">
                <i class="fa fa-crown fs-3 text-white"></i>
            </div>
            <div>
                <small class="text-white-50 text-uppercase fw-bold">Hạng hiện tại</small>
                <h3 class="mb-0 fw-bold">{{ $membership['current_rank'] }}</h3>
            </div>
        </div>

        {{-- Thanh tiến độ --}}
        <div class="progress bg-white bg-opacity-25" style="height: 6px; border-radius: 10px;">
            <div class="progress-bar bg-white"
                role="progressbar"
                style="width: {{ $membership['percent'] }}%;"
                aria-valuenow="{{ $membership['percent'] }}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>

        {{-- Dòng chữ nhắc nhở bên dưới --}}
        {{-- CÁCH 1: Căn giữa (Thay text-end bằng text-center) --}}
        <div class="mt-2 text-center" style="font-size: 13px;">
            @if($membership['next_rank'] == 'MAX')
                <span>🏆 Bạn đã đạt cấp độ cao nhất!</span>
            @else
                <span>
                    Chi thêm <strong>{{ number_format($membership['remaining']) }}đ</strong>
                    để lên <strong>{{ $membership['next_rank'] }}</strong>
                </span>
            @endif
        </div>

    </div>
</div>

{{-- 2. Menu danh sách --}}
<div class="bg-white rounded-3 shadow-sm p-3">

    {{-- Thông tin cá nhân --}}
    <a href="{{ route('account.index') }}"
       class="menu-item {{ request()->routeIs('account.index') ? 'active' : '' }}">
        <i class="fa fa-user"></i> Thông tin cá nhân
    </a>

    {{-- Sổ địa chỉ (Chưa làm -> để #) --}}
    <a href="{{ route('address.index') }}"
        class="menu-item {{ request()->routeIs('address.index') ? 'active' : '' }}">
        <i class="fa fa-map-marker-alt"></i> Sổ địa chỉ nhận hàng
    </a>

    {{-- Lịch sử đơn hàng --}}
    <a href="{{ route('account.orders') }}"
       class="menu-item {{ request()->routeIs('account.orders') ? 'active' : '' }}">
        <i class="fa fa-file-alt"></i> Lịch sử đơn hàng
    </a>

    <a href="{{ route('account.coupons') }}"
    class="menu-item {{ request()->routeIs('account.coupons') ? 'active' : '' }}">
        <i class="fas fa-tags"></i> Mã giảm giá
    </a>

    {{-- Thông báo (Vừa làm xong) --}}
    <a href="{{ route('notifications.index') }}"
       class="menu-item {{ request()->routeIs('notifications.index') ? 'active' : '' }}">
        <i class="fa fa-bell"></i> Thông báo của tôi

        {{-- Badge số lượng chưa đọc (Optional) --}}
        @php
            $unread = \App\Models\ThongBao::where('nguoi_dung_id', Auth::id())->where('da_xem', 0)->count();
        @endphp
        @if($unread > 0)
            <span class="badge bg-danger ms-auto rounded-pill">{{ $unread }}</span>
        @endif
    </a>

    <hr class="my-2 text-muted">

    {{-- Đăng xuất --}}
    <a href="{{ route('logout') }}" class="menu-item text-danger"
       onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
        <i class="fa fa-sign-out-alt"></i> Đăng xuất
    </a>
    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
