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
            <div class="text-muted mb-4 border-bottom pb-3">
                <span><i class="far fa-clock me-1"></i> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                <span class="ms-3"><i class="far fa-user me-1"></i> Dược sĩ Dola</span>
            </div>

            <div class="content-body text-justify" style="line-height: 1.8; font-size: 16px;">
                {!! $post->noi_dung !!}
            </div>
        </div>

        {{-- SIDEBAR BÀI LIÊN QUAN --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-2">Bài viết liên quan</h5>
                <div class="list-group list-group-flush">
                    @foreach($relatedPosts as $rel)
                    <a href="{{ route('baiviet.show', $rel->slug) }}" class="list-group-item list-group-item-action d-flex gap-3 py-3 border-0">
                        {{-- Thêm 'news/' vào đường dẫn --}}
                        <img src="{{ asset('images/news/' . $rel->hinh_anh) }}" class="rounded" width="80" height="60" style="object-fit: cover;"
                            onerror="this.src='{{ asset('images/no-image.png') }}'">
                            <h6 class="mb-1 fw-bold text-dark small two-lines">{{ $rel->tieu_de }}</h6>
                            <small class="text-muted">{{ $rel->created_at->format('d/m') }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Banner quảng cáo thuốc ở đây thì tuyệt vời --}}
                <div class="mt-4">
                    <img src="{{ asset('images/banner_spm1.png') }}" class="img-fluid rounded w-100">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
