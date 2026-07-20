@extends('layouts.admin')

@section('title', 'Application Details')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Applications</a></li>
<li class="breadcrumb-item active">{{ $application->application_number }}</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Application: {{ $application->application_number }}</h4>
        <span class="text-muted">Submitted on {{ $application->created_at->format('F j, Y g:i A') }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.applications.print', $application->id) }}" class="btn btn-outline-primary" target="_blank">
            <i class="bi bi-printer me-2"></i>Print
        </a>
        <form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')">
                <i class="bi bi-trash me-2"></i>Delete
            </button>
        </form>
    </div>
</div>

<div class="row">
    <!-- Status Update -->
    <div class="col-lg-4">
        <div class="stat-card mb-4">
            <h5 class="mb-3">Update Status</h5>
            <form method="POST" action="{{ route('admin.applications.status', $application->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(\App\Models\Application::getStatuses() as $value => $label)
                        <option value="{{ $value }}" {{ $application->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $application->notes }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary-custom w-100">Update Status</button>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="stat-card mb-4">
            <h5 class="mb-3">Quick Actions</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                    <i class="bi bi-check2-circle me-2"></i>Send Acceptance Email
                </button>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#shortlistModal">
                    <i class="bi bi-check-circle me-2"></i>Send Shortlist Email
                </button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-2"></i>Send Rejection Email
                </button>
            </div>
        </div>

        <!-- Documents -->
        <div class="stat-card">
            <h5 class="mb-3">Uploaded Documents</h5>
            @forelse($application->documents as $doc)
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                <span><i class="bi bi-file-earmark me-2"></i>{{ $doc->document_type }}</span>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.documents.preview', $doc->id) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </div>
            @empty
            <p class="text-muted mb-0">No documents uploaded</p>
            @endforelse
            @if($application->documents->count() > 1)
            <div class="mt-3">
                <a href="{{ route('admin.applications.export', $application->id) }}" class="btn btn-outline-success w-100">
                    <i class="bi bi-download me-2"></i>Download All Documents
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Application Details -->
    <div class="col-lg-8">
        <div class="stat-card mb-4">
            <h5 class="mb-3">Personal Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Name:</strong> {{ $application->full_name }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Gender:</strong> {{ ucfirst($application->gender) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Date of Birth:</strong> {{ data_get($application->personal_info, 'date_of_birth', 'N/A') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Marital Status:</strong> {{ ucfirst(data_get($application->personal_info, 'marital_status', 'N/A')) }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Nationality:</strong> {{ data_get($application->personal_info, 'nationality', 'N/A') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>State of Origin:</strong> {{ $application->state }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Local Government:</strong> {{ data_get($application->personal_info, 'local_government', 'N/A') }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Email:</strong> {{ $application->email }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Phone:</strong> {{ $application->phone }}
                </div>
                <div class="col-12 mb-2">
                    <strong>Address:</strong> {{ data_get($application->personal_info, 'residential_address', 'N/A') }}
                </div>
            </div>
        </div>

        <div class="stat-card mb-4">
            <h5 class="mb-3">Academic Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Qualification:</strong> {{ $application->qualification }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Institution:</strong> {{ $application->academic_info['institution_attended'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Course:</strong> {{ $application->academic_info['course_studied'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Grade:</strong> {{ $application->academic_info['grade_class'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Graduation Year:</strong> {{ $application->academic_info['graduation_year'] ?? 'N/A' }}
                </div>
            </div>
        </div>

        @if($application->employment_info && ($application->employment_info['employer'] ?? null))
        <div class="stat-card mb-4">
            <h5 class="mb-3">Employment Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Employer:</strong> {{ $application->employment_info['employer'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Position:</strong> {{ $application->employment_info['position'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Years of Experience:</strong> {{ $application->employment_info['years_experience'] ?? 'N/A' }}
                </div>
            </div>
        </div>
        @endif

        <div class="stat-card">
            <h5 class="mb-3">Application Details</h5>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <strong>Position:</strong> {{ $application->application_details['position_applying_for'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Programme:</strong> {{ $application->application_details['programme_applying_for'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Department:</strong> {{ $application->application_details['department'] ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-2">
                    <strong>Category:</strong> {{ $application->application_details['category'] ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shortlist Modal -->
<div class="modal fade" id="shortlistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Shortlist Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.applications.shortlist', $application->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Interview Date</label>
                        <input type="date" name="interview_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interview Time</label>
                        <input type="time" name="interview_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Link (Optional)</label>
                        <input type="url" name="meeting_link" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Rejection Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.applications.reject', $application->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Message (Optional)</label>
                        <textarea name="message" class="form-control" rows="3" placeholder="Add a personalized message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Send Rejection Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Acceptance Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.applications.accept', $application->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Position/Role</label>
                        <input type="text" name="position" class="form-control" placeholder="e.g. Software Engineer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" placeholder="e.g. IT Department">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reporting Venue</label>
                        <input type="text" name="venue" class="form-control" placeholder="e.g. Main Office, Floor 3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Name of contact person">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" placeholder="hr@institution.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="+2341234567890">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional Message</label>
                        <textarea name="additional_message" class="form-control" rows="3" placeholder="Any additional information..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Send Acceptance Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection