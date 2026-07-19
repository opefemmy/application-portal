<?php
/**
 * Database Connection Debug Script
 *
 * Upload to /public/ folder and access: https://career.personel.ink/check-db.php
 *
 * Checks:
 * - Database connection
 * - Tables exist
 * - Application types exist
 * - Settings exist
 *
 * DELETE AFTER USE!
 */

$bg = '#1a1a1a';
$green = '#00ff00';
$yellow = '#ffdd00';
$red = '#ff4444';

echo "<pre style='background:$bg;color:$green;padding:20px;font-family:monospace;'>";
echo "========================================\n";
echo "DATABASE CONNECTION CHECK\n";
echo "========================================\n\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✓ Laravel bootstrapped\n\n";

    // Check database connection
    echo "1. Checking database connection...\n";
    try {
        \DB::connection()->getPdo();
        echo "✓ Database connected\n";
        echo "  Database: " . \DB::connection()->getDatabaseName() . "\n";
    } catch (Exception $e) {
        echo "✗ Database connection failed: " . $e->getMessage() . "\n";
        echo "\n⚠️  Check your .env file database settings!\n";
        exit(1);
    }

    // Check tables
    echo "\n2. Checking tables...\n";
    $tables = ['roles', 'administrators', 'applications', 'application_types', 'settings', 'form_fields'];
    foreach ($tables as $table) {
        try {
            $count = \DB::table($table)->count();
            echo "  ✓ $table: $count rows\n";
        } catch (Exception $e) {
            echo "  ✗ $table: NOT FOUND\n";
        }
    }

    // Check Application Types
    echo "\n3. Checking Application Types...\n";
    try {
        $types = \App\Models\ApplicationType::all();
        if ($types->count() > 0) {
            foreach ($types as $type) {
                echo "  ✓ ID {$type->id}: {$type->name} (" . ($type->is_active ? 'Active' : 'Inactive') . ")\n";
            }
        } else {
            echo "  ⚠ No application types found - RUN SEEDER!\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }

    // Check Settings
    echo "\n4. Checking Settings...\n";
    try {
        $portalName = \App\Models\Setting::get('portal_name');
        echo "  Portal Name: " . ($portalName ?: 'NOT SET') . "\n";

        $programmes = \App\Models\Setting::get('programmes');
        $progCount = $programmes ? count(json_decode($programmes, true)) : 0;
        echo "  Programmes: $progCount\n";
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }

    // Check Admin User
    echo "\n5. Checking Admin User...\n";
    try {
        $admin = \App\Models\Administrator::first();
        if ($admin) {
            echo "  ✓ Admin exists: {$admin->email}\n";
        } else {
            echo "  ⚠ No admin user found - RUN SEEDER!\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }

    echo "\n========================================\n";
    echo "✅ DATABASE CHECK COMPLETE\n";
    echo "========================================\n";
    echo "\nIf you see errors, run the seeder:\n";
    echo "  https://career.personel.ink/run-seeder.php\n";

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";