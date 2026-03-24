@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- 1. TIÊU ĐỀ & NÚT QUAY LẠI (Dùng Flexbox để căn 2 bên) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-label-secondary me-2">
                <i class="bx bx-arrow-back me-1"></i> Quay lại
            </a>
            {{-- Nút In Hóa Đơn --}}
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bx bx-printer me-1"></i> In hóa đơn
            </button>
        </div>
        <h4 class="fw-bold py-3 mb-0">Đơn hàng #{{ $order->ma_don_hang }}</h4>
    </div>

    {{-- CSS để khi in thì ẩn hết menu, chỉ hiện nội dung đơn hàng --}}
    <style>
    @media print {
        .layout-navbar, .layout-menu, .footer, .btn, .card-header a {
            display: none !important;
        }
        .content-wrapper { margin: 0 !important; padding: 0 !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
    </style>

    {{-- 2. HIỂN THỊ THÔNG BÁO THÀNH CÔNG (Nếu có) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 3. HIỂN THỊ LỖI (Phòng hờ) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        {{-- CỘT TRÁI: THÔNG TIN SẢN PHẨM --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <h5 class="card-header">Danh sách sản phẩm</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->thuoc && $item->thuoc->hinh_anh ? asset('images/images_san_pham/'.$item->thuoc->hinh_anh) : 'https://via.placeholder.com/50' }}"
                                             class="rounded me-3" width="50" height="50" style="object-fit: contain; border: 1px solid #eee;">
                                        <div>
                                            <strong class="d-block">{{ $item->ten_thuoc }}</strong>
                                            <small class="text-muted">{{ $item->thuoc->ma_san_pham ?? '---' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->so_luong }}</td>
                                <td class="text-end">{{ number_format($item->gia_ban) }} đ</td>
                                <td class="text-end fw-bold">{{ number_format($item->thanh_tien) }} đ</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="text-end fw-bold text-primary fs-5">{{ number_format($order->tong_tien) }} đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN KHÁCH & TRẠNG THÁI --}}
        <div class="col-md-4">

            {{-- CẬP NHẬT TRẠNG THÁI --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Xử lý đơn hàng</h5>

                    @if($order->trang_thai == 'da_giao' || $order->trang_thai == 'da_huy')
                        {{-- TRƯỜNG HỢP 1: ĐƠN ĐÃ ĐÓNG (NIÊM PHONG) --}}
                        <div class="alert {{ $order->trang_thai == 'da_giao' ? 'alert-success' : 'alert-danger' }} mb-0 text-center" role="alert">
                            <i class='bx {{ $order->trang_thai == 'da_giao' ? 'bx-check-double' : 'bx-block' }} bx-lg mb-2'></i>
                            <h6 class="fw-bold mb-1">
                                {{ $order->trang_thai == 'da_giao' ? 'ĐƠN HÀNG ĐÃ GIAO' : 'ĐƠN HÀNG ĐÃ HỦY' }}
                            </h6>
                            {{-- <p class="small mb-0">Đơn hàng đã được niêm phong.<br>Không thể thay đổi trạng thái.</p> --}}
                        </div>
                    @else
                        {{-- TRƯỜNG HỢP 2: ĐƠN ĐANG XỬ LÝ (CHO PHÉP SỬA) --}}
                        <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Trạng thái hiện tại</label>
                                <select name="trang_thai" class="form-select">
                                    {{-- Chỉ hiện các trạng thái logic tiếp theo --}}
                                    <option value="cho_xac_nhan" {{ $order->trang_thai == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="xac_nhan" {{ $order->trang_thai == 'xac_nhan' ? 'selected' : '' }}>Đã xác nhận (Đóng gói)</option>
                                    <option value="dang_giao" {{ $order->trang_thai == 'dang_giao' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="da_giao">Đã giao thành công</option>
                                    <option value="da_huy" class="text-danger">Hủy đơn hàng</option>
                                </select>
                            </div>

                            {{-- Cảnh báo nhẹ khi đổi trạng thái --}}
                            <div class="form-text text-muted mb-3 small">
                                <i class="bx bx-info-circle me-1"></i>
                                Lưu ý: Khách hàng sẽ nhận được thông báo ngay khi bạn cập nhật.
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- THÔNG TIN NGƯỜI NHẬN --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thông tin giao hàng</h5>
                    <div class="info-container">
                        <ul class="list-unstyled mb-0">
                            {{-- 1. NGƯỜI NHẬN HÀNG (Tên trên đơn) --}}
                            <li class="mb-3 pb-2 border-bottom">
                                <span class="fw-bold d-block mb-1">Người nhận hàng:</span>
                                <span class="fs-5">{{ $order->ten_nguoi_nhan }}</span>

                                {{-- [MỚI] HIỂN THỊ TÀI KHOẢN GỐC --}}
                                <div class="mt-1">
                                    @if($order->nguoiDung)
                                        <small class="text-primary bg-label-primary px-2 py-1 rounded">
                                            <i class="bx bx-user-circle me-1"></i>
                                            TK: {{ $order->nguoiDung->ten }}
                                        </small>
                                    @else
                                        <small class="text-muted bg-label-secondary px-2 py-1 rounded">
                                            <i class="bx bx-user-x me-1"></i> Khách vãng lai
                                        </small>
                                    @endif
                                </div>
                            </li>

                            {{-- 2. CÁC THÔNG TIN KHÁC (Giữ nguyên) --}}
                            <li class="mb-2">
                                <span class="fw-bold me-2">Số điện thoại:</span>
                                <span>{{ $order->sdt_nguoi_nhan }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="fw-bold me-2">Địa chỉ:</span>
                                <span>{{ $order->dia_chi_giao_hang }}</span>
                            </li>
                            <li class="mb-2">
                                <span class="fw-bold me-2">Ngày đặt:</span>
                                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="mt-3 pt-3 border-top">
                                <span class="fw-bold d-block mb-2">Phương thức thanh toán:</span>
                                @if(isset($order->phuong_thuc_thanh_toan))
                                    @if($order->phuong_thuc_thanh_toan == 'banking')
                                        <div class="alert alert-warning text-dark mb-0 py-2 shadow-sm border border-warning">
                                            <i class='bx bx-credit-card me-1'></i> <strong>Chuyển khoản Ngân hàng</strong>
                                            <div class="small mt-2 text-danger">⚠️ <strong>LƯU Ý QUAN TRỌNG:</strong><br>Vui lòng kiểm tra sao kê App Ngân hàng xem đã nhận được đúng số tiền với BẮT BUỘC nội dung là: <br><span class="badge bg-danger mt-1 fs-6">{{ $order->ma_don_hang }}</span></div>
                                        </div>
                                    @elseif($order->phuong_thuc_thanh_toan == 'cod')
                                        <div class="alert alert-success mb-0 py-2 shadow-sm">
                                            <i class='bx bx-money me-1'></i> <strong>Tiền mặt (Thanh toán khi nhận hàng - COD)</strong>
                                        </div>
                                    @endif
                                @else
                                    <span class="badge bg-label-secondary">Chưa xác định (Cũ)</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
