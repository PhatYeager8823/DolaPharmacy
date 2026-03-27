@extends('layouts.app')
@section('title', 'Chi tiết đơn hàng #' . $order->ma_don_hang)

        @section('content')
        <div class="bg-light py-4">
            <div class="container">
                {{-- Breadcrumb --}}
                <div class="mb-3">
                    <a href="{{ route('account.orders') }}" class="text-decoration-none text-muted">
                        <i class="fa fa-arrow-left me-1"></i> Quay lại lịch sử đơn hàng
                    </a>
                </div>

                @php
            // 1. Tính tổng tiền hàng (Subtotal) từ danh sách chi tiết
            $subtotal = 0;
            foreach($order->items as $item) {
                $subtotal += $item->gia_ban * $item->so_luong;
            }

            // 2. Xác định phí ship (Logic giống lúc đặt hàng)
            // Nếu bạn có lưu cột 'phi_ship' trong DB thì dùng: $shippingFee = $order->phi_ship;
            // Nếu chưa lưu, ta check lại theo địa chỉ:
            $shippingFee = 15000; // Mặc định
            if (str_contains(mb_strtolower($order->dia_chi_giao_hang, 'UTF-8'), 'bạc liêu')) {
                $shippingFee = 0;
            }

            // 3. Tính ra số tiền đã được giảm (Voucher)
            // Công thức: (Tiền hàng + Ship) - Thực trả = Giảm giá
            $discount = ($subtotal + $shippingFee) - $order->tong_tien;

            // Xử lý lệch số lẻ (nếu có) để tránh số âm
            if($discount < 0) $discount = 0;
        @endphp

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- DANH SÁCH SẢN PHẨM --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="fw-bold mb-0">Kiện hàng</h5>
                        <small class="text-muted">Mã vận đơn: {{ $order->ma_don_hang }}</small>
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $item->thuoc && $item->thuoc->hinh_anh ? asset('images/images_san_pham/' . $item->thuoc->hinh_anh) : 'https://via.placeholder.com/60' }}"
                                     class="rounded border" width="60" height="60" style="object-fit: contain;">
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1 text-dark">{{ $item->ten_thuoc }}</h6>
                                    <small class="text-muted">x {{ $item->so_luong }}</small>
                                </div>
                                <div class="fw-bold">{{ number_format($item->gia_ban) }} đ</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- THÔNG TIN THANH TOÁN --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Chi tiết thanh toán</h6>

                        {{-- 1. Tiền hàng (Tạm tính) --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền hàng</span>
                            <span>{{ number_format($subtotal) }} đ</span>
                        </div>

                        {{-- 2. Phí vận chuyển --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            @if($shippingFee == 0)
                                <span class="text-success fw-bold">Miễn phí (Bạc Liêu)</span>
                            @else
                                <span>{{ number_format($shippingFee) }} đ</span>
                            @endif
                        </div>

                        {{-- 3. Giảm giá (Chỉ hiện nếu có) --}}
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>
                                    <i class="fa fa-ticket-alt me-1"></i> Voucher giảm giá
                                </span>
                                <span>-{{ number_format($discount) }} đ</span>
                            </div>
                        @endif

                        <hr>

                        {{-- 4. Tổng thực trả --}}
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>Thành tiền</span>
                            <span class="text-primary">{{ number_format($order->tong_tien) }} đ</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- THÔNG TIN NGƯỜI NHẬN --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Địa chỉ nhận hàng</h6>
                        <p class="mb-1 fw-bold">{{ $order->ten_nguoi_nhan }}</p>
                        <p class="mb-1 text-secondary small">{{ $order->sdt_nguoi_nhan }}</p>
                        <p class="mb-0 text-secondary small">{{ $order->dia_chi_giao_hang }}</p>
                    </div>
                </div>

                {{-- TRẠNG THÁI --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Trạng thái đơn hàng</h6>

                        {{-- Hiển thị trạng thái --}}
                        @php
                            $alertClass = match($order->trang_thai) {
                                'da_giao' => 'alert-success',
                                'da_huy' => 'alert-danger',
                                'dang_giao' => 'alert-warning',
                                default => 'alert-info' // cho_xac_nhan
                            };

                            $statusText = match($order->trang_thai) {
                                'da_giao' => 'Giao hàng thành công',
                                'da_huy' => 'Đơn hàng đã hủy',
                                'dang_giao' => 'Đang vận chuyển',
                                default => 'Chờ xác nhận'
                            };
                        @endphp

                        <div class="alert {{ $alertClass }} mb-3 text-center fw-bold">
                            {{ $statusText }}
                        </div>

                        {{-- NÚT HỦY ĐƠN (Chỉ hiện khi chưa xử lý) --}}
                        @if($order->trang_thai == 'cho_xac_nhan')
                            <div class="d-grid">
                                <form id="cancel-form" action="{{ route('account.orders.cancel', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-outline-danger w-100" onclick="confirmCancel()">
                                        <i class="fa fa-times me-1"></i> Hủy đơn hàng
                                    </button>
                                </form>
                                <script>
                                    function confirmCancel() {
                                        Swal.fire({
                                            title: 'Hủy đơn hàng?',
                                            text: "Bạn có chắc chắn muốn hủy đơn hàng này không?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Đồng ý hủy',
                                            cancelButtonText: 'Quay lại'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('cancel-form').submit();
                                            }
                                        });
                                    }
                                </script>
                                <small class="text-muted text-center mt-2" style="font-size: 12px;">
                                    Bạn chỉ có thể hủy khi đơn hàng chưa được xác nhận.
                                </small>
                            </div>
                        @endif

                        {{-- NÚT MUA LẠI (Hiện khi đã hoàn tất) --}}
                        @if($order->trang_thai == 'da_giao' || $order->trang_thai == 'da_huy')
                            <div class="d-grid mt-2">
                                <form action="{{ route('cart.reorder', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fa fa-redo me-1"></i> Mua lại đơn này
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
