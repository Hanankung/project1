FROM php:8.2-fpm-alpine

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
    && docker-php-ext-install -j$(nproc) pdo_mysql zip pcntl gd bcmath exif \
    && apk del .build-deps

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# กำหนด Working Directory
WORKDIR /var/www/html

# Copy composer files and install dependencies to leverage Docker cache
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy the rest of the application's source code
COPY . .

# Prepare the environment for the build process.
# This .env file is used ONLY during the build.
RUN cp .env.example .env \
    && php artisan key:generate --ansi \
    && php artisan config:clear \
    && touch database/database.sqlite \
    && composer dump-autoload --optimize --no-dev

# กำหนด Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose Port
EXPOSE 9000

# Command ที่ใช้ Run Application
CMD ["php-fpm", "-F"]
