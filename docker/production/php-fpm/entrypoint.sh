#!/bin/sh
set -e

# Create required directories
mkdir -p /var/www/storage/framework/{cache,sessions,views}
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/app/public
mkdir -p /var/www/storage/fonts
chown -R www-data:www-data /var/www/storage

# Initialize storage directory if empty
if [ ! "$(ls -A /var/www/storage)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
  chown -R www-data:www-data /var/www/storage
fi

# Remove storage-init directory (may fail if owned by root, ignore)
rm -rf /var/www/storage-init 2>/dev/null || true

# Run Laravel migrations
php artisan migrate --force

# Clear and cache configurations
php artisan config:cache
php artisan route:cache

# Run the default command
php-fpm