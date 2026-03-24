@extends('layouts.app')
@section('title', 'Liên hệ với chúng tôi')

@section('content')
<div class="bg-light py-5">
    <div class="container">

        <div class="row g-5">
            {{-- CỘT TRÁI: THÔNG TIN --}}
            <div class="col-lg-5">
                <h2 class="fw-bold text-primary mb-4">Thông tin liên hệ</h2>
                <p class="text-muted mb-4">
                    Nếu bạn có thắc mắc về đơn hàng, thuốc hoặc cần tư vấn sức khỏe,
                    đừng ngần ngại liên hệ với {{ $global_setting->ten_website }}.
                </p>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white p-3 rounded-circle shadow-sm text-primary me-3">
                        <i class="fa fa-map-marker-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Địa chỉ nhà thuốc</h6>
                        {{-- ĐÃ SỬA ĐỊA CHỈ --}}
                        <p class="text-secondary mb-0">{{ $global_setting->dia_chi ?? 'Đang cập nhật...' }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white p-3 rounded-circle shadow-sm text-primary me-3">
                        <i class="fa fa-phone-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Hotline tư vấn</h6>
                        <p class="text-secondary mb-0">
                            <a href="tel:0123456789" class="text-decoration-none text-secondary">{{ $global_setting->hotline ?? '0123.456.789' }}</a>
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-white p-3 rounded-circle shadow-sm text-primary me-3">
                        <i class="fa fa-envelope fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email hỗ trợ</h6>
                        <p class="text-secondary mb-0">{{ $global_setting->email ?? '...' }}</p>
                    </div>
                </div>

                {{-- BẢN ĐỒ GOOGLE MAPS (ĐỘNG) --}}
                <div class="rounded-3 overflow-hidden shadow-sm mt-4 border map-container">
                    @if(!empty($global_setting->maps))
                        {{-- Cách 1: Nếu Admin đã dán mã nhúng thì hiện ra --}}
                        {{-- Lưu ý: Dùng {!! !!} để chạy mã HTML (iframe) lưu trong database --}}
                        {!! $global_setting->maps !!}
                    @else
                        {{-- Cách 2: Nếu chưa có thì hiện bản đồ mặc định (Ví dụ: Cà Mau) --}}
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d251693.18915858277!2d104.99404287661576!3d9.177263920958197!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a6c1e136b6a327%3A0x62660d268d37443e!2zQ8Og Mau!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s" 
                            width="100%" 
                            height="250" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    @endif
                </div>
            </div>

            {{-- CỘT PHẢI: FORM --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4">
                    <h3 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" name="ho_ten" class="form-control bg-light border-0 py-3" placeholder="Nhập họ tên..." required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">Số điện thoại</label>
                                <input type="text" name="sdt" class="form-control bg-light border-0 py-3" placeholder="Nhập số điện thoại...">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-3" placeholder="Nhập email..." required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Tiêu đề</label>
                                <input type="text" name="tieu_de" class="form-control bg-light border-0 py-3" placeholder="Bạn cần hỗ trợ vấn đề gì?">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small">Nội dung <span class="text-danger">*</span></label>
                                <textarea name="noi_dung" rows="5" class="form-control bg-light border-0" placeholder="Viết nội dung tin nhắn..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase">
                                    <i class="fa fa-paper-plane me-2"></i> Gửi tin nhắn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
