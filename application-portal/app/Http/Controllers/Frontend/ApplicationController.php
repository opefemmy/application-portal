<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Setting;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationReceived;
use Illuminate\Support\Str;
use App\Models\ApplicationType;
use App\Models\FormField;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function showForm()
    {
        if (!Setting::isPortalOpen()) {
            $closeDate = Setting::get('portal_close_date');
            return redirect()->route('home')->with('error', 'Application portal is currently closed. Will reopen on ' . Carbon::parse($closeDate)->format('F j, Y'));
        }

        $applicationTypes = ApplicationType::active()->with('formFields')->get();

        // Get the first application type for field configuration
        $selectedType = $applicationTypes->first();

        // Get form fields with application type configuration
        $formFields = [];
        if ($selectedType) {
            $typeFieldConfigs = $selectedType->formFields->keyBy('id');

            $sections = ['personal', 'academic', 'employment', 'details'];
            foreach ($sections as $section) {
                $fields = FormField::where('section', $section)
                    ->where('is_visible', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->map(function ($field) use ($typeFieldConfigs) {
                        $config = $typeFieldConfigs->get($field->id);
                        $field->is_enabled = $config ? $config->pivot->is_enabled : true;
                        $field->is_required = $config ? $config->pivot->is_required : $field->is_required;
                        return $field;
                    })
                    ->filter(fn($field) => $field->is_enabled);
                $formFields[$section] = $fields;
            }
        }

        // Get active programmes
        $programmesData = json_decode(Setting::get('programmes', '[]'), true);
        $programmes = collect($programmesData)->where('is_active', true)->values()->all();

        return view('frontend.apply', compact('applicationTypes', 'programmes', 'formFields'));
    }

    public function submit(Request $request)
    {
        if (!Setting::isPortalOpen()) {
            return redirect()->route('home')->with('error', 'Application portal is closed.');
        }

        // Get field configurations for validation
        $applicationType = ApplicationType::with('formFields')->find($request->application_type_id);
        $fieldConfigs = $applicationType ? $applicationType->formFields->keyBy('id') : collect();

        // Build dynamic validation rules
        $validationRules = [
            // Application Type
            'application_type_id' => 'required|exists:application_types,id',

            // Personal Information - always required
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:100',
            'state_of_origin' => 'required|string|max:100',
            'local_government' => 'required|string|max:100',
            'residential_address' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',

            // Academic Information - always required
            'highest_qualification' => 'required|string|max:100',
            'institution_attended' => 'required|string|max:255',
            'course_studied' => 'required|string|max:255',
            'grade_class' => 'required|string|max:50',
            'graduation_year' => 'required|numeric|min:1950|max:' . date('Y'),

            // Application Details - always required
            'position_applying_for' => 'required|string|max:255',

            // Declaration
            'declaration' => 'accepted',

            // Documents - keep these required for now
            'passport_photograph' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'birth_certificate' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'lg_certificate' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'degree_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
            'olevel_result' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
            'nysc_certificate' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'cv' => 'nullable|mimes:pdf|max:5120',
            'transcript' => 'nullable|mimes:pdf|max:10240',
            'recommendation_letter' => 'nullable|mimes:pdf|max:5120',
            'other_documents' => 'nullable|array|max:5',
            'other_documents.*' => 'mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        // Add employment validation
        $validationRules['employment'] = 'nullable|array';
        $validationRules['employment.*.employer'] = 'nullable|string|max:200';
        $validationRules['employment.*.position'] = 'nullable|string|max:100';
        $validationRules['employment.*.years_experience'] = 'nullable|integer|min:0';

        // Get all visible form fields and add validation based on config
        $allFields = FormField::where('is_visible', true)->get();
        foreach ($allFields as $field) {
            $config = $fieldConfigs->get($field->id);
            $isRequired = $config ? $config->pivot->is_required : $field->is_required;

            // Determine validation rule
            $rule = $isRequired ? 'required' : 'nullable';

            // Add field-specific validation rules
            switch ($field->field_type) {
                case 'email':
                    $validationRules[$field->field_name] = $rule . '|email|max:255';
                    break;
                case 'number':
                    $validationRules[$field->field_name] = $rule . '|numeric';
                    break;
                case 'date':
                    $validationRules[$field->field_name] = $rule . '|date';
                    break;
                case 'select':
                    $validationRules[$field->field_name] = $rule . '|string|max:200';
                    break;
                case 'textarea':
                    $validationRules[$field->field_name] = $rule . '|string';
                    break;
                default:
                    $validationRules[$field->field_name] = $rule . '|string|max:255';
            }
        }

        // Validate fields
        $validated = $request->validate($validationRules);

        // Check for duplicate email or phone (MariaDB compatible)
        $existingEmail = Application::whereRaw('JSON_UNQUOTE(JSON_EXTRACT(personal_info, "$.email")) = ?', [$validated['email']])->exists();
        if ($existingEmail) {
            return back()->with('error', 'An application with this email already exists.')->withInput();
        }

        $existingPhone = Application::whereRaw('JSON_UNQUOTE(JSON_EXTRACT(personal_info, "$.phone_number")) = ?', [$validated['phone_number']])->exists();
        if ($existingPhone) {
            return back()->with('error', 'An application with this phone number already exists.')->withInput();
        }

        // Generate application number
        $applicationNumber = Application::generateApplicationNumber();

        // Prepare data - use data_get to safely access validated fields
        $personalInfo = [
            'first_name' => data_get($validated, 'first_name'),
            'middle_name' => data_get($validated, 'middle_name'),
            'last_name' => data_get($validated, 'last_name'),
            'gender' => data_get($validated, 'gender'),
            'date_of_birth' => data_get($validated, 'date_of_birth'),
            'marital_status' => data_get($validated, 'marital_status'),
            'nationality' => data_get($validated, 'nationality'),
            'state_of_origin' => data_get($validated, 'state_of_origin'),
            'local_government' => data_get($validated, 'local_government'),
            'residential_address' => data_get($validated, 'residential_address'),
            'postal_address' => data_get($validated, 'postal_address') ?? data_get($validated, 'residential_address'),
            'phone_number' => data_get($validated, 'phone_number'),
            'alternative_phone' => data_get($validated, 'alternative_phone'),
            'email' => data_get($validated, 'email'),
        ];

        $academicInfo = [
            'highest_qualification' => data_get($validated, 'highest_qualification'),
            'institution_attended' => data_get($validated, 'institution_attended'),
            'course_studied' => data_get($validated, 'course_studied'),
            'grade_class' => data_get($validated, 'grade_class'),
            'graduation_year' => data_get($validated, 'graduation_year'),
        ];

        $employmentInfo = data_get($validated, 'employment', []);

        $applicationDetails = [
            'position_applying_for' => data_get($validated, 'position_applying_for'),
        ];

        // Create application with retry on duplicate
        $maxRetries = 10;
        $created = false;
        $lastError = '';

        for ($retry = 0; $retry < $maxRetries && !$created; $retry++) {
            try {
                $application = Application::create([
                    'application_number' => $applicationNumber,
                    'application_type_id' => $validated['application_type_id'],
                    'personal_info' => $personalInfo,
                    'academic_info' => $academicInfo,
                    'employment_info' => $employmentInfo,
                    'application_details' => $applicationDetails,
                    'status' => 'pending',
                ]);
                $created = true;
            } catch (\Illuminate\Database\QueryException $e) {
                $lastError = $e->getMessage();
                // Check for duplicate entry error
                if ($e->getCode() == 23000 && str_contains($e->getMessage(), '1062')) {
                    // Generate a new application number and retry
                    $applicationNumber = Application::generateApplicationNumber();
                    \Log::warning("Duplicate application number, retrying with: " . $applicationNumber);

                    // Small delay to reduce collision chances on next attempt
                    usleep(100000); // 100ms
                } else {
                    // Log other errors and break
                    \Log::error("Application creation error: " . $e->getMessage());
                    break;
                }
            }
        }

        if (!$created) {
            \Log::error("Failed to create application after {$maxRetries} retries. Last error: {$lastError}. Application number: {$applicationNumber}");
            return back()->with('error', 'Unable to create application. Please try again. Error: ' . substr($lastError, 0, 100))->withInput();
        }

        // Upload documents
        $documents = [
            ['key' => 'passport_photograph', 'type' => 'Passport Photograph'],
            ['key' => 'birth_certificate', 'type' => 'Birth Certificate'],
            ['key' => 'lg_certificate', 'type' => 'Local Government Certificate'],
            ['key' => 'degree_certificate', 'type' => 'Degree Certificate'],
            ['key' => 'olevel_result', 'type' => 'O\'Level Result'],
            ['key' => 'nysc_certificate', 'type' => 'NYSC Certificate'],
            ['key' => 'cv', 'type' => 'CV'],
            ['key' => 'transcript', 'type' => 'Transcript'],
            ['key' => 'recommendation_letter', 'type' => 'Recommendation Letter'],
        ];

        foreach ($documents as $doc) {
            if ($request->hasFile($doc['key'])) {
                $file = $request->file($doc['key']);
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('applications/' . $applicationNumber, $filename);

                ApplicationDocument::create([
                    'application_id' => $application->id,
                    'document_type' => $doc['type'],
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Upload other documents
        if ($request->hasFile('other_documents')) {
            foreach ($request->file('other_documents') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('applications/' . $applicationNumber, $filename);

                ApplicationDocument::create([
                    'application_id' => $application->id,
                    'document_type' => 'Other Document',
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Create notification for admin (wrapped in try-catch for database compatibility)
        try {
            Notification::createNotification(
                'new_application',
                'New Application Received',
                "New application {$applicationNumber} from {$personalInfo['first_name']} {$personalInfo['last_name']}",
                ['application_id' => $application->id, 'application_number' => $applicationNumber]
            );
        } catch (\Exception $e) {
            // Log but don't fail - notification is not critical
            \Log::warning('Failed to create notification: ' . $e->getMessage());
        }

        // Send confirmation email
        try {
            Mail::to($personalInfo['email'])->send(new ApplicationReceived($application));
        } catch (\Exception $e) {
            // Log error but don't fail the submission
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
            // Store warning in session to display
            session()->flash('email_warning', 'Note: Confirmation email could not be sent. Please contact support if you did not receive one.');
        }

        return redirect()->route('application.acknowledge', $application->id)
            ->with('success', 'Application submitted successfully!');
    }

    public function acknowledge(Application $application)
    {
        $settings = \App\Models\Setting::getSettings();
        return view('frontend.acknowledge', compact('application', 'settings'));
    }

    // Public print view - for applicants (without Employment History)
    public function publicPrint(Application $application)
    {
        $settings = \App\Models\Setting::getSettings();
        return view('admin.applications.print', compact('application', 'settings'));
    }

    public function downloadAcknowledge(Application $application)
    {
        try {
            // Use dompdf v2.0
            $options = new \Dompdf\Options();
            $options->setIsRemoteEnabled(true);
            $options->setIsHtml5ParserEnabled(true);

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->setOptions($options);

            // Load application with documents
            $application->load('documents');
            $settings = \App\Models\Setting::getSettings();

            // Use the same print template as admin (which works)
            $html = view('admin.applications.print', [
                'application' => $application,
                'settings' => $settings
            ])->render();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('application-' . $application->application_number . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'PDF Generation Failed',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}