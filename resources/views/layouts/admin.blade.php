<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
  data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    {{-- Giữ nguyên phần Head cũ của bạn --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - Quản trị {{ $global_setting->ten_website ?? 'System' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />

    {{-- Các link CSS giữ nguyên --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    
    {{-- Flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>

    {{-- SweetAlert2 CDN (Admin Layout chưa có) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        window.showAlert = function(message, type = 'success') {
            const container = document.getElementById('notification-container');
            if (!container) return;
            
            const iconClass = type === 'success' ? 'fa-check' : (type === 'error' ? 'fa-exclamation-triangle' : 'fa-exclamation');
            const title = type === 'success' ? 'Thành công!' : (type === 'error' ? 'Lỗi!' : 'Thông báo!');
            
            const alertHtml = `
                <div class="alert custom-alert custom-alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show mb-3" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon-circle alert-icon-circle-${type === 'error' ? 'danger' : type}">
                            <i class="fa ${iconClass}"></i>
                        </div>
                        <div>
                            <span class="alert-title">${title}</span>
                            <span class="alert-msg">${message}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            const alertElement = document.createElement('div');
            alertElement.innerHTML = alertHtml;
            container.appendChild(alertElement);
            
            setTimeout(() => {
                const alert = alertElement.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        };
    </script>
    @stack('styles')

    <style>
        /* SỬA LỖI NỀN TRẮNG ĐÈ GIAO DIỆN (FORCE TRANSPARENT) */
        .bg-light, .bg-white, .input-group-text, .layout-menu, .navbar-detached, .card, .dropdown-menu {
            transition: none !important; /* Tắt transition khi load trang để tránh cảm giác "biến hình" */
        }
        
        .bg-light, .bg-white, .input-group-text {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        /* === SIÊU HIỆU ỨNG 3D TRONG SUỐT (PREMIUM GLASSMORPHISM) === */
        body, html {
            font-family: 'Quicksand', sans-serif !important;
            background-color: #e0f7ff !important;
            background-image: 
                radial-gradient(circle at top right, rgba(200, 245, 255, 0.6), transparent),
                radial-gradient(circle at bottom left, rgba(175, 230, 255, 0.6), transparent),
                linear-gradient(135deg, #7adfff 0%, #3ad4ff 40%, #0090e0 100%) !important;
            background-attachment: fixed !important;
            color: #ffffff !important;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0; padding: 0;
            transition: none !important;
        }

        .layout-wrapper { background: transparent !important; transition: none !important; }
        .bg-menu-theme, .bg-navbar-theme, .content-wrapper, .layout-page {
            background-color: transparent !important;
            transition: none !important;
        }

        /* Cố định Sidebar (Fixed) */
        .layout-menu {
            position: fixed !important;
            top: 20px !important;
            left: 20px !important;
            height: calc(100vh - 40px) !important;
            width: 260px !important;
            margin: 0 !important;
            border-radius: 24px !important;
            z-index: 1100 !important;
            background: rgba(20, 60, 140, 0.45) !important;
        }

        /* Nội dung chính */
        .layout-page {
            padding-left: 280px !important;
            padding-top: 0 !important;
            min-height: 100vh;
        }
        .navbar-detached {
            width: calc(100% - 40px) !important;
            margin: 20px 20px 20px 20px !important;
            background: rgba(20, 60, 140, 0.45) !important;
            z-index: 1050 !important;
            backdrop-filter: blur(25px) saturate(180%) !important;
        }

        /* FIX MÀU CHỮ TÌM KIẾM (ĐẢM BẢO NHÌN RÕ) */
        #admin-search-input {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.3) !important;
            width: 350px !important; /* Nới rộng để hiện Ctrl + K */
        }
        #admin-search-input::placeholder {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        .navbar-nav .bx-search {
            color: #ffffff !important;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5)) !important;
        }

        /* === 🌟 LOGO GLOW EFFECT (LIKE HOMEPAGE) 🌟 === */
        .app-brand-link img {
            /* Tăng độ sáng và làm màu xanh lá rực rỡ hơn */
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.8)) 
                    drop-shadow(0 0 8px rgba(46, 204, 113, 0.5)) 
                    brightness(1.15) 
                    saturate(1.3) !important;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        }
        .app-brand-link:hover img {
            filter: drop-shadow(0 0 18px #ffffff) 
                    drop-shadow(0 0 30px rgba(58, 212, 255, 0.5)) 
                    drop-shadow(0 0 15px rgba(46, 204, 113, 0.8))
                    brightness(1.25) 
                    saturate(1.5) !important;
            transform: scale(1.05) rotate(-1deg) !important;
        }

        /* Container cha của search cần nổi lên trên Overlay */
        .navbar-nav .nav-item.position-relative {
            z-index: 9999 !important;
            min-width: 400px; /* Đảm bảo đủ không gian cho input rộng */
        }

        /* Card & UI: Glassmorphism 3D */
        .layout-menu, .navbar-detached, .card, .dropdown-menu {
            background: rgba(25, 85, 175, 0.25) !important;
            backdrop-filter: blur(35px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(35px) saturate(180%) !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            box-shadow: 0 20px 60px rgba(0, 40, 90, 0.35) !important;
            border-radius: 24px !important;
        }

        .card:hover {
            transform: translateY(-10px) !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.45), inset 0 0 0 1px rgba(58, 212, 255, 0.5) !important;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
        }

        /* Chữ & Tiêu đề nổi khối */
        h1, h2, h3, h4, h5, h6, .text-heading, .card-title, .menu-link, .menu-header-text, p, label, span, td, th {
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4) !important;
            font-weight: 600 !important;
        }
        .text-muted { color: rgba(255, 255, 255, 0.8) !important; }
        .text-primary { color: #00f2fe !important; font-weight: 800 !important; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5) !important; }

        /* ===================================================================
           🌟 FORM CONTENT VISIBILITY — Nổi bật nội dung trên nền xanh 🌟
           =================================================================== */

        /* --- Card Header: Phân cách rõ ràng --- */
        .card-header {
            background: rgba(0, 0, 0, 0.2) !important;
            border-bottom: 1px solid rgba(58, 212, 255, 0.4) !important;
            padding: 1rem 1.5rem !important;
        }
        .card-header h5, .card-header h4 {
            color: #3ad4ff !important;
            text-shadow: 0 0 12px rgba(58, 212, 255, 0.6) !important;
            font-weight: 700 !important;
            letter-spacing: 0.3px !important;
        }

        /* --- Form Label: Nổi bật, dễ đọc --- */
        .form-label {
            color: #e0f7ff !important;
            font-weight: 700 !important;
            font-size: 0.82rem !important;
            letter-spacing: 0.6px !important;
            text-transform: uppercase !important;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.5) !important;
            margin-bottom: 6px !important;
        }
        .form-label .text-danger {
            color: #ff6b6b !important;
            text-shadow: 0 0 8px rgba(255, 107, 107, 0.6) !important;
        }

        /* --- Input & Textarea: Viền sáng, nền bán trong suốt --- */
        .form-control {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1.5px solid rgba(58, 212, 255, 0.45) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            transition: all 0.25s ease !important;
            backdrop-filter: blur(4px) !important;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.45) !important;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.18) !important;
            border-color: #3ad4ff !important;
            box-shadow: 0 0 0 3px rgba(58, 212, 255, 0.25), 0 0 12px rgba(58, 212, 255, 0.15) !important;
            color: #ffffff !important;
            outline: none !important;
        }
        .form-control:disabled, .form-control[readonly] {
            background-color: rgba(0, 0, 0, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: rgba(255, 255, 255, 0.5) !important;
            cursor: not-allowed !important;
        }
        textarea.form-control {
            min-height: 90px !important;
            resize: vertical !important;
        }

        /* --- Input Group Text (prefix/suffix) --- */
        .input-group-text {
            background-color: rgba(58, 212, 255, 0.15) !important;
            border: 1.5px solid rgba(58, 212, 255, 0.45) !important;
            color: #3ad4ff !important;
            font-weight: 700 !important;
        }

        /* --- Form Check / Switch: Label rõ hơn --- */
        .form-check-label {
            color: #e0f7ff !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4) !important;
        }
        /* Glow màu theo trạng thái */
        .form-check-label.text-success { color: #4ade80 !important; text-shadow: 0 0 8px rgba(74, 222, 128, 0.5) !important; }
        .form-check-label.text-danger  { color: #ff6b6b !important; text-shadow: 0 0 8px rgba(255, 107, 107, 0.5) !important; }
        .form-check-label.text-warning { color: #fbbf24 !important; text-shadow: 0 0 8px rgba(251, 191, 36, 0.5) !important; }
        .form-check-label.text-info    { color: #38bdf8 !important; text-shadow: 0 0 8px rgba(56, 189, 248, 0.5) !important; }
        .form-check-label.text-primary { color: #818cf8 !important; text-shadow: 0 0 8px rgba(129, 140, 248, 0.5) !important; }
        .form-check-label.text-secondary { color: rgba(255,255,255,0.7) !important; }

        /* --- HR Divider trong card --- */
        .card hr {
            border-color: rgba(58, 212, 255, 0.25) !important;
            opacity: 1 !important;
        }

        /* --- Table: Row dễ đọc trên nền xanh --- */
        .table {
            color: #ffffff !important;
        }
        .table thead th {
            background: rgba(0, 0, 0, 0.25) !important;
            color: #3ad4ff !important;
            border-color: rgba(58, 212, 255, 0.3) !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            text-shadow: 0 0 10px rgba(58, 212, 255, 0.4) !important;
        }
        .table tbody td {
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #f0f9ff !important;
            vertical-align: middle !important;
        }
        .table tbody tr:hover td {
            background: rgba(58, 212, 255, 0.08) !important;
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background: rgba(0, 0, 0, 0.12) !important;
            color: #f0f9ff !important;
        }
        .table-light thead th {
            background: rgba(0, 20, 60, 0.35) !important;
            color: #7dd3fc !important;
        }

        /* --- Badges / Pills: Nổi màu rõ --- */
        .badge {
            font-weight: 700 !important;
            padding: 5px 10px !important;
            border-radius: 20px !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.3px !important;
        }
        .badge.bg-success  { background: #16a34a !important; box-shadow: 0 2px 8px rgba(22, 163, 74, 0.5) !important; }
        .badge.bg-danger   { background: #dc2626 !important; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.5) !important; }
        .badge.bg-warning  { background: #d97706 !important; box-shadow: 0 2px 8px rgba(217, 119, 6, 0.5) !important; }
        .badge.bg-info     { background: #0284c7 !important; box-shadow: 0 2px 8px rgba(2, 132, 199, 0.5) !important; }
        .badge.bg-primary  { background: #4f46e5 !important; box-shadow: 0 2px 8px rgba(79, 70, 229, 0.5) !important; }
        .badge.bg-secondary{ background: rgba(255,255,255,0.18) !important; color: #fff !important; }
        .badge.bg-label-success { background: rgba(22,163,74,0.2) !important; color: #4ade80 !important; border: 1px solid rgba(74,222,128,0.4) !important; }
        .badge.bg-label-danger  { background: rgba(220,38,38,0.2) !important;  color: #ff6b6b !important; border: 1px solid rgba(255,107,107,0.4) !important; }
        .badge.bg-label-warning { background: rgba(217,119,6,0.2) !important;  color: #fbbf24 !important; border: 1px solid rgba(251,191,36,0.4) !important; }
        .badge.bg-label-info    { background: rgba(2,132,199,0.2) !important;   color: #38bdf8 !important; border: 1px solid rgba(56,189,248,0.4) !important; }
        .badge.bg-label-primary { background: rgba(79,70,229,0.2) !important;  color: #818cf8 !important; border: 1px solid rgba(129,140,248,0.4) !important; }

        /* --- Alert messages trong card --- */
        .alert {
            border-radius: 10px !important;
            border-left-width: 4px !important;
        }
        .alert-danger  { background: rgba(220,38,38,0.15) !important;  border-color: #dc2626 !important; color: #fca5a5 !important; }
        .alert-success { background: rgba(22,163,74,0.15) !important;  border-color: #16a34a !important; color: #86efac !important; }
        .alert-warning { background: rgba(217,119,6,0.15) !important;  border-color: #d97706 !important; color: #fde68a !important; }
        .alert-info    { background: rgba(2,132,199,0.15) !important;   border-color: #0284c7 !important; color: #7dd3fc !important; }
        .alert span, .alert li, .alert strong, .alert p {
            color: inherit !important;
            text-shadow: none !important;
        }

        /* ICON SIDEBAR */
        .menu-link .menu-icon { font-size: 1.4rem !important; transition: all 0.3s ease !important; }
        .menu-item .bx-home-circle { color: #00f2fe !important; text-shadow: 0 0 10px rgba(0, 242, 254, 0.5); }
        .menu-item .bx-receipt { color: #fde047 !important; text-shadow: 0 0 10px rgba(253, 224, 71, 0.5); }
        .menu-item .bx-package { color: #ff9f43 !important; text-shadow: 0 0 10px rgba(255, 159, 67, 0.5); }
        .menu-item .bx-download { color: #4ade80 !important; text-shadow: 0 0 10px rgba(74, 222, 128, 0.5); }
        .menu-item .bx-category { color: #a855f7 !important; text-shadow: 0 0 10px rgba(168, 85, 247, 0.5); }
        .menu-item .bx-gift { color: #ec4899 !important; text-shadow: 0 0 10px rgba(236, 72, 153, 0.5); }
        .menu-item .bx-store { color: #3b82f6 !important; text-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
        .menu-item .bx-buildings { color: #94a3b8 !important; text-shadow: 0 0 10px rgba(148, 163, 184, 0.5); }
        .menu-item .bx-user { color: #22d3ee !important; text-shadow: 0 0 10px rgba(34, 211, 238, 0.5); }
        .menu-item .bx-message-rounded-dots { color: #f59e0b !important; text-shadow: 0 0 10px rgba(245, 158, 11, 0.5); }
        .menu-item .bx-news { color: #10b981 !important; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5); }
        .menu-item .bx-video { color: #ef4444 !important; text-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }
        .menu-item .bx-question-mark { color: #facc15 !important; text-shadow: 0 0 12px rgba(250, 204, 21, 0.6); }
        .menu-item .bx-envelope { color: #8b5cf6 !important; text-shadow: 0 0 10px rgba(139, 92, 246, 0.5); }
        .menu-item .bx-carousel { color: #fb7185 !important; text-shadow: 0 0 10px rgba(251, 113, 133, 0.5); }
        .menu-item .bx-cog { color: #cbd5e1 !important; text-shadow: 0 0 10px rgba(203, 213, 225, 0.5); }

        .menu-item:hover .menu-icon {
            transform: scale(1.25) rotate(8deg) !important;
        }

        /* --- ⚡ PREMIUM BUTTON ICON HOVER EFFECTS ⚡ --- */
        .btn-icon {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
        }

        .btn-icon:hover {
            transform: scale(1.22) rotate(8deg) !important;
            z-index: 10;
        }

        /* Phát sáng theo màu của Button Label */
        .btn-label-danger:hover {
            box-shadow: 0 0 20px rgba(255, 62, 29, 0.6) !important;
            background-color: #ff3e1d !important;
            color: #fff !important;
        }

        .btn-label-warning:hover {
            box-shadow: 0 0 20px rgba(255, 171, 0, 0.6) !important;
            background-color: #ffab00 !important;
            color: #fff !important;
        }

        .btn-label-success:hover {
            box-shadow: 0 0 20px rgba(113, 221, 55, 0.6) !important;
            background-color: #71dd37 !important;
            color: #fff !important;
        }

        .btn-label-primary:hover {
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.6) !important;
            background-color: #00f2fe !important;
            color: #fff !important;
        }

        .btn-label-info:hover {
            box-shadow: 0 0 20px rgba(3, 195, 236, 0.6) !important;
            background-color: #03c3ec !important;
            color: #fff !important;
        }
        
        .menu-item:hover .menu-icon {
            filter: brightness(1.4) drop-shadow(0 0 5px currentColor) !important;
        }

        /* NÚT BẤM */
        .btn-outline-primary {
            border-color: #3ad4ff !important;
            color: #3ad4ff !important;
            background: rgba(58, 212, 255, 0.05) !important;
        }
        .btn-outline-primary:hover {
            background: #3ad4ff !important;
            color: #0d3a6e !important;
            box-shadow: 0 0 20px rgba(58, 212, 255, 0.6) !important;
            transition: all 0.3s ease !important;
        }

        /* SIDEBAR ACTIVE */
        .menu-item.active > .menu-link {
            background: rgba(58, 212, 255, 0.12) !important;
            color: #ffffff !important;
            box-shadow: 0 0 20px rgba(58, 212, 255, 0.2), inset 0 0 0 1px rgba(58, 212, 255, 0.4) !important;
            border-left: 4px solid #3ad4ff !important;
        }

        /* DROPDOWN MENU FIX */
        html.light-style .dropdown-menu,
        html.light-style .dropdown-menu-end,
        html.light-style .navbar-dropdown .dropdown-menu {
            background: rgba(15, 35, 75, 0.96) !important;
            border: 1px solid rgba(58, 212, 255, 0.35) !important;
            backdrop-filter: blur(30px) saturate(150%) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
            padding: 8px !important;
            /* Hiệu ứng trượt chuyên nghiệp */
            animation: adminDropdownSlideIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.15) forwards !important;
            transform-origin: top center;
        }

        @keyframes adminDropdownSlideIn {
            from {
                opacity: 0;
                transform: translateY(-15px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        html.light-style .dropdown-item {
            color: #ffffff !important;
            border-radius: 10px !important;
            margin: 4px 0 !important;
            padding: 10px 16px !important;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
            display: flex !important;
            align-items: center !important;
        }

        html.light-style .dropdown-item i {
            font-size: 1.2rem !important;
            margin-right: 12px !important;
            transition: all 0.2s ease !important;
            color: inherit !important;
        }

        html.light-style .dropdown-item:hover,
        html.light-style .dropdown-item:focus,
        html.light-style .dropdown-item.active {
            background-color: rgba(58, 212, 255, 0.2) !important;
            color: #3ad4ff !important;
            transform: translateX(8px) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        html.light-style .dropdown-item:hover i {
            transform: scale(1.2) rotate(8deg);
        }

        /* Riêng mục Đăng xuất trong Admin - Rực rỡ hơn */
        html.light-style .dropdown-item.text-danger:hover {
            background-color: rgba(255, 62, 29, 0.25) !important;
            color: #ff3e1d !important;
            border-left: 3px solid #ff3e1d;
        }
        /* KHUNG GỢI Ý TÌM KIẾM (Cyber-Glass) */
        .search-results-container {
            position: absolute;
            top: 100%;
            left: 0;
            width: 400px;
            z-index: 9999;
            background: rgba(10, 25, 50, 0.96) !important;
            backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid rgba(58, 212, 255, 0.4) !important;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            max-height: 450px;
            overflow-y: auto;
            display: none;
            margin-top: 10px;
            padding: 10px 0;
        }
        .search-result-item {
            display: flex;
            align-items: center;
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none !important;
        }
        .search-result-item:hover, .search-result-item.selected {
            background: rgba(58, 212, 255, 0.15) !important;
            padding-left: 25px;
        }
        .search-result-item i {
            font-size: 1.4rem;
            margin-right: 15px;
            color: #3ad4ff;
        }
        .menu-path {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            display: block;
        }
        #search-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height : 100vh;
            background: rgba(0,0,0,0.15);
            backdrop-filter: blur(1px);
            z-index: 9998;
            display: none;
        }

        /* --- MODERN TOAST NOTIFICATION STYLES --- */
        #notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 999999;
            width: 350px;
            max-width: calc(100vw - 40px);
            pointer-events: none;
        }

        .custom-alert {
            pointer-events: auto;
            background: rgba(15, 35, 75, 0.92) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-left: 5px solid #007aff !important;
            border-radius: 12px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4) !important;
            padding: 15px 20px !important;
            margin-bottom: 12px !important;
            animation: slideInRight 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            color: white !important;
        }

        /* --- CYBER SWEETALERT CUSTOM STYLES --- */
        .cyber-swal-popup {
            font-family: 'Quicksand', sans-serif !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            box-shadow: none !important;
            backdrop-filter: blur(15px) saturate(180%) !important;
            border-radius: 12px !important;
            background: rgba(15, 35, 75, 0.98) !important;
            width: 360px !important; /* RỘNG VỪA ĐỦ ĐỂ KHÔNG BỊ TRÀN/CẮT */
            padding: 15px !important;
        }
        .cyber-swal-title {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
            margin: 0 !important;
            padding: 0 !important;
            text-align: left !important;
        }
        .cyber-swal-text {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0 !important;
        }
        .cyber-swal-confirm {
            background: #1b5e20 !important;
            box-shadow: 0 5px 15px rgba(27, 94, 32, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 10px 25px !important;
            font-weight: 700 !important;
            border-radius: 10px !important;
            color: #ffffff !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 auto !important; /* CĂN GIỮA NÚT */
            display: block !important;
        }
        .cyber-swal-confirm:hover { 
            transform: translateY(-3px) scale(1.05); 
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.5) !important;
            background: #2e7d32 !important;
        }
        .cyber-swal-cancel {
            background: rgba(12, 74, 110, 0.1) !important;
            border: 1px solid rgba(12, 74, 110, 0.2) !important;
            color: #0c4a6e !important;
            padding: 14px 35px !important;
            margin: 15px !important;
            font-weight: 600 !important;
            border-radius: 12px !important;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .cyber-swal-confirm:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 8px 25px rgba(255, 62, 29, 0.6) !important; }
        .cyber-swal-cancel:hover { background: rgba(133, 146, 163, 0.45) !important; transform: translateY(-2px); }

        .custom-alert::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0, 122, 255, 0.3);
            animation: toastProgress 5s linear forwards;
        }

        .alert-icon-circle {
            width: 40px;
            height: 40px;
            background: rgba(0, 122, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: #007aff;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .alert-title {
            display: block;
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff !important;
            margin-bottom: 2px;
        }

        .alert-msg {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .custom-alert-success {
            border-left-color: #2ecc71 !important;
        }
        .custom-alert-success .alert-icon-circle { background: rgba(46, 204, 113, 0.15); color: #2ecc71; }
        .custom-alert-success::before { background: #2ecc71; }

        .custom-alert-danger {
            border-left-color: #e74c3c !important;
        }
        .custom-alert-danger .alert-icon-circle { background: rgba(231, 76, 60, 0.15); color: #e74c3c; }
        .custom-alert-danger::before { background: #e74c3c; }

        .custom-alert-warning {
            border-left-color: #f1c40f !important;
        }
        .custom-alert-warning .alert-icon-circle { background: rgba(241, 196, 15, 0.15); color: #f1c40f; }
        .custom-alert-warning::before { background: #f1c40f; }

        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* ===== DROPDOWN FIX TOÀN CỤC: Ngăn inherit nền xanh từ card glassmorphism ===== */

        /* --- 1. form-select THUẦN (không dùng Select2) --- */
        .form-select {
            background-color: #fff !important;
            color: #435971 !important;
            border: 1px solid #d9dee3 !important;
        }
        .form-select:focus {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25) !important;
            background-color: #fff !important;
            color: #435971 !important;
        }
        /* Fix màu các option bên trong */
        .form-select option {
            background-color: #fff !important;
            color: #435971 !important;
        }

        /* --- 2. SELECT2: Selection box (ô hiển thị đang chọn) --- */
        .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: 1px solid #d9dee3 !important;
            border-radius: 6px !important;
            height: 38px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #435971 !important;
            line-height: 38px !important;
            padding-left: 12px !important;
            padding-right: 30px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            top: 1px !important;
            right: 4px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #aab3bd !important;
        }
        /* Khi focus/mở */
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #696cff !important;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25) !important;
            outline: none !important;
        }

        /* --- 3. SELECT2: Dropdown popup list --- */
        .select2-dropdown {
            background-color: #fff !important;
            border: 1px solid #d9dee3 !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            z-index: 99999 !important;
        }
        .select2-search--dropdown {
            background-color: #fff !important;
            padding: 8px !important;
        }
        .select2-search--dropdown .select2-search__field {
            background-color: #f8f9fa !important;
            border: 1px solid #d9dee3 !important;
            color: #435971 !important;
            border-radius: 4px !important;
            padding: 6px 10px !important;
        }
        .select2-results {
            background-color: #fff !important;
        }
        .select2-results__option {
            color: #435971 !important;
            background-color: #fff !important;
            padding: 8px 12px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #696cff !important;
            color: #fff !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e7e7ff !important;
            color: #696cff !important;
        }
        .select2-results__option--disabled {
            color: #aab3bd !important;
        }

        /* --- 4. Cover class .select2-product (dùng trong phiếu nhập kho) --- */
        .select2-product + .select2-container--default .select2-selection--single,
        .select2-container--default.select2-product-container .select2-selection--single {
            background-color: #fff !important;
            color: #435971 !important;
        }
    </style>
</head>

<body>
    <div id="notification-container"></div>
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
    {{-- Flatpickr --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>

    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tắt kiểm tra chính tả trình duyệt trên toàn bộ input/textarea
            $('input, textarea').attr('spellcheck', 'false');

            // Tự khởi tạo Select2 cho các trường có class .select2
            $('.select2').select2({
                width: '100%',
                language: {
                    noResults: function () {
                        return "Không tìm thấy kết quả nào";
                    }
                }
            });
        });
    </script>

    {{-- Chart.js (dùng cho trang Báo cáo) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @stack('scripts')

    <script>
        // --- HÀM XÁC NHẬN XÓA GLOBAL (SWEETALERT2) ---
        $(document).ready(function() {
            $(document).on('click', '.delete-confirm-btn', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const message = $(this).data('message') || 'Dữ liệu này sẽ bị xóa vĩnh viễn và không thể khôi phục!';
                
                Swal.fire({
                    title: 'Xác nhận xóa 🛑',
                    text: message,
                    icon: 'warning',
                    iconColor: '#ff3e1d',
                    showCancelButton: true,
                    confirmButtonText: 'CÓ, XÓA NGAY! 🚀',
                    cancelButtonText: 'Đóng lại',
                    customClass: {
                        popup: 'cyber-swal-popup',
                        title: 'cyber-swal-title',
                        htmlContainer: 'cyber-swal-text',
                        confirmButton: 'cyber-swal-confirm',
                        cancelButton: 'cyber-swal-cancel'
                    },
                    buttonsStyling: false,
                    buttonsStyling: false,
                    background: 'rgba(224, 242, 254, 0.98)',
                    color: '#0c4a6e',
                    backdrop: `rgba(0, 0, 0, 0.3)`
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // --- TỰ ĐỘNG LƯU VÀ KHÔI PHỤC TAB ĐANG CHỌN (BOOTSTRAP 5) ---
        $(document).ready(function() {
            // 1. Khi một tab được bấm, lưu ID của tab đó vào localStorage theo URL hiện tại
            $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function (e) {
                const tabId = $(e.target).attr('data-bs-target');
                const path = window.location.pathname;
                localStorage.setItem('activeTab_' + path, tabId);
            });

            // 2. Khi tải lại trang, kiểm tra xem có tab nào được lưu cho URL này không
            const path = window.location.pathname;
            const activeTabId = localStorage.getItem('activeTab_' + path);
            if (activeTabId) {
                const tabTriggerEl = document.querySelector(`button[data-bs-target="${activeTabId}"]`);
                if (tabTriggerEl) {
                    const tab = new bootstrap.Tab(tabTriggerEl);
                    tab.show();
                }
            }
        });

        // --- 🔍 QUICK SEARCH NAVIGATION SCRIPT (COMPATIBILITY FIX) 🔍 ---
        $(document).ready(function() {
            const $searchInput = $('#admin-search-input');
            const $resultsContainer = $('#search-results');
            if ($searchInput.length === 0 || $resultsContainer.length === 0) return;

            let menuItems = [];
            let selectedIndex = -1;

            // 1. Thu thập dữ liệu menu
            function mapSidebarMenu() {
                menuItems = [];
                $('.menu-inner .menu-link').each(function() {
                    const $link = $(this);
                    const href = $link.attr('href');
                    
                    if (href && href !== 'javascript:void(0);' && href !== '#') {
                        // Lấy tên nhãn
                        let name = $link.find('div, span').filter(function() {
                            return !$(this).hasClass('menu-icon') && !$(this).hasClass('badge');
                        }).first().text().trim();
                        
                        if (!name) name = $link.contents().filter(function() {
                            return this.nodeType === 3;
                        }).text().trim();

                        // Lấy Icon class
                        const $icon = $link.find('.menu-icon');
                        const iconClass = $icon.length ? $icon.attr('class') : 'bx bx-circle';

                        // Lấy Nhóm (Header)
                        const $header = $link.closest('.menu-item').prevAll('.menu-header').first();
                        const group = $header.length ? $header.find('.menu-header-text').text().trim() : "";

                        if (name) {
                            menuItems.push({ name, href, icon: iconClass, group });
                        }
                    }
                });
            }

            console.log("🔍 Quick Search: Đang quét menu...");
            mapSidebarMenu();
            console.log("✅ Quick Search: Đã quét xong " + menuItems.length + " mục.");

            // 2. Xử lý gõ phím tìm kiếm
            $searchInput.on('input', function() {
                const val = $(this).val().toLowerCase().trim();
                selectedIndex = -1;
                
                if (val.length < 1) {
                    hideResults();
                    return;
                }

                const filtered = menuItems.filter(item => 
                    item.name.toLowerCase().includes(val) || 
                    item.group.toLowerCase().includes(val)
                );

                renderResults(filtered);
            });

            function showResults() {
                $resultsContainer.addClass('active').show();
                $('#search-overlay').addClass('active').fadeIn(200);
            }

            function hideResults() {
                $resultsContainer.removeClass('active').fadeOut(200);
                $('#search-overlay').removeClass('active').fadeOut(200);
            }

            function renderResults(items) {
                if (items.length === 0) {
                    $resultsContainer.html('<div class="no-results">Không tìm thấy mục nào khớp...</div>');
                } else {
                    let html = items.map((item, index) => `
                        <a href="${item.href}" class="search-result-item" data-index="${index}">
                            <i class="${item.icon}"></i>
                            <div>
                                <span class="d-block fw-bold" style="font-size: 0.85rem;">${item.name}</span>
                                ${item.group ? `<span class="menu-path">${item.group}</span>` : ''}
                            </div>
                        </a>
                    `).join('');
                    $resultsContainer.html(html);
                }
                showResults();
            }

            // 3. Xử lý phím mũi tên và Enter
            $searchInput.on('keydown', function(e) {
                const $items = $resultsContainer.find('.search-result-item');
                
                if (e.which === 40) { // Down
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, $items.length - 1);
                    updateSelection($items);
                } else if (e.which === 38) { // Up
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, 0);
                    updateSelection($items);
                } else if (e.which === 13) { // Enter
                    if (selectedIndex > -1 && $items[selectedIndex]) {
                        e.preventDefault();
                        window.location.href = $($items[selectedIndex]).attr('href');
                    } else if ($items.length > 0) {
                        e.preventDefault();
                        window.location.href = $($items[0]).attr('href');
                    }
                } else if (e.which === 27) { // ESC
                    hideResults();
                    $(this).blur();
                }
            });

            function updateSelection($items) {
                $items.removeClass('selected');
                if (selectedIndex > -1) {
                    const $selected = $($items[selectedIndex]);
                    $selected.addClass('selected');
                    const container = $resultsContainer[0];
                    const item = $selected[0];
                    if (item.offsetTop < container.scrollTop || (item.offsetTop + item.offsetHeight) > (container.scrollTop + container.offsetHeight)) {
                        container.scrollTop = item.offsetTop - 10;
                    }
                }
            }

            // Phím tắt Ctrl + K
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && (e.which === 75 || e.key === 'k')) {
                    e.preventDefault();
                    $searchInput.focus();
                }
            });

            // Khi focus vào input
            $searchInput.on('focus', function() {
                if ($(this).val().trim().length > 0) {
                    showResults();
                }
            });

            // Đóng khi click ngoài
            $(document).on('mousedown', function(e) {
                if (!$searchInput.is(e.target) && !$resultsContainer.is(e.target) && $resultsContainer.has(e.target).length === 0) {
                    hideResults();
                }
            });
        });

        // --- 🔔 REAL-TIME ORDER NOTIFICATION SCRIPT 🔔 ---
        $(document).ready(function() {
            function checkNewOrders() {
                $.ajax({
                    url: "{{ route('admin.api.check_new_orders') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success && response.count > 0) {
                            // PHÁT TIẾNG "TINH TINH" (LUÔN THỬ PHÁT, BROWSER SẼ TỰ HANDLE CHẶN/MỞ)
                            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                            audio.volume = 0.8;
                            audio.play().catch(e => {
                                console.log("Sound play error (Interaction required):", e);
                                // Dự phòng: Nếu bị chặn, thử phát lại khi user click
                                $(document).one('click.audioFallback', function() {
                                    audio.play().catch(e => {});
                                });
                            });

                            response.orders.forEach(function(order) {
                                Swal.fire({
                                    title: '🎉 ĐƠN HÀNG MỚI!',
                                    html: `
                                        <div style="text-align: center; color: white; margin-top: 10px;">
                                            <div style="margin-bottom: 8px; font-size: 0.95rem;">
                                                <i class="fa fa-user-circle me-1" style="color: #00f2fe;"></i> 
                                                Khách: <b style="color: #00f2fe">${order.ten_nguoi_nhan}</b>
                                            </div>
                                            <div style="margin-bottom: 8px; font-size: 0.95rem;">
                                                <i class="fa fa-hashtag me-1" style="color: #fde047;"></i> 
                                                Mã: <b>${order.ma_don_hang}</b>
                                            </div>
                                            <div style="margin-bottom: 15px; font-size: 1.1rem;">
                                                <i class="fa fa-wallet me-1" style="color: #4ade80;"></i> 
                                                Tiền: <b style="color: #4ade80">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.tong_tien)}</b>
                                            </div>
                                        </div>
                                    `,
                                    icon: 'success',
                                    iconColor: '#2ecc71',
                                    toast: true,
                                    position: 'top-end', // TỪ BÊN PHẢI CHẠY RA
                                    showConfirmButton: true,
                                    confirmButtonText: 'Xem ngay 🚀',
                                    confirmButtonColor: '#1b5e20',
                                    showCloseButton: true,
                                    timer: 15000,
                                    timerProgressBar: true,
                                    width: '360px',
                                    background: 'rgba(15, 35, 75, 0.98)',
                                    color: '#ffffff',
                                    padding: '1rem',
                                    customClass: {
                                        popup: 'cyber-swal-popup',
                                        actions: 'justify-content-center w-100' // CĂN GIỮA NÚT TRONG BOX
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = `/quan-tri/orders/${order.id}`;
                                    }
                                });
                            });
                        }
                    },
                    error: function(err) {
                        console.error("Lỗi polling đơn hàng:", err);
                    }
                });
            }

            // CSS bổ sung cho viền thông báo phát sáng
            $('<style>').prop('type', 'text/css').html(`
                .cyber-toast-border {
                    border: 1px solid #00f2fe !important;
                    box-shadow: 0 0 15px rgba(0, 242, 254, 0.4) !important;
                    backdrop-filter: blur(15px) !important;
                }
            `).appendTo('head');

            // Kiểm tra lần đầu sau 2 giây (Nhanh hơn)
            setTimeout(checkNewOrders, 2000);
            
            // TỰ ĐỘNG MỞ KHÓA ÂM THANH KHI USER CLICK VÀO TRANG LẦN ĐẦU (NÂNG CẤP)
            $(document).on('click.unmute', function() {
                window.audioContextUnmuted = true;
                const probe = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                probe.volume = 0;
                probe.play().then(() => {
                    console.log("🔊 Audio Context Unlocked!");
                    $(document).off('click.unmute'); // Gỡ bỏ listener sau khi đã unlock
                }).catch(e => {});
            });

            // Gửi yêu cầu lặp lại mỗi 5 giây (Gần như thời gian thực)
            setInterval(checkNewOrders, 5000);
        });
    </script>

    {{-- GLOBAL TOAST TRIGGER SCRIPT --}}
    @if(session('success') || session('error') || session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    showAlert("{!! addslashes(session('success')) !!}", 'success');
                @endif
                @if(session('error'))
                    showAlert("{!! addslashes(session('error')) !!}", 'error');
                @endif
                @if(session('warning'))
                    showAlert("{!! addslashes(session('warning')) !!}", 'warning');
                @endif
            });
        </script>
    @endif
</body>
</html>
