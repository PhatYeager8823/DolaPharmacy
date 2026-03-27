<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Token bảo mật cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    {{--
       SỬ DỤNG VITE:
       LƯU Ý: Bạn nhớ di chuyển file cart.js vào thư mục "resources/js/" nhé!
    --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* CSS THÔNG BÁO TÙY CHỈNH (THEO HÌNH ẢNH YÊU CẦU) */
        .custom-alert {
            border-radius: 12px !important;
            border: none !important;
            padding: 1rem 1.5rem !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .custom-alert-success {
            background-color: #e8f5e9 !important;
            color: #1b5e20 !important;
        }
        .custom-alert-danger {
            background-color: #ffebee !important;
            color: #c62828 !important;
        }
        .custom-alert-warning {
            background-color: #fff3e0 !important;
            color: #e65100 !important;
        }
        .alert-icon-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            font-size: 14px;
        }
        .alert-icon-circle-success { background-color: #1b5e20; color: white; }
        .alert-icon-circle-danger { background-color: #c62828; color: white; }
        .alert-icon-circle-warning { background-color: #e65100; color: white; }
        
        .alert-title { font-weight: 700; font-size: 0.95rem; margin-bottom: 2px; display: block; }
        .alert-msg { font-size: 0.875rem; opacity: 0.9; }

        /* Container cho toast động */
        #notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 350px;
            max-width: 90vw;
        }
    </style>
    
    <script>
        /**
         * Hàm hiển thị thông báo đồng bộ toàn site
         * @param {string} message - Nội dung thông báo
         * @param {string} type - 'success', 'error', 'warning'
         */
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            const alertElement = document.createElement('div');
            alertElement.innerHTML = alertHtml;
            container.appendChild(alertElement);

            // Tự động xóa sau 5 giây
            setTimeout(() => {
                const alert = alertElement.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        };
    </script>
    <title>@yield('title') - {{ $global_setting->ten_website ?? 'Dola Pharmacy' }}</title>
</head>
<body>
    <div id="notification-container"></div>

    @include('partials.header')
    
    {{-- THÔNG BÁO TOÀN CỤC MỚI --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert custom-alert custom-alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-circle alert-icon-circle-success">
                        <i class="fa fa-check"></i>
                    </div>
                    <div>
                        <span class="alert-title">Thành công!</span>
                        <span class="alert-msg">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert custom-alert custom-alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon-circle alert-icon-circle-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <span class="alert-title">Thông báo!</span>
                        <span class="alert-msg">{{ session('error') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.floating-contact')
    @include('partials.mobile-bottom-nav')
    {{-- @include('partials.promo-modal') --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/provinces.js') }}"></script>

    {{-- Nơi để các file con đẩy script riêng vào --}}
    @stack('scripts')

</body>
</html>
