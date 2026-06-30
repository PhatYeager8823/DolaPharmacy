#!/bin/sh
set -e

echo "========================================="
echo " Dola Pharmacy - Container Startup"
echo "========================================="

# ── 1. Đảm bảo quyền ghi cho storage ─────────────────────
echo "[1/6] Fixing storage permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ── 2. Tạo storage link (public images) ───────────────────
echo "[2/6] Creating storage symlink..."
php artisan storage:link --force || true

# ── 3. Xóa cache cũ ───────────────────────────────────────
echo "[3/6] Clearing old caches..."
php artisan optimize:clear

# ── 4. Cache config & routes cho production performance ───
echo "[4/6] Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── 5. Chạy migrations ────────────────────────────────────
echo "[5/6] Running database migrations..."
php artisan migrate --force

# ── 6. Khởi động Apache ───────────────────────────────────
echo "[6/6] Starting Apache..."
exec apache2-foreground
