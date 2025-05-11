#Backend/Dockerfile
FROM php:8.2-fpm

# Install system deps
RUN apt-get update && apt-get install -y \
    git curl libpq-dev unzip libzip-dev zip \
    && docker-php-ext-install pdo_pgsql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy files
COPY . .

# Install Laravel deps
RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000
