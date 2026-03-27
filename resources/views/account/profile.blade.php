@extends('layouts.app')
@section('title', 'Thông tin cá nhân')

@section('content')
<div class="bg-light py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR TRÁI --}}
            <div class="col-12 col-lg-3 profile-sidebar">
                @include('partials.account-sidebar')
            </div>

            {{-- NỘI DUNG FORM --}}
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold mb-0">Thông tin cá nhân</h5>
                    </div>

                    <div class="card-body p-4">

                        {{-- Thông báo đặc biệt cho khách vãng lai vừa mua hàng xong --}}
                        @if(session('account_created'))
                            <div class="alert alert-info mb-4">
                                <i class="fa fa-info-circle me-2"></i> {{ session('account_created') }}
                            </div>
                        @endif

                        <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- 1. AVATAR --}}
                            <div class="avatar-upload d-flex align-items-center gap-3 mb-4">
                                <div class="avatar-circle-lg overflow-hidden position-relative rounded-circle" style="width: 80px; height: 80px; background-color: #e9ecef;">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                             alt="Avatar"
                                             class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-primary fw-bold fs-3">
                                            {{ substr(Auth::user()->ten ?? 'U', 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    <button type="button" class="btn btn-light border btn-sm fw-bold"
                                            onclick="document.getElementById('avatarInput').click()">
                                        <i class="fa fa-camera me-1"></i> Cập nhật ảnh
                                    </button>
                                    <div class="text-muted small mt-1" style="font-size: 12px;">Dung lượng tối đa 5 MB. Định dạng: .webp, .webp</div>
                                </div>
                            </div>

                            {{-- 2. THÔNG TIN CƠ BẢN --}}
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-secondary">Họ và tên</label>
                                        <input type="text" name="ten" class="form-control" value="{{ old('ten', Auth::user()->ten) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-secondary">Ngày sinh</label>
                                        <input type="date" name="ngay_sinh" class="form-control" value="{{ old('ngay_sinh', Auth::user()->ngay_sinh) }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-secondary">Giới tính</label>
                                        <select name="gioi_tinh" class="form-select">
                                            <option value="0" {{ Auth::user()->gioi_tinh == 0 ? 'selected' : '' }}>Khác</option>
                                            <option value="1" {{ Auth::user()->gioi_tinh == 1 ? 'selected' : '' }}>Nam</option>
                                            <option value="2" {{ Auth::user()->gioi_tinh == 2 ? 'selected' : '' }}>Nữ</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-secondary">Số điện thoại</label>
                                        <div class="input-group">
                                            <input type="text" name="sdt" class="form-control" value="{{ old('sdt', Auth::user()->sdt) }}">
                                            <span class="input-group-text bg-white text-success"><i class="fa fa-check-circle"></i></span>
                                        </div>
                                    </div>

                                    {{-- EMAIL (Modal) --}}
                                    <div class="mb-3 d-flex justify-content-between align-items-center border-bottom pb-2 pt-2">
                                        <div class="overflow-hidden me-2">
                                            <label class="form-label mb-0 d-block fw-bold small text-secondary">Email</label>
                                            <div class="text-dark text-truncate">{{ Auth::user()->email ?? 'Chưa cập nhật' }}</div>
                                        </div>
                                        <a href="#" class="text-decoration-none small fw-bold" data-bs-toggle="modal" data-bs-target="#emailModal">
                                            {{ Auth::user()->email ? 'Cập nhật >' : 'Thêm >' }}
                                        </a>
                                    </div>

                                    {{-- MẬT KHẨU (Logic Khách/Thành viên) --}}
                                    <div class="mb-3 border-bottom pb-2 pt-2">
                                        <div class="d-flex justify-content-between align-items-center">

                                            {{-- 1. BÊN TRÁI: Label + Trạng thái + Cảnh báo --}}
                                            <div>
                                                <label class="form-label mb-0 d-block fw-bold small text-secondary">Mật khẩu</label>

                                                @if(Auth::user()->is_guest)
                                                    <div class="d-flex flex-column">
                                                        {{-- Dòng 1: Trạng thái --}}
                                                        <small class="text-danger fst-italic">Bạn chưa thiết lập mật khẩu!</small>

                                                        {{-- Dòng 2: Cảnh báo (Nằm ngay bên dưới, căn trái) --}}
                                                        @if(!Auth::user()->email)
                                                            <small class="text-danger mt-1" style="font-size: 0.75rem;">
                                                                <i class="fa fa-exclamation-circle me-1"></i>Vui lòng cập nhật Email để nhận mã xác thực.
                                                            </small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <small class="text-muted">********</small>
                                                @endif
                                            </div>

                                            {{-- 2. BÊN PHẢI: Nút hành động (Giữ nguyên) --}}
                                            <div>
                                                @if(Auth::user()->is_guest)
                                                    @if(Auth::user()->email)
                                                        <a href="#" class="text-decoration-none small fw-bold text-danger" data-bs-toggle="modal" data-bs-target="#modalSetPassword">
                                                            Thiết lập mật khẩu >
                                                        </a>
                                                    @else
                                                        <span class="text-muted small fw-bold"
                                                            style="cursor: not-allowed; opacity: 0.5;"
                                                            title="Vui lòng cập nhật Email trước">
                                                            Thiết lập mật khẩu >
                                                        </span>
                                                    @endif
                                                @else
                                                    <a href="#" class="text-decoration-none small fw-bold" data-bs-toggle="modal" data-bs-target="#passwordModal">
                                                        Đổi mật khẩu >
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold">Lưu thay đổi</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================== --}}
{{-- CÁC MODAL XỬ LÝ RIÊNG --}}
{{-- =========================================================== --}}

{{-- 1. MODAL ĐỔI MẬT KHẨU (Cho thành viên cũ) --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form action="{{ route('account.change_password') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small text-muted">Mật khẩu hiện tại</label>
                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control" required>
                            <span class="input-group-text bg-white toggle-password" style="cursor: pointer;"><i class="fa fa-eye text-muted"></i></span>
                        </div>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password" class="form-control" required>
                            <span class="input-group-text bg-white toggle-password" style="cursor: pointer;"><i class="fa fa-eye text-muted"></i></span>
                        </div>
                        @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small text-muted">Nhập lại mật khẩu mới</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" class="form-control" required>
                            <span class="input-group-text bg-white toggle-password" style="cursor: pointer;"><i class="fa fa-eye text-muted"></i></span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2">Xác nhận thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL THIẾT LẬP MẬT KHẨU (Cho khách vãng lai - ĐÃ SỬA THEO YÊU CẦU) --}}
<div class="modal fade" id="modalSetPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary">Thiết lập mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-4">
                <form action="{{ route('account.set_password') }}" method="POST">
                    @csrf

                    {{-- ĐÃ XÓA ALERT XANH --}}

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Mật khẩu</label>
                        <div class="input-group">
                            {{-- Giữ nguyên name="new_password" để khớp Controller --}}
                            <input type="password" name="new_password" class="form-control" required placeholder="Nhập mật khẩu của bạn">
                            <span class="input-group-text bg-white toggle-password" style="cursor: pointer;">
                                <i class="fa fa-eye text-muted"></i>
                            </span>
                        </div>
                        @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Nhập lại mật khẩu</label>
                        <div class="input-group">
                            <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Xác nhận lại mật khẩu">
                            <span class="input-group-text bg-white toggle-password" style="cursor: pointer;">
                                <i class="fa fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger fw-bold py-2">Lưu mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 3. MODAL CẬP NHẬT EMAIL (ĐÃ SỬA NÚT) --}}
<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Cập nhật Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form action="{{ route('account.update_email') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted">Địa chỉ Email mới</label>
                        <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                    </div>
                    <div class="d-grid border-0">
                        {{-- Đã sửa text thành Tiếp tục --}}
                        <button type="submit" class="btn btn-primary fw-bold py-2">Tiếp tục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT XỬ LÝ CHUNG --}}
<script>
    // 1. Preview Ảnh Avatar
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var container = document.querySelector('.avatar-circle-lg');
                container.innerHTML = '<img src="' + e.target.result + '" class="w-100 h-100 object-fit-cover">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. Ẩn/Hiện Mật khẩu (Dùng chung cho cả 2 Modal)
    // Script này sẽ tìm tất cả các element có class 'toggle-password'
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Tìm input nằm ngay trước icon con mắt
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (input && input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else if (input) {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // 3. Tự động mở lại Modal nếu có lỗi Validate từ Server trả về
        @if($errors->has('current_password') || ($errors->has('new_password') && !Auth::user()->is_guest))
            // Mở modal đổi mật khẩu thường
            new bootstrap.Modal(document.getElementById('passwordModal')).show();
        @endif

        @if($errors->has('new_password') && Auth::user()->is_guest)
             // Mở modal thiết lập mật khẩu (cho khách)
            new bootstrap.Modal(document.getElementById('modalSetPassword')).show();
        @endif

        @if($errors->has('email'))
            new bootstrap.Modal(document.getElementById('emailModal')).show();
        @endif
    });
</script>
@endsection
