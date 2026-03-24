@extends('layouts.admin')
@section('title', 'Cập nhật thương hiệu')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Thương hiệu /</span> Cập nhật</h4>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chỉnh sửa thương hiệu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten" value="{{ old('ten', $brand->ten) }}" required />
                            @error('ten') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Xuất xứ</label>
                            <input type="text" class="form-control" name="xuat_xu" value="{{ old('xuat_xu', $brand->xuat_xu) }}" />
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
