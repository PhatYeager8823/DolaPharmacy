# 💊 Dola Pharmacy — Website Nhà Thuốc Thông Minh

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white"/>
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white"/>
  <img src="https://img.shields.io/badge/Google_Gemini-8E75B2?style=for-the-badge&logo=google&logoColor=white"/>
  <img src="https://img.shields.io/badge/WebSocket-Reverb-F48024?style=for-the-badge&logo=laravel&logoColor=white"/>
</p>

> **Dola Pharmacy** là nền tảng thương mại điện tử dược phẩm toàn diện xây dựng trên Laravel 12. Tích hợp Chatbot AI dược sĩ ảo (Gemini 2.5 Flash), xác thực OTP qua SMS, thanh toán MoMo/VNPay, thông báo đơn hàng realtime (WebSocket) và hệ thống quản trị admin đầy đủ.

---

## 📋 Mục Lục

- [Tổng Quan](#-tổng-quan)
- [Tính Năng](#-tính-năng)
- [Công Nghệ Sử Dụng](#-công-nghệ-sử-dụng)
- [Kiến Trúc Hệ Thống](#-kiến-trúc-hệ-thống)
- [Cài Đặt & Chạy](#-cài-đặt--chạy)
- [Cấu Trúc Thư Mục](#-cấu-trúc-thư-mục)
- [Cơ Sở Dữ Liệu](#-cơ-sở-dữ-liệu)
- [Điểm Nhấn Công Nghệ](#-điểm-nhấn-công-nghệ)

---

## 🎯 Tổng Quan

Dola Pharmacy giải quyết bài toán **quản lý và kinh doanh dược phẩm trực tuyến** với 3 điểm khác biệt cốt lõi:

- 🤖 **AI Dược Sĩ Ảo**: Chatbot tích hợp Google Gemini 2.5 Flash, tư vấn thuốc dựa trên catalog sản phẩm thực tế
- ⚡ **Realtime Admin**: Admin nhận thông báo đơn hàng mới ngay lập tức qua WebSocket (Laravel Reverb)
- 🔐 **Bảo mật OTP**: Xác thực mọi hành động nhạy cảm (đăng nhập, đổi mật khẩu, đổi email) qua SMS Twilio

---

## ✨ Tính Năng

### 👤 Phân Hệ Khách Hàng

<details>
<summary><strong>🔐 Xác thực & Tài khoản</strong></summary>

- Đăng nhập bằng **SĐT + OTP SMS** (Twilio) — không cần nhớ mật khẩu
- Đăng nhập truyền thống bằng **Email + Mật khẩu**
- **Guest Checkout**: Khách chưa đăng nhập vẫn đặt hàng, hệ thống tự tạo tài khoản
- Đổi mật khẩu / Đổi email đều có xác thực OTP bảo mật
- Quản lý sổ địa chỉ giao hàng (thêm/sửa/xóa/đặt mặc định)
- **Hạng Thành Viên**: Bạc → Vàng (≥5 triệu) → Kim Cương (≥10 triệu) với thanh tiến độ

</details>

<details>
<summary><strong>🛒 Mua sắm & Thanh toán</strong></summary>

- Lọc sản phẩm thông minh: theo giá (khoảng/tự nhập), thương hiệu, hot deals
- Sắp xếp: Mới nhất, Giá tăng/giảm, Tên A-Z
- **Tìm kiếm AJAX** gợi ý theo tên, hoạt chất, công dụng (8 kết quả)
- **Quick View Modal**: Xem + thêm giỏ hàng không cần vào trang chi tiết
- Thêm giỏ hàng AJAX — không reload trang, có fly-to-cart animation
- **Mua Ngay (Buy Now)**: Bỏ qua giỏ hàng, vào thanh toán ngay
- Áp dụng **Coupon** (giảm % hoặc tiền mặt cố định)
- Tính phí ship tự động (Free ship nội thành, 15.000đ ngoại tỉnh)
- Thanh toán: **COD**, **MoMo**, **VNPay** (HMAC-SHA512)
- **Mua lại (Reorder)**: Thêm lại toàn bộ đơn cũ bằng 1 click
- Hủy đơn hàng tự chủ từ trang lịch sử

</details>

<details>
<summary><strong>🤖 Tính Năng Đặc Biệt</strong></summary>

- **Chatbot AI Dược Sĩ**: Google Gemini 2.5 Flash tư vấn thuốc, báo giá theo catalog thực tế
- **Wishlist**: Lưu/bỏ sản phẩm yêu thích (toggle AJAX)
- **Đánh giá sản phẩm**: Chấm sao 1-5 + bình luận (có trạng thái duyệt)
- Xem sản phẩm theo danh mục đa cấp / thương hiệu
- Xem sản phẩm liên quan (cùng danh mục)
- Trang tin tức, video hướng dẫn, FAQ, liên hệ

</details>

---

### ⚙️ Phân Hệ Quản Trị (Admin)

<details>
<summary><strong>📊 Dashboard & Báo Cáo</strong></summary>

- **4 KPI Cards**: Doanh thu, Đơn hàng, Sản phẩm, Khách hàng
- **Biểu đồ doanh thu 12 tháng** (Bar Chart)
- **Biểu đồ số đơn 12 tháng** (Line Chart)
- **Biểu đồ trạng thái đơn** (Doughnut: Chờ / Đang giao / Đã giao / Hủy)
- Cảnh báo sản phẩm **sắp hết hàng** (tồn kho < 10)
- **🔔 Realtime**: Popup thông báo đơn mới qua WebSocket (không cần refresh)
- Xuất báo cáo

</details>

<details>
<summary><strong>📦 Quản Lý Sản Phẩm & Kho</strong></summary>

- CRUD: Thuốc, Danh mục (đa cấp), Thương hiệu, Nhà cung cấp
- **Soft Delete** thuốc — dữ liệu không bị mất vĩnh viễn
- Tự động resize & chuyển ảnh sang **WebP** (giảm 30–50% dung lượng)
- Quản lý **Nhập hàng**: Tạo phiếu nhập, tự động cộng tồn kho qua MySQL Trigger
- **Báo cáo tồn kho**: Cảnh báo hàng sắp hết, lịch sử nhập/xuất

</details>

<details>
<summary><strong>🛍️ Quản Lý Giao Dịch & Marketing</strong></summary>

- Xem, lọc, cập nhật trạng thái đơn hàng
- Quản lý **Coupon**: CRUD, loại % hoặc tiền mặt, hạn dùng
- Duyệt / Ẩn đánh giá sản phẩm
- Gửi thông báo hệ thống đến toàn bộ user hoặc user cụ thể
- Xem và xử lý form liên hệ từ khách hàng

</details>

<details>
<summary><strong>🎨 Quản Lý Nội Dung & Giao Diện</strong></summary>

- Quản lý **Slider/Banner** trang chủ (ảnh, tiêu đề, link, thứ tự)
- Quản lý **Blog tin tức**, **Video** hướng dẫn, **FAQ**
- **Cài đặt website**: Logo, Hotline, Email, mạng xã hội
- Quản lý người dùng: Xem, Khóa/Mở tài khoản, Xóa
- Quản lý khách hàng, tài khoản

</details>

---

## 🛠️ Công Nghệ Sử Dụng

### Backend
| Công nghệ | Phiên bản | Mục đích |
|-----------|-----------|----------|
| **Laravel** | 12.x | PHP Framework chính |
| **PHP** | 8.2+ | Ngôn ngữ lập trình |
| **MySQL** | 8.0 | Cơ sở dữ liệu chính |
| **Laravel Reverb** | - | WebSocket server (Realtime) |
| **Google Gemini** | 2.5 Flash | AI Chatbot dược sĩ ảo |
| **Twilio SDK** | ^8.9 | Gửi OTP xác thực qua SMS |
| **Intervention Image** | ^3.11 | Upload & chuyển đổi WebP |
| **GuzzleHTTP** | ^7.10 | HTTP client gọi API |
| **Doctrine DBAL** | ^4.4 | Thao tác database nâng cao |

### Frontend
| Công nghệ | Mục đích |
|-----------|----------|
| **Vite** | Module bundler |
| **TailwindCSS 4.0** | Utility-first CSS framework |
| **Bootstrap 5.3** | UI component framework |
| **Laravel Echo** | Lắng nghe sự kiện WebSocket phía client |
| **SweetAlert2** | Thông báo xác nhận chuyên nghiệp |
| **Swiper.js** | Slider sản phẩm & banner |
| **FontAwesome 7.1** | Hệ thống icon |
| **Axios** | AJAX HTTP client |

### Infrastructure
| Công nghệ | Mục đích |
|-----------|----------|
| **Docker** | Container hóa ứng dụng |
| **Docker Compose** | Orchestrate toàn bộ services |

---

## 🏗️ Kiến Trúc Hệ Thống

```
┌─────────────────────────────────────────────────────────────┐
│                     DOLA PHARMACY SYSTEM                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   [Browser Client]                [Admin Dashboard]         │
│   Blade + Vite + AJAX   ◄──WS──►  Laravel Echo + Reverb     │
│        │                                   │                │
│        ▼                                   ▼                │
│   ┌─────────────────────────────────────────────┐           │
│   │              Laravel 12 (MVC)               │           │
│   │  Controllers → Services → Models → Views    │           │
│   └───────────────────┬─────────────────────────┘           │
│                       │                                     │
│          ┌────────────┼────────────┐                        │
│          ▼            ▼            ▼                        │
│       MySQL        Twilio      Google                       │
│      Database      SMS OTP    Gemini AI                     │
│      (Triggers)               (Chatbot)                     │
│                                                             │
│   ┌─────────────────────────────────────────────┐           │
│   │  Thanh toán: MoMo API | VNPay HMAC-SHA512   │           │
│   └─────────────────────────────────────────────┘           │
└─────────────────────────────────────────────────────────────┘
```

### MVC Architecture

| Layer | Vị trí | Mô tả |
|-------|--------|-------|
| **Model** | `app/Models/` | Eloquent ORM, Relationships, Soft Deletes |
| **View** | `resources/views/` | Blade Templates (`admin/`, `account/`, `layouts/`, `auth/`) |
| **Controller** | `app/Http/Controllers/` | Tách namespace `Admin\` (17) và Client (17) |
| **Service** | `app/Services/` | `ImageService` xử lý upload/resize/WebP |
| **Events** | `app/Events/` | `RealtimeNotification` phát qua WebSocket |
| **Middleware** | - | `admin.auth` bảo vệ `/quan-tri`, `auth` bảo vệ tài khoản |

### MySQL Triggers
- `trg_capnhap_tonkho` — Tự động cộng/trừ tồn kho sau mỗi phiếu nhập
- `trg_tinh_thanh_tien_hdct` — Tự động tính `thanh_tien = so_luong × don_gia`

---

## 🚀 Cài Đặt & Chạy

### Yêu cầu hệ thống
- PHP 8.2+, Composer
- Node.js 18+, npm
- MySQL 8.0+ hoặc Docker Desktop

---

### 🐳 Cách 1: Docker (Khuyên dùng)

```bash
# 1. Clone project
git clone https://github.com/PhatYeager8823/DolaPharmacy.git
cd DolaPharmacy

# 2. Cấu hình môi trường
cp .env.example .env
# Chỉnh sửa .env (DB, Twilio, Gemini API key...)

# 3. Khởi chạy Docker
docker compose up -d --build

# Ứng dụng chạy tại: http://localhost:8000
```

---

### 💻 Cách 2: Cài đặt thủ công

```bash
# 1. Clone project
git clone https://github.com/PhatYeager8823/DolaPharmacy.git
cd DolaPharmacy

# 2. Cài đặt PHP dependencies
composer install

# 3. Cài đặt Node dependencies & build assets
npm install && npm run build

# 4. Cấu hình .env
cp .env.example .env
php artisan key:generate

# 5. Cấu hình database trong .env, sau đó migrate
php artisan migrate --seed

# 6. Chạy server
php artisan serve
php artisan reverb:start   # WebSocket server (terminal riêng)
php artisan queue:work     # Queue worker (terminal riêng)
```

---

### ⚙️ Biến .env Quan Trọng

```env
# Database
DB_DATABASE=dola_pharmacy
DB_USERNAME=root
DB_PASSWORD=your_password

# Twilio (OTP SMS)
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_FROM=+1234567890

# Google Gemini AI (Chatbot)
GEMINI_API_KEY=your_gemini_api_key

# MoMo Payment
MOMO_PARTNER_CODE=your_partner_code
MOMO_ACCESS_KEY=your_access_key
MOMO_SECRET_KEY=your_secret_key

# VNPay Payment
VNPAY_TMN_CODE=your_tmn_code
VNPAY_HASH_SECRET=your_hash_secret

# Laravel Reverb (WebSocket)
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
```

---

## 📁 Cấu Trúc Thư Mục

```
nhathuoc-laravel/
├── app/
│   ├── Events/
│   │   └── RealtimeNotification.php    # WebSocket event
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # 17 Admin controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ThuocController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── WarehouseController.php
│   │   │   │   └── ...
│   │   │   ├── AuthController.php      # OTP Authentication
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php  # MoMo + VNPay
│   │   │   ├── ChatbotController.php   # Gemini AI
│   │   │   └── ...                    # 17 Client controllers
│   │   └── Middleware/
│   ├── Models/                         # Eloquent Models (20+ tables)
│   └── Services/
│       └── ImageService.php            # Upload + WebP converter
├── resources/
│   ├── views/
│   │   ├── admin/                      # Admin dashboard views
│   │   ├── account/                    # Customer account views
│   │   ├── layouts/                    # Shared templates
│   │   ├── auth/                       # Authentication views
│   │   ├── checkout/                   # Checkout flow
│   │   └── partials/                   # Reusable components
│   ├── js/
│   │   ├── app.js
│   │   └── echo.js                     # Laravel Echo (WebSocket)
│   └── sass/
├── routes/
│   ├── web.php                         # 50+ routes
│   └── channels.php                    # WebSocket channels
├── database/
│   ├── migrations/                     # 20+ migrations
│   └── seeders/
├── docker-compose.yml
├── Dockerfile
└── .env.example
```

---

## 🗄️ Cơ Sở Dữ Liệu

Hệ thống có **20+ bảng** được chuẩn hóa:

| Nhóm | Bảng chính |
|------|-----------|
| **Sản phẩm** | `thuocs`, `danh_mucs`, `brands`, `nha_cung_caps` |
| **Khách hàng** | `nguoi_dungs`, `dia_chis` |
| **Giao dịch** | `orders`, `order_items`, `gio_hangs`, `gio_hang_chi_tiets` |
| **Marketing** | `danh_gias`, `yeu_thiches`, `coupons`, `sliders`, `thong_baos` |
| **Kho** | `phieu_nhaps`, `chi_tiet_phieu_nhaps`, `ton_khos` |
| **Nội dung** | `bai_viets`, `videos`, `faqs`, `lien_hes`, `settings` |

---

## 🔑 Điểm Nhấn Công Nghệ

| # | Tính năng | Mô tả |
|---|-----------|-------|
| 1 | **WebP Auto-Convert** | Toàn bộ ảnh upload tự động resize + chuyển WebP, giảm 30–50% dung lượng |
| 2 | **OTP Multi-Action** | Xác thực OTP cho đăng nhập, đổi mật khẩu, đổi email — qua Twilio SMS |
| 3 | **AI Chatbot** | Gemini 2.5 Flash tư vấn thuốc dựa trên context catalog thực tế |
| 4 | **Realtime WebSocket** | Laravel Reverb + Echo — admin nhận thông báo đơn mới ~0ms |
| 5 | **MySQL Triggers** | Tự động cập nhật tồn kho và tính thành tiền không qua PHP |
| 6 | **Dual Payment** | MoMo API + VNPay HMAC-SHA512 |
| 7 | **Loyalty Program** | Hạng thành viên Bạc/Vàng/Kim Cương dựa trên tổng chi tiêu |
| 8 | **AJAX Throughout** | Giỏ hàng, tìm kiếm, quick view — không reload trang |
| 9 | **Soft Deletes** | Xóa thuốc không mất dữ liệu, có thể khôi phục |
| 10 | **Docker Ready** | Triển khai nhất quán mọi môi trường |

---

## 🔐 Bảo Mật

- ✅ Chống **SQL Injection, XSS, CSRF** (tích hợp Laravel mặc định)
- ✅ Mật khẩu hash **bcrypt** — không lưu plaintext
- ✅ **OTP SMS** xác thực mọi hành động nhạy cảm
- ✅ **Middleware phân quyền** Admin/User rõ ràng
- ✅ VNPay callback xác thực **HMAC-SHA512**
- ✅ Tài khoản bị khóa không thể đăng nhập

---

## 📝 License

Dự án phát triển nhằm mục đích học tập — Đồ án môn học.

---

<p align="center">
  <strong>Phát triển bởi PhatYeager8823</strong><br/>
  <a href="https://github.com/PhatYeager8823/DolaPharmacy">github.com/PhatYeager8823/DolaPharmacy</a>
</p>
