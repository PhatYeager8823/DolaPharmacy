@extends('layouts.app')

@section('title', 'Về chúng tôi - Dola Pharmacy')

@section('content')

{{-- 1. HEADER BANNER --}}
<div class="bg-primary py-5 text-center text-white">
    <div class="container">
        <h1 class="fw-bold display-5">Về chúng tôi - {{ $global_setting->ten_website }}</h1>
        <p class="lead mb-0">Tận tâm chăm sóc - Vững bước sức khỏe</p>
    </div>
</div>

{{-- 2. CÂU CHUYỆN THƯƠNG HIỆU --}}
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-image-card shine-effect shadow-sm rounded-3 overflow-hidden">
                    <img src="{{ asset('images/banners/gioithieu_doctor.webp') }}"
                         alt="Dola Pharmacy" class="img-fluid w-100">
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="text-primary fw-bold text-uppercase">Câu chuyện của chúng tôi</h5>
                <h2 class="fw-bold mb-4">Hơn 10 năm đồng hành cùng sức khỏe người Việt</h2>
                <p class="text-secondary" style="line-height: 1.8;">
                    Thành lập từ năm 2013, {{ $global_setting->ten_website }} khởi đầu là một nhà thuốc nhỏ tại trung tâm thành phố.
                    Với phương châm "Thuốc thật - Giá tốt - Tận tâm", chúng tôi đã không ngừng nỗ lực để trở thành
                    địa chỉ tin cậy của hàng triệu gia đình.
                </p>
                <p class="text-secondary" style="line-height: 1.8;">
                    Chúng tôi hiểu rằng, đằng sau mỗi đơn thuốc là niềm hy vọng về sức khỏe của khách hàng.
                    Vì vậy, mọi sản phẩm tại {{ $global_setting->ten_website }} đều được kiểm duyệt khắt khe, cam kết chính hãng 100%.
                </p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-check-circle text-success fs-3 me-3"></i>
                            <div>
                                <h4 class="fw-bold mb-0">100%</h4>
                                <small class="text-muted">Thuốc chính hãng</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-users text-primary fs-3 me-3"></i>
                            <div>
                                <h4 class="fw-bold mb-0">50+</h4>
                                <small class="text-muted">Dược sĩ chuyên môn</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3. GIÁ TRỊ CỐT LÕI (ICONS) --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Giá trị cốt lõi</h2>
            <p class="text-muted">Những điều làm nên sự khác biệt của {{ $global_setting->ten_website }}</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                    <div class="mb-3 text-primary">
                        <i class="fa fa-heartbeat fa-3x"></i>
                    </div>
                    <h4 class="fw-bold">Tận tâm</h4>
                    <p class="text-secondary mb-0">
                        Chúng tôi phục vụ khách hàng bằng cả trái tim, coi khách hàng như người thân trong gia đình.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                    <div class="mb-3 text-primary">
                        <i class="fa fa-shield-alt fa-3x"></i>
                    </div>
                    <h4 class="fw-bold">Uy tín</h4>
                    <p class="text-secondary mb-0">
                        Nói không với hàng giả, hàng kém chất lượng. Luôn minh bạch về nguồn gốc và giá cả.
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                    <div class="mb-3 text-primary">
                        <i class="fa fa-user-md fa-3x"></i>
                    </div>
                    <h4 class="fw-bold">Chuyên môn</h4>
                    <p class="text-secondary mb-0">
                        Đội ngũ dược sĩ đại học luôn sẵn sàng tư vấn đúng thuốc, đúng bệnh, đúng liều lượng.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. ĐỘI NGŨ DƯỢC SĨ --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Đội ngũ chuyên gia</h2>
        </div>
        <div class="row g-4">
            {{-- Dược sĩ 1 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <img src="{{ asset('images/team/pharmacist_1.png') }}"
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold">DS. Nguyễn Văn A</h5>
                        <p class="text-muted small">Dược sĩ trưởng - 15 năm kinh nghiệm</p>
                    </div>
                </div>
            </div>
            {{-- Dược sĩ 2 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <img src="{{ asset('images/team/pharmacist_2.png') }}"
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold">DS. Trần Thị B</h5>
                        <p class="text-muted small">Tư vấn chuyên môn</p>
                    </div>
                </div>
            </div>
            {{-- Dược sĩ 3 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <img src="{{ asset('images/team/pharmacist_3.png') }}"
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold">DS. Lê Văn C</h5>
                        <p class="text-muted small">Quản lý kho dược</p>
                    </div>
                </div>
            </div>
            {{-- Dược sĩ 4 --}}
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <img src="{{ asset('images/team/pharmacist_4.png') }}"
                             class="rounded-circle mb-3" width="120" height="120" style="object-fit: cover;">
                        <h5 class="fw-bold">DS. Phạm Thị D</h5>
                        <p class="text-muted small">Chăm sóc khách hàng</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
