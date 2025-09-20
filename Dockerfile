FROM php:8.1-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk update && apk add --no-cache \
    # Runtime dependencies
    git \
    zip \
    unzip \
    freetype \
    libpng \
    libjpeg-turbo \
    libzip \
    # Build dependencies (will be removed)
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    pcre-dev \
    libzip-dev \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    # Configure and install extensions, then clean up
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql zip pcntl gd \
    && apk del .build-deps

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# กำหนด Working Directory
WORKDIR /var/www/html

# Copy Source Code
COPY . .

# ติดตั้ง Dependencies (Laravel)
RUN composer install --optimize-autoloader --no-dev

# กำหนด Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose Port
EXPOSE 9000

# Command ที่ใช้ Run Application
CMD ["php-fpm", "-F"]
