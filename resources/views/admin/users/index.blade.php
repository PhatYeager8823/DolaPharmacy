@extends('layouts.admin')
@section('title', 'Danh sách Khách hàng')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Người dùng /</span> Danh sách khách hàng
    </h4>

    <div class="card">
        {{-- Header: Tiêu đề + Tổng số --}}
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Khách hàng đã đăng ký</h5>
            <span class="badge bg-primary">{{ $users->total() }} Tài khoản</span>
        </div>

        {{-- Toolbar: Tìm kiếm & Bộ lọc (Đã tách riêng cho đẹp) --}}
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <div class="d-flex align-items-center gap-2">
                {{-- Có thể thêm bộ lọc trạng thái ở đây nếu cần --}}
            </div>

            {{-- Form Tìm kiếm (Dài rộng thoải mái) --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex" style="width: 400px; max-width: 100%;">
                <div class="input-group position-relative" id="user-search-group">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="keyword" id="user-search-input" class="form-control" 
                           placeholder="Tìm kiếm theo Tên, SĐT, Email..." value="{{ request('keyword') }}"
                           autocomplete="off">
                    <button type="submit" class="btn btn-primary">Tìm</button>
                    @if(request('keyword'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Xóa tìm kiếm"><i class="bx bx-x"></i></a>
                    @endif
                    {{-- Khung gợi ý Cyber-Glass --}}
                    <div id="user-suggestions" class="search-results-container scrollbar-hidden" style="top: 100%; width: 100%;"></div>
                </div>
            </form>
        </div>

        {{-- Bảng dữ liệu --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Liên hệ</th>
                        <th>Ngày tham gia</th>
                        <th>Trạng thái</th>
                        <th width="100">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($users as $user)
                    <tr>
                        <td><strong>#{{ $user->id }}</strong></td>

                        {{-- Avatar + Tên --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" style="object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($user->ten, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-primary d-block">{{ $user->ten }}</strong>
                                    <small class="text-muted">
                                        @if($user->gioi_tinh == 1) Nam
                                        @elseif($user->gioi_tinh == 2) Nữ
                                        @else Khác @endif
                                    </small>
                                </div>
                            </div>
                        </td>

                        {{-- Liên hệ --}}
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $user->sdt ?? '---' }}</span>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </td>

                        <td>{{ $user->created_at->format('d/m/Y') }}</td>

                        {{-- Trạng thái --}}
                        <td>
                            @if($user->trang_thai)
                                <span class="badge bg-label-success">Hoạt động</span>
                            @else
                                <span class="badge bg-label-danger">Đã khóa</span>
                            @endif
                        </td>

                        {{-- Hành động --}}
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-icon {{ $user->trang_thai ? 'btn-label-warning' : 'btn-label-success' }}"
                                            title="{{ $user->trang_thai ? 'Khóa tài khoản' : 'Mở khóa' }}">
                                        @if($user->trang_thai)
                                            <i class="bx bx-lock-alt"></i>
                                        @else
                                            <i class="bx bx-lock-open-alt"></i>
                                        @endif
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-icon btn-label-danger delete-confirm-btn" 
                                            data-message="CẢNH BÁO: Xóa khách hàng sẽ mất toàn bộ lịch sử đơn hàng. Bạn có chắc chắn không?" title="Xóa vĩnh viễn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bx bx-search fs-1 d-block mb-2"></i>
                            Không tìm thấy khách hàng nào phù hợp.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($users->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const $input = $('#user-search-input');
    const $suggestions = $('#user-suggestions');
    let debounceTimer;

    $input.on('input', function() {
        clearTimeout(debounceTimer);
        const keyword = $(this).val().trim();

        if (keyword.length < 2) {
            $suggestions.hide();
            return;
        }

        debounceTimer = setTimeout(() => {
            $.ajax({
                url: "{{ route('admin.users.suggest') }}",
                data: { keyword: keyword },
                success: function(users) {
                    if (users.length > 0) {
                        let html = '';
                        users.forEach(u => {
                            const avatarPath = u.avatar ? `{{ asset('storage') }}/${u.avatar}` : null;
                            const initial = u.ten.charAt(0).toUpperCase();
                            
                            let avatarHtml = '';
                            if (avatarPath) {
                                avatarHtml = `<img src="${avatarPath}" class="rounded-circle me-2" width="35" height="35" style="object-fit: cover;">`;
                            } else {
                                avatarHtml = `<div class="avatar-initial rounded-circle bg-label-primary me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-size: 0.8rem;">${initial}</div>`;
                            }

                            html += `
                                <div class="search-result-item py-2 px-3 border-bottom cursor-pointer" style="border-color: rgba(255,255,255,0.05) !important;" data-name="${u.ten}">
                                    <div class="d-flex align-items-center">
                                        ${avatarHtml}
                                        <div class="flex-grow-1 overflow-hidden text-start">
                                            <div class="fw-bold text-truncate" style="font-size: 0.9rem; color: #fff;">${u.ten}</div>
                                            <small class="text-info d-block text-truncate" style="font-size: 0.75rem;">${u.sdt || u.email}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $suggestions.html(html).fadeIn(200);
                    } else {
                        $suggestions.hide();
                    }
                }
            });
        }, 300);
    });

    // Khi click vào một khách hàng
    $(document).on('click', '.search-result-item', function() {
        const name = $(this).data('name');
        $input.val(name);
        $suggestions.hide();
        $input.closest('form').submit();
    });

    // Đóng khi click ngoài
    $(document).on('mousedown', function(e) {
        if (!$(e.target).closest('#user-search-group').length) {
            $suggestions.hide();
        }
    });

    // Hiển thị lại khi focus
    $input.on('focus', function() {
        if ($(this).val().trim().length >= 2 && $suggestions.children().length > 0) {
            $suggestions.fadeIn(200);
        }
    });
});
</script>

<style>
    #user-suggestions {
        position: absolute;
        z-index: 1000;
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(25px) saturate(180%);
        border: 1px solid rgba(0, 242, 254, 0.4) !important;
        border-radius: 12px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.8);
        max-height: 400px;
        overflow-y: auto;
        display: none;
        margin-top: 5px;
    }
    .cursor-pointer { cursor: pointer; }
    
    /* Hover effect đồng bộ Cyber Theme */
    #user-suggestions .search-result-item:hover {
        background: rgba(0, 242, 254, 0.12) !important;
        transition: all 0.2s ease;
    }
</style>
@endpush
