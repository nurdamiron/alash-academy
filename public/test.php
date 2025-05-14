<?php

// Display PHP information
phpinfo();

// Check database connection
try {
    $dbType = getenv('DB_CONNECTION');
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');
    $dbName = getenv('DB_DATABASE');
    $dbUser = getenv('DB_USERNAME');
    $dbPass = getenv('DB_PASSWORD');
    
    echo "<h2>Database Connection Test</h2>";
    echo "Connection type: $dbType<br>";
    echo "Host: $dbHost<br>";
    echo "Port: $dbPort<br>";
    echo "Database: $dbName<br>";
    echo "Username: $dbUser<br>";
    
    if ($dbType == 'pgsql') {
        $dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;user=$dbUser;password=$dbPass";
    } else {
        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
    }
    
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p style='color:green;'>Database connection successful!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>Database connection failed: " . $e->getMessage() . "</p>";
}