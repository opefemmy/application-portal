<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;

class FixApplicationData extends Command
{
    protected $signature = 'application:fix-data {application_number : The application number}
                            {--marital_status= : Marital Status}
                            {--nationality= : Nationality}
                            {--address= : Residential Address}
                            {--postal= : Postal Address}
                            {--alt_phone= : Alternative Phone}
                            {--grade= : Grade/Class}
                            {--year= : Graduation Year}
                            {--position= : Position Applied}';

    protected $description = 'Fix application data by filling empty fields';

    public function handle()
    {
        $appNumber = $this->argument('application_number');

        $application = Application::where('application_number', $appNumber)->first();

        if (!$application) {
            $this->error("Application not found: $appNumber");
            return 1;
        }

        $personal = $application->personal_info ?? [];
        $academic = $application->academic_info ?? [];
        $details = $application->application_details ?? [];

        // Update personal info
        if ($this->option('marital_status')) {
            $personal['marital_status'] = $this->option('marital_status');
        }
        if ($this->option('nationality')) {
            $personal['nationality'] = $this->option('nationality');
        }
        if ($this->option('address')) {
            $personal['residential_address'] = $this->option('address');
        }
        if ($this->option('postal')) {
            $personal['postal_address'] = $this->option('postal');
        }
        if ($this->option('alt_phone')) {
            $personal['alternative_phone'] = $this->option('alt_phone');
        }

        // Update academic info
        if ($this->option('grade')) {
            $academic['grade_class'] = $this->option('grade');
        }
        if ($this->option('year')) {
            $academic['graduation_year'] = $this->option('year');
        }

        // Update application details
        if ($this->option('position')) {
            $details['position_applying_for'] = $this->option('position');
        }

        // Save
        $application->personal_info = $personal;
        $application->academic_info = $academic;
        $application->application_details = $details;
        $application->save();

        $this->info("Application $appNumber updated successfully!");
        $this->info(print_r($personal, true));
        $this->info(print_r($academic, true));
        $this->info(print_r($details, true));

        return 0;
    }
}