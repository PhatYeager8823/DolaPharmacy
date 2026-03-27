@extends('layouts.app')
@section('title', 'Xác thực đổi mật khẩu')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3 text-primary">Xác thực đổi mật khẩu</h4>
                    <p class="text-muted mb-4">
                        Mã OTP đã được gửi đến Email của bạn.<br>
                        Vui lòng kiểm tra hộp thư.
                    </p>

                    <form action="{{ route('account.verify_change_password') }}" method="POST" id="otpPassForm">
                        @csrf

                        {{-- Input ẩn --}}
                        <input type="hidden" name="otp" id="realOtpPassInput">

                        {{-- 6 ô vuông --}}
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control otp-box text-center fw-bold fs-3 text-primary"
                                       maxlength="1"
                                       inputmode="numeric"
                                       autocomplete="one-time-code"
                                       style="width: 50px; height: 50px; border: 2px solid #dee2e6; border-radius: 8px;">
                            @endfor
                        </div>

                        {{-- Hiển thị lỗi --}}
                        @error('otp')
                            <div class="alert alert-danger py-2 mb-3 small fw-bold">
                                <i class="fa fa-exclamation-triangle me-1"></i> {{ $message }}
                            </div>
                        @enderror

                        <button type="button" onclick="submitPassOtp()" class="btn btn-primary w-100 py-2 fw-bold">
                            Xác nhận đổi mật khẩu
                        </button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('account.index') }}" class="text-decoration-none text-muted small">
                            <i class="fa fa-arrow-left"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-box');
    const realInput = document.getElementById('realOtpPassInput');
    const form = document.getElementById('otpPassForm');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1) {
                if (index < inputs.length - 1) inputs[index + 1].focus();
            }
            updateRealInput();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value) {
                if (index > 0) inputs[index - 1].focus();
            }
        });

        input.addEventListener('keypress', function (e) {
            if (e.which < 48 || e.which > 57) e.preventDefault();
        });
    });

    function updateRealInput() {
        let otpValue = '';
        inputs.forEach(input => { otpValue += input.value; });
        realInput.value = otpValue;
    }

    function submitPassOtp() {
        updateRealInput();
        if (realInput.value.length < 6) {
            showAlert('Vui lòng nhập đủ 6 số OTP', 'error');
            return;
        }
        form.submit();
    }

    window.onload = function() {
        if(inputs.length > 0) inputs[0].focus();
    };
</script>

<style>
    .otp-box:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }
</style>
@endsection
