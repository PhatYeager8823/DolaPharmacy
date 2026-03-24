import './bootstrap';

// ===== THÊM PHẦN NÀY VÀO =====

// 1. Import Swiper
import Swiper from 'swiper/bundle';

// 2. Import CSS của Swiper (Rất quan trọng)
import 'swiper/css/bundle';

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
    },

    // Kích hoạt nút Next/Prev
    pagination: {
        el: '.swiper-pagination',
        clickable: true, // Cho phép bấm vào chấm để chuyển slide
    },
});

const featuredCatSwiper = new Swiper('.featured-categories-swiper', {
    loop: true,

    autoplay: {
        delay: 4500,            // GIẢM TỐC – tự chạy chậm giống mẫu
        disableOnInteraction: false,
    },

    speed: 900,                 // Chuyển slide mượt hơn

    slidesPerView: 5,
    spaceBetween: 32,

    navigation: {
        nextEl: '.featured-cat-next',
        prevEl: '.featured-cat-prev',
    },

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
    },
    speed: 900,
    slidesPerView: 2.2, // Hiển thị hơn 2 item trên mobile
    spaceBetween: 10,
    navigation: {
        nextEl: '.hot-deals-next',
        prevEl: '.hot-deals-prev',
    },
    breakpoints: {
        576: { slidesPerView: 2.2, spaceBetween: 16 },
        768: { slidesPerView: 3, spaceBetween: 18 },
        1200:{ slidesPerView: 4, spaceBetween: 20 },
    },
});

// COUNTDOWN "Khuyến mãi hấp dẫn"
document.addEventListener('DOMContentLoaded', function () {
    const countdown = document.querySelector('.hot-deals-countdown');
    if (!countdown) return;

    // TODO: chỉnh lại ngày kết thúc khuyến mãi cho đúng
    const endTime = new Date('2025-12-31T23:59:59'); // ISO format

    const boxDays    = countdown.querySelector('.count-box[data-unit="days"] .value');
    const boxHours   = countdown.querySelector('.count-box[data-unit="hours"] .value');
    const boxMinutes = countdown.querySelector('.count-box[data-unit="minutes"] .value');
    const boxSeconds = countdown.querySelector('.count-box[data-unit="seconds"] .value');

    function updateCountdown() {
        const now = new Date();
        let diff = endTime - now;

        if (diff <= 0) {
            // Hết giờ
            boxDays.textContent    = '0';
            boxHours.textContent   = '0';
            boxMinutes.textContent = '0';
            boxSeconds.textContent = '0';
            return;
        }

        const totalSeconds = Math.floor(diff / 1000);
        const days    = Math.floor(totalSeconds / (24 * 60 * 60));
        const hours   = Math.floor((totalSeconds % (24 * 60 * 60)) / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        boxDays.textContent    = days;
        boxHours.textContent   = hours.toString().padStart(2, '0');
        boxMinutes.textContent = minutes.toString().padStart(2, '0');
        boxSeconds.textContent = seconds.toString().padStart(2, '0');
    }

    // chạy ngay lần đầu + lặp mỗi giây
    updateCountdown();
    setInterval(updateCountdown, 1000);
});

const newProductsSwiper = new Swiper('.new-products-swiper', {
    loop: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
    },
    speed: 900,
    slidesPerView: 2.2, // Chỉnh xuống mobile
    spaceBetween: 10,
    navigation: {
        nextEl: '.new-products-next',
        prevEl: '.new-products-prev',
    },
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
    },
    speed: 900,
    slidesPerView: 2.2, // Chỉnh xuống mobile
    spaceBetween: 10,
    navigation: {
        nextEl: '.featured-products-next',
        prevEl: '.featured-products-prev',
    },
    breakpoints: {
        576: { slidesPerView: 2.2, spaceBetween: 16 },
        768: { slidesPerView: 3,   spaceBetween: 18 },
        1200:{ slidesPerView: 3.5, spaceBetween: 20 },
    },
});

const videoSwiper = new Swiper('.video-swiper', {
    loop: true,
    autoplay: {
        delay: 4500,
        disableOnInteraction: false,
    },
    speed: 900,
    slidesPerView: 1.1,
    spaceBetween: 16,
    navigation: {
        nextEl: '.video-swiper-button-next',
        prevEl: '.video-swiper-button-prev',
    },
    breakpoints: {
        576: { slidesPerView: 2, spaceBetween: 18 },
        992: { slidesPerView: 3, spaceBetween: 20 },
        1200:{ slidesPerView: 4, spaceBetween: 22 },
    },
});

// ==========================================
// HÀM XỬ LÝ GIỎ HÀNG (GLOBAL)
// ==========================================

window.addToCart = function(productId, btnElement) {
    // === PHẦN 1: XỬ LÝ HIỆU ỨNG BAY (FLY EFFECT) ===
    if (btnElement) {
        // 1. Tìm ảnh gốc để bay
        let productImg = null;

        // Nếu là trang danh sách (nút nằm trong card)
        const card = btnElement.closest('.fp-card');
        if (card) {
            productImg = card.querySelector('.fp-image img');
        }
        // Nếu là trang chi tiết (tìm ảnh to #mainImage)
        else {
            productImg = document.getElementById('mainImage');
        }

        // 2. Tìm đích đến (Icon giỏ hàng trên Header)
        // Lưu ý: Đảm bảo icon giỏ hàng của bạn có class .fa-shopping-cart hoặc id specific
        // Ở đây mình target vào class .cart-count hoặc icon giỏ
        const cartIcon = document.querySelector('.fa-shopping-cart') || document.querySelector('.cart-count');

        // 3. Thực hiện bay nếu tìm thấy cả 2
        if (productImg && cartIcon) {
            // Tạo bản sao của ảnh
            const flyImg = productImg.cloneNode();
            flyImg.classList.add('fly-item');

            // Lấy tọa độ
            const startRect = productImg.getBoundingClientRect();
            const endRect = cartIcon.getBoundingClientRect();

            // Thiết lập vị trí bắt đầu (trùng với ảnh gốc)
            flyImg.style.top = startRect.top + 'px';
            flyImg.style.left = startRect.left + 'px';
            flyImg.style.width = startRect.width + 'px';
            flyImg.style.height = startRect.height + 'px';

            document.body.appendChild(flyImg);

            // Sau 50ms thì set vị trí kết thúc (để kích hoạt transition CSS)
            setTimeout(() => {
                flyImg.style.top = (endRect.top + 10) + 'px'; // +10 để vào giữa icon
                flyImg.style.left = (endRect.left + 10) + 'px';
                flyImg.style.width = '20px'; // Thu nhỏ lại còn 20px
                flyImg.style.height = '20px';
                flyImg.style.opacity = '0.5';
            }, 50);

            // Bay xong thì xóa ảnh đi
            setTimeout(() => {
                flyImg.remove();
            }, 850); // Khớp với transition 0.8s trong CSS
        }
    }

    // === PHẦN 2: LOGIC GỬI AJAX CŨ (GIỮ NGUYÊN) ===
    let quantity = 1;
    const qtyInput = document.getElementById('product_qty');
    if (qtyInput) {
        quantity = parseInt(qtyInput.value);
        if (isNaN(quantity) || quantity < 1) quantity = 1;
    }

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
// HÀM XỬ LÝ YÊU THÍCH (WISHLIST)
// ==========================================
window.toggleWishlist = function(id, btn) {
    // 1. Lấy URL từ thuộc tính data-url của nút bấm
    const url = btn.getAttribute('data-url');
    const icon = btn.querySelector('i');

    // 2. Lấy CSRF Token
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfTokenMeta) {
        console.error('CSRF token not found');
        return;
    }
    const csrfToken = csrfTokenMeta.getAttribute('content');

    // 3. Gửi AJAX
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
        // Xử lý khi chưa đăng nhập
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

        // Xử lý thành công
        if(data.action === 'added') {
            btn.classList.add('active');
            icon.classList.remove('far'); // Tim rỗng
            icon.classList.add('fas');    // Tim đặc

            // Hiện toast thông báo nhỏ (Tùy chọn)
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 1500
            });
            Toast.fire({ icon: 'success', title: 'Đã thích' });

        } else {
            btn.classList.remove('active');
            icon.classList.remove('fas'); // Tim đặc
            icon.classList.add('far');    // Tim rỗng
        }

        // Cập nhật số lượng trên Header (tìm class .wishlist-count-badge)
        const badges = document.querySelectorAll('.wishlist-count-badge');
        badges.forEach(el => {
            el.innerText = data.count;
            el.style.display = data.count > 0 ? 'inline-block' : 'none';
        });
    })
    .catch(error => console.error('Error:', error));
};
