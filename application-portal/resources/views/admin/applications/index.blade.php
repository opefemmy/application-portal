@extends('layouts.admin')

@section('title', 'Applications')

@section('breadcrumbs')
<li class="breadcrumb-item active">Applications</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Applications</h4>
    <div>
        <button class="btn btn-outline-primary" onclick="exportData('csv')">
            <i class="bi bi-download me-2"></i>Export CSV
        </button>
        <button class="btn btn-outline-primary" onclick="exportData('excel')">
            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </button>
    </div>
</div>

<!-- Filters -->
<div class="stat-card mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, app number..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                <option value="interview_scheduled" {{ request('status') == 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="gender" class="form-select">
                <option value="">All Gender</option>
                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" placeholder="Date From" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" placeholder="Date To" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary-custom w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="stat-card mb-4">
    <form id="bulkActionForm" method="POST">
        @csrf
        <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="selectAll">
                <label class="form-check-label" for="selectAll">Select All</label>
            </div>
            <div class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Bulk Actions</option>
                    <option value="reviewed">Mark as Reviewed</option>
                    <option value="shortlisted">Mark as Shortlisted</option>
                    <option value="rejected">Mark as Rejected</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary-custom">Apply</button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkDelete()">
                    <i class="bi bi-trash"></i> Delete Selected
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Applications Table -->
<div class="table-container">
    <div class="table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th width="50"></th>
                    <th>App Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Gender</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                <tr>
                    <td>
                        <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" class="form-check-input">
                    </td>
                    <td><span class="fw-bold">{{ $application->application_number }}</span></td>
                    <td>{{ $application->full_name }}</td>
                    <td>{{ $application->email }}</td>
                    <td>{{ $application->phone }}</td>
                    <td>{{ $application->application_details['position_applying_for'] ?? 'N/A' }}</td>
                    <td>{{ ucfirst($application->gender) }}</td>
                    <td>
                        @switch($application->status)
                            @case('pending')
                                <span class="badge badge-pending">Pending</span>
                                @break
                            @case('reviewed')
                                <span class="badge badge-reviewed">Reviewed</span>
                                @break
                            @case('shortlisted')
                                <span class="badge badge-shortlisted">Shortlisted</span>
                                @break
                            @case('interview_scheduled')
                                <span class="badge badge-interview">Interview</span>
                                @break
                            @case('accepted')
                                <span class="badge badge-accepted">Accepted</span>
                                @break
                            @case('rejected')
                                <span class="badge badge-rejected">Rejected</span>
                                @break
                            @case('completed')
                                <span class="badge badge-completed">Completed</span>
                                @break
                        @endswitch
                    </td>
                    <td>{{ $application->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.applications.print', $application->id) }}" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank">
                                <i class="bi bi-printer"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.applications.destroy', $application->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No applications found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $applications->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Select all checkbox
    $('#selectAll').change(function() {
        $('input[name="application_ids[]"]').prop('checked', $(this).prop('checked'));
    });

    // Bulk status update
    $('#bulkActionForm').submit(function(e) {
        e.preventDefault();
        const selected = $('input[name="application_ids[]"]:checked').length;
        if (selected === 0) {
            alert('Please select at least one application');
            return;
        }
        if (confirm(`Update status for ${selected} application(s)?`)) {
            $.ajax({
                url: '{{ route("admin.applications.bulk-status") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        }
    });

    // Bulk delete
    function bulkDelete() {
        const selected = $('input[name="application_ids[]"]:checked').length;
        if (selected === 0) {
            alert('Please select at least one application');
            return;
        }
        if (confirm(`Delete ${selected} application(s)? This cannot be undone.`)) {
            $.ajax({
                url: '{{ route("admin.applications.bulk-delete") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    application_ids: $('input[name="application_ids[]"]:checked').map(function() { return this.value; }).get()
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    alert('An error occurred');
                }
            });
        }
    }

    // Export
    function exportData(format) {
        const params = new URLSearchParams(window.location.search);
        params.set('format', format);
        window.location.href = '{{ route("admin.applications.export") }}?' + params.toString();
    }
</script>
@endsection