@extends('layouts.app')
@section('title', 'Đăng nhập hệ thống')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">

                {{-- XÁC ĐỊNH TRẠNG THÁI TAB --}}
                @php
                    // Nếu có lỗi về email (nhập sai mk/email) hoặc có dữ liệu cũ của email -> Active Tab Email
                    $isEmailActive = $errors->has('email') || old('email');
                @endphp

                {{-- HEADER TABS --}}
                <div class="card-header bg-white border-bottom-0 p-0">
                    <ul class="nav nav-pills nav-fill" id="loginTab" role="tablist">

                        {{-- TAB BUTTON: SỐ ĐIỆN THOẠI --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ !$isEmailActive ? 'active' : '' }} rounded-0 py-3 fw-bold"
                                    id="phone-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#phone-login"
                                    type="button"
                                    role="tab"
                                    aria-controls="phone-login"
                                    aria-selected="{{ !$isEmailActive ? 'true' : 'false' }}">
                                <i class="fa fa-mobile-alt me-2"></i> Số điện thoại
                            </button>
                        </li>

                        {{-- TAB BUTTON: EMAIL --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $isEmailActive ? 'active' : '' }} rounded-0 py-3 fw-bold"
                                    id="email-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#email-login"
                                    type="button"
                                    role="tab"
                                    aria-controls="email-login"
                                    aria-selected="{{ $isEmailActive ? 'true' : 'false' }}">
                                <i class="fa fa-envelope me-2"></i> Email / Mật khẩu
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    {{-- === [BẮT ĐẦU ĐOẠN CODE MỚI] === --}}
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- === [KẾT THÚC ĐOẠN CODE MỚI] === --}}
                    <div class="tab-content" id="loginTabContent">

                        {{-- TAB CONTENT 1: ĐĂNG NHẬP SĐT (OTP) --}}
                        {{-- Thêm logic check active --}}
                        <div class="tab-pane fade {{ !$isEmailActive ? 'show active' : '' }}" id="phone-login" role="tabpanel" aria-labelledby="phone-tab">
                            <p class="text-center text-muted small mb-4">Nhập số điện thoại để đăng nhập nhanh</p>

                            <form action="{{ route('auth.send_otp') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-comment-dots"></i></span>
                                        <input type="text" name="sdt" class="form-control border-start-0 fs-6" placeholder="Ví dụ: 0912345678" required autofocus>
                                    </div>
                                    @error('sdt') <small class="text-danger ms-1">{{ $message }}</small> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Tiếp tục</button>
                            </form>

                            <div class="text-center mt-4">
                                <span class="text-muted small">Chưa có tài khoản?</span>
                                <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1">Đăng ký ngay</a>
                            </div>
                        </div>

                        {{-- TAB CONTENT 2: ĐĂNG NHẬP EMAIL --}}
                        {{-- Thêm logic check active --}}
                        <div class="tab-pane fade {{ $isEmailActive ? 'show active' : '' }}" id="email-login" role="tabpanel" aria-labelledby="email-tab">
                            <p class="text-center text-muted small mb-4">Dành cho thành viên đã cập nhật thông tin</p>

                            <form action="{{ route('auth.login_email') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" required value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="loginPass" required>
                                        <span class="input-group-text bg-white cursor-pointer" onclick="togglePass()">
                                            <i class="fa fa-eye text-muted" id="eyeIcon"></i>
                                        </span>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Đăng nhập</button>
                                <div class="text-center mt-3">
                                    <a href="#" class="text-decoration-none small">Quên mật khẩu?</a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function togglePass() {
        const input = document.getElementById('loginPass');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<style>
    .nav-pills .nav-link { color: #555; background: #f8f9fa; border-bottom: 2px solid transparent; cursor: pointer; }
    .nav-pills .nav-link.active { color: #0d6efd; background: #fff; border-bottom: 2px solid #0d6efd; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection
