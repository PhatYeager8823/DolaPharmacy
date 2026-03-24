<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    {{-- Giữ nguyên phần Head cũ của bạn --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - Quản trị {{ $global_setting->ten_website ?? 'System' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon/favicon.ico') }}" />

    {{-- Các link CSS giữ nguyên --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>

    <style>
        /* === NỀN TỔNG THỂ (BACKGROUND VŨ TRỤ) === */
        body, html, .layout-wrapper {
            background: linear-gradient(135deg, #050b14 0%, #0a1930 50%, #1e1b4b 100%) !important;
            background-attachment: fixed !important;
            color: #e2e8f0;
            min-height: 100vh;
        }

        /* Xóa trắng nền mặc định của template Sneat */
        .bg-menu-theme, .bg-navbar-theme, .content-wrapper, .layout-page {
            background-color: transparent !important;
        }

        /* === BẢNG ĐIỀU KHIỂN TRONG SUỐT (GLASSMORPHISM) === */
        .layout-menu, .navbar-detached, .card, .bg-white {
            background: rgba(255, 255, 255, 0.05) !important; /* Kính đen mờ */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
            color: #f8fafc !important;
        }

        /* Card bo góc và màu chữ */
        .card {
            border-radius: 16px !important;
            overflow: hidden;
            margin-bottom: 2rem !important;
        }
        .card-header, .card-footer {
            background-color: rgba(255,255,255,0.02) !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* Chữ trong giao diện */
        h1, h2, h3, h4, h5, h6, .text-heading, .card-title, .menu-link, .menu-header-text, span, p, label {
            color: #f8fafc !important; /* Trắng sáng */
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .text-muted { color: #94a3b8 !important; }

        /* Menu Sidebar Giao diện Active/Hover */
        .menu-link {
            color: #cbd5e1 !important;
        }
        .menu-icon {
            color: #60a5fa !important; /* Màu xanh nước biển nhạt */
        }
        .menu-link:hover, .menu-item.active > .menu-link {
            background-color: rgba(56, 189, 248, 0.15) !important; /* Xanh neon trong suốt */
            box-shadow: inset 4px 0 0 #38bdf8, 0 0 15px rgba(56, 189, 248, 0.2) !important;
            color: #fff !important;
            border-radius: 8px;
            margin: 0 8px;
            width: calc(100% - 16px);
        }
        .menu-item.active > .menu-link .menu-icon {
            color: #7dd3fc !important;
            text-shadow: 0 0 10px rgba(125, 211, 252, 0.8);
        }

        /* Nút button phát sáng - GIẢM ĐỘ CHÓI */
        .btn-primary {
            background: rgba(56, 189, 248, 0.7) !important; 
            border: 1px solid rgba(56, 189, 248, 0.5) !important;
            box-shadow: 0 2px 6px rgba(56, 189, 248, 0.3) !important;
            backdrop-filter: blur(5px);
            color: #fff !important;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background: rgba(56, 189, 248, 0.85) !important;
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.4) !important;
        }

        /* Giảm chói cho các loại nút khác */
        .btn-success, .btn-danger, .btn-warning, .btn-info {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2) !important;
        }
        .btn-success:hover, .btn-danger:hover, .btn-warning:hover, .btn-info:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3) !important;
        }

        /* Bảng Table trong suốt */
        .table {
            color: #e2e8f0 !important;
        }
        .table th {
            color: #38bdf8 !important; /* Text neon */
            background: rgba(255, 255, 255, 0.05) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table td {
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* Cải thiện Input/Select để hợp với nền mờ */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
        }

        /* Fix lỗi dropdown Option (Trình duyệt thường để nền trắng làm chữ bị lẫn) */
        option {
            background-color: #1e293b !important; /* Xanh Navy tối */
            color: #fff !important;
        }
        
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: #38bdf8 !important;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.5) !important;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .dropdown-item { color: #cbd5e1 !important; }
        .dropdown-item:hover { background: rgba(56, 189, 248, 0.2) !important; color: #fff !important; }

        /* Icon gốc svg trong dashboard - GIẢM ĐỘ CHÓI */
        svg { filter: drop-shadow(0 0 2px rgba(255,255,255,0.2)); }

        /* Ẩn bớt logo nền trắng trong layout mờ */
        .app-brand img { filter: drop-shadow(0 0 5px rgba(255,255,255,0.2)); }

        /* =========================================================
           ✨ CÁC HIỆU ỨNG CHUYỂN ĐỘNG (ANIMATIONS & TRANSITIONS) ✨
           ========================================================= */

        /* 1. Chuyển đổi mượt mà các trạng thái hover/click */
        .menu-link, .btn-primary, .card, .menu-icon, .dropdown-item, .form-control {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }

        /* 2. Hiệu ứng "nhún" (Scale Bounce) khi click chuột (tạo cảm giác chạm vật lý mượt) */
        .menu-link:active, .btn-primary:active, .dropdown-item:active {
            transform: scale(0.95);
        }

        /* Thẻ Card: Nổi bồng bềnh - GIẢM ĐỘ CHÓI */
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(56, 189, 248, 0.3) !important;
        }

        /* 3. Hiệu ứng mở đầu (Fade & Slide Up) khi vừa tải trang hoặc chuyển trang */
        @keyframes glassFadeIn {
            0% { opacity: 0; transform: translateY(30px) scale(0.98); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .content-wrapper > .container-xxl, .layout-menu {
            animation: glassFadeIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
        
        /* Hiệu ứng xoay và phình to nhẹ cho icon bên cạnh Text khi hover */
        .menu-link:hover .menu-icon {
            transform: scale(1.15) rotate(5deg);
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            {{-- 1. NHÚNG SIDEBAR --}}
            @include('admin.partials.sidebar')

            <div class="layout-page">

                {{-- 2. NHÚNG NAVBAR --}}
                @include('admin.partials.navbar')

                <div class="content-wrapper">

                    @yield('content')
                    {{-- 3. NHÚNG FOOTER --}}
                    @include('admin.partials.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                </div>
            </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    {{-- Các Script JS giữ nguyên --}}
    <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    <script src="{{ asset('admin/assets/js/dashboards-analytics.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    @stack('scripts')
</body>
</html>
