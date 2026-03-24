<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center" id="layout-navbar">
<div class="navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" onclick="document.documentElement.classList.toggle('layout-menu-expanded');">
      <i class="bx bx-menu bx-sm"></i>
    </a>
</div>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <div class="navbar-nav align-items-center">
    <div class="nav-item d-flex align-items-center">
        <i class="bx bx-search fs-4 lh-0 me-2"></i>
        <input
        type="text"
        class="form-control border-0 shadow-none"
        placeholder="Tìm kiếm..."
        aria-label="Search..."
        />
    </div>
    </div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="{{ asset('admin/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
        </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item" href="#">
            <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                    <img src="{{ asset('admin/assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                </div>
                </div>
                <div class="flex-grow-1">
                <span class="fw-semibold d-block">{{ Auth::user()->ten ?? 'Admin' }}</span>
                <small class="text-muted">Quản trị viên</small>
                </div>
            </div>
            </a>
        </li>
        <li><div class="dropdown-divider"></div></li>
        <li>
            <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
            <i class="bx bx-globe me-2"></i>
            <span class="align-middle">Xem website</span>
            </a>
        </li>
        <li><div class="dropdown-divider"></div></li>
        <li>
            {{-- Nút đăng xuất --}}
            <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle">Đăng xuất</span>
            </a>
            <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
        </ul>
    </li>
    </ul>
</div>
</nav>
