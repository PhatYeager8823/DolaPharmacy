{{-- ================= TOP FOOTER ================= --}}
<section class="footer-topbar">
    <div class="container d-flex justify-content-between align-items-center py-3 flex-wrap">

        {{-- Form đăng ký email --}}
        <div class="footer-subscribe">
            <input type="text" class="footer-email-input" placeholder="Nhập email nhận tin khuyến mãi">
            <button class="footer-sub-btn">ĐĂNG KÝ</button>
        </div>

        {{-- Kết nối với chúng tôi --}}
        <div class="footer-social d-flex align-items-center gap-3 mt-3 mt-lg-0">

            <span class="footer-social-label">Kết nối với chúng tôi:</span>

            {{-- Icon Zalo --}}
            <a href="{{ $global_setting->zalo ?? '#' }}" class="footer-social-icon">
                <img src="/images/icons/zalo.png" alt="Zalo">
            </a>

            {{-- Icon Facebook --}}
            <a href="{{ $global_setting->facebook ?? '#' }}" class="footer-social-icon">
                <img src="/images/icons/facebook.png" alt="Facebook">
            </a>

            {{-- Icon YouTube --}}
            <a href="{{ $global_setting->youtube ?? '#' }}" class="footer-social-icon">
                <img src="/images/icons/youtube.png" alt="YouTube">
            </a>

            {{-- Icon Google --}}
            <a href="#" class="footer-social-icon">
                <img src="/images/icons/google.png" alt="Google">
            </a>
        </div>
    </div>
</section>

<section class="footer-main py-5">
    <div class="container">

        <div class="row g-4">

            {{-- Cột 1 – Thông tin cửa hàng --}}
            <div class="col-12 col-md-4 col-lg-3">

                <p class="footer-about-text">
                    Cửa hàng thực phẩm chức năng <strong>{{ $global_setting->ten_website ?? 'Dola Pharmacy' }}</strong> là địa chỉ tin cậy để bạn tìm kiếm những sản phẩm chất lượng nhất.
                </p>

                <div class="footer-contact">
                    <p><i class="fa fa-map-marker me-2"></i> {{ $global_setting->dia_chi ?? 'Đang cập nhật...' }}</p>
                    <p><i class="fa fa-phone me-2"></i> {{ $global_setting->hotline ?? '...' }}</p>
                    <p><i class="fa fa-envelope me-2"></i> {{ $global_setting->email ?? '...' }}</p>
                </div>
            </div>

            {{-- Cột 2 – Chính sách --}}
            <div class="col-6 col-md-4 col-lg-2">
                <h5 class="footer-title">CHÍNH SÁCH</h5>
                <ul class="footer-list">
                    <li>Chính sách thành viên</li>
                    <li>Chính sách thanh toán</li>
                    <li>Hướng dẫn mua hàng</li>
                    <li>Bảo mật thông tin cá nhân</li>
                </ul>
            </div>

            {{-- Cột 3 – Hướng dẫn --}}
            <div class="col-6 col-md-4 col-lg-2">
                <h5 class="footer-title">HƯỚNG DẪN</h5>
                <ul class="footer-list">
                    <li>Hướng dẫn mua hàng</li>
                    <li>Hướng dẫn thanh toán</li>
                    <li>Đăng ký thành viên</li>
                    <li>Hỗ trợ khách hàng</li>
                    <li>Câu hỏi thường gặp</li>
                </ul>
            </div>

            {{-- Cột 4 – Danh mục --}}
            <div class="col-6 col-md-4 col-lg-2">
                <h5 class="footer-title">DANH MỤC</h5>
                <ul class="footer-list">
                    <li>Về Dola Pharmacy</li>
                    <li>Tuyển dụng nhân sự</li>
                    <li>Giá trị cốt lõi</li>
                    <li>Nguồn gốc thực phẩm</li>
                    <li>Liên hệ</li>
                </ul>
            </div>

            {{-- Cột 5 – Đăng ký nhận tin + Liên kết sàn --}}
            <div class="col-12 col-md-8 col-lg-3">
                <h5 class="footer-title">ĐĂNG KÝ NHẬN TIN</h5>

                <div class="footer-phone-box">
                    <b>MUA ONLINE (08:30 - 20:30)</b>
                    <div class="footer-phone">{{ $global_setting->hotline ?? '...' }}</div>
                    <small>Tất cả các ngày trong tuần (Trừ tết Âm Lịch)</small>
                </div>

                <div class="footer-phone-box mt-3">
                    <b>GÓP Ý & KHIẾU NẠI (08:30 - 20:30)</b>
                    <div class="footer-phone">{{ $global_setting->hotline ?? '...' }}</div>
                    <small>Tất cả các ngày trong tuần (Trừ tết Âm Lịch)</small>
                </div>

                <h5 class="footer-title mt-4">LIÊN KẾT SÀN</h5>

                <div class="footer-ecommerce d-flex gap-3">
                    {{-- Bạn tự thay link hình sau --}}
                    <img src="{{ asset('images/icons/shopee.png') }}" class="footer-icon" alt="">
                    <img src="{{ asset('images/icons/lazada.png') }}" class="footer-icon" alt="">
                    <img src="{{ asset('images/icons/tiki.png') }}" class="footer-icon" alt="">
                    <img src="{{ asset('images/icons/sendo.png') }}" class="footer-icon" alt="">
                </div>

            </div>

        </div>

    </div>
</section>

