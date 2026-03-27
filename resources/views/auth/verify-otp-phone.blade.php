@extends('layouts.app')
@section('title', 'Nhập mã xác thực')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-3 text-primary">Xác thực số điện thoại</h4>
                    <p class="text-muted mb-4">
                        Mã xác thực đã được gửi đến SĐT: <br>
                        <strong>{{ session('auth_sdt') }}</strong>
                        <br><span class="small fst-italic text-secondary">(Check log nếu đang test local)</span>
                    </p>

                    <form action="{{ route('auth.verify_submit') }}" method="POST" id="otpPhoneForm">
                        @csrf

                        {{-- Input ẩn để chứa giá trị OTP thực sự --}}
                        <input type="hidden" name="otp" id="realOtpPhoneInput">

                        {{-- 6 ô vuông nhập liệu --}}
                        <div class="d-flex justify-content-center gap-2 mb-4">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" class="form-control otp-box text-center fw-bold fs-3 text-primary"
                                       maxlength="1"
                                       inputmode="numeric"
                                       autocomplete="one-time-code"
                                       style="width: 50px; height: 50px; border: 2px solid #dee2e6; border-radius: 8px;">
                            @endfor
                        </div>

                        {{-- Hiển thị lỗi từ Session (Controller trả về) --}}
                        @if(session('error'))
                            <div class="alert alert-danger py-2 mb-3 small fw-bold">
                                <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                            </div>
                        @endif

                        {{-- Hiển thị lỗi Validate --}}
                        @error('otp')
                            <div class="text-danger small fw-bold mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        <button type="button" onclick="submitPhoneOtp()" class="btn btn-primary w-100 py-2 fw-bold">
                            Xác nhận
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="btn btn-link text-decoration-none mt-3 text-muted small">
                        <i class="fa fa-arrow-left"></i> Đổi số điện thoại
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-box');
    const realInput = document.getElementById('realOtpPhoneInput');
    const form = document.getElementById('otpPhoneForm');

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

    function submitPhoneOtp() {
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
