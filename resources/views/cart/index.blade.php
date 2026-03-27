@extends('layouts.app')
@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="bg-light py-5">
    <div class="container">

        <h4 class="fw-bold mb-4">Giỏ hàng <span class="fs-6 text-muted fw-normal">({{ count($cart) }} sản phẩm)</span></h4>

        @if(count($cart) > 0)
            <div class="row g-4">
                {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            {{-- Header Bảng --}}
                            <div class="d-none d-md-flex bg-light p-3 fw-bold text-secondary small">
                                <div style="width: 45%;">Sản phẩm</div>
                                <div style="width: 15%;" class="text-center">Đơn giá</div>
                                <div style="width: 20%;" class="text-center">Số lượng</div>
                                <div style="width: 15%;" class="text-end">Thành tiền</div>
                                <div style="width: 5%;"></div>
                            </div>

                            {{-- Danh sách Items --}}
                            @foreach($cart as $id => $item)
                                <div class="d-flex align-items-center p-3 border-bottom position-relative item-row">
                                    {{-- Ảnh & Tên --}}
                                    <div class="d-flex align-items-center" style="width: 100%; md:width: 45%; flex-basis: 45%; flex-grow: 1;">
                                        <img src="{{ $item['image'] ? asset('images/images_san_pham/' . $item['image']) : asset('images/no-image.png') }}"
                                             class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">
                                        <div class="ms-3">
                                            <a href="{{ route('thuoc.show', $item['slug']) }}" class="text-decoration-none text-dark fw-bold two-lines">
                                                {{ $item['name'] }}
                                            </a>
                                            <div class="small text-muted mt-1">Đơn vị: {{ $item['unit'] ?? 'Hộp' }}</div>
                                        </div>
                                    </div>

                                    {{-- Giá (Desktop) --}}
                                    <div class="d-none d-md-block text-center fw-bold text-secondary" style="width: 15%;">
                                        {{ number_format($item['price']) }} đ
                                    </div>

                                    {{-- Số lượng --}}
                                    <div class="d-flex justify-content-center align-items-center mt-3 mt-md-0" style="width: 100%; md:width: 20%; flex-basis: 20%;">
                                        <div class="input-group input-group-sm" style="width: 110px;">
                                            <button class="btn btn-outline-secondary" onclick="updateQty({{ $id }}, -1)">-</button>
                                            <input type="text" class="form-control text-center bg-white" value="{{ $item['quantity'] }}" readonly id="qty-{{ $id }}">
                                            <button class="btn btn-outline-secondary" onclick="updateQty({{ $id }}, 1)">+</button>
                                        </div>
                                    </div>

                                    {{-- Thành tiền --}}
                                    <div class="d-none d-md-block text-end fw-bold text-primary" style="width: 15%;">
                                        {{ number_format($item['price'] * $item['quantity']) }} đ
                                    </div>

                                    {{-- Nút Xóa --}}
                                    <div class="text-end" style="width: 5%; min-width: 30px;">
                                        <button class="btn btn-link text-danger p-0" 
                                                onclick="removeItem({{ $id }})">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>

                {{-- CỘT PHẢI: TỔNG TIỀN & KHUYẾN MÃI --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Cộng giỏ hàng</h5>
                        </div>
                        <div class="card-body">

                            {{-- KHU VỰC CHỌN MÃ KHUYẾN MÃI --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"><i class="fa fa-ticket-alt me-1"></i> Khuyến mãi</label>

                                @if(session()->has('coupon'))
                                    {{-- Đã áp dụng --}}
                                    <div class="d-flex justify-content-between align-items-center p-2 border border-success rounded bg-success bg-opacity-10">
                                        <div>
                                            <span class="fw-bold text-success">{{ session('coupon')['code'] }}</span>
                                            <div class="small text-muted">Đã áp dụng</div>
                                        </div>
                                        <a href="{{ route('cart.coupon.remove') }}" class="text-danger small text-decoration-none fw-bold">Gỡ bỏ</a>
                                    </div>
                                @else
                                    {{-- Chưa áp dụng --}}
                                    <div class="d-flex justify-content-between align-items-center p-2 border rounded cursor-pointer hover-bg-light"
                                         data-bs-toggle="modal" data-bs-target="#modalSelectCoupon" style="cursor: pointer;">
                                        <span class="text-muted small">Chọn hoặc nhập mã</span>
                                        <span class="text-primary small fw-bold">Chọn mã ></span>
                                    </div>
                                @endif
                            </div>

                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    Tạm tính
                                    <span>{{ number_format($total) }} đ</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    Giảm giá
                                    @if($discountAmount > 0)
                                        <span class="text-success fw-bold">- {{ number_format($discountAmount) }} đ</span>
                                    @else
                                        <span>0 đ</span>
                                    @endif
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3 pt-3 border-top">
                                    <strong class="fs-5">Tổng cộng</strong>
                                    <span class="text-primary fw-bold fs-4">{{ number_format($finalTotal) }} đ</span>
                                </li>
                            </ul>

                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-3 fw-bold text-uppercase">
                                Tiến hành thanh toán
                            </a>

                            <div class="text-center mt-3">
                                <small class="text-muted"><i class="fa fa-shield-alt me-1"></i> Cam kết bảo mật thông tin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- GIỎ HÀNG TRỐNG --}}
            <div class="text-center py-5">
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/9bdd8040b334d31946f49e36beaf32db.webp" width="150" class="mb-4 opacity-50">
                <h5 class="text-muted">Giỏ hàng của bạn đang trống</h5>
                <p class="text-secondary mb-4">Hãy dạo một vòng xem có gì ưng ý không nhé!</p>
                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 fw-bold">Mua sắm ngay</a>
            </div>
        @endif

        {{-- SẢN PHẨM ĐÃ ĐẶT (QUÁ KHỨ) - HIỆN BÊN DƯỚI ĐỂ NGĂN CÁCH --}}
        @if(Auth::check() && count($orderedItems) > 0)
            <div class="mt-5 pt-4 border-top">
                <h5 class="fw-bold mb-4 text-secondary"><i class="fa fa-history me-2"></i> Sản phẩm đã đặt (Gần đây)</h5>
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="card-body p-0">
                        @foreach($orderedItems as $itemId => $item)
                            <div class="d-flex align-items-center p-3 border-bottom bg-light bg-opacity-50 ordered-item" style="opacity: 0.8;">
                                <img src="{{ $item['image'] ? asset('images/images_san_pham/' . $item['image']) : asset('images/no-image.png') }}"
                                     class="rounded border grayscale" style="width: 60px; height: 60px; object-fit: cover; filter: grayscale(40%);">
                                <div class="ms-3 flex-grow-1">
                                    <div class="text-dark fw-bold small">{{ $item['name'] }}</div>
                                    <div class="small text-muted mt-1">{{ number_format($item['price']) }} đ • Đã thanh toán</div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-outline-primary btn-sm fw-bold px-3 rounded-pill" onclick="repurchase({{ $itemId }})">
                                        <i class="fa fa-redo me-1"></i> Mua lại
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL CHỌN MÃ KHUYẾN MÃI --}}
<div class="modal fade" id="modalSelectCoupon" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold fs-6">Chọn Dola Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light p-3">
                {{-- Form nhập tay --}}
                <form action="{{ route('cart.coupon.add') }}" method="POST" class="d-flex gap-2 mb-3">
                    @csrf
                    <input type="text" name="coupon_code" class="form-control form-control-sm" placeholder="Nhập mã voucher">
                    <button class="btn btn-primary btn-sm px-3" type="submit">ÁP DỤNG</button>
                </form>

                <p class="small text-muted fw-bold mb-2">Mã giảm giá của bạn</p>

                {{-- Form chọn từ danh sách --}}
                <form action="{{ route('cart.coupon.add') }}" method="POST" id="formSelectCoupon">
                    @csrf

                    @if(isset($userCoupons) && count($userCoupons) > 0)
                        @foreach($userCoupons as $coupon)
                        <div class="card border-0 shadow-sm mb-2 hover-shadow">
                            <div class="card-body p-0">
                                <div class="d-flex align-items-center p-2">
                                    {{-- Hình ảnh --}}
                                    <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded"
                                         style="width: 70px; height: 70px; min-width: 70px;">
                                        <div class="text-center">
                                            <div class="fw-bold text-uppercase small">{{ $coupon->code }}</div>
                                            <div style="font-size: 9px;">VOUCHER</div>
                                        </div>
                                    </div>

                                    {{-- Thông tin --}}
                                    <div class="flex-grow-1 ps-3 pe-2">
                                        <div class="fw-bold text-dark small">
                                            @if($coupon->type == 'fixed')
                                                Giảm {{ number_format($coupon->value) }}đ
                                            @else
                                                Giảm {{ $coupon->value }}%
                                            @endif
                                        </div>
                                        <div class="small text-muted" style="font-size: 11px;">HSD: {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('d/m/Y') }}</div>
                                    </div>

                                    {{-- Radio --}}
                                    <div>
                                        <input class="form-check-input coupon-radio" type="radio" name="coupon_code"
                                               value="{{ $coupon->code }}"
                                               id="coupon{{ $coupon->id }}"
                                               style="cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted small bg-white rounded shadow-sm">
                            <i class="fa fa-ticket-alt mb-2 fs-4 text-secondary"></i><br>
                            Bạn chưa có mã giảm giá nào.<br>
                            <span class="text-danger" style="font-size: 11px;">(Hãy tạo tài khoản mới để nhận mã trải nghiệm)</span>
                        </div>
                    @endif
                </form>
            </div>

            <div class="modal-footer bg-white p-2">
                <button type="button" class="btn btn-secondary btn-sm w-25" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-sm w-50 disabled" id="btnApplySelected" onclick="document.getElementById('formSelectCoupon').submit()">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateQty(id, change) {
        let currentQty = parseInt(document.getElementById('qty-' + id).value);
        let newQty = currentQty + change;
        if (newQty < 1) return;

        fetch('{{ route('cart.update') }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id, quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) location.reload();
        });
    }

    function removeItem(id) {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1b5e20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('cart.remove') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) location.reload();
                });
            }
        });
    }

    function repurchase(id) {
        fetch('{{ route('cart.repurchase') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) location.reload();
            else showAlert('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        });
    }

    // Script kích hoạt nút OK
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.coupon-radio');
        const btnApply = document.getElementById('btnApplySelected');
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) btnApply.classList.remove('disabled');
            });
        });
    });
</script>

<style>
    .hover-bg-light:hover { background-color: #f8f9fa; }
    .hover-shadow:hover { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; transition: 0.3s; }
    .two-lines { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection
