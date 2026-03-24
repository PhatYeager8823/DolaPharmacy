@extends('layouts.app')
@section('title', 'Đăng nhập / Đăng ký')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-center text-primary mb-4">Xin chào!</h4>
                    <p class="text-center text-muted mb-4">Nhập số điện thoại để đăng nhập hoặc đăng ký</p>

                    <form action="{{ route('auth.send_otp') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fa fa-phone"></i></span>
                                <input type="text" name="sdt" class="form-control border-start-0" placeholder="0912345678" required autofocus>
                            </div>
                            @error('sdt') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Tiếp tục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
