@extends('layouts.app')
@section('title', 'Đăng ký tài khoản')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    <h4 class="fw-bold text-center text-primary mb-4">Đăng ký tài khoản</h4>
                    <p class="text-center text-muted small mb-4">Nhập số điện thoại để tạo tài khoản mới</p>

                    {{-- Form Đăng ký gửi về cùng route send_otp nhưng có thể xử lý logic khác nếu cần --}}
                    <form action="{{ route('auth.send_otp') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <div class="input-group input-group-lg">
                                {{-- Đổi fa-phone thành fa-comment-dots --}}
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-comment-dots"></i></span>
                                <input type="text" name="sdt" class="form-control border-start-0 fs-6" placeholder="Ví dụ: 0912345678" required autofocus>
                            </div>
                            @error('sdt') <small class="text-danger ms-1">{{ $message }}</small> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">Tiếp tục</button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Đã có tài khoản?</span>
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1">Đăng nhập ngay</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
