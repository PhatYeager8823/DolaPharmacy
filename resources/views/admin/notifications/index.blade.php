@extends('layouts.admin')
@section('title', 'Quản lý Thông Báo')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Hệ thống /</span> Thông Báo</h4>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Gửi thông báo mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <h5 class="card-header">Danh sách thông báo đã gửi</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Người nhận</th>
                        <th>Tiêu đề</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($notifications as $notif)
                    <tr>
                        <td>{{ $notif->id }}</td>
                        <td>
                            @if($notif->nguoiDung)
                                <span>{{ $notif->nguoiDung->ten }}</span><br>
                                <small class="text-muted">{{ $notif->nguoiDung->email }}</small>
                            @else
                                <span class="text-muted">---</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $notif->tieu_de }}</strong><br>
                            <small class="text-muted">{{ Str::limit($notif->noi_dung, 50) }}</small>
                        </td>
                        <td>
                            @php
                                $badgeMap = [
                                    'don_hang'   => ['label' => 'Đơn hàng',   'class' => 'bg-label-primary'],
                                    'khuyen_mai' => ['label' => 'Khuyến mãi', 'class' => 'bg-label-danger'],
                                    'he_thong'   => ['label' => 'Hệ thống',   'class' => 'bg-label-secondary'],
                                    'bao_mat'    => ['label' => 'Bảo mật',    'class' => 'bg-label-warning'],
                                    'thong_bao'  => ['label' => 'Thông báo',  'class' => 'bg-label-info'],
                                ];
                                $badge = $badgeMap[$notif->loai] ?? ['label' => $notif->loai, 'class' => 'bg-label-secondary'];
                            @endphp
                            <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td>
                            @if($notif->da_xem)
                                <span class="badge bg-label-success">Đã xem</span>
                            @else
                                <span class="badge bg-label-warning">Chưa xem</span>
                            @endif
                        </td>
                        <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST"
                                  onsubmit="return confirm('Xóa thông báo này?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bx bx-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bx bx-bell-off fs-4"></i>
                            <p class="mt-2">Chưa có thông báo nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
