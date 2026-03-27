@extends('layouts.app')
@section('title', 'Thanh toán')

@section('content')
{{-- Thêm CSS và JS của Tom Select --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
    /* Chỉnh chiều cao danh sách tối đa 200px (khoảng 5 dòng) */
    .ts-dropdown-content { max-height: 200px !important; }
</style>
<div class="bg-light py-4">
    <div class="container">
        {{-- 1. Hiển thị thông báo Lỗi từ Controller (Session Error) --}}
        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            {{-- Giữ Order Code để gửi lên Controller --}}
            <input type="hidden" name="ma_don_hang" value="{{ $ma_don_hang }}">
            {{-- ================================ --}}
            <div class="row g-4">

                {{-- CỘT TRÁI: THÔNG TIN --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="fw-bold mb-0">Thông tin giao hàng</h5>
                        </div>
                        <div class="card-body">

                            {{-- 1. CHỌN TỪ SỔ ĐỊA CHỈ --}}
                            @if(Auth::check() && $addresses->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chọn từ sổ địa chỉ:</label>
                                    <select class="form-select" id="addressSelector" onchange="fillAddress()">
                                        <option value="">-- Chọn địa chỉ có sẵn --</option>
                                        @foreach($addresses as $addr)
                                            <option value="{{ json_encode($addr) }}">
                                                {{ $addr->ten_nguoi_nhan }} - {{ $addr->dia_chi_cu_the }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <hr>
                            @endif

                            <div class="row g-3">
                                {{-- Hàng 1: Họ tên + SĐT --}}
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_nguoi_nhan" id="inputName" class="form-control"
                                        value="{{ Auth::check() ? Auth::user()->ten : '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="sdt_nguoi_nhan" id="inputPhone" class="form-control"
                                        value="{{ Auth::check() ? Auth::user()->sdt : '' }}" required>
                                </div>

                                {{-- Hàng 2: Tỉnh thành + Địa chỉ chi tiết --}}

                                <div class="col-md-8">
                                    <label class="form-label small fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                                    {{-- Thêm class form-control-sm --}}
                                    <input type="text" name="dia_chi_chi_tiet" id="inputDetail" class="form-control form-control-sm"
                                        placeholder="Số nhà, tên đường..." required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                    {{-- Thêm class form-select-sm --}}
                                    <select name="tinh_thanh" id="checkoutProvince" class="form-select form-select-sm" required>
                                        <option value="">-- Chọn --</option>
                                    </select>
                                </div>

                                {{-- Hàng 3: Ghi chú --}}
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Ghi chú (Tùy chọn)</label>
                                    <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                                </div>
                            </div>

                            {{-- Khoảng trống đệm --}}
                            <div style="height: 50px;"></div>
                        </div>
                    </div>

                    {{-- PHƯƠNG THỨC THANH TOÁN --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="fw-bold mb-0">Thanh toán</h5>
                        </div>
                        <div class="card-body">

                            {{-- 1. COD --}}
                            <div class="form-check mb-3">
                                {{-- Thêm class 'payment-radio' để JS bắt sự kiện --}}
                                <input class="form-check-input payment-radio" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label fw-bold" for="cod">
                                    <i class="fa fa-money-bill-alt text-success me-2"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>

                            {{-- 2. Chuyển khoản qua Ngân hàng --}}
                            <div class="form-check">
                                <input class="form-check-input payment-radio" type="radio" name="payment_method" id="banking" value="banking">
                                <label class="form-check-label fw-bold" for="banking">
                                    <i class="fa fa-university text-primary me-2"></i> Chuyển khoản ngân hàng (Quét mã QR)
                                </label>
                            </div>

                            {{-- Khung hiển thị QR tĩnh (Ban đầu ẩn) --}}
                            <div id="qr-info" class="mt-3 p-3 border rounded text-center bg-light" style="display: none;">
                                <h6 class="text-primary fw-bold mb-2">MÃ QR THANH TOÁN TỰ ĐỘNG</h6>
                                @php
                                    $qrAmount = $total + 15000; // Mặc định + 15k ship, JS sẽ tự động sửa nếu Free Ship
                                @endphp
                                <img src="https://img.vietqr.io/image/OCB-{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0919795426') }}-compact2.webp?amount={{ $qrAmount }}&addInfo={{ $ma_don_hang }}&accountName=DUONG THINH PHAT" alt="Mã QR Thanh Toán" class="img-fluid rounded border mb-2" id="dynamic-qr" style="max-height: 200px;">
                                <div class="text-start">
                                    <p class="mb-1 small"><strong>Ngân hàng:</strong> OCB (Phương Đông)</p>
                                    <p class="mb-1 small"><strong>Tài khoản:</strong> DUONG THINH PHAT</p>
                                    <p class="mb-1 small"><strong>Số tài khoản:</strong> <span class="text-danger fw-bold">{{ $global_setting->hotline ?? '0919795426' }}</span></p>
                                    <p class="mb-1 small"><strong>Số tiền:</strong> <span id="qr-amount-text" class="text-danger fw-bold">{{ number_format($qrAmount) }} đ</span></p>
                                    <p class="mb-1 small text-danger"><em>* Chuyển khoản vui lòng ghi đúng nội dung: <strong>{{ $ma_don_hang }}</strong></em></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: TÓM TẮT --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="fw-bold mb-0">Đơn hàng</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                    <li class="list-group-item d-flex align-items-center py-3">
                                        <img src="{{ $item['image'] ? asset('images/images_san_pham/' . $item['image']) : 'https://via.placeholder.com/50' }}"
                                             width="50" height="50" class="rounded border me-3" style="object-fit: contain;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 text-dark two-lines small fw-bold">{{ $item['name'] }}</h6>
                                            <small class="text-muted">x {{ $item['quantity'] }}</small>
                                        </div>
                                        <div class="text-end fw-bold">
                                            {{ number_format($item['price'] * $item['quantity']) }} đ
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-light">
                            {{-- 1. Tạm tính --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <strong>{{ number_format($subtotal ?? $total) }} đ</strong>
                            </div>

                            {{-- 2. Vận chuyển (ĐÃ SỬA: Thêm ID để JS can thiệp) --}}
                            <div class="d-flex justify-content-between mb-2">
                                <span>Vận chuyển:</span>
                                {{-- Mặc định là 15k, JS sẽ sửa lại nếu chọn Bạc Liêu --}}
                                <span id="shipping-fee" class="text-primary">15,000 đ</span>
                            </div>

                            {{-- 3. Mã giảm giá (Giữ nguyên) --}}
                            @if(session()->has('coupon') && isset($discount) && $discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>
                                        <i class="fa fa-ticket-alt me-1"></i> Mã giảm giá ({{ session('coupon')['code'] }}):
                                    </span>
                                    <strong>-{{ number_format($discount) }} đ</strong>
                                </div>
                            @endif

                            <hr>

                            {{-- 4. Tổng cộng (ĐÃ SỬA: Thêm ID và data-base-total) --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fs-5 fw-bold">Tổng cộng:</span>
                                {{-- data-base-total: Lưu số tiền gốc (đã trừ giảm giá voucher nhưng CHƯA cộng ship) --}}
                                <span id="grand-total" class="fs-4 fw-bold text-primary" data-base-total="{{ $total }}">
                                    {{ number_format($total + 15000) }} đ
                                </span>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase shadow">
                                Đặt hàng ngay
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT XỬ LÝ ĐỊA CHỈ --}}
<script>
    // 1. Danh sách tỉnh thành
    const provinces = [
        "Hồ Chí Minh", "Hà Nội", "Đà Nẵng", "Cần Thơ", "Hải Phòng", "An Giang", "Bà Rịa - Vũng Tàu", "Bắc Giang", "Bắc Kạn", "Bạc Liêu", "Bắc Ninh", "Bến Tre", "Bình Định", "Bình Dương", "Bình Phước", "Bình Thuận", "Cà Mau", "Cao Bằng", "Đắk Lắk", "Đắk Nông", "Điện Biên", "Đồng Nai", "Đồng Tháp", "Gia Lai", "Hà Giang", "Hà Nam", "Hà Tĩnh", "Hải Dương", "Hậu Giang", "Hòa Bình", "Hưng Yên", "Khánh Hòa", "Kiên Giang", "Kon Tum", "Lai Châu", "Lâm Đồng", "Lạng Sơn", "Lào Cai", "Long An", "Nam Định", "Nghệ An", "Ninh Bình", "Ninh Thuận", "Phú Thọ", "Quảng Bình", "Quảng Nam", "Quảng Ngãi", "Quảng Ninh", "Quảng Trị", "Sóc Trăng", "Sơn La", "Tây Ninh", "Thái Bình", "Thái Nguyên", "Thanh Hóa", "Thừa Thiên Huế", "Tiền Giang", "Trà Vinh", "Tuyên Quang", "Vĩnh Long", "Vĩnh Phúc", "Yên Bái"
    ];

    let tomSelectInstance = null;

    // === HÀM TÍNH PHÍ SHIP ===
    function calculateShipping(provinceName) {
        const shippingEl = document.getElementById('shipping-fee');
        const totalEl = document.getElementById('grand-total');

        // Lấy tiền gốc (Đã trừ voucher) từ data attribute
        let baseTotal = parseFloat(totalEl.getAttribute('data-base-total'));
        let shipFee = 15000; // Mặc định 15k

        // Logic kiểm tra Bạc Liêu (Không phân biệt hoa thường)
        if (provinceName && provinceName.toLowerCase().includes('bạc liêu')) {
            shipFee = 0;
        }

        // Cập nhật giao diện
        if (shipFee === 0) {
            shippingEl.innerText = 'Miễn phí';
            shippingEl.className = 'text-success fw-bold';
        } else {
            shippingEl.innerText = new Intl.NumberFormat('vi-VN').format(shipFee) + ' đ';
            shippingEl.className = 'text-primary'; // Màu xanh dương thường
        }

        // Cập nhật Tổng tiền cuối cùng
        let finalTotal = baseTotal + shipFee;
        totalEl.innerText = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' đ';

        // Cập nhật lại Mã QR VietQR động để giá tiền ăn khớp luôn
        const dynamicQr = document.getElementById('dynamic-qr');
        const qrAmountText = document.getElementById('qr-amount-text');
        if (dynamicQr) {
            let orderCode = '{{ $ma_don_hang }}';
            dynamicQr.src = `https://img.vietqr.io/image/OCB-${'{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0919795426') }}'}-compact2.webp?amount=${finalTotal}&addInfo=${orderCode}&accountName=DUONG THINH PHAT`;
            if (qrAmountText) {
                qrAmountText.innerText = new Intl.NumberFormat('vi-VN').format(finalTotal) + ' đ';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('checkoutProvince');

        // Sắp xếp và thêm option
        provinces.sort((a, b) => a.localeCompare(b));
        provinces.forEach(prov => {
            const opt = document.createElement('option');
            opt.value = prov;
            opt.text = prov;
            select.appendChild(opt);
        });

        // Kích hoạt Tom Select & Gắn sự kiện thay đổi
        tomSelectInstance = new TomSelect("#checkoutProvince",{
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "-- Chọn Tỉnh/Thành --",
            onChange: function(value) {
                // Gọi hàm tính phí ship mỗi khi chọn tỉnh
                calculateShipping(value);
            }
        });

        // Gọi 1 lần lúc đầu (để set mặc định 15k)
        calculateShipping('');
    });

    // Hàm điền tự động (Sổ địa chỉ)
    function fillAddress() {
        const select = document.getElementById('addressSelector');
        if(!select.value) return;

        const data = JSON.parse(select.value);

        document.getElementById('inputName').value = data.ten_nguoi_nhan;
        document.getElementById('inputPhone').value = data.sdt_nguoi_nhan;

        let fullAddr = data.dia_chi_cu_the;
        let lastComma = fullAddr.lastIndexOf(',');

        if (lastComma !== -1) {
            let city = fullAddr.substring(lastComma + 1).trim();
            let detail = fullAddr.substring(0, lastComma).trim();

            // Cập nhật giá trị cho Tom Select -> Nó sẽ tự kích hoạt sự kiện onChange ở trên
            if(tomSelectInstance) {
                tomSelectInstance.setValue(city);
            }
            document.getElementById('inputDetail').value = detail;
        } else {
            document.getElementById('inputDetail').value = fullAddr;
        }
    }

    // === JS XỬ LÝ ẨN HIỆN QR CODE LẠI NHƯ CŨ ===
    document.addEventListener('DOMContentLoaded', function() {
        const qrInfo = document.getElementById('qr-info');
        const paymentRadios = document.querySelectorAll('.payment-radio');

        function toggleQR() {
            const selected = document.querySelector('input[name="payment_method"]:checked');
            if (selected && selected.value === 'banking') {
                qrInfo.style.display = 'block'; 
            } else {
                qrInfo.style.display = 'none';  
            }
        }

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', toggleQR);
        });

        toggleQR();
    });

    // === THÊM ĐOẠN NÀY ĐỂ CHẶN DOUBLE CLICK ===
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('checkoutForm');
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');

            // 1. Khóa nút lại ngay lập tức
            btn.disabled = true;

            // 2. Đổi nội dung nút để báo hiệu đang xử lý
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Đang xử lý...';
        });
    });
</script>
@endsection
