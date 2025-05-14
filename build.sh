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
echo "Creating symbolic links..."
mkdir -p public/storage || true
php artisan storage:link || true

# Test database connection and migrate only if connection is successful
echo "Testing database connection..."
if php -r "try { new PDO(getenv('DB_CONNECTION').':host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); echo 'Database connection successful!\n'; exit(0); } catch(PDOException \$e) { echo 'Database connection failed: '.\$e->getMessage().'\n'; exit(1); }"; then
    echo "Running database migrations..."
    php artisan migrate --force || true
else
    echo "Skipping migrations due to database connection failure"
fi

# Verify PHP extensions
echo "Checking PHP extensions..."
php -m

# Set permissions
echo "Setting permissions..."
chmod -R 777 storage bootstrap/cache public

# Create test PHP files
echo "Creating test files..."
echo '<?php echo "PHP is working on Render.com!"; ?>' > public/render-test.php
echo '<?php echo json_encode(["status" => "ok", "message" => "API is working"]); ?>' > public/api-test.php

# Create a front controller backup for debugging
echo "Creating a backup of index.php..."
cp public/index.php public/index.original.php

# Create health check file
echo "Creating health check file..."
echo '<?php echo "Health check OK"; ?>' > public/health.php

echo "===== Build completed successfully! ====="