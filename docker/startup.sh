#!/bin/sh

# Exit on error
set -e

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (forced)
# php artisan migrate --force

# Start Apache
apache2-foreground
