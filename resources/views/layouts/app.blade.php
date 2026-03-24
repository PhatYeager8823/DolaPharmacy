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

    <title>@yield('title') - {{ $global_setting->ten_website ?? 'Dola Pharmacy' }}</title>
</head>
<body>

    @include('partials.header')
    
    {{-- THÔNG BÁO TOÀN CỤC --}}
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa fa-check-circle fs-4 me-3"></i>
                    <div>
                        <strong class="d-block">Thành công!</strong>
                        {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fa fa-exclamation-circle fs-4 me-3"></i>
                    <div>
                        <strong class="d-block">Thông báo!</strong>
                        {{ session('error') }}
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
    @include('partials.promo-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/provinces.js') }}"></script>

    {{-- Nơi để các file con đẩy script riêng vào --}}
    @stack('scripts')

</body>
</html>
