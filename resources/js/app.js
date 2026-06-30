import './bootstrap';
import './echo';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

// === GLOBAL SINGLETON GUARD ===
if (window.__dola_app_initialized) {
    console.log('Dola App already initialized. Skipping redundant load.');
} else {
    window.__dola_app_initialized = true;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dola Pharmacy JS Initializing (Once)...');


    document.querySelectorAll(".mega-dropdown").forEach(item => {
        let timeout;
    
        item.addEventListener("mouseenter", () => {
            clearTimeout(timeout);
            item.classList.add("open");
        });
    
        item.addEventListener("mouseleave", () => {
            timeout = setTimeout(() => {
                item.classList.remove("open");
            }, 150);
        });
    });



// 3. Khởi tạo Swiper cho slider
const heroSwiper = new Swiper('.hero-swiper', {
    // Tùy chọn
    loop: true, // Cho nó chạy vòng

    autoplay: {
        delay: 4000, // 3000ms = 3 giây
        disableOnInteraction: false, // Vẫn tự chạy sau khi người dùng
                                     // tự bấm next/prev
        pauseOnMouseEnter: true,      // Tạm dừng khi di chuột vào (Để tránh nhảy liên tục)
    },

    // Kích hoạt nút Next/Prev
    pagination: {
        el: '.swiper-pagination',
        clickable: true, // Cho phép bấm vào chấm để chuyển slide
    },

    navigation: {
        nextEl: '.hero-next',
        prevEl: '.hero-prev',
    },
    watchOverflow: false, // Hiện mũi tên ngay cả khi ít slide
});

const featuredCatSwiper = new Swiper('.featured-categories-swiper', {
    loop: true,

    autoplay: {
        delay: 4500,            // GIẢM TỐC – tự chạy chậm giống mẫu
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },

    speed: 900,                 // Chuyển slide mượt hơn

    slidesPerView: 5,
    spaceBetween: 32,

    navigation: {
        nextEl: '.featured-cat-next',
        prevEl: '.featured-cat-prev',
    },
    watchOverflow: false,

    breakpoints: {
        0:   { slidesPerView: 2.5, spaceBetween: 16 },
        576: { slidesPerView: 3.5, spaceBetween: 20 },
        768: { slidesPerView: 4,   spaceBetween: 24 },
        992: { slidesPerView: 5,   spaceBetween: 28 },
        1200:{ slidesPerView: 6,   spaceBetween: 32 },
    }
});

const hotDealsSwiper = new Swiper('.hot-deals-swiper', {
    loop: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },
    speed: 900,
    slidesPerView: 2.2, // Hiển thị hơn 2 item trên mobile
    spaceBetween: 10,
    navigation: {
        nextEl: '.hot-deals-next',
        prevEl: '.hot-deals-prev',
    },
    watchOverflow: false,
    breakpoints: {
        576: { slidesPerView: 2.2, spaceBetween: 16 },
        768: { slidesPerView: 3, spaceBetween: 18 },
        1200:{ slidesPerView: 4, spaceBetween: 20 },
    },
});

// (Countdown script removed because it conflicts with the one in home.blade.php)

const newProductsSwiper = new Swiper('.new-products-swiper', {
    loop: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },
    speed: 900,
    slidesPerView: 2.2, // Hiển thị đồng nhất với Hot Deals
    spaceBetween: 12,
    navigation: {
        nextEl: '.new-products-next',
        prevEl: '.new-products-prev',
    },
    watchOverflow: false,
    breakpoints: {
        576: { slidesPerView: 2.2, spaceBetween: 16 },
        768: { slidesPerView: 3,   spaceBetween: 18 },
        1200:{ slidesPerView: 4,   spaceBetween: 20 },
    },
});


const featuredProductsSwiper = new Swiper('.featured-products-swiper', {
    loop: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
    },
    speed: 900,
    slidesPerView: 2.2, // Chỉnh xuống mobile
    spaceBetween: 10,
    navigation: {
        nextEl: '.featured-products-next',
        prevEl: '.featured-products-prev',
    },
    watchOverflow: false,
    breakpoints: {
        576: { slidesPerView: 2.2, spaceBetween: 16 },
        768: { slidesPerView: 3,   spaceBetween: 18 },
        1200:{ slidesPerView: 3.5, spaceBetween: 20 },
    },
});

    const videoSwiperEl = document.querySelector('.video-swiper');
    if (videoSwiperEl) {
        const slides = videoSwiperEl.querySelectorAll('.swiper-slide');
        const videoSwiper = new Swiper('.video-swiper', {
            loop: slides.length > 4, // 1200: { slidesPerView: 4 }
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            speed: 900,
            slidesPerView: 1.1,
            spaceBetween: 16,
            navigation: {
                nextEl: '.video-swiper-button-next',
                prevEl: '.video-swiper-button-prev',
            },
            watchOverflow: true,
            breakpoints: {
                576: { slidesPerView: 2, spaceBetween: 18 },
                992: { slidesPerView: 3, spaceBetween: 20 },
                1200:{ slidesPerView: 4, spaceBetween: 22 },
            },
        });
    }


// ==========================================
// HÀM XỬ LÝ GIỎ HÀNG (GLOBAL)
// ==========================================

window.addToCart = function(productId, sourceElement, quantity = null) {
    // === PHẦN 1: XỬ LÝ HIỆU ỨNG BAY (FLY EFFECT) ===
    let productImg = null;

    // 1. Nếu sourceElement là một <img> (được truyền trực tiếp từ modal)
    if (sourceElement && sourceElement.tagName === 'IMG') {
        productImg = sourceElement;
    } 
    // 2. Nếu là trang danh sách (nút nằm trong card)
    else if (sourceElement) {
        const card = sourceElement.closest('.fp-card');
        if (card) {
            productImg = card.querySelector('.fp-image img');
        }
    }
    
    // 3. Nếu vẫn không thấy, thử tìm trong Quick Buy Modal
    if (!productImg) {
        const modal = document.getElementById('quickBuyModal');
        if (modal && modal.classList.contains('show')) {
            productImg = modal.querySelector('.qb-image-section img');
        }
    }

    // 4. Nếu là trang chi tiết (mặc định)
    if (!productImg && !sourceElement) {
        productImg = document.getElementById('mainImage');
    }

    // Đích đến (Icon giỏ hàng trên Header)
    const cartIcon = document.querySelector('.fa-shopping-cart') || document.querySelector('.cart-count') || document.querySelector('#cart-icon-header');

    if (productImg && cartIcon) {
        const flyImg = productImg.cloneNode();
        flyImg.classList.add('fly-item');

        const startRect = productImg.getBoundingClientRect();
        const endRect = cartIcon.getBoundingClientRect();

        flyImg.style.top = startRect.top + 'px';
        flyImg.style.left = startRect.left + 'px';
        flyImg.style.width = startRect.width + 'px';
        flyImg.style.height = startRect.height + 'px';

        document.body.appendChild(flyImg);

        setTimeout(() => {
            flyImg.style.top = (endRect.top + 10) + 'px';
            flyImg.style.left = (endRect.left + 10) + 'px';
            flyImg.style.width = '30px';
            flyImg.style.height = '30px';
            flyImg.style.opacity = '0.3';
            flyImg.style.transform = 'rotate(360deg)';
        }, 50);

        setTimeout(() => {
            flyImg.remove();
            // Hiệu ứng rung nhẹ icon giỏ hàng
            cartIcon.classList.add('cart-bump');
            setTimeout(() => cartIcon.classList.remove('cart-bump'), 300);
        }, 850);
    }

    // === PHẦN 2: LOGIC GỬI AJAX CŨ (GIỮ NGUYÊN) ===
    // Nếu quantity chưa được truyền vào, thử lấy từ input #product_qty
    if (quantity === null) {
        const qtyInput = document.getElementById('product_qty');
        quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    }
    if (isNaN(quantity) || quantity < 1) quantity = 1;

    const url = '/cart/add/' + productId;
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) return;
    const csrfToken = csrfTokenMeta.getAttribute('content');

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ so_luong: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Cập nhật số lượng Header
            document.querySelectorAll('.cart-count').forEach(el => {
                el.innerText = data.cartCount;
                el.style.display = 'inline-block';
            });

            // Hiện Toast thông báo (Code Swal cũ của bạn)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: 'Đã thêm vào giỏ!',
                text: data.message
            });

        } else {
            Swal.fire({icon: 'error', title: 'Lỗi', text: data.message});
        }
    })
    .catch(error => console.error('Error:', error));
};

// ==========================================
// HÀM MỞ MODAL CHỌN MUA (QUICK BUY)
// ==========================================
window.openQuickBuyModal = function(productId) {
    const modalElement = document.getElementById('quickBuyModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const body = document.getElementById('quickBuyModalBody');

    // Reset content to loading
    body.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    modal.show();

    // Fetch product info
    fetch(`/api/products/${productId}/quick-view`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = data.data;
                const oldPriceHtml = item.old_price ? `<span class="qb-old-price">${item.old_price}</span>` : '';
                
                body.innerHTML = `
                    <div class="qb-container">
                        <div class="qb-image-section">
                            <img src="${item.image}" alt="${item.name}">
                        </div>
                        <div class="qb-info-section">
                            <div class="qb-badge-ship mb-2 text-primary small fw-bold"><i class="fa fa-truck"></i> Miễn phí vận chuyển cho mọi đơn hàng 0đ</div>
                            <h3 class="qb-title fw-bold mb-1" style="font-size: 1.2rem;">${item.name}</h3>
                            <div class="qb-category text-muted small mb-3">Danh mục: ${item.category_name}</div>

                            <div class="qb-price-card">
                                <div class="qb-current-price">${item.price}</div>
                                ${oldPriceHtml}
                                <div class="qb-price-note mt-2" style="font-size: 0.75rem; color: #7f8c8d;">
                                    Giá đã bao gồm thuế. Phí vận chuyển và các chi phí khác (nếu có) sẽ được thể hiện khi đặt hàng.
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="qb-label d-block mb-2 fw-bold" style="font-size: 0.9rem;">Quy cách</span>
                                <div class="qb-unit-pill d-inline-block px-3 py-1 bg-light rounded-pill border text-primary fw-bold">${item.unit}</div>
                            </div>

                            <div class="mb-4">
                                <span class="qb-label d-block mb-2 fw-bold" style="font-size: 0.9rem;">Số lượng</span>
                                <div class="qb-qty-box">
                                    <button class="qb-qty-btn" onclick="updateModalQty(-1)"><i class="fa fa-minus"></i></button>
                                    <input type="number" id="qb-quantity" class="qb-qty-input" value="1" min="1" max="99" readonly>
                                    <button class="qb-qty-btn" onclick="updateModalQty(1)"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="qb-action-group d-flex gap-2">
                                <button class="qb-btn-add-cart flex-grow-1" onclick="addToCartAndClose(${item.id})">Thêm vào giỏ</button>
                                <button class="qb-btn-buy-now flex-grow-1" onclick="buyNow(${item.id})">Mua ngay</button>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                body.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(err => {
            console.error(err);
            body.innerHTML = `<div class="alert alert-danger">Đã có lỗi xảy ra. Vui lòng thử lại.</div>`;
        });
};

window.updateModalQty = function(change) {
    const input = document.getElementById('qb-quantity');
    if (!input) return;
    let val = parseInt(input.value) + change;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
};

window.addToCartAndClose = function(productId) {
    const modalElement = document.getElementById('quickBuyModal');
    if (!modalElement) return;

    const qtyInput = modalElement.querySelector('#qb-quantity');
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;

    // Tìm ảnh trong modal để làm hiệu ứng bay
    const modalImg = modalElement.querySelector('.qb-image-section img');
    
    // Gọi hàm addToCart với đầy đủ thông tin
    window.addToCart(productId, modalImg, quantity);
    
    // Đóng modal
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    if (modalInstance) modalInstance.hide();
};

window.buyNow = function(productId) {
    const qtyInput = document.getElementById('qb-quantity') || document.getElementById('product_qty');
    const quantity = qtyInput ? parseInt(qtyInput.value) : 1;

    // Thay vì gửi AJAX thêm vào giỏ hàng chung, chuyển hướng thẳng đến Checkout kèm tham số
    window.location.href = `/thanh-toan?buy_now_id=${productId}&qty=${quantity}`;
};

// ==========================================
// HÀM XỬ LÝ YÊU THÍCH (WISHLIST)
// ==========================================
window.toggleWishlist = function(id, btn) {
    const url = btn.getAttribute('data-url');
    const icon = btn.querySelector('i');
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfTokenMeta) return;
    const csrfToken = csrfTokenMeta.getAttribute('content');

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'login_required') {
            Swal.fire({
                icon: 'warning',
                title: 'Thông báo',
                text: data.message,
                confirmButtonText: 'Đăng nhập',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/dang-nhap';
                }
            });
            return;
        }

        if(data.action === 'added') {
            btn.classList.add('active');
            icon.classList.remove('far');
            icon.classList.add('fas');
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
            Toast.fire({ icon: 'success', title: 'Đã thích' });
        } else {
            btn.classList.remove('active');
            icon.classList.remove('fas');
            icon.classList.add('far');
        }

        const badges = document.querySelectorAll('.wishlist-count-badge');
        badges.forEach(el => {
            el.innerText = data.count;
            el.style.display = data.count > 0 ? 'inline-block' : 'none';
        });
    })
    .catch(error => console.error('Error:', error));
};

    // ==========================================
    // XỬ LÝ HEADER THU GỌN THÔNG MINH (Version 9.0 - BODY STATE)
    // ==========================================
    const header = document.querySelector('.pharmacy-header');
    const hook = document.getElementById('header-hook-toggle');
    
    if (header && hook) {
        // 1. CLICK ĐỂ THU GỌN/MỞ RỘNG (Dùng Body để CSS dễ kiểm soát)
        hook.addEventListener('click', function() {
            document.body.classList.toggle('header-manual-collapsed');
            console.log('Header Toggle - Body State:', document.body.classList.contains('header-manual-collapsed'));
        });

        // 2. TỰ ĐỘNG RESET KHI VỀ ĐẦU TRANG & THEO DÕI CUỘN
        window.addEventListener('scroll', function() {
            if (window.scrollY < 50) {
                document.body.classList.remove('header-manual-collapsed', 'scrolled');
            } else {
                document.body.classList.add('scrolled');
            }
        }, { passive: true });

        console.log('Header Toggle 9.0 (Body State) Active');
    }

// ==========================================
// XỬ LÝ QUICK SEARCH (AJAX)
// ==========================================
(function() {
    const searchInput = document.getElementById('header-search-input');
    const resultsContainer = document.getElementById('quick-search-results');
    let debounceTimer;

    if (!searchInput || !resultsContainer) return;

    searchInput.addEventListener('input', function() {
        const keyword = this.value.trim();

        clearTimeout(debounceTimer);
        
        if (keyword.length < 2) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('active');
            return;
        }

        debounceTimer = setTimeout(() => {
            fetchQuickSearch(keyword);
        }, 300);
    });

    // Đóng khi click ngoài
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.remove('active');
        }
    });

    // Hiện lại khi click vào input (nếu có keyword)
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && resultsContainer.innerHTML !== '') {
            resultsContainer.classList.add('active');
        }
    });

    async function fetchQuickSearch(keyword) {
        // Hiện trạng thái loading
        resultsContainer.innerHTML = `<div class="search-loading"><i class="fa fa-spinner fa-spin"></i><br>Đang tìm kiếm...</div>`;
        resultsContainer.classList.add('active');

        try {
            const response = await fetch(`/api/search/quick?keyword=${encodeURIComponent(keyword)}`);
            const data = await response.json();

            renderResults(data);
        } catch (error) {
            console.error('Quick Search Error:', error);
            resultsContainer.innerHTML = `<div class="search-no-results">Có lỗi xảy ra, vui lòng thử lại.</div>`;
        }
    }

    function renderResults(products) {
        if (products.length === 0) {
            resultsContainer.innerHTML = `<div class="search-no-results"><i class="fa fa-search"></i><br>Không tìm thấy sản phẩm nào.</div>`;
            return;
        }

        let html = '';
        products.forEach(p => {
            html += `
                <a href="${p.url}" class="search-result-item">
                    <img src="${p.image}" class="res-thumb" alt="${p.name}">
                    <div class="res-info">
                        <span class="res-name">${p.name}</span>
                        <div class="res-meta">
                            <span class="res-price">
                                ${p.old_price ? `<span class="res-old-price">${p.old_price}</span>` : ''}
                                ${p.price}
                            </span>
                            <span class="res-unit">1 ${p.unit}</span>
                        </div>
                    </div>
                </a>
            `;
        });

        resultsContainer.innerHTML = html;
        resultsContainer.classList.add('active');
    }
})();

// ==========================================
// XỬ LÝ BACK TO TOP WITH PROGRESS
// ==========================================
(function() {
    const progressButton = document.getElementById('backToTop');
    if (!progressButton) return;

    const progressPath = progressButton.querySelector('.progress-circle path');
    const pathLength = progressPath.getTotalLength();

    progressPath.style.transition = progressPath.style.webkitTransition = 'none';
    progressPath.style.strokeDasharray = `${pathLength} ${pathLength}`;
    progressPath.style.strokeDashoffset = pathLength;
    progressPath.getBoundingClientRect();
    progressPath.style.transition = progressPath.style.webkitTransition = 'stroke-dashoffset 10ms linear';

    const updateProgress = () => {
        const scroll = window.scrollY;
        const height = document.documentElement.scrollHeight - window.innerHeight;
        const progress = pathLength - (scroll * pathLength / height);
        progressPath.style.strokeDashoffset = progress;

        if (scroll > 300) {
            progressButton.classList.add('active');
        } else {
            progressButton.classList.remove('active');
        }
    };

    updateProgress();
    window.addEventListener('scroll', updateProgress);

    progressButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
})();

// ==========================================
// XỬ LÝ STICKY BUY BAR (MOBILE)
// ==========================================
    // (Countdown, Search, BackToTop, StickyBar logic included above inside DOMContentLoaded)

}); // END DOMContentLoaded

} // END GLOBAL SINGLETON GUARD
// ==========================================
// REAL-TIME NOTIFICATIONS (REVERB)
// ==========================================
if (window.Echo) {
    window.Echo.channel('public-notifications')
        .listen('RealtimeNotification', (data) => {
            console.log('Realtime Notification Received:', data);
            
            // Sử dụng SweetAlert2 để hiển thị thông báo
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: data.title || 'Thông báo',
                    text: data.message,
                    icon: data.type || 'info', // success, error, warning, info
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            } else {
                console.warn('SweetAlert2 (Swal) is not defined. Notification fallback to alert.');
                // Fallback nếu Swal chưa load kịp
                // alert(data.message);
            }
        });
}

