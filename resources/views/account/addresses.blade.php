@extends('layouts.app')
@section('title', 'Sổ địa chỉ nhận hàng')

@section('content')
{{-- 1. THÊM THƯ VIỆN TOM SELECT (GIỐNG TRANG CHECKOUT) --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<style>
    /* Chỉnh chiều cao danh sách tối đa */
    .ts-dropdown-content { max-height: 200px !important; }

    /* QUAN TRỌNG: Sửa lỗi Dropdown bị chìm xuống dưới Modal */
    .ts-dropdown { z-index: 9999 !important; }
</style>

<div class="bg-light py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR --}}
            <div class="col-12 col-lg-3 profile-sidebar">
                @include('partials.account-sidebar')
            </div>

            {{-- NỘI DUNG CHÍNH --}}
            <div class="col-12 col-lg-9">
                {{-- Hiển thị thông báo --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Sổ địa chỉ</h5>
                        <button class="btn btn-primary btn-sm fw-bold shadow-sm" onclick="openAddModal()">
                            <i class="fa fa-plus me-1"></i> Thêm địa chỉ mới
                        </button>
                    </div>

                    <div class="card-body">
                        @if($addresses->count() > 0)
                            <div class="row g-3">
                                @foreach($addresses as $addr)
                                    <div class="col-12">
                                        <div class="border rounded p-3 position-relative {{ $addr->mac_dinh ? 'border-primary bg-primary bg-opacity-10' : 'bg-white' }} shadow-sm">

                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        {{ $addr->ten_nguoi_nhan }}
                                                        @if($addr->mac_dinh)
                                                            <span class="badge bg-primary ms-2" style="font-size: 10px;">Mặc định</span>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-1 text-secondary small">
                                                        <i class="fa fa-phone-alt me-1"></i> {{ $addr->sdt_nguoi_nhan }}
                                                    </p>
                                                    <p class="mb-0 text-secondary small">
                                                        <i class="fa fa-map-marker-alt me-1"></i> {{ $addr->dia_chi_cu_the }}
                                                    </p>
                                                </div>

                                                <div class="d-flex flex-column align-items-end gap-2">
                                                    <div>
                                                        {{-- Nút cập nhật --}}
                                                        <button class="btn btn-link btn-sm text-decoration-none p-0 me-2 fw-bold"
                                                                onclick="openEditModal({{ json_encode($addr) }})">Cập nhật</button>

                                                        @if(!$addr->mac_dinh)
                                                            <button class="btn btn-link btn-sm text-danger text-decoration-none p-0 fw-bold"
                                                                    onclick="deleteAddress({{ $addr->id }})">Xóa</button>
                                                        @endif
                                                    </div>

                                                    @if(!$addr->mac_dinh)
                                                        <a href="{{ route('address.set_default', $addr->id) }}" class="btn btn-outline-secondary btn-sm py-0" style="font-size: 12px;">Thiết lập mặc định</a>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/5fafbb923393b712b96488590b8f781f.webp" width="100" style="opacity: 0.5">
                                <p class="text-muted mt-3">Bạn chưa có địa chỉ nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MODAL THÊM / SỬA ĐỊA CHỈ --}}
<div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="addressForm" action="{{ route('address.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Họ và tên</label>
                        <input type="text" name="ten_nguoi_nhan" id="inputTen" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Số điện thoại</label>
                        <input type="text" name="sdt_nguoi_nhan" id="inputSDT" class="form-control" required>
                    </div>

                    {{-- DROPDOWN TỈNH THÀNH (Sẽ được Tom Select biến đổi) --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Tỉnh / Thành phố</label>
                        <select name="tinh_thanh" id="inputTinhThanh" class="form-select" required>
                            <option value="">-- Chọn Tỉnh / Thành --</option>
                            {{-- Option sẽ được JS thêm vào --}}
                        </select>
                    </div>

                    {{-- ĐỊA CHỈ CHI TIẾT --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Địa chỉ chi tiết</label>
                        <textarea name="dia_chi_chi_tiet" id="inputDiaChiChiTiet" class="form-control" rows="2"
                                  placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." required></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="mac_dinh" id="inputMacDinh" value="1">
                        <label class="form-check-label small" for="inputMacDinh">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2">Hoàn thành</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- FORM XÓA ẨN --}}
<form id="deleteForm" action="" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
    // 1. Danh sách tỉnh thành chuẩn (Giống hệt file Checkout)
    const provinces = [
        "Hồ Chí Minh", "Hà Nội", "Đà Nẵng", "Cần Thơ", "Hải Phòng", "An Giang", "Bà Rịa - Vũng Tàu", "Bắc Giang", "Bắc Kạn", "Bạc Liêu", "Bắc Ninh", "Bến Tre", "Bình Định", "Bình Dương", "Bình Phước", "Bình Thuận", "Cà Mau", "Cao Bằng", "Đắk Lắk", "Đắk Nông", "Điện Biên", "Đồng Nai", "Đồng Tháp", "Gia Lai", "Hà Giang", "Hà Nam", "Hà Tĩnh", "Hải Dương", "Hậu Giang", "Hòa Bình", "Hưng Yên", "Khánh Hòa", "Kiên Giang", "Kon Tum", "Lai Châu", "Lâm Đồng", "Lạng Sơn", "Lào Cai", "Long An", "Nam Định", "Nghệ An", "Ninh Bình", "Ninh Thuận", "Phú Thọ", "Quảng Bình", "Quảng Nam", "Quảng Ngãi", "Quảng Ninh", "Quảng Trị", "Sóc Trăng", "Sơn La", "Tây Ninh", "Thái Bình", "Thái Nguyên", "Thanh Hóa", "Thừa Thiên Huế", "Tiền Giang", "Trà Vinh", "Tuyên Quang", "Vĩnh Long", "Vĩnh Phúc", "Yên Bái"
    ];

    var modal = null;
    var tomSelectInstance = null; // Biến lưu đối tượng Tom Select

    document.addEventListener('DOMContentLoaded', function() {
        // A. Khởi tạo Modal
        var modalEl = document.getElementById('addressModal');
        if(modalEl) {
            modal = new bootstrap.Modal(modalEl);
        }

        // B. Khởi tạo Tom Select
        const selectElement = document.getElementById('inputTinhThanh');

        // Sắp xếp và thêm option vào thẻ select gốc trước
        provinces.sort((a, b) => a.localeCompare(b));
        provinces.forEach(prov => {
            const opt = document.createElement('option');
            opt.value = prov;
            opt.text = prov;
            selectElement.appendChild(opt);
        });

        // Biến nó thành Tom Select xịn xò
        tomSelectInstance = new TomSelect("#inputTinhThanh",{
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "-- Chọn Tỉnh/Thành --",
            allowEmptyOption: true,
            maxOptions: null
        });
    });

    // Mở Modal Thêm
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Thêm địa chỉ mới';
        document.getElementById('addressForm').action = "{{ route('address.store') }}";
        document.getElementById('formMethod').value = "POST";

        // Reset form
        document.getElementById('inputTen').value = '';
        document.getElementById('inputSDT').value = '';
        document.getElementById('inputDiaChiChiTiet').value = '';
        document.getElementById('inputMacDinh').checked = false;

        // Reset Tom Select về rỗng
        if(tomSelectInstance) {
            tomSelectInstance.clear();
        }

        modal.show();
    }

    // Mở Modal Sửa
    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = 'Cập nhật địa chỉ';

        let url = "{{ route('address.update', ':id') }}";
        url = url.replace(':id', data.id);
        document.getElementById('addressForm').action = url;
        document.getElementById('formMethod').value = "PUT";

        document.getElementById('inputTen').value = data.ten_nguoi_nhan;
        document.getElementById('inputSDT').value = data.sdt_nguoi_nhan;
        document.getElementById('inputMacDinh').checked = data.mac_dinh == 1;

        // --- TÁCH ĐỊA CHỈ & SET GIÁ TRỊ CHO TOM SELECT ---
        let fullAddr = data.dia_chi_cu_the;
        let lastComma = fullAddr.lastIndexOf(',');

        if (lastComma !== -1) {
            let city = fullAddr.substring(lastComma + 1).trim();
            let detail = fullAddr.substring(0, lastComma).trim();

            // Set giá trị cho Tom Select (Nó sẽ tự chọn đúng Tỉnh)
            if(tomSelectInstance) {
                tomSelectInstance.setValue(city);
            }
            document.getElementById('inputDiaChiChiTiet').value = detail;
        } else {
            // Trường hợp không có dấu phẩy
            document.getElementById('inputDiaChiChiTiet').value = fullAddr;
            if(tomSelectInstance) {
                tomSelectInstance.clear();
            }
        }

        modal.show();
    }

    function deleteAddress(id) {
        if(confirm('Bạn có chắc muốn xóa địa chỉ này?')) {
            let url = "{{ route('address.destroy', ':id') }}";
            url = url.replace(':id', id);
            let form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection
