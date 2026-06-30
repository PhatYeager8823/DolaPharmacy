<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Token bảo mật cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{--
       SỬ DỤNG VITE:
       LƯU Ý: Bạn nhớ di chuyển file cart.js vào thư mục "resources/js/" nhé!
    --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    
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
                        <div class="alert-icon-circle">
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

            const alertNode = alertElement.querySelector('.alert');
            
            // Tự động xóa sau 5 giây
            setTimeout(() => {
                if (alertNode && alertNode.parentElement) {
                    alertNode.style.animation = 'slideOutRight 0.4s ease-in forwards';
                    setTimeout(() => {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                        if (bsAlert) bsAlert.close();
                    }, 400);
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
    <div class="container mt-3" style="display: none;">
        @if(session('success') || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @if(session('success'))
                        showAlert("{!! addslashes(session('success')) !!}", 'success');
                    @endif
                    @if(session('error'))
                        showAlert("{!! addslashes(session('error')) !!}", 'error');
                    @endif
                });
            </script>
        @endif
    </div>

    <main class="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.floating-contact')
    @include('partials.mobile-bottom-nav')
    @include('partials.back-to-top')
    {{-- @include('partials.promo-modal') --}}

    <!-- Quick Buy Selection Modal -->
    <div class="modal fade" id="quickBuyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content quick-buy-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" style="font-size: 1.25rem; color: #007aff;">Thông tin sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="quickBuyModalBody">
                    {{-- Content populated via AJAX --}}
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/provinces.js') }}"></script>

    {{-- SCRIPT CHUNG --}}
    <script>
        function toggleBubbleMenu(event, button) {
            event.stopPropagation();
            // Đóng tất cả menu khác đang mở
            document.querySelectorAll('.bubble-menu').forEach(menu => {
                if (menu !== button.nextElementSibling) menu.classList.remove('active');
            });
            // Toggle menu hiện tại
            const menu = button.nextElementSibling;
            menu.classList.toggle('active');
        }

        // Đóng menu khi click ra ngoài
        document.addEventListener('click', function() {
            document.querySelectorAll('.bubble-menu').forEach(menu => menu.classList.remove('active'));
        });

        // === ABSOLUTE STABLE HEADER FIX (CLASS-BASED) ===
        // Note: Header toggle logic is now handled in resources/js/app.js via Vite.
        // Keeping event listeners here only if essential for immediate layout.




    </script>

    @stack('scripts')

</body>
</html>
