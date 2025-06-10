FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip curl git zip libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy project files
COPY . /var/www/html

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# =================================================================
# KONFIGURASI APACHE YANG DIPERBAIKI
# =================================================================

# 1. Set DocumentRoot ke /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 2. Aktifkan mod_rewrite
RUN a2enmod rewrite

# 3. Izinkan .htaccess untuk bekerja (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/html>/, /<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# =================================================================

# Set proper permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 80