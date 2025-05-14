<?php

/**
 * Laravel Debug Wrapper
 * This file helps to debug Laravel bootstrap issues
 */

// Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Show basic information
echo "<h1>Laravel Debug Information</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check critical directories
echo "<h2>Directory Checks</h2>";
$dirs = [
    'storage' => '../storage',
    'storage/framework' => '../storage/framework',
    'storage/framework/views' => '../storage/framework/views',
    'storage/framework/cache' => '../storage/framework/cache',
    'storage/framework/sessions' => '../storage/framework/sessions',
    'bootstrap/cache' => '../bootstrap/cache',
    'public' => './'
];

echo "<ul>";
foreach ($dirs as $name => $path) {
    $exists = is_dir($path);
    $writable = is_writable($path);
    $status = $exists ? ($writable ? "✅ Exists and writable" : "⚠️ Exists but not writable") : "❌ Missing";
    echo "<li>{$name}: {$status}</li>";
}
echo "</ul>";

// Check critical files
echo "<h2>File Checks</h2>";
$files = [
    '.env' => '../.env',
    'index.php' => './index.php',
    '.htaccess' => './.htaccess',
    'app.php' => '../config/app.php',
    'database.php' => '../config/database.php'
];

echo "<ul>";
foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $readable = is_readable($path);
    $status = $exists ? ($readable ? "✅ Exists and readable" : "⚠️ Exists but not readable") : "❌ Missing";
    echo "<li>{$name}: {$status}</li>";
}
echo "</ul>";

// Show environmental variables
echo "<h2>Environment Variables</h2>";
$envVars = [
    'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME',
    'FILESYSTEM_DISK', 'SESSION_DRIVER', 'CACHE_DRIVER'
];

echo "<ul>";
foreach ($envVars as $var) {
    $value = getenv($var);
    // Hide sensitive information
    if ($var === 'DB_PASSWORD' && !empty($value)) {
        $value = '********';
    }
    $status = empty($value) ? "⚠️ Not set" : "✅ Set: {$value}";
    echo "<li>{$var}: {$status}</li>";
}
echo "</ul>";

// Try to include the Laravel application with error catching
echo "<h2>Attempting to Bootstrap Laravel</h2>";
echo "<pre>";
try {
    // Include autoloader
    if (file_exists('../vendor/autoload.php')) {
        require '../vendor/autoload.php';
        echo "✅ Autoloader included\n";
    } else {
        echo "❌ Autoloader not found\n";
    }
    
    // Try to bootstrap Laravel
    if (file_exists('../bootstrap/app.php')) {
        $app = require_once '../bootstrap/app.php';
        echo "✅ Bootstrap/app.php included\n";
        
        if ($app instanceof \Illuminate\Foundation\Application) {
            echo "✅ Application instance created\n";
            
            $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
            echo "✅ Kernel created\n";
            
            echo "Laravel bootstrapped successfully!\n";
        } else {
            echo "❌ Failed to create Application instance\n";
        }
    } else {
        echo "❌ Bootstrap/app.php not found\n";
    }
} catch (Throwable $e) {
    echo "❌ Error bootstrapping Laravel: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
echo "</pre>";

// Show server information
echo "<h2>Server Information</h2>";
echo "<pre>";
print_r($_SERVER);
echo "</pre>";

// End of file