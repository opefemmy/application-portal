<?php
/**
 * Complete Database Seeder v5 - ALL IN ONE
 *
 * Upload to /public/ folder and access: https://career.personel.ink/debug-seeder.php
 *
 * DELETE AFTER USE!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='background:#1a1a1a;color:#00ffff;padding:20px;font-family:monospace;font-size:14px;'>";
echo "╔════════════════════════════════════════════════════════╗\n";
echo "║          DATABASE SEEDER v5 - COMPLETE FIX             ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "[✓] Laravel bootstrapped\n\n";

    $dbName = \DB::connection()->getDatabaseName();
    echo "[✓] Database: $dbName\n\n";

    // =====================================================
    // STEP 1: DROP ALL TABLES
    // =====================================================
    echo "═══ STEP 1: DROPPING ALL TABLES ═══\n";
    \DB::statement('SET FOREIGN_KEY_CHECKS = 0');

    $tables = \DB::select('SHOW TABLES');
    foreach ($tables as $t) {
        $tableName = array_values((array)$t)[0];
        echo "Dropping: $tableName\n";
        \DB::statement("DROP TABLE IF EXISTS `$tableName`");
    }

    \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    echo "✓ All tables dropped\n\n";

    // =====================================================
    // STEP 2: CREATE ALL TABLES
    // =====================================================
    echo "═══ STEP 2: CREATING ALL TABLES ═══\n";

    // ROLES
    \DB::statement("CREATE TABLE `roles` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `description` text,
        `permissions` json,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ roles\n";

    // ADMINISTRATORS (with SoftDeletes)
    \DB::statement("CREATE TABLE `administrators` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL UNIQUE,
        `password` varchar(255) NOT NULL,
        `role_id` bigint UNSIGNED,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        `deleted_at` timestamp NULL,
        FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ administrators\n";

    // APPLICATION TYPES
    \DB::statement("CREATE TABLE `application_types` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `description` text,
        `is_active` tinyint(1) DEFAULT 1,
        `sort_order` int DEFAULT 0,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ application_types\n";

    // FORM FIELDS
    \DB::statement("CREATE TABLE `form_fields` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `section` varchar(50) NOT NULL,
        `field_name` varchar(255) NOT NULL,
        `field_label` varchar(255) NOT NULL,
        `field_type` varchar(50) NOT NULL,
        `is_visible` tinyint(1) DEFAULT 1,
        `is_required` tinyint(1) DEFAULT 0,
        `sort_order` int DEFAULT 0,
        `options` json,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ form_fields\n";

    // APPLICATION TYPE FIELDS (pivot) - MUST MATCH MIGRATION
    \DB::statement("CREATE TABLE `application_type_fields` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_type_id` bigint UNSIGNED NOT NULL,
        `form_field_id` bigint UNSIGNED NOT NULL,
        `is_enabled` tinyint(1) DEFAULT 1,
        `is_required` tinyint(1) DEFAULT 0,
        `sort_order` int DEFAULT 0,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        FOREIGN KEY (`application_type_id`) REFERENCES `application_types`(`id`) ON DELETE CASCADE,
        FOREIGN KEY (`form_field_id`) REFERENCES `form_fields`(`id`) ON DELETE CASCADE,
        UNIQUE KEY `application_type_fields_application_type_id_form_field_id_unique` (`application_type_id`, `form_field_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ application_type_fields\n";

    // APPLICATIONS (with SoftDeletes)
    \DB::statement("CREATE TABLE `applications` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_number` varchar(50) NOT NULL UNIQUE,
        `application_type_id` bigint UNSIGNED,
        `personal_info` json,
        `academic_info` json,
        `employment_info` json,
        `application_details` json,
        `status` varchar(50) DEFAULT 'pending',
        `notes` text,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        `deleted_at` timestamp NULL,
        FOREIGN KEY (`application_type_id`) REFERENCES `application_types`(`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ applications\n";

    // APPLICATION DOCUMENTS
    \DB::statement("CREATE TABLE `application_documents` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_id` bigint UNSIGNED NOT NULL,
        `document_type` varchar(255) NOT NULL,
        `file_path` varchar(255) NOT NULL,
        `file_name` varchar(255) NOT NULL,
        `file_size` varchar(50),
        `mime_type` varchar(100),
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ application_documents\n";

    // INTERVIEWS
    \DB::statement("CREATE TABLE `interviews` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `application_id` bigint UNSIGNED NOT NULL,
        `interview_date` datetime,
        `venue` varchar(255),
        `notes` text,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL,
        FOREIGN KEY (`application_id`) REFERENCES `applications`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ interviews\n";

    // NOTIFICATIONS
    \DB::statement("CREATE TABLE `notifications` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` bigint UNSIGNED,
        `user_type` varchar(50),
        `title` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `is_read` tinyint(1) DEFAULT 0,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ notifications\n";

    // SETTINGS
    \DB::statement("CREATE TABLE `settings` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `key` varchar(255) NOT NULL UNIQUE,
        `value` text,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ settings\n";

    // EMAIL TEMPLATES
    \DB::statement("CREATE TABLE `email_templates` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL UNIQUE,
        `subject` varchar(255) NOT NULL,
        `body` text NOT NULL,
        `variables` json,
        `created_at` timestamp NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ email_templates\n";

    // ACTIVITY LOGS
    \DB::statement("CREATE TABLE `activity_logs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` bigint UNSIGNED,
        `user_type` varchar(50),
        `action` varchar(255) NOT NULL,
        `description` text,
        `old_values` json,
        `new_values` json,
        `ip_address` varchar(45),
        `user_agent` text,
        `created_at` timestamp NOT NULL,
        `updated_at` timestamp NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✓ activity_logs\n\n";

    // =====================================================
    // STEP 3: INSERT DATA
    // =====================================================
    echo "═══ STEP 3: INSERTING DATA ═══\n\n";

    // ROLES
    \DB::table('roles')->insert([
        ['name' => 'Super Administrator', 'slug' => 'super-admin', 'description' => 'Full system access', 'permissions' => json_encode(['*']), 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'Manage applications', 'permissions' => json_encode(['view-applications', 'manage-applications']), 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Registrar', 'slug' => 'registrar', 'description' => 'View and process applications', 'permissions' => json_encode(['view-applications', 'manage-applications']), 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 3 roles\n";

    // ADMIN USER
    \DB::table('administrators')->insert([
        'name' => 'Super Admin',
        'email' => 'admin@portal.com',
        'password' => password_hash('password123', PASSWORD_BCRYPT),
        'role_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✓ Admin user: admin@portal.com / password123\n";

    // SETTINGS
    \DB::table('settings')->insert([
        ['key' => 'portal_name', 'value' => 'Online Application Portal', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'institution_name', 'value' => 'EKSCOTECH', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'contact_email', 'value' => 'contact@ekscotech.edu.ng', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'phone_number', 'value' => '+2341234567890', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'application_prefix', 'value' => 'APP', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'maintenance_mode', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'primary_color', 'value' => '#38488e', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'secondary_color', 'value' => '#4052a0', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'accent_color', 'value' => '#fcb900', 'created_at' => now(), 'updated_at' => now()],
        ['key' => 'programmes', 'value' => json_encode([
            ['code' => 'NCE', 'name' => 'Nigeria Certificate in Education', 'department' => 'Education', 'is_active' => true, 'sort_order' => 1],
            ['code' => 'ND', 'name' => 'National Diploma', 'department' => 'Various', 'is_active' => true, 'sort_order' => 2],
            ['code' => 'HND', 'name' => 'Higher National Diploma', 'department' => 'Various', 'is_active' => true, 'sort_order' => 3],
            ['code' => 'PGDE', 'name' => 'Postgraduate Diploma in Education', 'department' => 'Education', 'is_active' => true, 'sort_order' => 4],
            ['code' => 'BACHELOR', 'name' => 'Bachelor Degree', 'department' => 'Various', 'is_active' => true, 'sort_order' => 5],
            ['code' => 'MASTERS', 'name' => "Master's Degree", 'department' => 'Various', 'is_active' => true, 'sort_order' => 6],
            ['code' => 'PHD', 'name' => 'Doctor of Philosophy', 'department' => 'Research', 'is_active' => true, 'sort_order' => 7],
        ]), 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 10 settings (including programmes)\n";

    // APPLICATION TYPES
    \DB::table('application_types')->insert([
        ['name' => 'Employment Application', 'slug' => 'employment', 'description' => 'Application for job positions', 'is_active' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Academic Admission', 'slug' => 'academic-admission', 'description' => 'Application for academic programmes', 'is_active' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Scholarship Application', 'slug' => 'scholarship', 'description' => 'Application for scholarships', 'is_active' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Training Application', 'slug' => 'training', 'description' => 'Application for training programmes', 'is_active' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "✓ 4 application types\n";

    // FORM FIELDS
    $formFields = [
        ['section' => 'personal', 'field_name' => 'first_name', 'field_label' => 'First Name', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'middle_name', 'field_label' => 'Middle Name', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 0, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'last_name', 'field_label' => 'Last Name', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'gender', 'field_label' => 'Gender', 'field_type' => 'select', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'date_of_birth', 'field_label' => 'Date of Birth', 'field_type' => 'date', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'email', 'field_label' => 'Email Address', 'field_type' => 'email', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'phone_number', 'field_label' => 'Phone Number', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 7, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'state_of_origin', 'field_label' => 'State of Origin', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 8, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'personal', 'field_name' => 'local_government', 'field_label' => 'Local Government', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 9, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'academic', 'field_name' => 'highest_qualification', 'field_label' => 'Highest Qualification', 'field_type' => 'select', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'academic', 'field_name' => 'institution_attended', 'field_label' => 'Institution Attended', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
        ['section' => 'academic', 'field_name' => 'course_studied', 'field_label' => 'Course Studied', 'field_type' => 'text', 'is_visible' => 1, 'is_required' => 1, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
    ];
    foreach ($formFields as $field) {
        \DB::table('form_fields')->insert($field);
    }
    echo "✓ 12 form fields\n\n";

    // =====================================================
    // VERIFICATION
    // =====================================================
    echo "═══ VERIFICATION ═══\n\n";

    $tablesCount = count(\DB::select('SHOW TABLES'));
    echo "Total Tables: $tablesCount\n\n";

    echo "--- DATA SUMMARY ---\n";
    echo "Roles: " . \DB::table('roles')->count() . "\n";
    echo "Admins: " . \DB::table('administrators')->count() . "\n";
    echo "Settings: " . \DB::table('settings')->count() . "\n";
    echo "Application Types: " . \DB::table('application_types')->count() . "\n";
    echo "Form Fields: " . \DB::table('form_fields')->count() . "\n\n";

    echo "--- APPLICATION TYPES ---\n";
    $types = \DB::table('application_types')->orderBy('id')->get();
    foreach ($types as $t) {
        echo "ID: $t->id | $t->name | Active: " . ($t->is_active ? 'YES' : 'NO') . "\n";
    }

    echo "\n--- PROGRAMMES ---\n";
    $prog = \DB::table('settings')->where('key', 'programmes')->first();
    if ($prog) {
        $progs = json_decode($prog->value, true);
        foreach ($progs as $p) {
            $active = isset($p['is_active']) && $p['is_active'] ? 'YES' : 'NO';
            echo "{$p['code']}: {$p['name']} (Active: $active)\n";
        }
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ SEEDING COMPLETE!\n";
    echo str_repeat("=", 50) . "\n\n";

    echo "🔗 LOGIN:\n";
    echo "URL: https://career.personel.ink/admin/login\n";
    echo "Email: admin@portal.com\n";
    echo "Password: password123\n\n";

    echo "🔗 QUICK LINKS:\n";
    echo "Application Types: https://career.personel.ink/admin/settings/application-types\n";
    echo "Programmes: https://career.personel.ink/admin/settings/programmes\n";
    echo "Apply Form: https://career.personel.ink/apply\n";

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n" . str_repeat("⚠", 20) . "\n";
echo "DELETE THIS FILE AFTER USE!\n";
echo "</pre>";