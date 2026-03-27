@extends('layouts.app')
@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="bg-light py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR --}}
            <div class="col-12 col-lg-3 profile-sidebar">
                @include('partials.account-sidebar')
            </div>

            {{-- NỘI DUNG CHÍNH --}}
            <div class="col-12 col-lg-9">
                <h4 class="fw-bold mb-4">Lịch sử đơn hàng</h4>

                @if($orders->count() > 0)
                    @foreach($orders as $order)
                        <div class="card border-0 shadow-sm mb-3">

                            {{-- Header Đơn hàng --}}
                            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <span class="fw-bold text-primary">#{{ $order->ma_don_hang }}</span>
                                    <span class="text-muted small mx-2">|</span>
                                    <span class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    @php
                                        $statusColor = match($order->trang_thai) {
                                            'da_giao' => 'success',
                                            'dang_giao' => 'warning text-dark',
                                            'da_huy' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusText = match($order->trang_thai) {
                                            'da_giao' => 'Đã giao hàng',
                                            'dang_giao' => 'Đang vận chuyển',
                                            'da_huy' => 'Đã hủy',
                                            default => 'Chờ xác nhận'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span>
                                </div>
                            </div>

                            {{-- Chi tiết sản phẩm --}}
                            <div class="card-body border-top">
                                @foreach($order->items as $item)
                                    <div class="d-flex align-items-center mb-3 last:mb-0">
                                        {{-- Sửa chữ 'storage/' thành 'images/images_san_pham/' --}}
                                        <img src="{{ $item->thuoc && $item->thuoc->hinh_anh ? asset('images/images_san_pham/' . $item->thuoc->hinh_anh) : 'https://via.placeholder.com/60' }}"
                                            class="rounded border" width="60" height="60" style="object-fit: contain;">

                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-1 fw-bold text-dark" style="font-size: 14px;">
                                                {{ $item->ten_thuoc }}
                                            </h6>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">x {{ $item->so_luong }}</small>
                                                <span class="fw-bold text-dark">{{ number_format($item->gia_ban) }} đ</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Footer Đơn hàng --}}
                            <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Tổng tiền: <span class="fw-bold text-danger fs-5 ms-2">{{ number_format($order->tong_tien) }} đ</span>
                                </div>
                                <div>
                                    {{-- Nút hành động --}}
                                    {{-- 1. Nút Chi tiết (Link thường) --}}
                                    <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm me-2">
                                        Chi tiết
                                    </a>

                                    {{-- 2. Nút Hủy đơn (Chỉ hiện khi Chờ xác nhận) --}}
                                    @if($order->trang_thai == 'cho_xac_nhan')
                                        <form id="cancel-form-{{ $order->id }}" action="{{ route('account.orders.cancel', $order->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="button" class="btn btn-danger btn-sm me-2" onclick="confirmCancel({{ $order->id }})">Hủy đơn</button>
                                        </form>
                                    @endif

                                    {{-- 3. Nút Mua lại (Chỉ hiện khi Đã giao hoặc Đã hủy) --}}
                                    @if($order->trang_thai == 'da_giao' || $order->trang_thai == 'da_huy')
                                        <form action="{{ route('cart.reorder', $order->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Mua lại</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/orderlist/5fafbb923393b712b96488590b8f781f.webp" width="100" style="opacity: 0.5">
                        <p class="mt-3 text-muted">Bạn chưa có đơn hàng nào.</p>
                        <a href="{{ route('thuoc.index') }}" class="btn btn-primary px-4">Mua sắm ngay</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmCancel(orderId) {
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
                document.getElementById('cancel-form-' + orderId).submit();
            }
        });
    }
</script>
@endpush
