#!/bin/bash

# Exit on error
set -e

# Copy environment file
cp .env.render .env

# Use database config for Render
cp config/database.example.php config/database.php

# Create cache directory
mkdir -p bootstrap/cache

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
php artisan key:generate --force

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Create storage directories
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# Create storage link
php artisan storage:link || true

# Run database migrations
php artisan migrate:fresh --force || true

# Set permissions
chmod -R 777 storage bootstrap/cache public

echo "Build completed successfully!"