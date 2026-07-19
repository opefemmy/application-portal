<?php

/**
 * Debug Script - Helps identify PHP errors
 *
 * Upload to cPanel and access: https://career.personel.ink/debug.php
 * Delete after use!
 */

echo "<pre style='background:#1a1a1a;color:#00ff00;padding:20px;font-family:monospace;'>";
echo "========================================\n";
echo "Laravel Environment Debug\n";
echo "========================================\n\n";

// Check PHP version
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check if .env exists
$envPath = __DIR__ . '/.env';
echo ".env file: " . (file_exists($envPath) ? "✓ EXISTS" : "✗ MISSING") . "\n";

// Check key Laravel files
echo "\nKey Files:\n";
$files = [
    'vendor/autoload.php' => 'Vendor Autoload',
    'bootstrap/app.php' => 'Bootstrap App',
    'config/app.php' => 'Config App',
    'config/database.php' => 'Config Database',
    'config/auth.php' => 'Config Auth',
    'storage/logs' => 'Storage Logs',
    'storage/framework/sessions' => 'Sessions Dir',
    'storage/framework/views' => 'Views Dir',
];

foreach ($files as $path => $name) {
    $exists = file_exists($path) ? "✓" : "✗";
    $perms = file_exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : '';
    echo "$exists $name: $perms\n";
}

// Check .env content (masked)
echo "\n.env Settings (masked):\n";
if (file_exists($envPath)) {
    $env = parse_ini_file($envPath);
    foreach ($env as $key => $value) {
        if (in_array($key, ['APP_KEY', 'DB_PASSWORD', 'MAIL_PASSWORD', 'MAIL_MAILER'])) {
            $value = '********';
        }
        echo "  $key = $value\n";
    }
}

// Check PHP extensions
echo "\nPHP Extensions:\n";
$exts = ['pdo', 'mbstring', 'openssl', 'curl', 'json'];
foreach ($exts as $ext) {
    echo "  " . (extension_loaded($ext) ? "✓" : "✗") . " $ext\n";
}

// Test Laravel bootstrap
echo "\n========================================\n";
echo "Testing Laravel Bootstrap\n";
echo "========================================\n\n";

try {
    require __DIR__.'/vendor/autoload.php';
    echo "✓ Vendor autoload loaded\n";

    $app = require __DIR__.'/bootstrap/app.php';
    echo "✓ Bootstrap app loaded\n";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✓ HTTP Kernel created\n";

    echo "\n✅ Laravel is working correctly!\n";

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n========================================\n";
echo "IMPORTANT: Delete this file after use!\n";
echo "========================================\n";
echo "</pre>";