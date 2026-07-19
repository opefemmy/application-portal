<?php
/**
 * Debug Script - Upload to /public/ folder
 * Access: https://career.personel.ink/debug.php
 * DELETE AFTER USE!
 */
$bg = '#1a1a1a';
$green = '#00ff00';
$red = '#ff4444';
$yellow = '#ffdd00';
echo "<pre style='background:$bg;color:$green;padding:20px;font-family:monospace;overflow:auto;'>";
echo "========================================\n";
echo "LARAVEL DEBUG - career.personel.ink\n";
echo "========================================\n\n";

// PHP Info
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check files
$files = [
    '.env' => '../.env',
    'vendor/autoload.php' => '../vendor/autoload.php',
    'bootstrap/app.php' => '../bootstrap/app.php',
    'config/app.php' => '../config/app.php',
    'config/database.php' => '../config/database.php',
    'storage/logs/laravel.log' => '../storage/logs/laravel.log',
    'storage/framework' => '../storage/framework',
    'bootstrap/cache' => '../bootstrap/cache',
];

echo "FILE CHECKS:\n";
echo str_repeat('-', 50) . "\n";
foreach ($files as $name => $path) {
    $fullPath = __DIR__ . '/' . $path;
    if (file_exists($fullPath)) {
        echo "✓ $name\n";
    } else {
        echo "✗ $name - MISSING\n";
    }
}

// Check .env
echo "\nENV FILE:\n";
echo str_repeat('-', 50) . "\n";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "✓ .env exists\n";
    $env = parse_ini_file($envPath);
    $keys = ['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'DB_HOST', 'DB_DATABASE'];
    foreach ($keys as $key) {
        if (isset($env[$key])) {
            $val = $key === 'DB_PASSWORD' ? '******' : $env[$key];
            echo "  $key = $val\n";
        } else {
            echo "  $key = NOT SET\n";
        }
    }
} else {
    echo "✗ .env MISSING - THIS IS YOUR PROBLEM!\n";
    echo "  Upload your .env file to: /home/ekscotech/application-portal/\n";
}

// Try bootstrap
echo "\nLARAVEL BOOTSTRAP:\n";
echo str_repeat('-', 50) . "\n";
try {
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        throw new Exception("vendor/autoload.php missing - run 'composer install'");
    }
    require __DIR__ . '/../vendor/autoload.php';
    echo "✓ Autoload loaded\n";

    if (!file_exists(__DIR__ . '/../bootstrap/app.php')) {
        throw new Exception("bootstrap/app.php missing");
    }
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "✓ App bootstrapped\n";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✓ HTTP Kernel created\n";
    echo "\n✅ LARAVEL IS WORKING!\n";

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
}

echo "\n========================================\n";
echo "DELETE THIS FILE AFTER USE!\n";
echo "========================================\n";
echo "</pre>";