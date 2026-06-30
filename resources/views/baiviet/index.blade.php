@extends('layouts.app')
@section('title', 'Góc sức khỏe')

@section('content')
{{-- 1. HEADER BANNER --}}
<div class="bg-primary pt-4 pb-2 py-lg-5 text-center text-white">
    <div class="container">
        <h1 class="fw-bold display-6">Góc Sức Khỏe & Y Khoa</h1>
        <p class="lead mb-0">Cập nhật kiến thức y tế và bí quyết chăm sóc sức khỏe mỗi ngày</p>
    </div>
</div>

<div class="bg-light pb-5 pt-0 pt-lg-5">
    <div class="container">

    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow">
                <a href="{{ route('baiviet.show', $post->slug) }}" class="overflow-hidden rounded-top">
                    {{-- Thêm 'news/' vào đường dẫn --}}
                    <img src="{{ asset('images/news/' . $post->hinh_anh) }}" class="card-img-top" alt="{{ $post->tieu_de }}"
                        style="height: 200px; object-fit: cover; transition: transform 0.3s;"
                        onerror="this.src='{{ asset('images/no-image.png') }}'"> {{-- Thêm xử lý nếu ảnh lỗi --}}
                </a>
                <div class="card-body">
                    <div class="text-muted small mb-2"><i class="far fa-calendar-alt me-1"></i> {{ $post->created_at->format('d/m/Y') }}</div>
                    <h5 class="card-title">
                        <a href="{{ route('baiviet.show', $post->slug) }}" class="text-decoration-none text-dark fw-bold two-lines">
                            {{ $post->tieu_de }}
                        </a>
                    </h5>
                    <p class="card-text text-secondary small two-lines">{{ $post->mo_ta_ngan }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-5px); transition: all 0.3s; }
    .hover-shadow:hover img { transform: scale(1.1); }
</style>
@endsection
