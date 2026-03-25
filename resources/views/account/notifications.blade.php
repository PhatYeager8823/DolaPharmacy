@extends('layouts.app')
@section('title', 'Thông báo của tôi')

@section('content')
<div class="bg-light py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR --}}
            <div class="col-12 col-lg-3 profile-sidebar">
                @include('partials.account-sidebar')
            </div>

            {{-- NỘI DUNG CHÍNH --}}
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="fw-bold mb-0">Thông báo</h5>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notif)
                            <div class="list-group-item p-3 border-bottom {{ $notif->da_xem == 0 ? 'bg-light' : '' }}">
                                <div class="d-flex align-items-start">
                                    {{-- Icon tùy theo loại --}}
                                    <div class="me-3 mt-1">
                                        @if($notif->loai == 'don_hang')
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fa fa-box"></i>
                                            </div>
                                        @elseif($notif->loai == 'khuyen_mai')
                                            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fa fa-tags"></i>
                                            </div>
                                        @else
                                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fa fa-bell"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold {{ $notif->da_xem == 0 ? 'text-primary' : 'text-dark' }}">
                                                {{ $notif->tieu_de }}
                                            </h6>
                                            <small class="text-muted" style="font-size: 12px;">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <p class="mb-1 text-secondary small">
                                            {{ $notif->noi_dung }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/99e92f071e5d9dbbb16523d0c38738e9.webp" width="100">
                                <p class="text-muted mt-3">Chưa có thông báo nào.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Phân trang --}}
                    @if($notifications->hasPages())
                        <div class="card-footer bg-white py-3">
                            {{ $notifications->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
