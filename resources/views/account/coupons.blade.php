@extends('layouts.app')
@section('title', 'Kho Voucher')

@section('content')
<div class="bg-light py-4">
    <div class="container">
        <div class="row g-4">
            {{-- SIDEBAR --}}
            <div class="col-12 col-lg-3 profile-sidebar">
                @include('partials.account-sidebar')
            </div>

            {{-- CONTENT --}}
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Kho Voucher của tôi</h5>
                    </div>
                    <div class="card-body p-4">
                        @if($coupons->isEmpty())
                            <div class="text-center py-5">
                                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/c0a4e76957a224a0.webp" width="100" class="mb-3" style="opacity: 0.5">
                                <p class="text-muted">Bạn chưa có mã giảm giá nào.</p>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($coupons as $coupon)
                                <div class="col-md-6">

                                    {{-- THẺ VOUCHER DÙNG ẢNH --}}
                                    <div class="voucher-card" data-bs-toggle="modal" data-bs-target="#couponDetail{{ $coupon->id }}">

                                        {{-- Phần Trái: CHỨA ẢNH --}}
                                        <div class="voucher-left-img">
                                            {{-- Kiểm tra nếu có ảnh thì hiện ảnh, không thì hiện ảnh mặc định --}}
                                            <img src="{{ $coupon->image ? asset('images/coupons/' . $coupon->image) : asset('images/no-image.png') }}"
                                                alt="{{ $coupon->code }}"
                                                class="voucher-img-cover">
                                        </div>

                                        {{-- Phần Phải: THÔNG TIN --}}
                                        <div class="voucher-right">
                                            <div>
                                                <div class="voucher-code-badge">Mã: {{ $coupon->code }}</div>
                                                <div class="voucher-desc">
                                                    @if($coupon->type == 'fixed')
                                                        Giảm {{ number_format($coupon->value) }}đ cho đơn 0đ
                                                    @else
                                                        Giảm {{ $coupon->value }}% tối đa 50k
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="voucher-footer">
                                                <div class="voucher-date">HSD: {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d.m.Y') }}</div>
                                                <form action="{{ route('cart.coupon.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="coupon_code" value="{{ $coupon->code }}">
                                                    <input type="hidden" name="redirect" value="cart.index">
                                                    <button type="submit" class="btn-use border-0 bg-transparent">Dùng ngay</button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Các chấm tròn trang trí (Holes) --}}
                                        <div class="voucher-hole-top"></div>
                                        <div class="voucher-hole-bottom"></div>
                                    </div>
                                    {{-- KẾT THÚC THẺ --}}

                                    {{-- MODAL CHI TIẾT (Giữ nguyên) --}}
                                    <div class="modal fade" id="couponDetail{{ $coupon->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-bottom-0">
                                                    <h5 class="modal-title fw-bold">Chi tiết Voucher</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                {{-- Nếu muốn hiện ảnh to trong modal thì bỏ comment dòng dưới --}}
                                                {{-- <img src="{{ $coupon->image ? asset('images/coupons/' . $coupon->image) : asset('images/no-image.png') }}" class="w-100 px-3 mb-2" style="max-height: 200px; object-fit: contain;"> --}}
                                                <div class="modal-body pt-0 text-center">
                                                    <h3 class="text-primary fw-bold mb-1">{{ $coupon->code }}</h3>
                                                    <p class="text-muted small mb-4">
                                                        @if($coupon->type == 'fixed')
                                                            Giảm trực tiếp {{ number_format($coupon->value) }}đ
                                                        @else
                                                            Giảm {{ $coupon->value }}% giá trị đơn hàng
                                                        @endif
                                                    </p>
                                                    <div class="list-group list-group-flush text-start small">
                                                        <div class="list-group-item bg-light border-0 rounded mb-1">
                                                            <strong>Hạn sử dụng:</strong> {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y H:i') }}
                                                        </div>
                                                        <div class="list-group-item bg-light border-0 rounded mb-1">
                                                            <strong>Sản phẩm:</strong> Áp dụng toàn sàn
                                                        </div>
                                                        <div class="list-group-item bg-light border-0 rounded">
                                                            <strong>Thanh toán:</strong> Mọi hình thức
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <form action="{{ route('cart.coupon.add') }}" method="POST" class="w-100">
                                                        @csrf
                                                        <input type="hidden" name="coupon_code" value="{{ $coupon->code }}">
                                                        <input type="hidden" name="redirect" value="cart.index">
                                                        <button type="submit" class="btn btn-primary w-100 fw-bold">Dùng ngay</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* DESIGN VOUCHER DÙNG ẢNH */
    .voucher-card {
        display: flex;
        background: #fff;
        height: 110px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #eee;
    }

    .voucher-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #0d6efd;
    }

    /* Cột Trái (CHỨA ẢNH) */
    .voucher-left-img {
        width: 110px; /* Vuông vắn */
        height: 110px;
        position: relative;
        /* Đường kẻ đứt nét màu tối hơn để nhìn rõ trên ảnh */
        border-right: 2px dashed rgba(0,0,0,0.2);
    }

    /* Class cho ảnh để nó phủ kín khung */
    .voucher-img-cover {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Đảm bảo ảnh không bị méo */
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }

    /* Cột Phải (Thông tin - Giữ nguyên) */
    .voucher-right {
        flex: 1;
        padding: 12px 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .voucher-code-badge {
        display: inline-block;
        background-color: #e7f1ff;
        color: #0d6efd;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 4px;
    }

    .voucher-desc {
        font-size: 13px;
        color: #333;
        font-weight: 600;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .voucher-footer {
        display: flex;
        justify-content: space-between;
        align-items: end;
    }

    .voucher-date {
        font-size: 11px;
        color: #888;
    }

    .btn-use {
        background: none;
        border: none;
        color: #0d6efd;
        font-size: 12px;
        font-weight: 700;
        padding: 0;
    }

    /* HIỆU ỨNG LỖ KHUYẾT (Holes) */
    .voucher-hole-top, .voucher-hole-bottom {
        position: absolute;
        width: 16px;
        height: 16px;
        background-color: #f8f9fa; /* Màu trùng nền trang */
        border-radius: 50%;
        left: 102px; /* Canh ngay đường cắt */
        z-index: 10;
        /* Thêm bóng đổ trong để tạo chiều sâu trên nền ảnh */
        box-shadow: inset 1px 1px 3px rgba(0,0,0,0.2);
    }

    .voucher-hole-top { top: -8px; }
    .voucher-hole-bottom { bottom: -8px; }

</style>
@endsection
