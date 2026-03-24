@extends('layouts.app')
@section('title', 'Video sức khỏe')

@section('content')
<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">Video & Kiến thức sức khỏe</h2>
        <p class="text-muted">Cập nhật những thông tin y khoa bổ ích qua video trực quan</p>
    </div>

    <div class="row g-4">
        @foreach($videos as $video)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm video-card-hover">

                {{-- KHUNG ẢNH THUMBNAIL (Bấm vào hiện modal) --}}
                <a href="javascript:void(0)"
                   class="position-relative d-block overflow-hidden rounded-top"
                   onclick="playVideo('{{ $video->youtube_id }}')">

                    <img src="{{ asset('images/videos/' . $video->thumbnail) }}"
                    alt="{{ $video->tieu_de }}"
                    class="w-100"
                    style="height: 220px; object-fit: cover;">

                    {{-- Nút Play ở giữa --}}
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="btn btn-danger rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 60px; height: 60px;">
                            <i class="fa fa-play text-white fs-4 ms-1"></i>
                        </div>
                    </div>
                </a>

                <div class="card-body">
                    <h5 class="card-title">
                        <a href="javascript:void(0)" onclick="playVideo('{{ $video->youtube_id }}')" class="text-decoration-none text-dark fw-bold two-lines">
                            {{ $video->tieu_de }}
                        </a>
                    </h5>
                    <p class="card-text text-secondary small two-lines">{{ $video->mo_ta_ngan }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">
        {{ $videos->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL PHÁT VIDEO --}}
<div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-black">
            <div class="modal-body p-0 position-relative">
                {{-- Nút tắt --}}
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>

                {{-- Iframe Youtube --}}
                <div class="ratio ratio-16x9">
                    <iframe id="youtubeFrame" src="" title="YouTube video" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT XỬ LÝ VIDEO --}}
<script>
    function playVideo(videoId) {
        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        const iframe = document.getElementById('youtubeFrame');

        // Gắn link Youtube vào iframe và tự động chạy (autoplay=1)
        iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;

        modal.show();

        // Khi tắt modal thì dừng video (xóa src)
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
            iframe.src = "";
        });
    }
</script>

<style>
    .video-card-hover:hover { transform: translateY(-5px); transition: all 0.3s; }
    .video-card-hover:hover .btn-danger { transform: scale(1.1); transition: all 0.3s; }
</style>
@endsection
