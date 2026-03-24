@extends('layouts.app')
@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5 text-center">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body py-5">
            <div class="mb-4 text-success">
                <i class="fa fa-check-circle" style="font-size: 80px;"></i>
            </div>
            <h2 class="fw-bold mb-3">Đặt hàng thành công!</h2>
            <p class="text-muted mb-4">
                Cảm ơn bạn đã mua hàng tại Dola Pharmacy. <br>
                Mã đơn hàng của bạn là: <strong class="text-primary">#{{ $order->ma_don_hang }}</strong>
            </p>

            <div class="alert alert-info">
                Cảm ơn bạn đã mua hàng! <br>
                Đơn hàng đã được lưu vào lịch sử tài khoản gắn với số điện thoại <b>{{ $order->sdt_nguoi_nhan }}</b>.
                @if(!Auth::check())
                    <br> Vui lòng <a href="{{ route('login') }}">Đăng nhập</a> để theo dõi quá trình vận chuyển.
                @endif
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">Về trang chủ</a>

                {{-- CHỈ HIỆN NÚT NÀY NẾU ĐÃ ĐĂNG NHẬP --}}
                @auth
                    <a href="{{ route('account.orders') }}" class="btn btn-primary">Xem lịch sử đơn hàng</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
