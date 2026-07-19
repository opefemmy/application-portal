<?php
/**
 * Complete Database Seeder for cPanel
 *
 * Upload to /public/ folder and access: https://career.personel.ink/run-seeder.php
 *
 * This will:
 * - Run all migrations
 * - Seed all data (roles, admin, settings, programmes, application types)
 *
 * DELETE AFTER USE!
 */

$bg = '#1a1a1a';
$green = '#00ff00';
$yellow = '#ffdd00';
$red = '#ff4444';

echo "<pre style='background:$bg;color:$green;padding:20px;font-family:monospace;'>";
echo "========================================\n";
echo "COMPLETE DATABASE SEEDER\n";
echo "========================================\n\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✓ Laravel bootstrapped\n\n";

    // Run migrations
    echo "Running migrations...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        echo "✓ Migrations completed\n";
    } catch (Exception $e) {
        echo "⚠ Migration: " . $e->getMessage() . "\n";
    }

    // Run Database Seeder
    echo "\nRunning DatabaseSeeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        echo "✓ Database seeded\n";
    } catch (Exception $e) {
        echo "⚠ Seeder: " . $e->getMessage() . "\n";
    }

    // Run Application Type Seeder
    echo "\nRunning ApplicationTypeSeeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'ApplicationTypeSeeder', '--force' => true]);
        echo "✓ Application types seeded\n";
    } catch (Exception $e) {
        echo "⚠ ApplicationTypeSeeder: " . $e->getMessage() . "\n";
    }

    // Run FormField Seeder
    echo "\nRunning FormFieldSeeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'FormFieldSeeder', '--force' => true]);
        echo "✓ Form fields seeded\n";
    } catch (Exception $e) {
        echo "⚠ FormFieldSeeder: " . $e->getMessage() . "\n";
    }

    // Check what's in the database now
    echo "\n========================================\n";
    echo "CHECKING DATABASE CONTENTS\n";
    echo "========================================\n\n";

    $adminCount = \App\Models\Administrator::count();
    echo "Administrators: $adminCount\n";

    $roleCount = \App\Models\Role::count();
    echo "Roles: $roleCount\n";

    $typeCount = \App\Models\ApplicationType::count();
    echo "Application Types: $typeCount\n";

    $settingCount = \App\Models\Setting::count();
    echo "Settings: $settingCount\n";

    $programmes = json_decode(\App\Models\Setting::get('programmes', '[]'), true);
    echo "Programmes: " . count($programmes) . "\n";

    // Show application types
    $types = \App\Models\ApplicationType::all();
    if ($types->count() > 0) {
        echo "\nApplication Types:\n";
        foreach ($types as $type) {
            echo "  - {$type->name} (ID: {$type->id}, Active: " . ($type->is_active ? 'Yes' : 'No') . ")\n";
        }
    }

    // Show programmes
    if (count($programmes) > 0) {
        echo "\nProgrammes:\n";
        foreach ($programmes as $prog) {
            echo "  - {$prog['code']}: {$prog['name']}\n";
        }
    }

    echo "\n========================================\n";
    echo "✅ DATABASE SETUP COMPLETE!\n";
    echo "========================================\n";
    echo "\nAdmin Login:\n";
    echo "  URL: https://career.personel.ink/admin/login\n";
    echo "  Email: admin@portal.com\n";
    echo "  Password: password123\n";

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";