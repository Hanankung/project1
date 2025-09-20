FROM php:8.1-fpm-alpine

# ติดตั้ง Dependencies ที่จำเป็น
RUN apk update && apk add --no-cache \
    git \
    zip \
    unzip \
    libzip-dev \
    pcre-dev

# ติดตั้ง PHP Extensions ที่จำเป็น
RUN docker-php-ext-install pdo_mysql zip pcntl gd

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# กำหนด Working Directory
WORKDIR /var/www/html

# Copy Source Code
COPY . .

# ติดตั้ง Dependencies (Laravel)
RUN composer install --optimize-autoloader --no-dev

# สร้าง Cache Directory
RUN mkdir -p bootstrap/cache

# กำหนด Permissions
RUN chown -R www-data:www-data \
    /var/www/html \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Expose Port
EXPOSE 9000

# Command ที่ใช้ Run Application
CMD ["php-fpm", "-F"]
