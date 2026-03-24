@extends('layouts.admin')
@section('title', 'Cập nhật danh mục')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Danh mục /</span> Cập nhật</h4>

    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chỉnh sửa danh mục</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Quan trọng để gửi PUT request --}}

                        {{-- Tên danh mục --}}
                        <div class="mb-3">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_danh_muc" value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}" required />
                            @error('ten_danh_muc')
                                <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chọn danh mục cha --}}
                        <div class="mb-3">
                            <label class="form-label">Danh mục cha</label>
                            <select class="form-select" name="danh_muc_cha_id">
                                <option value="">-- Là danh mục gốc --</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ $category->danh_muc_cha_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
