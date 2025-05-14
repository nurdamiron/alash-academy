#!/bin/bash

# Exit on error
set -e

# Copy environment file
cp .env.render .env

# Install PHP dependencies
composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize the application
php artisan optimize

# Set permissions
chmod -R 777 storage bootstrap/cache