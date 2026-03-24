{{-- resources/views/partials/product-list.blade.php --}}

<div class="row g-2 g-md-3">
    @forelse($products as $product)
        <div class="col-6 col-md-4 col-xl-3 d-flex align-items-stretch">
        {{-- GỌI COMPONENT VÀ TRUYỀN BIẾN PRODUCT VÀO --}}
        @include('partials.product-card', ['product' => $product])
</div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="fas fa-search text-muted" style="font-size: 60px; opacity: 0.3;"></i>
            </div>
            <h5 class="text-muted">Không tìm thấy sản phẩm nào.</h5>
            <p class="text-secondary">Vui lòng thử tìm kiếm với từ khóa khác.</p>
        </div>
    @endforelse
</div>

{{-- PHÂN TRANG (Pagination) --}}
@if(method_exists($products, 'links'))
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endif
