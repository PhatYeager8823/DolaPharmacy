# ============================================================
# Stage 1: Build frontend assets with Node.js
# ============================================================
FROM node:20-alpine AS assets-builder
WORKDIR /app

# Copy package files first for layer caching
COPY package*.json ./
RUN npm ci --prefer-offline

# Copy source and build
COPY . .
RUN npm run build

# ============================================================
# Stage 2: PHP Application (Laravel + Apache)
# ============================================================
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install GD with JPEG + FreeType support
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache

# Copy OPcache config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy custom PHP config for upload limits
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first for layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader --prefer-dist


# Copy the entire application source
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=assets-builder /app/public/build ./public/build

# Generate optimized Composer autoloader
RUN composer dump-autoload --optimize


# Configure Apache: DocumentRoot → /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

# Fix permissions for Laravel writable directories
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# Copy and prepare startup script
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN sed -i 's/\r$//' /usr/local/bin/startup.sh \
    && chmod +x /usr/local/bin/startup.sh

# Expose port 80
EXPOSE 80

# Default environment overrides (can be overridden in docker-compose / Render)
ENV APP_ENV=production
ENV APP_DEBUG=false

ENTRYPOINT ["/usr/local/bin/startup.sh"]
