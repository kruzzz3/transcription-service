#!/bin/sh

# Install composer dependencies
php /var/www/composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Clear cache on startup
php /var/www/artisan cache:clear
php /var/www/artisan config:clear
php /var/www/artisan view:clear
php /var/www/artisan route:clear

# Link storage path
php /var/www/artisan storage:link

# Install npm dependencies
npm install --prefix /var/www

# Build assets
npm run production --prefix /var/www

# Show log messages
touch /var/www/storage/logs/laravel-$(date '+%Y-%m-%d').log
tail -f /var/www/storage/logs/laravel-$(date '+%Y-%m-%d').log &
touch /var/log/cron.log
tail -f /var/log/cron.log &

# Start php server
php-fpm
