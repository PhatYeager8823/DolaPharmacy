{{-- Promo Modal Partial --}}
<div id="promoModal" class="promo-modal-overlay">
    <div class="promo-modal-container">
        <button id="closePromo" class="promo-close-btn">
            <i class="fas fa-times"></i>
        </button>
        <div class="promo-content">
            <a href="{{ route('thuoc.index') }}">
                <img src="{{ asset('images/promos/banner_km.png') }}" alt="Promotional Banner" class="img-fluid rounded shadow-lg">
            </a>
            <div class="promo-footer mt-3 text-center">
                <a href="{{ route('thuoc.index') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold glow-btn">
                    MUA SẮM NGAY <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const promoModal = document.getElementById('promoModal');
        const closeBtn = document.getElementById('closePromo');
        
        // Kiểm tra xem đã hiển thị trong session này chưa
        if (!sessionStorage.getItem('promo_shown')) {
            // Hiển thị sau 2 giây để người dùng không bị quá bất ngờ
            setTimeout(() => {
                promoModal.classList.add('active');
            }, 2000);
        }

        closeBtn.addEventListener('click', () => {
            promoModal.classList.remove('active');
            sessionStorage.setItem('promo_shown', 'true');
        });

        // Đóng khi click ra ngoài
        promoModal.addEventListener('click', (e) => {
            if (e.target === promoModal) {
                promoModal.classList.remove('active');
                sessionStorage.setItem('promo_shown', 'true');
            }
        });
    });
</script>
