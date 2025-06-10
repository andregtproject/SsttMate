#!/bin/bash
set -e

echo "=== Docker Entrypoint Start ==="

# Always ensure we're in the right directory
cd /var/www/html

# Check current composer status
echo "Current Composer status:"
composer diagnose

# Force clear composer cache and reinstall
echo "Clearing composer cache..."
composer clear-cache

# Remove and reinstall dependencies
echo "Removing vendor directory and reinstalling..."
rm -rf vendor/
composer install --optimize-autoloader --no-interaction

# Specifically require Firebase package
echo "Ensuring Firebase package is properly installed..."
composer require kreait/firebase-php:^7.18 --no-interaction

# Generate optimized autoloader
echo "Generating optimized autoloader..."
composer dump-autoload --optimize

# Debug autoloader
echo "Checking autoloader registration for Kreait namespace..."
php -r "
\$loader = require 'vendor/autoload.php';
\$prefixes = \$loader->getPrefixesPsr4();
if (isset(\$prefixes['Kreait\\\\'])) {
    echo 'Kreait namespace registered at: ' . implode(', ', \$prefixes['Kreait\\\\']) . PHP_EOL;
} else {
    echo 'Kreait namespace NOT registered!' . PHP_EOL;
}
"

# Check if Firebase classes are available
echo "Testing Firebase classes..."
php -r "
require_once 'vendor/autoload.php';
\$classes = [
    'Kreait\\FirebaseFactory',
    'Kreait\\Firebase\\Database',
    'Kreait\\Firebase\\ServiceAccount'
];
foreach (\$classes as \$class) {
    if (class_exists(\$class)) {
        echo '✓ ' . \$class . ' is available' . PHP_EOL;
    } else {
        echo '✗ ' . \$class . ' NOT found' . PHP_EOL;
    }
}
"

# Try to include the file directly
echo "Testing direct file inclusion..."
php -r "
if (file_exists('vendor/kreait/firebase-php/src/FirebaseFactory.php')) {
    require_once 'vendor/kreait/firebase-php/src/FirebaseFactory.php';
    echo '✓ FirebaseFactory.php file exists and can be included' . PHP_EOL;
} else {
    echo '✗ FirebaseFactory.php file not found' . PHP_EOL;
}
"

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

echo "=== Docker Entrypoint Complete ==="

# Execute the original command
exec "$@"
