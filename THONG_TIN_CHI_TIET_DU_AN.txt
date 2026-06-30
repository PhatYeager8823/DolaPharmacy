# TÀI LIỆU CHI TIẾT DỰ ÁN: WEBSITE NHÀ THUỐC DOLA PHARMACY

Tài liệu này cung cấp toàn bộ thông tin kỹ thuật, tính năng và cấu trúc của dự án để hỗ trợ việc viết báo cáo chi tiết.

---

## 1. THÔNG TIN CHUNG & CÔNG NGHỆ (TECH STACK)

### Backend
- **Framework:** Laravel 12.0 (Phiên bản mới nhất).
- **Ngôn ngữ:** PHP 8.2+.
- **Database:** MySQL (với Trigger tự động cập nhật tồn kho).
- **Realtime:** Laravel Reverb (WebSocket server tích hợp sẵn) + Laravel Broadcasting.
- **AI:** Google Gemini API (`gemini-2.5-flash`) — dùng cho Chatbot tư vấn dược.
- **Thư viện quan trọng:**
    - `intervention/image ^3.11`: Xử lý hình ảnh (upload, resize, chuyển WebP).
    - `twilio/sdk ^8.9`: Gửi mã OTP xác thực qua SMS.
    - `doctrine/dbal ^4.4`: Hỗ trợ thao tác database nâng cao (alter column).
    - `guzzlehttp/guzzle ^7.10`: HTTP client gọi API ngoài (Gemini, Twilio).
- **Triển khai:** Docker + Docker Compose (Dockerfile + docker-compose.yml có sẵn).

### Frontend
- **Bundler:** Vite.
- **CSS Framework:** TailwindCSS 4.0 & Bootstrap 5.3 (Kết hợp linh hoạt).
- **Javascript:** ES6+, Axios (gọi API AJAX).
- **Thư viện UI/UX:**
    - `SweetAlert2`: Thông báo xác nhận, cảnh báo chuyên nghiệp.
    - `Swiper`: Slider sản phẩm và banner quảng cáo.
    - `FontAwesome 7.1`: Hệ thống icon.
    - `Popperjs`: Xử lý tooltip/popover.
    - `Laravel Echo + Reverb`: Lắng nghe sự kiện realtime phía client (thông báo đơn mới).

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
    - Đăng nhập bằng SĐT + OTP (Xác nhận qua SMS Twilio).
    - Hỗ trợ đăng nhập truyền thống bằng Email + Mật khẩu.
    - Tự động tạo tài khoản khi mua hàng lần đầu (is_guest = 1).
    - Chặn đăng nhập tài khoản bị khóa (trang_thai = 0).
    - Dev Mode: Tự động hiển thị OTP ngay trên màn hình (môi trường local).
- **Trải nghiệm mua sắm:**
    - Xem sản phẩm theo Danh mục đa cấp / Thương hiệu.
    - Lọc sản phẩm thông minh: theo giá (khoảng/tự nhập), thương hiệu, hot deals.
    - Sắp xếp: Mới nhất, Giá tăng/giảm, Tên A-Z.
    - Tìm kiếm nhanh AJAX (gợi ý theo tên, hoạt chất, công dụng — giới hạn 8 kết quả).
    - **Quick View Modal:** Xem nhanh thông tin + thêm giỏ hàng mà không cần vào trang chi tiết.
    - Xem chi tiết sản phẩm với đầy đủ thông tin dược lý (Công dụng, Cách dùng, Tác dụng phụ, Thành phần).
    - Xem sản phẩm liên quan (cùng danh mục, ngẫu nhiên).
- **Quản lý Giỏ hàng & Thanh toán:**
    - Thêm nhanh vào giỏ hàng từ trang danh sách (AJAX, không reload trang).
    - **Mua Ngay (Buy Now):** Bỏ qua giỏ hàng, chuyển thẳng đến trang thanh toán.
    - **Guest Checkout:** Khách chưa đăng nhập vẫn đặt hàng được; hệ thống tự tạo tài khoản.
    - Áp dụng mã giảm giá (Coupon — theo % hoặc số tiền cố định).
    - Tính phí ship tự động (Free ship nội thành Bạc Liêu, 15.000đ ngoại tỉnh).
    - Thanh toán 1 bước (One-page checkout): COD hoặc VNPay.
    - **VNPay Payment Gateway:** Tạo URL thanh toán, xử lý callback với HMAC-SHA512.
    - **Mua lại (Repurchase/Reorder):** Thêm lại toàn bộ đơn cũ vào giỏ bằng 1 click.
    - **Hủy đơn hàng:** Khách tự hủy đơn từ trang lịch sử mua hàng.
- **Quản lý Tài khoản (Dashboard):**
    - Cập nhật hồ sơ: Tên, Email, SĐT, Ngày sinh, Giới tính, Avatar.
    - Đổi mật khẩu / Cập nhật Email — đều có xác thực OTP bảo mật.
    - Quản lý sổ địa chỉ giao hàng (thêm/sửa/xóa/đặt mặc định).
    - Theo dõi lịch sử đơn hàng và trạng thái vận chuyển chi tiết.
    - Quản lý mã giảm giá cá nhân.
    - **Hệ thống Hạng Thành viên (Loyalty Program):** Bạc → Vàng (≥5 triệu) → Kim Cương (≥10 triệu) dựa trên tổng chi tiêu, hiển thị thanh tiến độ.
- **Tính năng đặc biệt:**
    - **Chatbot AI (Gemini 2.5 Flash):** Dược sĩ ảo tư vấn thuốc, báo giá, giải đáp dựa trên danh sách sản phẩm thực tế.
    - **Wishlist:** Lưu / bỏ lưu sản phẩm yêu thích (toggle AJAX).
    - **Reviews & Đánh giá:** Chấm sao (1-5) + bình luận, có trạng thái duyệt.

### 3.2. Phân hệ Quản trị (Admin Dashboard)
- **Tổng quan (Statistics Dashboard):**
    - 4 thẻ KPI: Tổng doanh thu, Tổng đơn hàng, Tổng sản phẩm, Tổng khách hàng.
    - Biểu đồ doanh thu 12 tháng (Bar Chart) và số đơn 12 tháng (Line Chart).
    - Biểu đồ trạng thái đơn hàng (Doughnut Chart: Chờ xác nhận / Đang giao / Đã giao / Hủy).
    - Danh sách cảnh báo sản phẩm sắp hết hàng (tồn kho < 10).
    - **Realtime:** Thông báo popup khi có đơn hàng mới (WebSocket qua Laravel Reverb).
- **Quản lý Sản phẩm & Kho:**
    - CRUD đầy đủ: Thuốc, Danh mục (đa cấp), Thương hiệu, Nhà cung cấp.
    - Xóa mềm (Soft Delete) thuốc — có thể khôi phục.
    - Quản lý Nhập hàng: Tạo phiếu nhập từ nhà cung cấp, tự động cộng tồn kho qua Trigger.
    - Báo cáo tồn kho chi tiết: Cảnh báo hàng sắp hết, lịch sử nhập/xuất.
- **Quản lý Giao dịch:**
    - Xem danh sách, lọc và cập nhật trạng thái đơn hàng (Chờ xác nhận → Đang giao → Đã giao / Hủy).
    - Xem chi tiết đơn: Sản phẩm, địa chỉ, phương thức thanh toán.
    - Quản lý mã giảm giá (Coupon): CRUD, loại % hoặc tiền mặt, hạn dùng.
- **Quản lý Tương tác:**
    - Duyệt / Ẩn đánh giá sản phẩm từ khách hàng.
    - Xem và phản hồi liên hệ từ form liên hệ (toggle trạng thái đã xử lý).
    - Gửi thông báo hệ thống đến toàn bộ user hoặc user cụ thể.
- **Quản lý Nội dung & Giao diện:**
    - Quản lý Slider/Banner quảng cáo trang chủ (ảnh, tiêu đề, link, thứ tự).
    - Quản lý Blog tin tức (Bài viết), Video hướng dẫn, FAQ.
    - Cài đặt website: Logo, Hotline, Email, mạng xã hội, bật/tắt tính năng khuyến mãi.
    - Quản lý người dùng: Xem danh sách, Khóa/Mở tài khoản, Xóa.

---

## 4. KIẾN TRÚC MÃ NGUỒN (MVC ARCHITECTURE)

Dự án tuân thủ nghiêm ngặt mô hình **MVC** của Laravel:
- **Model (`app/Models/`):** Sử dụng Eloquent ORM với đầy đủ Relationships (hasMany, belongsTo, belongsToMany). Bảng `thuocs` áp dụng **Soft Deletes** — dữ liệu xóa được giữ lại, không mất vĩnh viễn.
- **View (`resources/views/`):** Sử dụng Blade Template Engine. Cấu trúc rõ ràng: `admin/` (giao diện quản trị), `account/` (tài khoản khách hàng), `pages/` (nội dung tĩnh), `layouts/` (template dùng chung), `auth/` (xác thực), `checkout/` (thanh toán).
- **Controller (`app/Http/Controllers/`):** Tách biệt namespace `Admin\` (17 controller) và controller client (17 controller) để phân quyền và bảo mật rõ ràng.
- **Service Layer (`app/Services/`):** `ImageService.php` xử lý upload ảnh tập trung: resize, chống trùng tên, chuyển đổi WebP.
- **Events & Broadcasting (`app/Events/`):** `RealtimeNotification` event phát qua WebSocket (Laravel Reverb) để thông báo admin realtime.
- **Database Triggers (MySQL):**
    - `trg_capnhap_tonkho`: Tự động cộng/trừ `so_luong_ton` trong bảng `thuocs` sau mỗi giao dịch kho.
    - `trg_tinh_thanh_tien_hdct`: Tự động tính `thanh_tien = so_luong × don_gia` khi insert chi tiết hóa đơn.
- **Middleware:** `admin.auth` bảo vệ toàn bộ route `/quan-tri`, `auth` bảo vệ các trang tài khoản cá nhân.
- **Queue & Jobs:** Cấu hình sẵn queue listener để xử lý tác vụ nền (gửi email, SMS).

---

## 5. CÁC ĐIỂM NHẤN CÔNG NGHỆ (KEY HIGHLIGHTS)

1. **Tối ưu hình ảnh (WebP):** Toàn bộ ảnh khi upload (sản phẩm, danh mục, blog, slider) đều tự động resize và chuyển sang WebP, giảm 30–50% dung lượng, tăng tốc độ tải trang.
2. **SEO Friendly:** Slug đẹp (Pretty URL) cho sản phẩm và bài viết. Metadata tối ưu hóa. URL sạch thân thiện với công cụ tìm kiếm.
3. **Bảo mật đa lớp:**
    - Chống SQL Injection, XSS, CSRF (tích hợp mặc định từ Laravel).
    - OTP xác thực cho mọi hành động nhạy cảm: Đăng nhập, đổi mật khẩu, đổi email.
    - Mật khẩu lưu dưới dạng Hash (bcrypt) — không lưu plaintext.
    - Middleware phân quyền Admin / User rõ ràng.
    - VNPay callback xác thực bằng HMAC-SHA512.
4. **Realtime (WebSocket):** Laravel Reverb + Laravel Echo — Admin nhận thông báo đơn hàng mới ngay lập tức không cần refresh trang.
5. **AI Integration:** Chatbot dược sĩ ảo tích hợp Google Gemini 2.5 Flash — tư vấn thuốc dựa trên context là danh sách sản phẩm thực tế của nhà thuốc.
6. **UI/UX nâng cao:**
    - AJAX xuyên suốt: thêm giỏ hàng, cập nhật số lượng, tìm kiếm gợi ý, quick view — không reload trang.
    - SweetAlert2 cho tất cả thông báo xác nhận tương tác.
    - Fly-to-cart animation khi thêm sản phẩm vào giỏ.
    - Sidebar admin lưu trạng thái mở/đóng.
7. **Docker Containerization:** `Dockerfile` + `docker-compose.yml` hỗ trợ triển khai nhất quán trên mọi môi trường (dev/production).

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

