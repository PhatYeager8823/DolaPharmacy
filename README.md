# Dola Pharmacy - Hệ thống Website Nhà Thuốc

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)

**Dola Pharmacy** là một ứng dụng web quản lý và kinh doanh dược phẩm được xây dựng trên nền tảng framework Laravel. Hệ thống cung cấp giải pháp toàn diện cho việc quản lý thuốc, đặt hàng trực tuyến và hỗ trợ thanh toán điện tử.

## 🚀 Tính năng chính

- **Quản lý sản phẩm**: Quản lý danh mục thuốc, thông tin chi tiết, hình ảnh và giá cả.
- **Giỏ hàng & Đặt hàng**: Quy trình đặt hàng trực tuyến thuận tiện cho người dùng.
- **Thanh toán trực tuyến**: Tích hợp cổng thanh toán VNPay (đang phát triển/thử nghiệm).
- **Thông báo**: Hệ thống gửi thông báo qua Email/SMS (Twilio) khi có đơn hàng mới hoặc cập nhật trạng thái.
- **Quản trị hệ thống (Admin)**: Dashboard quản lý đơn hàng, thống kê doanh thu, quản lý người dùng và nội dung website.
- **Đánh giá & Phản hồi**: Cho phép khách hàng đánh giá sản phẩm sau khi mua.

## 🛠 Công nghệ sử dụng

- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Blade Template, Vite, Tailwind CSS (hoặc CSS thuần)
- **Database**: MySQL
- **Thư viện chính**:
    - `intervention/image`: Xử lý hình ảnh.
    - `twilio/sdk`: Gửi thông báo SMS.
    - `guzzlehttp/guzzle`: Gọi các API bên thứ ba.

## 📦 Hướng dẫn cài đặt

Để chạy dự án này trên môi trường local, hãy thực hiện theo các bước sau:

1. **Clone project:**
   ```bash
   git clone [URL_CUA_BAN]
   cd nhathuoc-laravel
   ```

2. **Cài đặt dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Cấu hình môi trường:**
    - Sao chép file `.env.example` thành `.env`.
    - Cấu hình thông tin Database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    - Cấu hình thông tin Mail và các API key (Gemini, Twilio) nếu cần.

4. **Khởi tạo Database và Key:**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

5. **Chạy ứng dụng:**
   ```bash
   npm run dev
   # Và ở một terminal khác:
   php artisan serve
   ```

## 📝 Giấy phép
Dự án được phát triển nhằm mục đích học tập/đồ án.

---
*Phát triển bởi PhatYeager8823 (hoặc chủ sở hữu dự án)*
