<?php

// Display header
echo "<!DOCTYPE html>
<html>
<head>
    <title>Environment Check</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #333; }
        .section { margin-bottom: 30px; border: 1px solid #ddd; border-radius: 4px; padding: 15px; }
        .variable { margin-bottom: 10px; }
        .variable-name { font-weight: bold; display: inline-block; width: 250px; }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Alash Academy Environment Check</h1>";

// Check PHP version and extensions
echo "<div class='section'>
    <h2>PHP Environment</h2>
    <div class='variable'><span class='variable-name'>PHP Version:</span> " . phpversion() . "</div>";

$requiredExtensions = ['pdo', 'pdo_pgsql', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo', 'openssl'];
echo "<div class='variable'><span class='variable-name'>Required Extensions:</span></div>
<table>
    <tr>
        <th>Extension</th>
        <th>Status</th>
    </tr>";

foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    $status = $loaded ? "<span class='success'>Loaded</span>" : "<span class='error'>Not Loaded</span>";
    echo "<tr><td>{$ext}</td><td>{$status}</td></tr>";
}
echo "</table></div>";

// Directory permissions
echo "<div class='section'>
    <h2>Directory Permissions</h2>";

$directories = [
    'storage/framework' => is_dir('../storage/framework') && is_writable('../storage/framework'),
    'storage/framework/cache' => is_dir('../storage/framework/cache') && is_writable('../storage/framework/cache'),
    'storage/framework/sessions' => is_dir('../storage/framework/sessions') && is_writable('../storage/framework/sessions'),
    'storage/framework/views' => is_dir('../storage/framework/views') && is_writable('../storage/framework/views'),
    'storage/logs' => is_dir('../storage/logs') && is_writable('../storage/logs'),
    'bootstrap/cache' => is_dir('../bootstrap/cache') && is_writable('../bootstrap/cache')
];

echo "<table>
    <tr>
        <th>Directory</th>
        <th>Status</th>
    </tr>";

foreach ($directories as $dir => $writable) {
    $status = $writable ? "<span class='success'>Writable</span>" : "<span class='error'>Not Writable</span>";
    echo "<tr><td>{$dir}</td><td>{$status}</td></tr>";
}
echo "</table></div>";

// Environment variables
echo "<div class='section'>
    <h2>Environment Variables</h2>";

$envVars = [
    'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'
];

echo "<table>
    <tr>
        <th>Variable</th>
        <th>Value</th>
    </tr>";

foreach ($envVars as $var) {
    $value = getenv($var);
    // For security, hide DB_PASSWORD
    if ($var === 'DB_PASSWORD' && !empty($value)) {
        $value = '********';
    }
    echo "<tr><td>{$var}</td><td>" . (empty($value) ? "<span class='warning'>Not Set</span>" : htmlspecialchars($value)) . "</td></tr>";
}
echo "</table></div>";

// Check if .env file exists
echo "<div class='section'>
    <h2>Configuration Files</h2>";

$files = [
    '.env file' => file_exists('../.env'),
    'config/app.php' => file_exists('../config/app.php'),
    'config/database.php' => file_exists('../config/database.php'),
    'public/.htaccess' => file_exists('.htaccess')
];

echo "<table>
    <tr>
        <th>File</th>
        <th>Status</th>
    </tr>";

foreach ($files as $file => $exists) {
    $status = $exists ? "<span class='success'>Exists</span>" : "<span class='error'>Missing</span>";
    echo "<tr><td>{$file}</td><td>{$status}</td></tr>";
}
echo "</table></div>";

// Server environment
echo "<div class='section'>
    <h2>Server Environment</h2>
    <div class='variable'><span class='variable-name'>Server Software:</span> " . $_SERVER['SERVER_SOFTWARE'] . "</div>
    <div class='variable'><span class='variable-name'>Document Root:</span> " . $_SERVER['DOCUMENT_ROOT'] . "</div>
    <div class='variable'><span class='variable-name'>Server Name:</span> " . $_SERVER['SERVER_NAME'] . "</div>
    <div class='variable'><span class='variable-name'>Request URI:</span> " . $_SERVER['REQUEST_URI'] . "</div>
    <div class='variable'><span class='variable-name'>Script Name:</span> " . $_SERVER['SCRIPT_NAME'] . "</div>
</div>";

echo "</body></html>";