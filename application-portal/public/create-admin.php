<?php
/**
 * Create Admin User Script
 *
 * Upload to /public/ folder and access: https://career.personel.ink/create-admin.php
 *
 * Default credentials after running:
 * Email: admin@portal.com
 * Password: admin123
 *
 * DELETE AFTER USE!
 */

$bg = '#1a1a1a';
$green = '#00ff00';
$red = '#ff4444';
$yellow = '#ffdd00';

echo "<pre style='background:$bg;color:$green;padding:20px;font-family:monospace;'>";
echo "========================================\n";
echo "CREATE ADMIN USER\n";
echo "========================================\n\n";

try {
    // Bootstrap Laravel
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    // Check if admin already exists
    $existingAdmin = App\Models\Administrator::where('email', 'admin@portal.com')->first();

    if ($existingAdmin) {
        // Update password
        $existingAdmin->update([
            'password' => Hash::make('admin123'),
            'name' => 'Super Admin',
            'role_id' => 1,
        ]);
        echo "✓ Admin user updated!\n";
        echo "  Email: admin@portal.com\n";
        echo "  Password: admin123\n";
    } else {
        // Create new admin
        App\Models\Administrator::create([
            'name' => 'Super Admin',
            'email' => 'admin@portal.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
        ]);
        echo "✓ Admin user created!\n";
        echo "  Email: admin@portal.com\n";
        echo "  Password: admin123\n";
    }

    echo "\n========================================\n";
    echo "✅ ADMIN USER READY!\n";
    echo "========================================\n";
    echo "\nLogin at: https://career.personel.ink/admin/login\n";
    echo "Email: admin@portal.com\n";
    echo "Password: admin123\n";

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
}

echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";