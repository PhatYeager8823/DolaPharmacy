@extends('layouts.admin')
@section('title', 'Quản lý Đánh Giá')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Quản lý Đánh Giá Sản Phẩm</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="mb-0 align-items-center d-flex">
                <i class="bx bx-message-rounded-dots text-primary me-2 fs-4"></i> Danh sách bình luận & đánh giá
            </h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">ID</th>
                        <th style="width: 20%">Người dùng</th>
                        <th style="width: 25%">Sản phẩm (Thuốc)</th>
                        <th style="width: 15%">Đánh giá</th>
                        <th style="width: 20%">Nội dung</th>
                        <th class="text-center" style="width: 15%">Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($danhGias as $review)
                    <tr class="{{ $review->trang_thai == 0 ? 'bg-label-danger' : '' }}">
                        <td class="text-center">#{{ $review->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ strtoupper(substr($review->nguoiDung->ten, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $review->nguoiDung->ten }}</span>
                                    <small class="text-muted">{{ $review->nguoiDung->so_dien_thoai ?? $review->nguoiDung->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('thuoc.show', $review->thuoc->slug) }}" target="_blank" class="fw-bold text-primary text-wrap" style="display:inline-block; max-width: 200px;">
                                {{ $review->thuoc->ten_thuoc }}
                            </a>
                        </td>
                        <td>
                            <div class="text-warning">
                                @for($i=1; $i<=5; $i++)
                                    <i class="bx bxs-star {{ $i <= $review->so_sao ? '' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px; font-size: 0.9em;">
                                {{ Str::limit($review->noi_dung, 60) }}
                                @if(strlen($review->noi_dung) > 60)
                                    <span class="text-primary cursor-pointer" title="{{ $review->noi_dung }}">...</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            {{-- Nút Ẩn/Hiện --}}
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-icon {{ $review->trang_thai ? 'btn-outline-secondary' : 'btn-success' }}" 
                                        data-bs-toggle="tooltip" title="{{ $review->trang_thai ? 'Bấm để Ẩn bình luận này' : 'Bấm để Cho phép Hiển thị' }}">
                                    <i class="bx {{ $review->trang_thai ? 'bx-hide' : 'bx-show' }}"></i>
                                </button>
                            </form>

                            {{-- Nút Xóa vĩnh viễn --}}
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline-block ms-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger delete-confirm-btn" 
                                        data-message="Bạn có CHẮC CHẮN muốn xóa vĩnh viễn đánh giá này không? Không thể khôi phục!"
                                        data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bx bx-ghost fs-1 mb-3"></i><br>
                            Chưa có đánh giá nào từ khách hàng!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Phân trang --}}
        @if($danhGias->hasPages())
        <div class="card-footer border-top pt-3 pb-0">
            {{ $danhGias->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>

{{-- Bật Tooltip --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
