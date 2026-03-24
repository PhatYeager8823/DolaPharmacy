@extends('layouts.admin')
@section('title', 'Sửa Nhà cung cấp')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Nhà cung cấp /</span> Cập nhật</h4>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-4">Chỉnh sửa nhà cung cấp</h5>

        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tên --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Tên nhà cung cấp <span class="text-danger">*</span></label>
                <input type="text" name="ten_nha_cung_cap" class="form-control" value="{{ $supplier->ten_nha_cung_cap }}" required />
            </div>

            {{-- Liên hệ --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Số điện thoại</label>
                    <input type="text" name="sdt" class="form-control" value="{{ $supplier->sdt }}" />
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-uppercase small fw-bold text-muted">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $supplier->email }}" />
                </div>
            </div>

            {{-- Địa chỉ --}}
            <div class="mb-3">
                <label class="form-label text-uppercase small fw-bold text-muted">Địa chỉ</label>
                <input type="text" name="dia_chi" class="form-control" value="{{ $supplier->dia_chi }}" />
            </div>

            {{-- Trạng thái --}}
            <div class="mb-4">
                <label class="form-label text-uppercase small fw-bold text-muted d-block">Trạng thái</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="trang_thai" id="statusSwitch" {{ $supplier->trang_thai ? 'checked' : '' }} />
                    <label class="form-check-label" for="statusSwitch">Đang hoạt động</label>
                </div>
            </div>

            {{-- Nút bấm --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
