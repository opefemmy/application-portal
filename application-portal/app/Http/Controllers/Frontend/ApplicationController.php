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

        // Get active programmes
        $programmesData = json_decode(Setting::get('programmes', '[]'), true);
        $programmes = collect($programmesData)->where('is_active', true)->values()->all();

        return view('frontend.apply', compact('applicationTypes', 'programmes'));
    }

    public function submit(Request $request)
    {
        if (!Setting::isPortalOpen()) {
            return redirect()->route('home')->with('error', 'Application portal is closed.');
        }

        // Validate basic fields
        $validated = $request->validate([
            // Application Type
            'application_type_id' => 'required|exists:application_types,id',

            // Personal Information
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:100',
            'state_of_origin' => 'required|string|max:100',
            'local_government' => 'required|string|max:100',
            'residential_address' => 'required|string',
            'postal_address' => 'nullable|string',
            'phone_number' => 'required|string|max:20',
            'alternative_phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',

            // Academic Information
            'highest_qualification' => 'required|string|max:200',
            'institution_attended' => 'required|string|max:200',
            'course_studied' => 'required|string|max:200',
            'grade_class' => 'required|string|max:50',
            'graduation_year' => 'required|digits:4|integer|min:1950|max:' . date('Y'),

            // Employment Information
            'employment' => 'nullable|array',
            'employment.*.employer' => 'nullable|string|max:200',
            'employment.*.position' => 'nullable|string|max:100',
            'employment.*.years_experience' => 'nullable|integer|min:0',

            // Application Details
            'position_applying_for' => 'required|string|max:200',
            'programme_applying_for' => 'nullable|string|max:200',
            'department' => 'nullable|string|max:200',
            'category' => 'nullable|string|max:100',

            // Declaration
            'declaration' => 'accepted',

            // Documents
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
        ]);

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

        // Prepare data
        $personalInfo = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'marital_status' => $validated['marital_status'],
            'nationality' => $validated['nationality'],
            'state_of_origin' => $validated['state_of_origin'],
            'local_government' => $validated['local_government'],
            'residential_address' => $validated['residential_address'],
            'postal_address' => $validated['postal_address'] ?? $validated['residential_address'],
            'phone_number' => $validated['phone_number'],
            'alternative_phone' => $validated['alternative_phone'],
            'email' => $validated['email'],
        ];

        $academicInfo = [
            'highest_qualification' => $validated['highest_qualification'],
            'institution_attended' => $validated['institution_attended'],
            'course_studied' => $validated['course_studied'],
            'grade_class' => $validated['grade_class'],
            'graduation_year' => $validated['graduation_year'],
        ];

        $employmentInfo = $validated['employment'] ?? [];

        $applicationDetails = [
            'position_applying_for' => $validated['position_applying_for'],
            'programme_applying_for' => $validated['programme_applying_for'] ?? null,
            'department' => $validated['department'] ?? null,
            'category' => $validated['category'] ?? null,
        ];

        // Create application
        $application = Application::create([
            'application_number' => $applicationNumber,
            'application_type_id' => $validated['application_type_id'],
            'personal_info' => $personalInfo,
            'academic_info' => $academicInfo,
            'employment_info' => $employmentInfo,
            'application_details' => $applicationDetails,
            'status' => 'pending',
        ]);

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

        // Create notification for admin
        Notification::createNotification(
            'new_application',
            'New Application Received',
            "New application {$applicationNumber} from {$personalInfo['first_name']} {$personalInfo['last_name']}",
            ['application_id' => $application->id, 'application_number' => $applicationNumber]
        );

        // Send confirmation email
        try {
            Mail::to($personalInfo['email'])->send(new ApplicationReceived($application));
        } catch (\Exception $e) {
            // Log error but don't fail the submission
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('application.acknowledge', $application->id)
            ->with('success', 'Application submitted successfully!');
    }

    public function acknowledge(Application $application)
    {
        return view('frontend.acknowledge', compact('application'));
    }

    public function downloadAcknowledge(Application $application)
    {
        $pdf = \PDF::loadView('frontend.acknowledge-pdf', compact('application'));
        return $pdf->download('acknowledgement-' . $application->application_number . '.pdf');
    }
}