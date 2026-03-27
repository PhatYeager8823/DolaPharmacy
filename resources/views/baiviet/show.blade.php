@extends('layouts.app')
@section('title', $post->tieu_de)

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- NỘI DUNG BÀI VIẾT --}}
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        {{-- Chỉ để text-decoration-none, nó sẽ tự có màu xanh --}}
                        <a href="/" class="text-decoration-none">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('baiviet.index') }}" class="text-decoration-none">Tin tức</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>

            <h1 class="fw-bold mb-3 text-primary">{{ $post->tieu_de }}</h1>
            <div class="text-muted mb-4 border-bottom pb-3 d-flex align-items-center justify-content-between">
                <div>
                    <span><i class="far fa-clock me-1"></i> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                    <span class="ms-3"><i class="far fa-user me-1"></i> Dược sĩ Dola</span>
                </div>
            </div>

            {{-- ẢNH ĐẠI DIỆN BÀI VIẾT --}}
            <div class="mb-4 text-center">
                <img src="{{ asset('images/news/' . $post->hinh_anh) }}" 
                     alt="{{ $post->tieu_de }}" 
                     class="img-fluid rounded-3 shadow-sm mx-auto d-block" 
                     style="max-height: 450px; width: 100%; object-fit: cover;"
                     onerror="this.src='{{ asset('images/no-image.png') }}'">
            </div>

            <div class="content-body text-justify" style="line-height: 1.8; font-size: 16px;">
                {!! $post->noi_dung !!}
            </div>
        </div>

        {{-- SIDEBAR BÀI LIÊN QUAN --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 130px; z-index: 5;">
                <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-2">Bài viết liên quan</h5>
                <div class="list-group list-group-flush">
                    @foreach($relatedPosts as $rel)
                    <a href="{{ route('baiviet.show', $rel->slug) }}" class="list-group-item list-group-item-action d-flex gap-3 py-3 border-0 px-0">
                        <img src="{{ asset('images/news/' . $rel->hinh_anh) }}" 
                             class="rounded shadow-sm" width="100" height="70" 
                             style="object-fit: cover; flex-shrink: 0;"
                             onerror="this.src='{{ asset('images/no-image.png') }}'">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark small two-lines" style="line-height: 1.4;">{{ $rel->tieu_de }}</h6>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>{{ $rel->created_at->format('d/m/Y') }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Đã gỡ bỏ banner quảng cáo dư thừa theo yêu cầu --}}
            </div>
        </div>
    </div>
</div>
@endsection
