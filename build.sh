#!/bin/bash

# Exit on error
set -e

# Copy environment file
cp .env.render .env

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
php artisan key:generate --force

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize the application
php artisan optimize

# Set permissions
chmod -R 777 storage bootstrap/cache public