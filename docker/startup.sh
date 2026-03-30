#!/bin/sh

# Exit on error
set -e

# Clear and cache configurations (only optimize:clear for dev)
php artisan optimize:clear

# Run migrations (forced)
 php artisan migrate --force

# Start Apache
apache2-foreground
