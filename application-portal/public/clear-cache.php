<?php
/**
 * Cache Clear Script - Upload to /public/ folder
 * Access: https://career.personel.ink/clear-cache.php
 * DELETE AFTER USE!
 */
$bg = '#1a1a1a';
$green = '#00ff00';
echo "<pre style='background:$bg;color:$green;padding:20px;font-family:monospace;'>";
echo "========================================\n";
echo "CACHE CLEAR - career.personel.ink\n";
echo "========================================\n\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $commands = [
        'route:clear' => 'Routes',
        'config:clear' => 'Config',
        'view:clear' => 'Views',
        'cache:clear' => 'Cache',
        'clear-compiled' => 'Compiled',
    ];

    foreach ($commands as $cmd => $name) {
        try {
            Illuminate\Support\Facades\Artisan::call($cmd);
            echo "✓ $name cleared\n";
        } catch (Exception $e) {
            echo "✗ $name: " . $e->getMessage() . "\n";
        }
    }

    echo "\n========================================\n";
    echo "✅ ALL CACHES CLEARED!\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Could not bootstrap Laravel\n";
    echo "Check that .env exists and vendor is installed\n";
}

echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";