<?php

namespace Database\Seeders;

use App\Models\ApplicationType;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class ApplicationTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Create Employment Application type only
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
    }
}