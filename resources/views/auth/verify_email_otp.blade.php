@extends('layouts.app')
@section('title', 'Xác thực Email')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">

                    <h4 class="fw-bold mb-3">Xác thực Email mới</h4>
                    <p class="text-muted mb-4">
                        Mã xác thực (OTP) đã được gửi đến: <br>
                        <strong class="text-primary">{{ $email ?? 'Email của bạn' }}</strong>
                    </p>

                    {{-- Form submit về route xử lý --}}
                    <form action="{{ route('account.verify_email') }}" method="POST" id="otpForm">
                        @csrf

                        {{-- Input ẩn để chứa giá trị OTP thực sự gửi đi --}}
                        <input type="hidden" name="otp" id="realOtpInput">

                        {{-- 6 ô vuông nhập liệu --}}
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control otp-box text-center fw-bold fs-4"
                                       maxlength="1"
                                       inputmode="numeric"
                                       autocomplete="one-time-code"
                                       style="width: 50px; height: 50px; border: 2px solid #ddd;">
                            @endfor
                        </div>

                        {{-- Hiển thị lỗi nếu nhập sai --}}
                        @error('otp')
                            <div class="text-danger small fw-bold mb-3">
                                <i class="fa fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror

                        <button type="button" onclick="submitOtp()" class="btn btn-primary w-100 py-2 fw-bold">
                            Xác nhận
                        </button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('account.index') }}" class="text-decoration-none text-muted small">
                            <i class="fa fa-arrow-left"></i> Quay lại / Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script xử lý 6 ô nhập liệu --}}
<script>
    const inputs = document.querySelectorAll('.otp-box');
    const realInput = document.getElementById('realOtpInput');
    const form = document.getElementById('otpForm');

    inputs.forEach((input, index) => {
        // 1. Khi nhập số -> nhảy sang ô kế tiếp
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
            updateRealInput();
        });

        // 2. Khi nhấn Backspace -> xóa và lùi về ô trước
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value) {
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });

        // 3. Chỉ cho phép nhập số
        input.addEventListener('keypress', function (e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
    });

    // Hàm cập nhật giá trị vào input ẩn
    function updateRealInput() {
        let otpValue = '';
        inputs.forEach(input => {
            otpValue += input.value;
        });
        realInput.value = otpValue;
    }

    // Hàm submit form
    function submitOtp() {
        updateRealInput();
        if (realInput.value.length < 6) {
            alert('Vui lòng nhập đủ 6 số OTP');
            return;
        }
        form.submit();
    }
</script>

<style>
    /* Hiệu ứng khi focus vào ô input */
    .otp-box:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
