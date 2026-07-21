<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ActivityLog;
use App\Models\Interview;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationReceived;
use App\Mail\ShortlistEmail;
use App\Mail\RejectionEmail;
use App\Mail\AcceptanceEmail;
use Carbon\Carbon;
use ZipArchive;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with('documents');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('personal_info->last_name', 'like', "%{$search}%")
                    ->orWhere('personal_info->first_name', 'like', "%{$search}%")
                    ->orWhere('personal_info->email', 'like', "%{$search}%")
                    ->orWhere('personal_info->phone_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('gender') && $request->gender) {
            $query->where('personal_info->gender', $request->gender);
        }

        if ($request->has('state') && $request->state) {
            $query->where('personal_info->state_of_origin', $request->state);
        }

        if ($request->has('qualification') && $request->qualification) {
            $query->where('academic_info->highest_qualification', $request->qualification);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        $applications = $query->paginate(20);

        $states = Application::distinct()
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(personal_info, "$.state_of_origin")) as state')
            ->pluck('state')
            ->filter()
            ->values();

        $qualifications = Application::distinct()
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(academic_info, "$.highest_qualification")) as qualification')
            ->pluck('qualification')
            ->filter()
            ->values();

        return view('admin.applications.index', compact('applications', 'states', 'qualifications'));
    }

    public function show(Application $application)
    {
        $application->load('documents', 'interview');
        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview_scheduled,accepted,rejected,completed',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $application->status;
        $application->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        ActivityLog::log('status_update', "Application status updated from {$oldStatus} to {$request->status}", ['status' => $oldStatus], ['status' => $request->status]);

        return back()->with('success', 'Application status updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'status' => 'required|in:pending,reviewed,shortlisted,interview_scheduled,accepted,rejected,completed',
        ]);

        Application::whereIn('id', $request->application_ids)->update([
            'status' => $request->status,
        ]);

        ActivityLog::log('bulk_status_update', "Updated status of " . count($request->application_ids) . " applications to {$request->status}");

        return back()->with('success', 'Applications updated successfully.');
    }

    public function sendShortlistEmail(Request $request, Application $application)
    {
        $request->validate([
            'interview_date' => 'required|date',
            'interview_time' => 'required',
            'venue' => 'required|string',
            'instructions' => 'nullable|string',
        ]);

        // Create interview
        Interview::updateOrCreate(
            ['application_id' => $application->id],
            [
                'interview_date' => $request->interview_date,
                'interview_time' => $request->interview_time,
                'venue' => $request->venue,
                'panel' => $request->panel ?? 'Admissions Panel',
                'meeting_link' => $request->meeting_link,
                'notes' => $request->instructions,
            ]
        );

        // Update status
        $application->update(['status' => 'interview_scheduled']);

        // Send email
        try {
            $email = $application->email;
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid or missing email address for this applicant.');
            }
            Mail::to($email)->send(new ShortlistEmail($application, $request->all()));
            ActivityLog::log('email_sent', "Shortlist email sent to {$email}");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return back()->with('success', 'Interview scheduled and email sent successfully.');
    }

    public function sendRejectionEmail(Request $request, Application $application)
    {
        $request->validate([
            'message' => 'nullable|string',
        ]);

        try {
            $email = $application->email;
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid or missing email address for this applicant.');
            }
            Mail::to($email)->send(new RejectionEmail($application, $request->message));
            $application->update(['status' => 'rejected']);
            ActivityLog::log('email_sent', "Rejection email sent to {$email}");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return back()->with('success', 'Rejection email sent successfully.');
    }

    public function sendAcceptanceEmail(Request $request, Application $application)
    {
        $request->validate([
            'position' => 'nullable|string',
            'department' => 'nullable|string',
            'start_date' => 'nullable|date',
            'venue' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'additional_message' => 'nullable|string',
        ]);

        try {
            $email = $application->email;
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', 'Invalid or missing email address for this applicant.');
            }
            Mail::to($email)->send(new AcceptanceEmail($application, $request->all()));
            $application->update(['status' => 'accepted']);
            ActivityLog::log('email_sent', "Acceptance email sent to {$email}");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        return back()->with('success', 'Acceptance email sent successfully.');
    }

    public function closeApplication(Request $request, Application $application)
    {
        $request->validate([
            'close_reason' => 'required|string|max:255',
            'close_notes' => 'nullable|string',
        ]);

        // Build notes with close reason
        $notes = '【CLOSED】Reason: ' . $request->close_reason;
        if ($request->close_notes) {
            $notes .= "\nNotes: " . $request->close_notes;
        }

        // Append to existing notes if any
        $existingNotes = $application->notes;
        if ($existingNotes) {
            $notes = $existingNotes . "\n\n" . $notes;
        }

        // Update application status and notes
        $application->update([
            'status' => 'closed',
            'notes' => $notes,
        ]);

        ActivityLog::log('update', "Application closed - Reason: {$request->close_reason}");

        return back()->with('success', 'Application has been closed successfully.');
    }

    public function downloadDocument(ApplicationDocument $document)
    {
        // Check if file exists
        $filePath = $document->file_path;

        // Try both local storage paths
        if (!Storage::exists($filePath)) {
            // Try with 'applications/' prefix
            if (!Storage::exists('applications/' . $filePath)) {
                abort(404, 'File not found: ' . $filePath);
            }
            $filePath = 'applications/' . $filePath;
        }

        ActivityLog::log('download', "Downloaded document: {$document->file_name}");

        return Storage::download($filePath, $document->file_name);
    }

    public function previewDocument(ApplicationDocument $document)
    {
        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found');
        }

        $mime = $document->mime_type;

        if (str_starts_with($mime, 'image/') || $mime === 'application/pdf') {
            return response()->file(storage_path('app/' . $document->file_path));
        }

        return redirect()->route('admin.applications.documents.download', $document);
    }

    public function export(Request $request)
    {
        $query = Application::with('documents');

        // Apply same filters as index
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('personal_info->last_name', 'like', "%{$search}%")
                    ->orWhere('personal_info->first_name', 'like', "%{$search}%")
                    ->orWhere('personal_info->email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        $format = $request->format ?? 'csv';

        if ($format === 'excel') {
            return $this->exportExcel($applications);
        }

        return $this->exportCsv($applications);
    }

    private function exportCsv($applications)
    {
        $headers = [
            'Application Number' => 'application_number',
            'First Name' => 'personal_info->first_name',
            'Last Name' => 'personal_info->last_name',
            'Email' => 'personal_info->email',
            'Phone' => 'personal_info->phone_number',
            'Gender' => 'personal_info->gender',
            'State' => 'personal_info->state_of_origin',
            'Qualification' => 'academic_info->highest_qualification',
            'Status' => 'status',
            'Date' => 'created_at',
        ];

        $filename = 'applications_' . date('Y-m-d') . '.csv';

        $callback = function () use ($applications, $headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($headers));

            foreach ($applications as $app) {
                $row = [];
                foreach ($headers as $header => $key) {
                    $value = data_get($app, $key);
                    if ($key === 'created_at' && $value) {
                        $value = Carbon::parse($value)->format('Y-m-d H:i:s');
                    }
                    $row[] = $value ?? '';
                }
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    private function exportExcel($applications)
    {
        // Simple CSV export for Excel (can be replaced with Maatwebsite Excel)
        return $this->exportCsv($applications);
    }

    public function print(Application $application)
    {
        $application->load('documents');
        $settings = \App\Models\Setting::getSettings();
        $showEmployment = true;
        return view('admin.applications.print', compact('application', 'settings', 'showEmployment'));
    }

    public function destroy(Application $application)
    {
        // Delete documents
        foreach ($application->documents as $document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        }

        ActivityLog::log('delete', "Application {$application->application_number} deleted", $application->toArray());

        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', 'Application deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
        ]);

        $count = count($request->application_ids);

        foreach ($request->application_ids as $id) {
            $application = Application::find($id);
            if ($application) {
                foreach ($application->documents as $document) {
                    if (Storage::exists($document->file_path)) {
                        Storage::delete($document->file_path);
                    }
                }
                $application->delete();
            }
        }

        ActivityLog::log('bulk_delete', "Deleted {$count} applications");

        return back()->with('success', "{$count} applications deleted successfully.");
    }
}