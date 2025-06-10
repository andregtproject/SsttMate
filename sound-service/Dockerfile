FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer.json first (composer.lock might not exist yet)
COPY composer.json ./

# Install Composer dependencies (this will create composer.lock)
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy all project files
COPY . .

# Complete the Composer installation and generate optimized autoloader
RUN composer dump-autoload --optimize

# =================================================================
# KONFIGURASI APACHE UNTUK LARAVEL
# =================================================================
# 1. Set DocumentRoot ke /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 2. Aktifkan mod_rewrite
RUN a2enmod rewrite

# 3. Izinkan .htaccess untuk bekerja (AllowOverride All)
RUN sed -i '/<Directory \/var\/www\/html>/, /<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# =================================================================

# Create required directories and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependencies with dev packages untuk testing
RUN composer install --optimize-autoloader

# Expose port
EXPOSE 80

# Custom entrypoint to handle volume mounts
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]