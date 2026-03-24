@extends('layouts.admin')
@section('title', 'Gửi Thông Báo Mới')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Thông Báo /</span> Gửi mới</h4>
        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <h5 class="card-header">Soạn thông báo</h5>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf

                {{-- Tiêu đề --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="tieu_de" class="form-control @error('tieu_de') is-invalid @enderror"
                           value="{{ old('tieu_de') }}" placeholder="Nhập tiêu đề thông báo...">
                    @error('tieu_de')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Nội dung --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="noi_dung" rows="4" class="form-control @error('noi_dung') is-invalid @enderror"
                              placeholder="Nhập nội dung chi tiết...">{{ old('noi_dung') }}</textarea>
                    @error('noi_dung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Loại thông báo --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Loại <span class="text-danger">*</span></label>
                    <select name="loai" class="form-select @error('loai') is-invalid @enderror">
                        <option value="">-- Chọn loại --</option>
                        <option value="thong_bao"  {{ old('loai') == 'thong_bao'  ? 'selected' : '' }}>Thông báo chung</option>
                        <option value="don_hang"   {{ old('loai') == 'don_hang'   ? 'selected' : '' }}>Đơn hàng</option>
                        <option value="khuyen_mai" {{ old('loai') == 'khuyen_mai' ? 'selected' : '' }}>Khuyến mãi</option>
                        <option value="he_thong"   {{ old('loai') == 'he_thong'   ? 'selected' : '' }}>Hệ thống</option>
                        <option value="bao_mat"    {{ old('loai') == 'bao_mat'    ? 'selected' : '' }}>Bảo mật</option>
                    </select>
                    @error('loai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Đường dẫn (tùy chọn) --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Đường dẫn khi click (tùy chọn)</label>
                    <input type="text" name="duong_dan" class="form-control"
                           value="{{ old('duong_dan') }}" placeholder="Ví dụ: /don-hang/123">
                </div>

                {{-- Người nhận --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Người nhận <span class="text-danger">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="nguoi_nhan" id="nguoi_nhan_all"
                               value="all" {{ old('nguoi_nhan', 'all') == 'all' ? 'checked' : '' }}
                               onclick="document.getElementById('specific-users').style.display='none'">
                        <label class="form-check-label" for="nguoi_nhan_all">
                            <strong>Tất cả người dùng</strong>
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="radio" name="nguoi_nhan" id="nguoi_nhan_specific"
                               value="specific" {{ old('nguoi_nhan') == 'specific' ? 'checked' : '' }}
                               onclick="document.getElementById('specific-users').style.display='block'">
                        <label class="form-check-label" for="nguoi_nhan_specific">
                            Chọn người dùng cụ thể
                        </label>
                    </div>
                </div>

                {{-- Chọn người dùng cụ thể --}}
                <div id="specific-users" class="mb-3" style="display: {{ old('nguoi_nhan') == 'specific' ? 'block' : 'none' }};">
                    <label class="form-label fw-bold">Chọn người dùng</label>
                    <select name="nguoi_dung_id[]" class="form-select @error('nguoi_dung_id') is-invalid @enderror" multiple size="8">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ is_array(old('nguoi_dung_id')) && in_array($user->id, old('nguoi_dung_id')) ? 'selected' : '' }}>
                                {{ $user->ten }} — {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Giữ Ctrl để chọn nhiều người.</small>
                    @error('nguoi_dung_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-send me-1"></i> Gửi thông báo
                    </button>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary ms-2">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
