@extends('layouts.admin')
@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- 1. TIÊU ĐỀ & NÚT QUAY LẠI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-label-secondary me-2">
                <i class="bx bx-arrow-back me-1"></i> Quay lại
            </a>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bx bx-printer me-1"></i> In hóa đơn
            </button>
        </div>
        <h4 class="fw-bold py-3 mb-0 text-white">Đơn hàng <span class="text-info">#{{ $order->ma_don_hang }}</span></h4>
    </div>

    {{-- ⚡ CHỈNH SỬA GIAO DIỆN PREMIUM CYBER-GLASS ⚡ --}}
    <style>
        .order-title-glow {
            color: #ffffff;
            text-shadow: 0 0 15px rgba(0, 242, 254, 0.4);
            font-weight: 800;
        }
        .table-cyber thead th {
            background: rgba(0, 242, 254, 0.12) !important;
            color: #00f2fe;
            text-shadow: 0 0 5px rgba(0, 242, 254, 0.3);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1.2px;
            border-bottom: 2px solid rgba(0, 242, 254, 0.25);
            padding: 18px 20px;
        }
        .product-hover-row:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            transition: all 0.3s ease;
        }
        .total-glow-price {
            color: #00f2fe !important;
            text-shadow: 0 0 15px rgba(0, 242, 254, 1);
            font-size: 1.5rem !important;
            font-weight: 800 !important;
        }
        
        /* Webhook Terminal Style - Đậm hơn */
        .cyber-terminal-v2 {
            background: rgba(5, 10, 20, 0.98) !important;
            border: 1px solid rgba(0, 242, 254, 0.5) !important;
            border-radius: 12px;
            padding: 15px;
            font-family: 'Courier New', Courier, monospace;
            box-shadow: inset 0 0 15px rgba(0, 242, 254, 0.15);
        }

        /* Status Neon Boxes - Tăng độ tương phản */
        .status-glass-success {
            background: rgba(16, 185, 129, 0.2) !important;
            border: 2px solid #10b981 !important;
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.4);
            border-radius: 16px;
            padding: 25px;
            color: #ffffff !important;
        }
        .status-glass-danger {
            background: rgba(239, 68, 68, 0.2) !important;
            border: 2px solid #ef4444 !important;
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.4);
            border-radius: 16px;
            padding: 25px;
            color: #ffffff !important;
        }
        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .info-row:last-child { border-bottom: none; }
        .info-icon {
            width: 32px; height: 32px;
            background: rgba(0, 242, 254, 0.1);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 12px; color: #00f2fe;
        }
        .order-badge-id {
            background: rgba(0, 242, 254, 0.15);
            color: #00f2fe;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            border: 1px solid rgba(0, 242, 254, 0.3);
        }

        @media print {
            body * { visibility: hidden !important; }
            #printable-invoice, #printable-invoice * { visibility: visible !important; }
            #printable-invoice {
                display: block !important;
                position: absolute; left: 0; top: 0; width: 100%;
                padding: 20px; color: #000 !important; background: #fff !important;
            }
            body { background: white !important; }
            .table-bordered th, .table-bordered td { border-color: #000 !important; color: #000 !important; }
        }
    </style>

    <div class="row">
        {{-- CỘT TRÁI: THÔNG TIN SẢN PHẨM --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-bottom border-secondary py-3">
                    <h5 class="mb-0 text-white fw-bold"><i class="bx bx-list-check me-2 text-info"></i>Dòng thời gian sản phẩm</h5>
                    <span class="badge bg-label-info">{{ $order->items->count() }} mặt hàng</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-cyber mb-0">
                        <thead>
                            <tr>
                                <th style="padding-left: 20px;">Sản phẩm</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end" style="padding-right: 20px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="product-hover-row">
                                <td style="padding-left: 20px;">
                                    <div class="d-flex align-items-center">
                                        <div class="p-1 rounded bg-white me-3" style="width: 50px; height: 50px; border: 1px solid rgba(255,255,255,0.1);">
                                            <img src="{{ $item->thuoc && $item->thuoc->hinh_anh ? asset('images/images_san_pham/'.$item->thuoc->hinh_anh) : 'https://via.placeholder.com/50' }}"
                                                 class="rounded" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                        <div>
                                            <strong class="d-block text-white" style="font-size: 0.9rem;">{{ $item->ten_thuoc }}</strong>
                                            <span class="order-badge-id mt-1 d-inline-block">{{ $item->thuoc->ma_san_pham ?? 'SKU-00' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-bold">{{ $item->so_luong }}</td>
                                <td class="text-end text-muted">{{ number_format($item->gia_ban) }} đ</td>
                                <td class="text-end fw-bold text-white px-3" style="padding-right: 20px;">{{ number_format($item->thanh_tien) }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top border-secondary">
                                <td colspan="3" class="text-end fw-bold py-4">TỔNG CỘNG ĐƠN HÀNG:</td>
                                <td class="text-end fw-bold total-glow-price py-4" style="padding-right: 20px;">{{ number_format($order->tong_tien) }} đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: TRẠNG THÁI & THÔNG TIN --}}
        <div class="col-md-4">
            {{-- THẺ TRẠNG THÁI --}}
            <div class="card glass-card-order mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-white d-flex align-items-center">
                        <i class="bx bx-cog me-2 text-primary"></i>Xử lý đơn hàng
                    </h5>

                    @if($order->trang_thai == 'da_giao' || $order->trang_thai == 'da_huy')
                        <div class="{{ $order->trang_thai == 'da_giao' ? 'status-glass-success' : 'status-glass-danger' }} text-center">
                            <i class='bx {{ $order->trang_thai == 'da_giao' ? 'bx-check-double' : 'bx-error-circle' }} bx-lg mb-2'></i>
                            <h5 class="fw-bold mb-0">
                                {{ $order->trang_thai == 'da_giao' ? 'ĐƠN HÀNG ĐÃ GIAO' : 'ĐƠN HÀNG ĐÃ HỦY' }}
                            </h5>
                        </div>
                    @else
                        <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="mb-4">
                                <label class="form-label text-muted small text-uppercase fw-bold">Trạng thái vận chuyển</label>
                                <select name="trang_thai" class="form-select bg-dark text-white border-secondary">
                                    <option value="cho_xac_nhan" {{ $order->trang_thai == 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="cho_lay_hang" {{ $order->trang_thai == 'cho_lay_hang' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                    <option value="dang_giao" {{ $order->trang_thai == 'dang_giao' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="da_giao" {{ $order->trang_thai == 'da_giao' ? 'selected' : '' }}>Đã giao thành công</option>
                                    <option value="tra_hang">Trả hàng</option>
                                    <option value="da_huy">Hủy đơn hàng</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Cập nhật đơn hàng</button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- WEBHOOK SIMULATOR --}}
            @if(in_array($order->trang_thai, ['cho_lay_hang', 'dang_giao']))
            <div class="card mb-4" style="border: 1px solid rgba(0, 242, 254, 0.3) !important;">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 d-flex align-items-center" style="color: #00f2fe;">
                        <i class="bx bx-terminal me-2"></i>Mô phỏng Webhook ĐVVC
                    </h6>
                    <div class="cyber-terminal-v2 mb-3">
                        <code class="d-block" style="color: #4ade80; font-size: 0.75rem;">$ POST /api/webhook/shipping/{{ $order->ma_don_hang }}</code>
                    </div>
                    <button type="button" id="btn-admin-webhook" class="btn btn-sm w-100"
                        style="background: rgba(0,242,254,0.1); border: 1px solid #00f2fe; color: #00f2fe;"
                        onclick="startAdminSimulate()">
                        <i class="bx bxs-zap me-1"></i> GIẢ LẬP VẬN CHUYỂN
                    </button>
                </div>
            </div>
            <script>
                function startAdminSimulate() {
                    const btn = document.getElementById('btn-admin-webhook');
                    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Đang giả lập...';
                    btn.disabled = true;
                    setTimeout(() => {
                        fetch('{{ route('api.webhook.simulate', $order->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => window.location.reload());
                    }, 3000);
                }
            </script>
            @endif

            {{-- THÔNG TIN NHẬN HÀNG --}}
            <div class="card glass-card-order mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 text-white d-flex align-items-center">
                        <i class="bx bx-map-pin me-2 text-warning"></i>Thông tin nhận hàng
                    </h5>
                    
                    <div class="info-row">
                        <div class="info-icon"><i class="bx bx-user"></i></div>
                        <div>
                            <small class="text-muted d-block">Người nhận</small>
                            <strong class="text-white">{{ $order->ten_nguoi_nhan }}</strong>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bx bx-phone"></i></div>
                        <div>
                            <small class="text-muted d-block">Điện thoại</small>
                            <strong class="text-white">{{ $order->sdt_nguoi_nhan }}</strong>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bx bx-current-location"></i></div>
                        <div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Địa chỉ giao hàng</small>
                            <span class="text-white" style="font-size: 0.85rem;">{{ $order->dia_chi_giao_hang }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bx bx-calendar"></i></div>
                        <div>
                            <small class="text-muted d-block">Thời gian đặt</small>
                            <strong class="text-white" style="font-size: 0.85rem;">{{ $order->created_at->format('H:i - d/m/Y') }}</strong>
                        </div>
                    </div>

                    <div class="mt-4">
                        <small class="text-muted d-block mb-2 text-uppercase fw-bold" style="font-size: 0.7rem;">Thanh toán</small>
                        @if($order->phuong_thuc_thanh_toan == 'banking')
                            <div class="p-3 rounded border border-warning" style="background: rgba(255, 171, 0, 0.05);">
                                <div class="text-warning fw-bold small"><i class='bx bx-credit-card me-1'></i>CHUYỂN KHOẢN</div>
                                <div class="text-white mt-1" style="font-size: 0.75rem;">Nội dung: <span class="badge bg-warning text-dark">{{ $order->ma_don_hang }}</span></div>
                            </div>
                        @elseif($order->phuong_thuc_thanh_toan == 'momo')
                            <div class="p-2 rounded border" style="border-color: #d2005a !important; background: rgba(210, 0, 80, 0.05);">
                                <div class="fw-bold small" style="color: #d2005a;"><i class='bx bx-wallet me-1'></i>VÍ MOMO</div>
                            </div>
                        @else
                            <div class="p-2 rounded border border-success" style="background: rgba(113, 221, 55, 0.05);">
                                <div class="text-success fw-bold small"><i class='bx bx-money me-1'></i>TIỀN MẶT (COD)</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- IN HÓA ĐƠN --}}
    <div id="printable-invoice" class="d-none">
        <div class="text-center mb-4">
            <h3 class="fw-bold">DOLA PHARMACY</h3>
            <p><strong>HÓA ĐƠN BÁN LẺ</strong></p>
            <p>Đơn hàng: #{{ $order->ma_don_hang }} | Ngày: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <div class="mb-3 border-top border-bottom py-2">
            <p><strong>Khách hàng:</strong> {{ $order->ten_nguoi_nhan }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->dia_chi_giao_hang }}</p>
        </div>
        <table class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">SL</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->ten_thuoc }}</td>
                    <td class="text-center">{{ $item->so_luong }}</td>
                    <td class="text-end">{{ number_format($item->gia_ban) }}</td>
                    <td class="text-end fw-bold">{{ number_format($item->thanh_tien) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">TỔNG CỘNG:</td>
                    <td class="text-end fw-bold">{{ number_format($order->tong_tien) }} đ</td>
                </tr>
            </tfoot>
        </table>
        <div class="text-center mt-5">
            <p><em>Cảm ơn Quý khách! Hẹn gặp lại!</em></p>
        </div>
    </div>
</div>
@endsection
