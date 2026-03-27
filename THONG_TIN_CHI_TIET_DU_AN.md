# TÀI LIỆU CHI TIẾT DỰ ÁN: WEBSITE NHÀ THUỐC DOLA PHARMACY

Tài liệu này cung cấp toàn bộ thông tin kỹ thuật, tính năng và cấu trúc của dự án để hỗ trợ việc viết báo cáo chi tiết.

---

## 1. THÔNG TIN CHUNG & CÔNG NGHỆ (TECH STACK)

### Backend
- **Framework:** Laravel 12.0 (Phiên bản mới nhất).
- **Ngôn ngữ:** PHP 8.2+.
- **Database:** MySQL.
- **Thư viện quan trọng:**
    - `intervention/image`: Xử lý hình ảnh (upload, resize).
    - `twilio/sdk`: Tích hợp gửi mã OTP qua SMS.
    - `doctrine/dbal`: Hỗ trợ thao tác database nâng cao.

### Frontend
- **Bundler:** Vite.
- **CSS Framework:** TailwindCSS 4.0 & Bootstrap 5.3 (Kết hợp linh hoạt).
- **Javascript:** ES6+, Axios (gọi API).
- **Thư viện UI/UX:**
    - `SweetAlert2`: Thông báo xác nhận, cảnh báo chuyên nghiệp.
    - `Swiper`: Slider sản phẩm và banner.
    - `FontAwesome 7.1`: Hệ thống icon.
    - `Popperjs`: Xử lý tooltip/popover.

---

## 2. CẤU TRÚC HỆ THỐNG DỮ LIỆU (CSDL - DATABASE)

Hệ thống sở hữu hơn 20 bảng dữ liệu được chuẩn hóa, phân chia thành các nhóm:

### Nhóm Sản phẩm & Danh mục
- `thuocs`: Bảng chính lưu trữ thông tin thuốc (Tên, slug, mã sản phẩm, số đăng ký, giá bán, giá cũ, đơn vị tính, quy cách, hoạt chất, hàm lượng, công dụng, cách dùng, tác dụng phụ, hình ảnh, tồn kho, flags: `is_featured`, `is_hot_deal`, `is_new`, `is_exclusive`, `is_suggested`...)
- `danh_mucs`: Danh mục sản phẩm (Hỗ trợ danh mục đa cấp thông qua `danh_muc_cha_id`).
- `brands`: Thương hiệu thuốc/dược phẩm.
- `nha_cung_caps`: Thông tin các nhà cung cấp sản phẩm.

### Nhóm Khách hàng & Giao dịch
- `nguoi_dungs`: Tài khoản người dùng (SĐT, Email, Mật khẩu, Avatar, vai trò, trạng thái khóa/mở).
- `dia_chis`: Sổ địa chỉ của người dùng (Hỗ trợ nhiều địa chỉ, có địa chỉ mặc định).
- `orders`: Hóa đơn đặt hàng (Mã đơn, tổng tiền, phương thức thanh toán, trạng thái, ghi chú).
- `order_items`: Chi tiết từng sản phẩm trong hóa đơn.
- `gio_hangs` & `gio_hang_chi_tiets`: Quản lý giỏ hàng của người dùng (Lưu trữ cả khi người dùng chưa đăng nhập hoặc đã đăng nhập).

### Nhóm Tương tác & Marketing
- `danh_gias`: Đánh giá và bình luận sản phẩm (Số sao, nội dung, trạng thái duyệt).
- `yeu_thiches`: Sản phẩm khách hàng lưu lại để xem sau.
- `coupons`: Mã giảm giá (Tên, mã, loại giảm: tiền mặt/phần trăm, hạn sử dụng, giới hạn sử dụng).
- `sliders`: Quản lý các banner quảng cáo trên trang chủ.
- `thong_baos`: Gửi thông báo từ hệ thống đến người dùng.

### Nhóm Kho & Quản trị
- `phieu_nhaps` & `chi_tiet_phieu_nhaps`: Quản lý việc nhập thuốc từ nhà cung cấp vào kho.
- `ton_khos`: Theo dõi số lượng tồn thực tế của từng loại thuốc.
- `settings`: Cấu hình website (Logo, thông tin liên hệ, mạng xã hội, các cờ bật/tắt tính năng khuyến mãi).

### Nhóm Nội dung
- `bai_viets`: Tin tức y tế, cẩm nang sức khỏe.
- `videos`: Các video giới thiệu hoặc hướng dẫn sử dụng sản phẩm.
- `faqs`: Các câu hỏi thường gặp.
- `lien_hes`: Tiếp nhận phản hồi từ khách hàng.

---

## 3. DANH SÁCH TÍNH NĂNG CHI TIẾT

### 3.1. Phân hệ Khách hàng (Client Side)
- **Đăng ký/Đăng nhập thông minh:** 
    - Đăng nhập bằng SĐT + OTP (Xác nhận qua SMS).
    - Hỗ trợ đăng nhập truyền thống bằng Email + Mật khẩu.
    - Tự động tạo tài khoản khi mua hàng lần đầu.
- **Trải nghiệm mua sắm:**
    - Xem sản phẩm theo Danh mục/Thương hiệu.
    - Lọc sản phẩm thông minh (Theo giá, độ phổ biến, hot deals).
    - Tìm kiếm nhanh (Ajax search gợi ý).
    - Xem chi tiết sản phẩm với đầy đủ thông tin dược lý (Công dụng, cách dùng...).
- **Quản lý Giỏ hàng & Thanh toán:**
    - Thêm nhanh vào giỏ hàng từ trang danh sách.
    - Áp dụng mã giảm giá (Coupon).
    - Thanh toán 1 bước (One-page checkout).
    - Mua lại đơn hàng cũ chỉ bằng 1 click (Repurchase).
- **Quản lý Tài khoản (Dashboard):**
    - Cập nhật hồ sơ: Tên, Email, SĐT, Avatar.
    - Đổi mật khẩu/Cập nhật Email có xác thực OTP.
    - Quản lý sổ địa chỉ giao hàng.
    - Theo dõi lịch sử đơn hàng và trạng thái vận chuyển.
    - Quản lý mã giảm giá của tôi.
- **Tính năng đặc biệt:**
    - **Chatbot AI (Gemini):** Tư vấn sức khỏe và giải đáp thắc mắc tự động thông qua tích hợp API Google Gemini.
    - **Wishlist:** Lưu sản phẩm yêu thích.
    - **Reviews:** Đánh giá sản phẩm kèm số sao.

### 3.2. Phân hệ Quản trị (Admin Dashboard)
- **Tổng quan (Statistics):** Biểu đồ doanh thu, thống kê đơn hàng mới, số lượng khách hàng.
- **Quản lý Sản phẩm & Kho:**
    - CRUD (Thêm, Sửa, Xóa) Thuốc, Danh mục, Thương hiệu.
    - Quản lý Nhập hàng: Tạo phiếu nhập, quản lý nhà cung cấp.
    - Báo cáo tồn kho: Cảnh báo hàng sắp hết.
- **Quản lý Giao dịch:**
    - Phê duyệt và cập nhật trạng thái đơn hàng (Đang xử lý, Đã giao, Hủy).
    - Quản lý mã giảm giá (Voucher).
- **Quản lý Tương tác:**
    - Duyệt/Ẩn đánh giá từ khách hàng.
    - Phản hồi liên hệ từ form liên hệ.
    - Gửi thông báo (Notification) đến toàn bộ user hoặc user cụ thể.
- **Quản lý Nội dung & Giao diện:**
    - Quản lý Slider/Banner quảng cáo.
    - Quản lý Blog tin tức, Video, FAQ.
    - Thay đổi cấu hình website (Logo, Hotline, Email hệ thống).
    - Quản lý danh sách người dùng (Khóa/Mở tài khoản).

---

## 4. KIẾN TRÚC MÃ NGUỒN (MVC ARCHITECTURE)

Dự án tuân thủ mô hình **MVC** của Laravel:
- **Model:** Nằm trong `app/Models/`, sử dụng Eloquent ORM mạnh mẽ để tương tác DB. Hỗ trợ Soft Deletes (xóa tạm) cho các bảng quan trọng như `thuocs`.
- **View:** Nằm trong `resources/views/`, sử dụng Blade Engine. Cấu trúc rõ ràng: `admin/` (quản trị), `account/` (tài khoản khách), `pages/` (nội dung tĩnh), `layouts/` (template chung).
- **Controller:** Nằm trong `app/Http/Controllers/`. Tách biệt `Admin/` controller và `Client/` controller để bảo mật và quản lý code dễ dàng.
- **Service Layer:** Sử dụng `app/Services/ImageService.php` để xử lý ảnh chung (webp conversion, resizing).

---

## 5. CÁC ĐIỂM NHẤN CÔNG NGHỆ (KEY HIGHLIGHTS)

1. **Tối ưu hình ảnh (WebP):** Toàn bộ ảnh sản phẩm, danh mục, blog khi upload đều được tự động chuyển sang định dạng WebP để giảm dung lượng (giảm 30-50%) mà vẫn giữ được chất lượng, giúp web tải nhanh.
2. **SEO Friendly:** Sử dụng slug đẹp (Pretty URL) cho tất cả sản phẩm và bài viết. Metadata được tối ưu hóa.
3. **Bảo mật:** 
    - Chống SQL Injection, XSS, CSRF (mặc định từ Laravel).
    - Xác thực OTP cho tất cả các hành động nhạy cảm (Đăng nhập, đổi mật khẩu, đổi email).
4. **UI/UX:** 
    - Sử dụng AJAX để thêm vào giỏ hàng, cập nhật giỏ hàng không cần load lại trang.
    - SweetAlert2 cho tất cả các thông báo tương tác.
    - Sidebar admin thông minh, lưu trữ trạng thái mở/đóng.

---

## 6. GỢI Ý NỘI DUNG CHO TỪNG PHẦN BÁO CÁO

### PHẦN 1: MỞ ĐẦU
- **Lý do:** Nhấn mạnh tính cấp thiết của việc bán thuốc online sau đại dịch và nhu cầu tư vấn dược phẩm 24/7. Việc tự xây dựng nền tảng giúp quản lý hồ sơ bệnh lý khách hàng tốt hơn.
- **Đối tượng:** Khách lẻ, hộ gia đình. Phạm vi: Dược phẩm không kê đơn, thực phẩm chức năng, dụng cụ y khoa.

### PHẦN 2: PHÂN TÍCH & THIẾT KẾ
- **Chức năng:** Trình bày chi tiết danh sách Admin/Khách hàng ở mục 3.
- **ERD:** Dựa vào danh sách bảng ở mục 2 để vẽ sơ đồ. Chú ý mối quan hệ 1-nhiều giữa `DanhMuc` và `Thuoc`, `Order` và `OrderItem`.

### PHẦN 3: CÀI ĐẶT
- **MVC:** Giải thích cách Laravel tách biệt `Thuoc.php` (Model), `ThuocController.php` (Controller) và `thuoc/show.blade.php` (View).
- **Tính năng TMĐT:** Mô tả cách tích hợp API Gemini (AI) và hệ thống OTP (SMS).

### PHẦN 4: TỔNG KẾT
- **Ưu điểm:** Tốc độ nhanh (WebP), Bảo mật cao (OTP), Có AI tư vấn.
- **Hạn chế:** Chưa tích hợp vận chuyển (GHTK, ViettelPost), chưa có App iOS/Android.
- **Hướng phát triển:** Tích hợp video call với dược sĩ, AI chuẩn đoán triệu chứng cơ bản.

