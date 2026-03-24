@extends('layouts.admin')
@section('title', 'Thêm danh mục mới')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Danh mục /</span> Thêm mới</h4>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin danh mục</h5>
                    <small class="text-muted float-end">Nhập thông tin chi tiết</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        {{-- Tên danh mục --}}
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="basic-default-fullname" name="ten_danh_muc" placeholder="Ví dụ: Thuốc giảm đau" required />
                            @error('ten_danh_muc')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chọn danh mục cha --}}
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-country">Danh mục cha (Nếu có)</label>
                            <select class="form-select" id="basic-default-country" name="danh_muc_cha_id">
                                <option value="">-- Là danh mục gốc --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Nếu chọn mục này, đây sẽ là danh mục con.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu lại</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
