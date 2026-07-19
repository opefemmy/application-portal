<?php
/**
 * Database Seeder v4 - Direct SQL Tables
 *
 * Upload to /public/ folder and access: https://career.personel.ink/debug-seeder.php
 *
 * Uses direct SQL to create tables (bypasses Laravel migrations)
 *
 * DELETE AFTER USE!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$bg = '#1a1a1a';
$cyan = '#00ffff';

echo "<pre style='background:$bg;color:$cyan;padding:20px;font-family:monospace;font-size:14px;'>";
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║          DATABASE SEEDER v4 - DIRECT SQL               ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "[1] Laravel ready\n\n";

    // Get database name
    $dbName = \DB::connection()->getDatabaseName();
    echo "[2] Database: $dbName\n\n";

    // Drop all tables
    echo "[3] Dropping tables...\n";
    $tables = \DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        \DB::statement("DROP TABLE IF EXISTS `$tableName`");
    }
    echo "✓ All tables dropped\n\n";

    // Create tables using DIRECT SQL
    echo "[4] Creating tables with DIRECT SQL...\n\n";

    // Roles table
    \DB::statement("CREATE TABLE `roles` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `description` text NULL,
        `permissions` json NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ roles table\n";

    // Administrators table
    \DB::statement("CREATE TABLE `administrators` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `role_id` bigint UNSIGNED NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        `deleted_at` timestamp NULL,
        FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ administrators table\n";

    // Applications table
    \DB::statement("CREATE TABLE `applications` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_number` varchar(50) NOT NULL UNIQUE,
        `application_type_id` bigint UNSIGNED NULL,
        `personal_info` json NULL,
        `academic_info` json NULL,
        `employment_info` json NULL,
        `application_details` json NULL,
        `status` varchar(50) DEFAULT 'pending',
        `notes` text NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        `deleted_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ applications table\n";

    // Add missing columns to existing tables if they exist
    echo "\n[4b] Adding missing columns to existing tables...\n";
    try {
        \DB::statement("ALTER TABLE `applications` ADD COLUMN `deleted_at` timestamp NULL AFTER `updated_at`");
        echo "✓ Added deleted_at to applications\n";
    } catch (Exception $e) {
        // Ignore if column exists
    }

    // Application documents table
    \DB::statement("CREATE TABLE `application_documents` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_id` bigint UNSIGNED NOT NULL,
        `document_type` varchar(255) NOT NULL,
        `file_path` varchar(255) NOT NULL,
        `file_name` varchar(255) NOT NULL,
        `file_size` varchar(50) NULL,
        `mime_type` varchar(100) NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ application_documents table\n";

    // Interviews table
    \DB::statement("CREATE TABLE `interviews` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_id` bigint UNSIGNED NOT NULL,
        `interview_date` datetime NULL,
        `venue` varchar(255) NULL,
        `notes` text NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ interviews table\n";

    // Notifications table
    \DB::statement("CREATE TABLE `notifications` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` bigint UNSIGNED NULL,
        `user_type` varchar(50) NULL,
        `title` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `is_read` tinyint(1) DEFAULT 0,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ notifications table\n";

    // Settings table
    \DB::statement("CREATE TABLE `settings` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `key` varchar(255) NOT NULL UNIQUE,
        `value` text NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ settings table\n";

    // Form fields table
    \DB::statement("CREATE TABLE `form_fields` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `section` varchar(50) NOT NULL,
        `field_name` varchar(255) NOT NULL,
        `field_label` varchar(255) NOT NULL,
        `field_type` varchar(50) NOT NULL,
        `is_visible` tinyint(1) DEFAULT 1,
        `is_required` tinyint(1) DEFAULT 0,
        `sort_order` int DEFAULT 0,
        `options` json NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ form_fields table\n";

    // Email templates table
    \DB::statement("CREATE TABLE `email_templates` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `subject` varchar(255) NOT NULL,
        `body` text NOT NULL,
        `variables` json NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ email_templates table\n";

    // Activity logs table
    \DB::statement("CREATE TABLE `activity_logs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` bigint UNSIGNED NULL,
        `user_type` varchar(50) NULL,
        `action` varchar(255) NOT NULL,
        `description` text NULL,
        `metadata` json NULL,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ activity_logs table\n";

    // Application types table
    \DB::statement("CREATE TABLE `application_types` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `description` text NULL,
        `is_active` tinyint(1) DEFAULT 1,
        `sort_order` int DEFAULT 0,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ application_types table\n";

    // Pivot table for application types and form fields
    \DB::statement("CREATE TABLE `application_type_form_field` (
        `application_type_id` bigint UNSIGNED NOT NULL,
        `form_field_id` bigint UNSIGNED NOT NULL,
        `is_enabled` tinyint(1) DEFAULT 1,
        `is_required` tinyint(1) DEFAULT 0,
        `sort_order` int DEFAULT 0,
        PRIMARY KEY (`application_type_id`, `form_field_id`),
        FOREIGN KEY (`application_type_id`) REFERENCES `application_types`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`form_field_id`) REFERENCES `form_fields`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "✓ application_type_form_field table\n\n";

    // Insert data
    echo "[5] Inserting data...\n\n";

    // Roles
    \DB::table('roles')->insert([
        ['name' => 'Super Administrator', 'slug' => 'super-admin', 'description' => 'Full system access', 'permissions' => json_encode(['*']), 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Manage applications', 'permissions' => json_encode(['view-applications', 'manage-applications']), 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 2 roles created\n";

    // Admin user
    \DB::table('administrators')->insert([
        'name' => 'Super Admin',
        'email' => 'admin@portal.com',
        'password' => password_hash('password123', PASSWORD_BCRYPT),
        'role_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✓ Admin user: admin@portal.com / password123\n";

    // Settings
    \DB::table('settings')->insert([
        ['key' => 'portal_name', 'value' => 'Online Application Portal', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'institution_name', 'value' => 'EKSCOTECH', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'contact_email', 'value' => 'contact@ekscotech.edu.ng', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'phone_number', 'value' => '+2341234567890', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'application_prefix', 'value' => 'APP', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'maintenance_mode', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'programmes', 'value' => json_encode([
            ['code' => 'NCE', 'name' => 'Nigeria Certificate in Education', 'is_active' => true, 'sort_order' => 1],
            ['code' => 'ND', 'name' => 'National Diploma', 'is_active' => true, 'sort_order' => 2],
            ['code' => 'HND', 'name' => 'Higher National Diploma', 'is_active' => true, 'sort_order' => 3],
            ['code' => 'PGDE', 'name' => 'Postgraduate Diploma in Education', 'is_active' => true, 'sort_order' => 4],
            ['code' => 'BACHELOR', 'name' => 'Bachelor Degree', 'is_active' => true, 'sort_order' => 5],
            ['code' => 'MASTERS', 'name' => "Master's Degree", 'is_active' => true, 'sort_order' => 6],
            ['code' => 'PHD', 'name' => 'Doctor of Philosophy', 'is_active' => true, 'sort_order' => 7],
        ]), 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 7 settings created\n";

    // Application Types
    \DB::table('application_types')->insert([
        ['name' => 'Employment Application', 'slug' => 'employment', 'description' => 'Application for job positions', 'is_active' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Academic Admission', 'slug' => 'academic-admission', 'description' => 'Application for academic programmes', 'is_active' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Scholarship Application', 'slug' => 'scholarship', 'description' => 'Application for scholarships', 'is_active' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Training Application', 'slug' => 'training', 'description' => 'Application for training programmes', 'is_active' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 4 application types created\n";

    // Form Fields
    $formFields = [
        ['section' => 'personal', 'field_name' => 'first_name', 'field_label' => 'First Name', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'last_name', 'field_label' => 'Last Name', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'email', 'field_label' => 'Email', 'field_type' => 'email', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'phone_number', 'field_label' => 'Phone', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'academic', 'field_name' => 'highest_qualification', 'field_label' => 'Qualification', 'field_type' => 'select', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'academic', 'field_name' => 'institution_attended', 'field_label' => 'Institution', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
    ];
    foreach ($formFields as $field) {
        \DB::table('form_fields')->insert($field);
    }
    echo "✓ 6 form fields created\n\n";

    // Verification
    echo "[6] Verification...\n\n";
    echo "Tables created: " . count(\DB::select('SHOW TABLES')) . "\n";
    echo "Roles: " . \DB::table('roles')->count() . "\n";
    echo "Admins: " . \DB::table('administrators')->count() . "\n";
    echo "Application Types: " . \DB::table('application_types')->count() . "\n";
    echo "Settings: " . \DB::table('settings')->count() . "\n";
    echo "Form Fields: " . \DB::table('form_fields')->count() . "\n";

    echo "\n--- APPLICATION TYPES ---\n";
    $types = \DB::table('application_types')->get();
    foreach ($types as $t) {
        echo "ID: $t->id | $t->name | Active: " . ($t->is_active ? 'YES' : 'NO') . "\n";
    }

    echo "\n--- PROGRAMMES ---\n";
    $progSetting = \DB::table('settings')->where('key', 'programmes')->first();
    if ($progSetting) {
        $progs = json_decode($progSetting->value, true);
        foreach ($progs as $p) {
            echo "{$p['code']}: {$p['name']}\n";
        }
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ DATABASE SETUP COMPLETE!\n";
    echo str_repeat("=", 50) . "\n\n";

    echo "🔗 QUICK LINKS:\n";
    echo "Login: https://career.personel.ink/admin/login\n";
    echo "Email: admin@portal.com\n";
    echo "Password: password123\n\n";
    echo "Application Types: https://career.personel.ink/admin/settings/application-types\n";
    echo "Programmes: https://career.personel.ink/admin/settings/programmes\n";
    echo "Apply: https://career.personel.ink/apply\n";

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n⚠️ DELETE THIS FILE NOW!\n";
echo "</pre>";