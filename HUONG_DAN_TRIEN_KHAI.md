# Hướng Dẫn Triển Khai Dự Án Nhà Thuốc (Docker)

Tài liệu này hướng dẫn chi tiết cách chuyển dự án từ máy này sang máy khác và chạy hoàn chỉnh bằng Docker.

## 1. Yêu cầu hệ thống (Máy người nhận)
Máy tính cần cài đặt **Docker Desktop**:
- **Tải về**: [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
- **Lưu ý**: Sau khi cài xong, hãy khởi động Docker Desktop và đảm bảo nó đang chạy (biểu tượng cá voi màu xanh lá cây ở thanh taskbar).

## 2. Quy trình chuyển dự án (Chỉ 5 bước)

### Bước 1: Sao chép mã nguồn
Copy toàn bộ thư mục dự án `nhathuoc-laravel` sang máy mới (qua USB, Google Drive, hoặc Git).

### Bước 2: Thiết lập File cấu hình (.env)
Tại thư mục gốc của dự án trên máy mới:
1. Tìm file `.env.example`.
2. Copy và đổi tên thành `.env`.
3. Mở file `.env` và kiểm tra các thông số Database (đã được cấu hình khớp với Docker):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=nhathuoc
   DB_USERNAME=root
   DB_PASSWORD=rootpassword
   ```

### Bước 3: Khởi chạy Docker
Mở Terminal (PowerShell hoặc CMD) tại thư mục dự án và chạy lệnh:
```bash
docker-compose up -d --build
```
*Lệnh này sẽ tự động tải các Image cần thiết, cài đặt PHP, MySQL, Apache và build lại giao diện.*

### Bước 4: Cài đặt Laravel (Chỉ làm lần đầu)
Chạy các lệnh sau để khởi tạo ứng dụng bên trong Container:
```bash
# 1. Cài đặt các thư viện PHP (nếu chưa có vendor)
docker-compose exec app composer install

# 2. Tạo khóa ứng dụng (App Key)
docker-compose exec app php artisan key:generate

# 3. Tạo bảng dữ liệu và dữ liệu mẫu
docker-compose exec app php artisan migrate --seed

# 4. Phân quyền thư mục storage
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Bước 5: Truy cập Website
Sau khi hoàn tất các bước trên, bạn có thể truy cập qua các địa chỉ:
- **Trang chủ Website**: [http://localhost:8000](http://localhost:8000)
- **Quản lý Database (phpMyAdmin)**: [http://localhost:8080](http://localhost:8080)

---

## 3. Các lệnh thường dùng

- **Dừng dự án**: `docker-compose down`
- **Khởi động lại**: `docker-compose start`
- **Xem Log lỗi**: `docker-compose logs -f app`
- **Vào terminal bên trong Container**: `docker-compose exec app bash`

## 4. Cách kết nối Database từ ngoài Docker (Nếu dùng Navicat/SQLYog)
- **Host**: `localhost`
- **Port**: `33066` (Cổng ngoài đã map)
- **User**: `root`
- **Password**: `rootpassword` (hoặc password bạn đã đặt)
