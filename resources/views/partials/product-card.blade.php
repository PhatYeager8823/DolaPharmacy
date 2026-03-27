{{-- resources/views/partials/product-card.blade.php --}}
<div class="fp-card h-100 w-100">
    <div class="fp-inner h-100 d-flex flex-column position-relative w-100">

        {{-- BADGE GIẢM GIÁ --}}
        @if($product->gia_cu > $product->gia_ban && $product->ke_don == 0)
            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                -{{ round((($product->gia_cu - $product->gia_ban) / $product->gia_cu) * 100) }}%
            </span>
        @endif

        {{-- WISHLIST --}}
        @php
            $isLiked = false;
            if(auth()->check()) {
                $isLiked = \App\Models\YeuThich::where('nguoi_dung_id', auth()->id())
                            ->where('thuoc_id', $product->id)->exists();
            }
        @endphp
        <button class="btn-wishlist {{ $isLiked ? 'active' : '' }}"
                data-url="{{ route('wishlist.toggle') }}"
                onclick="toggleWishlist({{ $product->id }}, this)">
            <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i>
        </button>

        {{-- ẢNH SẢN PHẨM (CHIỀU CAO CỐ ĐỊNH) --}}
        <div class="fp-image">
            <a href="{{ route('thuoc.show', $product->slug) }}" class="d-block w-100 h-100">
                <img src="{{ $product->hinh_anh ? asset('images/images_san_pham/' . $product->hinh_anh) : asset('images/no-image.png') }}"
                     alt="{{ $product->ten_thuoc }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
            </a>
        </div>

        {{-- THÔNG TIN (KHÓA CHIỀU CAO) --}}
        <div class="fp-info d-flex flex-column flex-grow-1">

            {{-- TÊN (GIỚI HẠN 2 DÒNG) --}}
            <h3 class="fp-name">
                <a href="{{ route('thuoc.show', $product->slug) }}"
                   class="text-decoration-none text-dark"
                   title="{{ $product->ten_thuoc }}">
                    {{ $product->ten_thuoc }}
                </a>
            </h3>

            {{-- QUY CÁCH --}}
            <div class="fp-meta">
                {{ $product->quy_cach ?? '' }}
            </div>

            {{-- THƯƠNG HIỆU (GIỮ CHỖ) --}}
            <div class="fp-brand">
                {{ $product->brand->ten ?? '' }}
            </div>

            {{-- GIÁ (LUÔN Ở ĐÁY) --}}
            <div class="fp-price mt-auto">
                <div class="current">
                    {{ number_format($product->gia_ban) }}đ
                    <span class="unit">/{{ $product->don_vi_tinh ?? 'Hộp' }}</span>
                </div>

                <div class="old">
                    @if($product->gia_cu > $product->gia_ban)
                        {{ number_format($product->gia_cu) }}đ
                    @endif
                </div>
            </div>
        </div>

        {{-- NÚT HÀNH ĐỘNG --}}
        <div class="fp-actions">
            @if($product->so_luong_ton > 0)
                @if($product->ke_don == 1)
                    <a href="tel:{{ str_replace(['.', ' '], '', $global_setting->hotline ?? '0123.456.789') }}" class="fp-consult-btn" title="Cần tư vấn">
                        <i class="fa fa-user-md"></i>
                    </a>
                @else
                    <button type="button" class="fp-cart-btn"
                            onclick="addToCart({{ $product->id }}, this)"
                            title="Thêm vào giỏ">
                        <i class="fa fa-shopping-bag"></i>
                    </button>
                @endif
            @else
                <span class="badge bg-secondary small">Hết hàng</span>
            @endif
        </div>

    </div>
</div>

