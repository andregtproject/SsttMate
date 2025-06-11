FROM php:8.2-apache

# Install system dependencies including Node.js
RUN apt-get update && apt-get install -y \
    unzip curl git zip libzip-dev libpng-dev \
    iputils-ping dnsutils net-tools \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (better caching)
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader --no-dev --prefer-dist

# Copy package.json and package-lock.json (if exists)
COPY package*.json ./

# Clean any existing node_modules and install fresh
RUN rm -rf node_modules && npm cache clean --force
RUN npm install --production

# Copy project files (excluding node_modules via .dockerignore)
COPY . /var/www/html

# Generate optimized autoloader
RUN composer dump-autoload --optimize

# Build CSS and JS assets with error handling
RUN npm run build || echo "NPM build failed, continuing..."

# Apache configuration
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Set proper permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate Laravel application key
RUN cp .env.example .env || echo ".env.example not found"
RUN php artisan key:generate || echo "Key generation failed"

# Clear Laravel caches
RUN php artisan config:clear || echo "Config clear failed"
RUN php artisan cache:clear || echo "Cache clear failed"

# Expose port
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]