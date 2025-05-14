#!/bin/bash

# Exit on error
set -e

echo "===== Starting build process ====="

# Copy environment file
echo "Configuring environment..."
cp .env.render .env

# Use database config for Render
cp config/database.example.php config/database.php

# Debug output for environment
echo "PHP Version:"
php -v
echo "Current directory:"
pwd
echo "Environment file:"
cat .env | grep -v PASSWORD

# Create required directories
echo "Creating required directories..."
mkdir -p bootstrap/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

# Install PHP dependencies
echo "Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Generate application key if not set
echo "Generating application key..."
php artisan key:generate --force

# Clear all caches
echo "Clearing caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Create storage link
echo "Creating storage link..."
php artisan storage:link || true

# Verify PHP extensions
echo "Checking PHP extensions..."
php -m

# Set permissions
echo "Setting permissions..."
chmod -R 777 storage bootstrap/cache public

# Create a test PHP file
echo "Creating test file..."
echo '<?php echo "PHP is working on Render.com!"; ?>' > public/render-test.php

echo "===== Build completed successfully! ====="