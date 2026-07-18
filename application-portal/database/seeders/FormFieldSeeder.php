<?php

namespace Database\Seeders;

use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{
    public function run(): void
    {
        // Personal Information Fields
        $personalFields = [
            ['field_name' => 'first_name', 'field_label' => 'First Name', 'field_type' => 'text', 'sort_order' => 1],
            ['field_name' => 'middle_name', 'field_label' => 'Middle Name', 'field_type' => 'text', 'sort_order' => 2],
            ['field_name' => 'last_name', 'field_label' => 'Last Name', 'field_type' => 'text', 'sort_order' => 3],
            ['field_name' => 'gender', 'field_label' => 'Gender', 'field_type' => 'select', 'options' => ['Male', 'Female'], 'sort_order' => 4],
            ['field_name' => 'date_of_birth', 'field_label' => 'Date of Birth', 'field_type' => 'date', 'sort_order' => 5],
            ['field_name' => 'marital_status', 'field_label' => 'Marital Status', 'field_type' => 'select', 'options' => ['Single', 'Married', 'Divorced', 'Widowed'], 'sort_order' => 6],
            ['field_name' => 'nationality', 'field_label' => 'Nationality', 'field_type' => 'text', 'sort_order' => 7],
            ['field_name' => 'state_of_origin', 'field_label' => 'State of Origin', 'field_type' => 'text', 'sort_order' => 8],
            ['field_name' => 'local_government', 'field_label' => 'Local Government Area', 'field_type' => 'text', 'sort_order' => 9],
            ['field_name' => 'residential_address', 'field_label' => 'Residential Address', 'field_type' => 'textarea', 'sort_order' => 10],
            ['field_name' => 'postal_address', 'field_label' => 'Postal Address', 'field_type' => 'textarea', 'sort_order' => 11],
            ['field_name' => 'email', 'field_label' => 'Email Address', 'field_type' => 'email', 'sort_order' => 12],
            ['field_name' => 'phone_number', 'field_label' => 'Phone Number', 'field_type' => 'text', 'sort_order' => 13],
        ];

        foreach ($personalFields as $field) {
            FormField::create([
                'section' => 'personal',
                'field_name' => $field['field_name'],
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'options' => isset($field['options']) ? json_encode($field['options']) : null,
                'is_visible' => true,
                'is_required' => in_array($field['field_name'], ['first_name', 'last_name', 'gender', 'date_of_birth', 'nationality', 'state_of_origin', 'email', 'phone_number']),
                'sort_order' => $field['sort_order'],
            ]);
        }

        // Academic Information Fields
        $academicFields = [
            ['field_name' => 'highest_qualification', 'field_label' => 'Highest Qualification', 'field_type' => 'select', 'options' => ['SSCE', 'NCE', 'OND', 'HND', 'BSc', 'MSc', 'PhD'], 'sort_order' => 1],
            ['field_name' => 'institution_attended', 'field_label' => 'Institution Attended', 'field_type' => 'text', 'sort_order' => 2],
            ['field_name' => 'course_studied', 'field_label' => 'Course Studied', 'field_type' => 'text', 'sort_order' => 3],
            ['field_name' => 'grade_class', 'field_label' => 'Grade/Class', 'field_type' => 'select', 'options' => ['First Class', 'Second Class Upper', 'Second Class Lower', 'Third Class', 'Pass', 'Distinction', 'Merit', 'Credit', 'Good', 'Fair'], 'sort_order' => 4],
            ['field_name' => 'graduation_year', 'field_label' => 'Graduation Year', 'field_type' => 'number', 'sort_order' => 5],
        ];

        foreach ($academicFields as $field) {
            FormField::create([
                'section' => 'academic',
                'field_name' => $field['field_name'],
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'options' => isset($field['options']) ? json_encode($field['options']) : null,
                'is_visible' => true,
                'is_required' => true,
                'sort_order' => $field['sort_order'],
            ]);
        }

        // Employment Information Fields
        $employmentFields = [
            ['field_name' => 'employer', 'field_label' => 'Employer', 'field_type' => 'text', 'sort_order' => 1],
            ['field_name' => 'position', 'field_label' => 'Position/Job Title', 'field_type' => 'text', 'sort_order' => 2],
            ['field_name' => 'years_experience', 'field_label' => 'Years of Experience', 'field_type' => 'number', 'sort_order' => 3],
            ['field_name' => 'job_start_date', 'field_label' => 'Start Date', 'field_type' => 'date', 'sort_order' => 4],
            ['field_name' => 'job_end_date', 'field_label' => 'End Date', 'field_type' => 'date', 'sort_order' => 5],
            ['field_name' => 'job_description', 'field_label' => 'Job Description', 'field_type' => 'textarea', 'sort_order' => 6],
        ];

        foreach ($employmentFields as $field) {
            FormField::create([
                'section' => 'employment',
                'field_name' => $field['field_name'],
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'options' => isset($field['options']) ? json_encode($field['options']) : null,
                'is_visible' => true,
                'is_required' => false,
                'sort_order' => $field['sort_order'],
            ]);
        }

        // Application Details Fields
        $detailsFields = [
            ['field_name' => 'position_applying_for', 'field_label' => 'Position/Programme Applying For', 'field_type' => 'text', 'sort_order' => 1],
            ['field_name' => 'programme_applying_for', 'field_label' => 'Programme Applying For', 'field_type' => 'text', 'sort_order' => 2],
            ['field_name' => 'department', 'field_label' => 'Department', 'field_type' => 'text', 'sort_order' => 3],
            ['field_name' => 'category', 'field_label' => 'Category', 'field_type' => 'text', 'sort_order' => 4],
        ];

        foreach ($detailsFields as $field) {
            FormField::create([
                'section' => 'details',
                'field_name' => $field['field_name'],
                'field_label' => $field['field_label'],
                'field_type' => $field['field_type'],
                'options' => isset($field['options']) ? json_encode($field['options']) : null,
                'is_visible' => true,
                'is_required' => in_array($field['field_name'], ['position_applying_for']),
                'sort_order' => $field['sort_order'],
            ]);
        }
    }
}