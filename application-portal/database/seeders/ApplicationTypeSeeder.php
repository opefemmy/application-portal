<?php

namespace Database\Seeders;

use App\Models\ApplicationType;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class ApplicationTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Create Employment Application type
        $employmentType = ApplicationType::create([
            'name' => 'Employment Application',
            'slug' => 'employment',
            'description' => 'Application for job positions',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Attach fields to Employment type (all fields enabled)
        $personalFields = FormField::where('section', 'personal')->get();
        $academicFields = FormField::where('section', 'academic')->get();
        $employmentFields = FormField::where('section', 'employment')->get();
        $detailsFields = FormField::where('section', 'details')->get();

        $order = 1;
        foreach ($personalFields as $field) {
            $employmentType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => $field->is_required,
                'sort_order' => $order++,
            ]);
        }

        $order = 100;
        foreach ($academicFields as $field) {
            $employmentType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => true,
                'sort_order' => $order++,
            ]);
        }

        $order = 200;
        foreach ($employmentFields as $field) {
            $employmentType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => false,
                'sort_order' => $order++,
            ]);
        }

        $order = 300;
        foreach ($detailsFields as $field) {
            $employmentType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => in_array($field->field_name, ['position_applying_for']),
                'sort_order' => $order++,
            ]);
        }

        // Create Academic/Admission Application type
        $academicType = ApplicationType::create([
            'name' => 'Academic Admission',
            'slug' => 'academic-admission',
            'description' => 'Application for academic programmes and admissions',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Create Scholarship Application type
        $scholarshipType = ApplicationType::create([
            'name' => 'Scholarship Application',
            'slug' => 'scholarship',
            'description' => 'Application for scholarships and grants',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // Create Training Application type
        $trainingType = ApplicationType::create([
            'name' => 'Training Application',
            'slug' => 'training',
            'description' => 'Application for training programmes',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Attach fields to Academic type
        $order = 1;
        foreach ($personalFields as $field) {
            $academicType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => $field->is_required,
                'sort_order' => $order++,
            ]);
        }

        $order = 100;
        foreach ($academicFields as $field) {
            $academicType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => true,
                'sort_order' => $order++,
            ]);
        }

        // Employment section disabled for academic
        $order = 200;
        foreach ($employmentFields as $field) {
            $academicType->formFields()->attach($field->id, [
                'is_enabled' => false,
                'is_required' => false,
                'sort_order' => $order++,
            ]);
        }

        $order = 300;
        foreach ($detailsFields as $field) {
            $academicType->formFields()->attach($field->id, [
                'is_enabled' => true,
                'is_required' => in_array($field->field_name, ['programme_applying_for']),
                'sort_order' => $order++,
            ]);
        }
    }
}