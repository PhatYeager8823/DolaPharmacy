@extends('layouts.admin')
@section('title', 'Thêm Nhà cung cấp')

@section('content')
{{-- THÊM DIV BAO NGOÀI NÀY --}}
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Nhà cung cấp /</span> Thêm mới</h4>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-4">Thông tin nhà cung cấp</h5>
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Tên nhà cung cấp <span class="text-danger">*</span></label>
                    <input type="text" name="ten_nha_cung_cap" class="form-control" placeholder="VD: Dược Hậu Giang..." required />
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Số điện thoại</label>
                        <input type="text" name="sdt" class="form-control" placeholder="VD: 028..." />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-uppercase small fw-bold text-muted">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="VD: contact@example.com" />
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control" placeholder="VD: KCN Tân Tạo, TP.HCM..." />
                </div>
                <div class="mb-4">
                    <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="trang_thai" id="statusSwitch" checked />
                        <label class="form-check-label" for="statusSwitch">Kích hoạt</label>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Lưu lại</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>

</div>
{{-- KẾT THÚC DIV BAO --}}
@endsection
