<?php
/**
 * Detailed Database Seeder with Full Error Output
 *
 * Upload to /public/ folder and access: https://career.personel.ink/debug-seeder.php
 *
 * DELETE AFTER USE!
 */

// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$bg = '#1a1a1a';
$green = '#00ff00';
$yellow = '#ffdd00';
$red = '#ff4444';
$cyan = '#00ffff';

echo "<pre style='background:$bg;color:$cyan;padding:20px;font-family:monospace;font-size:14px;line-height:1.6;'>";
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║          DETAILED DATABASE SEEDER v2                   ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

// Track all errors
$errors = [];
$success = [];

try {
    // Bootstrap Laravel
    echo "[1] Bootstrapping Laravel...\n";
    require __DIR__ . '/../vendor/autoload.php';

    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $success[] = "Laravel bootstrapped successfully";
    echo "✓ Laravel ready\n\n";

    // Check database connection
    echo "[2] Checking Database Connection...\n";
    try {
        $pdo = \DB::connection()->getPdo();
        $dbName = \DB::connection()->getDatabaseName();
        $success[] = "Database connected: $dbName";
        echo "✓ Connected to database: $dbName\n";
    } catch (Exception $e) {
        $errors[] = "Database connection failed: " . $e->getMessage();
        echo "✗ Database connection failed!\n";
        echo "  Error: " . $e->getMessage() . "\n\n";
        echo "=== FIX REQUIRED ===\n";
        echo "Check your .env file has correct DB settings:\n";
        echo "  DB_CONNECTION=mysql\n";
        echo "  DB_HOST=localhost\n";
        echo "  DB_DATABASE=your_database_name\n";
        echo "  DB_USERNAME=your_username\n";
        echo "  DB_PASSWORD=your_password\n";
        throw new Exception("Cannot continue without database connection");
    }

    echo "\n[3] Dropping and recreating tables...\n";
    try {
        // Use raw SQL to drop tables in correct order
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'activity_logs',
            'application_documents',
            'applications',
            'application_type_form_field',
            'application_types',
            'email_templates',
            'form_fields',
            'settings',
            'notifications',
            'interviews',
            'administrators',
            'roles',
        ];

        foreach ($tables as $table) {
            try {
                \DB::statement("DROP TABLE IF EXISTS $table");
                echo "  ✓ Dropped $table\n";
            } catch (Exception $e) {
                echo "  ⚠ $table: " . $e->getMessage() . "\n";
            }
        }

        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $success[] = "Tables dropped";
        echo "✓ Tables dropped\n";
    } catch (Exception $e) {
        $errors[] = "Drop tables: " . $e->getMessage();
        echo "⚠ Drop error: " . $e->getMessage() . "\n";
    }

    echo "\n[4] Running migrations...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        $success[] = "Migrations completed";
        echo "✓ Migrations completed\n";
    } catch (Exception $e) {
        $errors[] = "Migration: " . $e->getMessage();
        echo "⚠ Migration warning: " . $e->getMessage() . "\n";
    }

    echo "\n[5] Creating Roles...\n";
    try {
        \App\Models\Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super-admin',
            'description' => 'Full system access',
            'permissions' => ['*']
        ]);
        \App\Models\Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
            'description' => 'Manage applications and settings',
            'permissions' => ['view-applications', 'manage-applications']
        ]);
        $success[] = "Roles created";
        echo "✓ Created 2 roles\n";
    } catch (Exception $e) {
        $errors[] = "Roles: " . $e->getMessage();
        echo "✗ Roles error: " . $e->getMessage() . "\n";
    }

    echo "\n[6] Creating Admin User...\n";
    try {
        \App\Models\Administrator::create([
            'name' => 'Super Admin',
            'email' => 'admin@portal.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role_id' => 1,
        ]);
        $success[] = "Admin user created";
        echo "✓ Admin user: admin@portal.com / password123\n";
    } catch (Exception $e) {
        $errors[] = "Admin: " . $e->getMessage();
        echo "✗ Admin error: " . $e->getMessage() . "\n";
    }

    echo "\n[7] Creating Settings...\n";
    try {
        $settings = [
            ['key' => 'portal_name', 'value' => 'Online Application Portal'],
            ['key' => 'institution_name', 'value' => 'EKSCOTECH'],
            ['key' => 'contact_email', 'value' => 'contact@ekscotech.edu.ng'],
            ['key' => 'phone_number', 'value' => '+2341234567890'],
            ['key' => 'programmes', 'value' => json_encode([
                ['code' => 'NCE', 'name' => 'Nigeria Certificate in Education', 'is_active' => true, 'sort_order' => 1],
                ['code' => 'ND', 'name' => 'National Diploma', 'is_active' => true, 'sort_order' => 2],
                ['code' => 'HND', 'name' => 'Higher National Diploma', 'is_active' => true, 'sort_order' => 3],
                ['code' => 'PGDE', 'name' => 'Postgraduate Diploma in Education', 'is_active' => true, 'sort_order' => 4],
                ['code' => 'BACHELOR', 'name' => 'Bachelor Degree', 'is_active' => true, 'sort_order' => 5],
                ['code' => 'MASTERS', 'name' => "Master's Degree", 'is_active' => true, 'sort_order' => 6],
                ['code' => 'PHD', 'name' => 'Doctor of Philosophy', 'is_active' => true, 'sort_order' => 7],
            ])],
            ['key' => 'application_prefix', 'value' => 'APP'],
            ['key' => 'maintenance_mode', 'value' => '0'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::create($setting);
        }
        $success[] = "Settings created";
        echo "✓ Created " . count($settings) . " settings\n";
    } catch (Exception $e) {
        $errors[] = "Settings: " . $e->getMessage();
        echo "✗ Settings error: " . $e->getMessage() . "\n";
    }

    echo "\n[8] Creating Application Types...\n";
    try {
        $type1 = \App\Models\ApplicationType::create([
            'name' => 'Employment Application',
            'slug' => 'employment',
            'description' => 'Application for job positions',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        echo "✓ Created: Employment Application (ID: {$type1->id})\n";

        $type2 = \App\Models\ApplicationType::create([
            'name' => 'Academic Admission',
            'slug' => 'academic-admission',
            'description' => 'Application for academic programmes',
            'is_active' => true,
            'sort_order' => 2,
        ]);
        echo "✓ Created: Academic Admission (ID: {$type2->id})\n";

        $type3 = \App\Models\ApplicationType::create([
            'name' => 'Scholarship Application',
            'slug' => 'scholarship',
            'description' => 'Application for scholarships',
            'is_active' => true,
            'sort_order' => 3,
        ]);
        echo "✓ Created: Scholarship Application (ID: {$type3->id})\n";

        $type4 = \App\Models\ApplicationType::create([
            'name' => 'Training Application',
            'slug' => 'training',
            'description' => 'Application for training programmes',
            'is_active' => true,
            'sort_order' => 4,
        ]);
        echo "✓ Created: Training Application (ID: {$type4->id})\n";

        $success[] = "Application types created";
    } catch (Exception $e) {
        $errors[] = "Application Types: " . $e->getMessage();
        echo "✗ Application Types error: " . $e->getMessage() . "\n";
    }

    echo "\n[9] Creating Form Fields...\n";
    try {
        $fields = [
            ['section' => 'personal', 'field_name' => 'first_name', 'field_label' => 'First Name', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 1],
            ['section' => 'personal', 'field_name' => 'last_name', 'field_label' => 'Last Name', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 2],
            ['section' => 'personal', 'field_name' => 'email', 'field_label' => 'Email', 'field_type' => 'email', 'is_visible' => true, 'is_required' => true, 'sort_order' => 3],
            ['section' => 'personal', 'field_name' => 'phone_number', 'field_label' => 'Phone', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 4],
            ['section' => 'academic', 'field_name' => 'highest_qualification', 'field_label' => 'Qualification', 'field_type' => 'select', 'is_visible' => true, 'is_required' => true, 'sort_order' => 1],
            ['section' => 'academic', 'field_name' => 'institution_attended', 'field_label' => 'Institution', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 2],
        ];

        foreach ($fields as $field) {
            \App\Models\FormField::create($field);
        }
        $success[] = "Form fields created";
        echo "✓ Created " . count($fields) . " form fields\n";
    } catch (Exception $e) {
        $errors[] = "Form Fields: " . $e->getMessage();
        echo "✗ Form Fields error: " . $e->getMessage() . "\n";
    }

    // Final verification
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "FINAL DATABASE CHECK\n";
    echo str_repeat("=", 50) . "\n\n";

    $roles = \App\Models\Role::count();
    $admins = \App\Models\Administrator::count();
    $types = \App\Models\ApplicationType::count();
    $settings = \App\Models\Setting::count();

    echo "Roles: $roles\n";
    echo "Admins: $admins\n";
    echo "Application Types: $types\n";
    echo "Settings: $settings\n";

    // Show all application types
    $allTypes = \App\Models\ApplicationType::all();
    if ($allTypes->count() > 0) {
        echo "\n--- ALL APPLICATION TYPES ---\n";
        foreach ($allTypes as $t) {
            echo "ID: {$t->id} | {$t->name} | Active: " . ($t->is_active ? 'YES' : 'NO') . "\n";
        }
    }

    // Show programmes
    $progSetting = \App\Models\Setting::where('key', 'programmes')->first();
    if ($progSetting) {
        $progs = json_decode($progSetting->value, true);
        echo "\n--- ALL PROGRAMMES ---\n";
        foreach ($progs as $p) {
            echo "{$p['code']}: {$p['name']} (Active: " . (isset($p['is_active']) && $p['is_active'] ? 'YES' : 'NO') . ")\n";
        }
    }

    echo "\n" . str_repeat("=", 50) . "\n";

    if (count($errors) > 0) {
        echo "⚠️  ERRORS ENCOUNTERED:\n";
        foreach ($errors as $err) {
            echo "  - $err\n";
        }
    } else {
        echo "✅ ALL OPERATIONS COMPLETED SUCCESSFULLY!\n";
    }

    echo str_repeat("=", 50) . "\n\n";

    echo "📋 QUICK ACCESS LINKS:\n";
    echo "----------------------\n";
    echo "Login: https://career.personel.ink/admin/login\n";
    echo "  Email: admin@portal.com\n";
    echo "  Password: password123\n\n";
    echo "Application Types: https://career.personel.ink/admin/settings/application-types\n";
    echo "Programmes: https://career.personel.ink/admin/settings/programmes\n";
    echo "Apply Form: https://career.personel.ink/apply\n";

} catch (Exception $e) {
    echo "\n" . str_repeat("!", 50) . "\n";
    echo "FATAL ERROR!\n";
    echo str_repeat("!", 50) . "\n\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("⚠", 30) . "\n";
echo "DELETE THIS FILE AFTER USE!\n";
echo str_repeat("⚠", 30) . "\n";
echo "</pre>";