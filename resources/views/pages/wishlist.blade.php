@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích')

@section('content')
<div class="bg-light py-5">
    <div class="container">

        <h2 class="fw-bold text-primary mb-4">
            <i class="fas fa-heart me-2"></i> Sản phẩm yêu thích
            <span class="fs-5 text-muted">({{ count($favorites) }})</span>
        </h2>

        @if($favorites->count() > 0)
            <div class="row g-3 g-md-4">
                @foreach($favorites as $item)
                    {{-- Lấy thông tin thuốc từ quan hệ --}}
                    @php $product = $item->thuoc; @endphp

                    {{-- Kiểm tra nếu thuốc lỡ bị xóa thì không hiện lỗi --}}
                    @if($product)
                        <div class="col-6 col-md-3" id="wishlist-item-{{ $product->id }}">
                            {{-- TÁI SỬ DỤNG CLASS fp-card ĐỂ ĐỒNG BỘ GIAO DIỆN --}}
                            <div class="fp-card h-100">
                                <div class="fp-inner h-100 d-flex flex-column position-relative">

                                    {{-- Nút Xóa khỏi yêu thích (Góc phải trên) --}}
                                    <button class="btn btn-sm btn-light border position-absolute top-0 end-0 m-2 shadow-sm text-danger z-3"
                                            title="Bỏ thích"
                                            onclick="removeFromWishlist({{ $product->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    {{-- Badge giảm giá (Nếu có) --}}
                                    @if($product->gia_cu > $product->gia_ban)
                                        <span class="fp-badge-discount">
                                            -{{ round((($product->gia_cu - $product->gia_ban) / $product->gia_cu) * 100) }}%
                                        </span>
                                    @endif

                                    {{-- Ảnh sản phẩm --}}
                                    <div class="fp-image">
                                        <a href="{{ route('thuoc.show', $product->slug) }}">
                                            <img src="{{ $product->hinh_anh ? asset('images/images_san_pham/' . $product->hinh_anh) : asset('images/no-image.webp') }}"
                                                 alt="{{ $product->ten_thuoc }}"
                                                 style="object-fit: contain; width: 100%; height: 100%;">
                                        </a>
                                    </div>

                                    {{-- Thông tin --}}
                                    <div class="fp-info flex-grow-1 d-flex flex-column">
                                        <h3 class="fp-name" style="min-height: 48px;">
                                            <a href="{{ route('thuoc.show', $product->slug) }}" class="text-dark text-decoration-none two-lines">
                                                {{ $product->ten_thuoc }}
                                                @if($product->quy_cach)
                                                    <span class="text-secondary fw-normal" style="font-size: 14px;">
                                                        ({{ $product->quy_cach }})
                                                    </span>
                                                @endif
                                            </a>
                                        </h3>

                                        <div class="fp-price mt-auto">
                                            <span class="fp-price-current">
                                                {{ number_format($product->gia_ban) }} đ
                                                <span class="text-dark fw-normal small">/{{ $product->don_vi_tinh ?? 'Hộp' }}</span>
                                            </span>
                                            @if($product->gia_cu > $product->gia_ban)
                                                <span class="fp-price-old">{{ number_format($product->gia_cu) }} đ</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Nút mua --}}
                                    <div class="fp-actions">
                                        <button type="button" class="fp-cart-btn" onclick="addToCart({{ $product->id }}, this)">
                                            <i class="fa fa-shopping-bag"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            {{-- Giao diện khi trống --}}
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <div class="mb-3">
                    <i class="far fa-heart text-muted" style="font-size: 80px; opacity: 0.3;"></i>
                </div>
                <h4 class="text-muted">Chưa có sản phẩm yêu thích nào</h4>
                <p class="mb-4">Hãy thả tim các sản phẩm bạn quan tâm để xem lại sau nhé!</p>
                <a href="{{ route('thuoc.index') }}" class="btn btn-primary px-4 fw-bold">
                    Khám phá ngay
                </a>
            </div>
        @endif

    </div>
</div>

<script>
    function removeFromWishlist(id) {
        if(!confirm('Bạn muốn bỏ sản phẩm này khỏi danh sách yêu thích?')) return;

        // Gọi lại API toggle cũ, vì nó có chức năng xóa nếu đã tồn tại
        fetch('{{ route('wishlist.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // Xóa thẻ sản phẩm khỏi giao diện ngay lập tức
                const item = document.getElementById('wishlist-item-' + id);
                if(item) {
                    item.remove();

                    // Cập nhật số lượng trên Header
                    document.querySelectorAll('.wishlist-count-badge').forEach(el => {
                        el.innerText = data.count;
                        el.style.display = data.count > 0 ? 'inline-block' : 'none';
                    });

                    // Nếu xóa hết thì reload để hiện màn hình trống
                    if(data.count == 0) location.reload();
                }
            }
        });
    }
</script>
@endsection
