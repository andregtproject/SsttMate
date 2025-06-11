#!/bin/bash
set -e

echo "=== Docker Entrypoint Start ==="

# Always ensure we're in the right directory
cd /var/www/html

# Basic composer install
echo "Installing composer dependencies..."
composer install --optimize-autoloader --no-interaction || echo "Composer install failed, continuing..."

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Copy .env if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env || echo ".env.example not found"
fi

# Generate app key if needed
echo "Generating application key..."
php artisan key:generate --force || echo "Key generation failed"

# Clear caches
echo "Clearing caches..."
php artisan config:clear || echo "Config clear failed"
php artisan cache:clear || echo "Cache clear failed"

echo "=== Docker Entrypoint Complete ==="

# Execute the original command
exec "$@"
