@extends('layouts.admin')
@section('title', 'Cấu hình hệ thống')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Hệ thống /</span> Cài đặt chung</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Thông tin Website</h5>

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- NAV TABS --}}
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-general">
                                    <i class="bx bx-home me-1"></i> Chung
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-contact">
                                    <i class="bx bx-phone me-1"></i> Liên hệ
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-social">
                                    <i class="bx bx-share-alt me-1"></i> Mạng xã hội
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link text-warning" role="tab" data-bs-toggle="tab" data-bs-target="#navs-promo">
                                    <i class="bx bx-gift me-1"></i> Khuyến mãi
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">

                            {{-- TAB 1: THÔNG TIN CHUNG --}}
                            <div class="tab-pane fade show active" id="navs-general" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tên Website</label>
                                        <input type="text" name="ten_website" class="form-control" value="{{ $setting->ten_website }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        {{-- Khoảng trống --}}
                                    </div>

                                    {{-- Logo --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Logo Website</label>
                                        <input type="file" name="logo" class="form-control mb-2" accept="image/*" />
                                        @if($setting->logo)
                                            <img src="{{ asset('images/settings/'.$setting->logo) }}" height="60" class="border rounded p-1">
                                        @endif
                                    </div>

                                    {{-- Favicon --}}
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Favicon (Icon trên tab)</label>
                                        <input type="file" name="favicon" class="form-control mb-2" accept="image/*" />
                                        @if($setting->favicon)
                                            <img src="{{ asset('images/settings/'.$setting->favicon) }}" height="32" class="border rounded p-1">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 2: LIÊN HỆ --}}
                            <div class="tab-pane fade" id="navs-contact" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hotline</label>
                                        <input type="text" name="hotline" class="form-control" value="{{ $setting->hotline }}" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email liên hệ</label>
                                        <input type="email" name="email" class="form-control" value="{{ $setting->email }}" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Địa chỉ trụ sở</label>
                                        <input type="text" name="dia_chi" class="form-control" value="{{ $setting->dia_chi }}" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Mã nhúng bản đồ (Google Maps Iframe)</label>
                                        <textarea name="maps" class="form-control" rows="3">{{ $setting->maps }}</textarea>
                                        <div class="form-text">Vào Google Maps -> Chia sẻ -> Nhúng bản đồ -> Copy đoạn iframe dán vào đây.</div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 3: MẠNG XÃ HỘI --}}
                            <div class="tab-pane fade" id="navs-social" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">Link Facebook Fanpage</label>
                                    <input type="url" name="facebook" class="form-control" value="{{ $setting->facebook }}" placeholder="https://facebook.com/..." />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link Zalo OA</label>
                                    <input type="url" name="zalo" class="form-control" value="{{ $setting->zalo }}" placeholder="https://zalo.me/..." />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link Youtube</label>
                                    <input type="url" name="youtube" class="form-control" value="{{ $setting->youtube }}" placeholder="https://youtube.com/..." />
                                </div>
                            </div>
                            
                            {{-- TAB 4: KHUYẾN MÃI --}}
                            <div class="tab-pane fade" id="navs-promo" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_promo_active" id="is_promo_active" value="1" {{ $setting->is_promo_active ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-warning" for="is_promo_active">Kích hoạt chương trình khuyến mãi (Banner + Đồng hồ)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Nội dung Banner khuyến mãi (Dòng 1)</label>
                                        <input type="text" name="promo_text" class="form-control" value="{{ $setting->promo_text }}" placeholder="Ví dụ: CHÀO MỪNG BẠN MỚI" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mã Promo / Coupon</label>
                                        <input type="text" name="promo_code" class="form-control" value="{{ $setting->promo_code }}" placeholder="Ví dụ: BANMOI" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-danger fw-bold">Thời gian kết thúc (Đồng hồ đếm ngược)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-secondary"><i class="bx bx-calendar text-white"></i></span>
                                            <input type="text" id="promo_end_date_picker" name="promo_end_date" class="form-control" 
                                                value="{{ $setting->promo_end_date }}" placeholder="Chọn ngày và giờ kết thúc..." />
                                        </div>
                                        <div class="form-text">Đồng hồ đếm ngược ở trang chủ sẽ chạy theo mốc thời gian này.</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#promo_end_date_picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i:S",
            time_24hr: true,
            locale: "vn",
            altInput: true,
            altFormat: "d/m/Y H:i",
            theme: "dark",
            minDate: "today"
        });
    });
</script>
@endpush

@push('styles')
<style>
    .form-control, .form-select, input, textarea {
        color: #ffffff !important;
    }
    .form-label {
        color: #ffffff !important;
    }
</style>
@endpush
