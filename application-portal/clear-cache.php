<?php

/**
 * Route Clear Script
 *
 * Upload this file to your cPanel server and access it via browser
 * e.g., https://yourdomain.com/clear-cache.php
 *
 * Delete this file after use for security!
 */

echo "<pre>";
echo "========================================\n";
echo "Laravel Cache Clear Script\n";
echo "========================================\n\n";

// Bootstrap Laravel
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Clear caches
echo "Clearing routes cache...\n";
Illuminate\Support\Facades\Artisan::call('route:clear');
echo "✓ Routes cleared\n";

echo "Clearing configuration cache...\n";
Illuminate\Support\Facades\Artisan::call('config:clear');
echo "✓ Configuration cleared\n";

echo "Clearing view cache...\n";
Illuminate\Support\Facades\Artisan::call('view:clear');
echo "✓ Views cleared\n";

echo "Clearing application cache...\n";
Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "✓ Application cache cleared\n";

echo "Clearing compiled classes...\n";
Illuminate\Support\Facades\Artisan::call('clear-compiled');
echo "✓ Compiled classes cleared\n";

echo "\n========================================\n";
echo "All caches cleared successfully!\n";
echo "========================================\n";
echo "\nIMPORTANT: Delete this file after use!\n";
echo "</pre>";