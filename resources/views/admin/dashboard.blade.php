{{-- Kế thừa từ layout admin --}}
@extends('layouts.admin')

@section('title', 'Trang quản trị')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Dashboard</h4>

    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Xin chào Admin! 🎉</h5>
                            <p class="mb-4">
                                Chúc bạn một ngày làm việc hiệu quả. Hiện tại hệ thống đang hoạt động tốt.
                            </p>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Xem thống kê</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('admin/assets/img/illustrations/man-with-laptop-light.webp') }}"
                                height="140" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
