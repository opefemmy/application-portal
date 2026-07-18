<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\Administrator;
use App\Models\Setting;
use App\Models\EmailTemplate;
use App\Models\FormField;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Full system access',
                'permissions' => ['*']
            ],
            [
                'name' => 'Registrar',
                'slug' => 'registrar',
                'description' => 'View, download and manage all applications',
                'permissions' => ['view-applications', 'download-applications', 'manage-applications', 'view-reports']
            ],
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Manage applications and settings',
                'permissions' => ['manage-applications', 'view-reports', 'manage-settings']
            ],
            [
                'name' => 'Reviewer',
                'slug' => 'reviewer',
                'description' => 'Review and process applications',
                'permissions' => ['view-applications', 'manage-applications']
            ],
            [
                'name' => 'HR Officer',
                'slug' => 'hr-officer',
                'description' => 'Human resources operations',
                'permissions' => ['view-applications', 'download-applications', 'manage-applications']
            ],
            [
                'name' => 'Admission Officer',
                'slug' => 'admission-officer',
                'description' => 'Admissions processing',
                'permissions' => ['view-applications', 'download-applications', 'manage-applications']
            ],
            [
                'name' => 'Data Entry Officer',
                'slug' => 'data-entry',
                'description' => 'Data entry operations',
                'permissions' => ['view-applications', 'manage-applications']
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Super Admin
        $superAdmin = Administrator::create([
            'name' => 'Super Admin',
            'email' => 'admin@portal.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
        ]);

        // Create Settings
        $settings = [
            ['key' => 'portal_name', 'value' => 'Online Application Portal'],
            ['key' => 'institution_name', 'value' => 'Institution Name'],
            ['key' => 'contact_email', 'value' => 'contact@institution.com'],
            ['key' => 'phone_number', 'value' => '+2341234567890'],
            ['key' => 'application_prefix', 'value' => 'APP'],
            ['key' => 'portal_open_date', 'value' => '2024-01-01'],
            ['key' => 'portal_close_date', 'value' => '2024-12-31'],
            ['key' => 'max_applications', 'value' => '10000'],
            ['key' => 'max_upload_size', 'value' => '10240'],
            ['key' => 'allowed_file_types', 'value' => 'pdf,jpg,jpeg,png'],
            ['key' => 'maintenance_mode', 'value' => '0'],
            ['key' => 'maintenance_message', 'value' => ''],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Create Email Templates
        $templates = [
            [
                'name' => 'Application Received',
                'slug' => 'application-received',
                'subject' => 'Application Received - {{application_number}}',
                'body' => 'Your application has been received. Application Number: {{application_number}}',
                'variables' => ['application_number', 'first_name', 'last_name']
            ],
            [
                'name' => 'Shortlist Notification',
                'slug' => 'shortlist-notification',
                'subject' => 'Congratulations! You Have Been Shortlisted',
                'body' => 'Congratulations! You have been shortlisted. Interview Date: {{interview_date}}',
                'variables' => ['application_number', 'first_name', 'interview_date', 'venue']
            ],
            [
                'name' => 'Rejection Notification',
                'slug' => 'rejection-notification',
                'subject' => 'Application Status Update',
                'body' => 'Thank you for your application. Unfortunately, you were not shortlisted.',
                'variables' => ['application_number', 'first_name']
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }

        // Create Form Fields
        $formFields = [
            // Personal Information
            ['section' => 'personal', 'field_name' => 'first_name', 'field_label' => 'First Name', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 1],
            ['section' => 'personal', 'field_name' => 'middle_name', 'field_label' => 'Middle Name', 'field_type' => 'text', 'is_visible' => true, 'is_required' => false, 'sort_order' => 2],
            ['section' => 'personal', 'field_name' => 'last_name', 'field_label' => 'Last Name', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 3],
            ['section' => 'personal', 'field_name' => 'gender', 'field_label' => 'Gender', 'field_type' => 'select', 'is_visible' => true, 'is_required' => true, 'sort_order' => 4],
            ['section' => 'personal', 'field_name' => 'date_of_birth', 'field_label' => 'Date of Birth', 'field_type' => 'date', 'is_visible' => true, 'is_required' => true, 'sort_order' => 5],
            ['section' => 'personal', 'field_name' => 'email', 'field_label' => 'Email Address', 'field_type' => 'email', 'is_visible' => true, 'is_required' => true, 'sort_order' => 6],

            // Academic Information
            ['section' => 'academic', 'field_name' => 'highest_qualification', 'field_label' => 'Highest Qualification', 'field_type' => 'select', 'is_visible' => true, 'is_required' => true, 'sort_order' => 1],
            ['section' => 'academic', 'field_name' => 'institution_attended', 'field_label' => 'Institution Attended', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 2],
            ['section' => 'academic', 'field_name' => 'course_studied', 'field_label' => 'Course Studied', 'field_type' => 'text', 'is_visible' => true, 'is_required' => true, 'sort_order' => 3],
        ];

        foreach ($formFields as $field) {
            FormField::create($field);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Default admin login: admin@portal.com / password123');
    }
}