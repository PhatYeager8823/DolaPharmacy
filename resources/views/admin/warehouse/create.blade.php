@extends('layouts.admin')
@section('title', 'Tạo Phiếu Nhập Kho')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- 1. TIÊU ĐỀ & NÚT QUAY LẠI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Kho /</span> Nhập hàng</h4>
        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
        </a>
    </div>

    {{-- 2. KHỐI THÔNG BÁO LỖI (QUAN TRỌNG) --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.warehouse.store') }}" method="POST">
        @csrf

        {{-- SỬA LỖI LAYOUT: Thêm class row --}}
        <div class="row">

            {{-- Cột Trái: Thông tin chung --}}
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Thông tin phiếu</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nhà cung cấp <span class="text-danger">*</span></label>
                            <select name="nha_cung_cap_id" class="form-select select2" required>
                                <option value="">-- Chọn NCC --</option>
                                @foreach($providers as $ncc)
                                    <option value="{{ $ncc->id }}" {{ old('nha_cung_cap_id') == $ncc->id ? 'selected' : '' }}>
                                        {{ $ncc->ten }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <a href="{{ route('admin.suppliers.create') }}" target="_blank" class="small text-primary fw-bold">
                                    <i class="bx bx-plus"></i> Thêm Nhà cung cấp mới
                                </a>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Nhập ghi chú (nếu có)">{{ old('ghi_chu') }}</textarea>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class='bx bx-save'></i> Lưu & Nhập kho
                        </button>
                        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-outline-secondary w-100">Hủy bỏ</a>
                    </div>
                </div>
            </div>

            {{-- Cột Phải: Danh sách hàng hóa --}}
            <div class="col-md-8">
                <div class="card">
                    <h5 class="card-header">Danh sách thuốc cần nhập</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblNhapHang">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40%">Tên thuốc <span class="text-danger">*</span></th>
                                        <th style="width: 15%">Số lượng</th>
                                        <th style="width: 25%">Đơn giá nhập (VNĐ)</th>
                                        <th style="width: 10%" class="text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="wrapper-product">
                                    {{-- Dòng mẫu mặc định --}}
                                    <tr>
                                        <td>
                                            <select name="product_id[]" class="form-select select2-product" required>
                                                <option value="">Chọn thuốc...</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}">
                                                        {{ $p->ten_thuoc }} ({{ $p->ma_thuoc }}) - Tồn: {{ number_format($p->so_luong_ton) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="so_luong[]" class="form-control text-center" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="gia_nhap[]" class="form-control text-end" min="0" placeholder="0" required>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-center">
                            <button type="button" class="btn btn-outline-primary" id="btnAddRow">
                                <i class="bx bx-plus-circle me-1"></i> Thêm dòng sản phẩm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableBody = document.getElementById('wrapper-product');
        const btnAdd = document.getElementById('btnAddRow');

        // Copy dòng đầu tiên để làm mẫu (Template)
        // Lưu ý: Nếu dùng Select2 hoặc JS library khác thì cloneNode có thể không copy được sự kiện của thư viện đó
        // Tuy nhiên với code thuần này thì ổn.
        const templateRow = tableBody.firstElementChild.cloneNode(true);

        btnAdd.addEventListener('click', function() {
            let newRow = templateRow.cloneNode(true);

            // Reset giá trị input trong dòng mới
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('input[type="number"]').value = 1; // Reset số lượng về 1
            newRow.querySelectorAll('select').forEach(select => select.value = ''); // Reset select

            tableBody.appendChild(newRow);
        });

        // Xử lý nút xóa dòng (Event Delegation)
        tableBody.addEventListener('click', function(e) {
            // Kiểm tra xem click có trúng nút xóa (hoặc icon bên trong nút xóa) không
            if (e.target.closest('.remove-row')) {
                if (tableBody.children.length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Phiếu nhập phải có ít nhất 1 sản phẩm!');
                }
            }
        });
    });
</script>
@endsection
