@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Applications</p>
                    <h3 class="mb-0">{{ number_format($stats['total_applications']) }}</h3>
                </div>
                <div class="icon bg-primary text-white">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Today</p>
                    <h3 class="mb-0">{{ number_format($stats['today_applications']) }}</h3>
                </div>
                <div class="icon bg-success text-white">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">This Week</p>
                    <h3 class="mb-0">{{ number_format($stats['week_applications']) }}</h3>
                </div>
                <div class="icon bg-info text-white">
                    <i class="bi bi-calendar-week"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">This Month</p>
                    <h3 class="mb-0">{{ number_format($stats['month_applications']) }}</h3>
                </div>
                <div class="icon bg-warning text-white">
                    <i class="bi bi-calendar-month"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="icon bg-warning text-white mx-auto mb-2">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <h4>{{ number_format($stats['pending']) }}</h4>
            <p class="text-muted mb-0 small">Pending</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="icon bg-success text-white mx-auto mb-2">
                <i class="bi bi-check-circle"></i>
            </div>
            <h4>{{ number_format($stats['shortlisted']) }}</h4>
            <p class="text-muted mb-0 small">Shortlisted</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="icon bg-danger text-white mx-auto mb-2">
                <i class="bi bi-x-circle"></i>
            </div>
            <h4>{{ number_format($stats['rejected']) }}</h4>
            <p class="text-muted mb-0 small">Rejected</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="icon bg-primary text-white mx-auto mb-2">
                <i class="bi bi-gender-male"></i>
            </div>
            <h4>{{ number_format($stats['male_applicants']) }}</h4>
            <p class="text-muted mb-0 small">Male Applicants</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="icon bg-info text-white mx-auto mb-2">
                <i class="bi bi-gender-female"></i>
            </div>
            <h4>{{ number_format($stats['female_applicants']) }}</h4>
            <p class="text-muted mb-0 small">Female Applicants</p>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="stat-card">
            <h5 class="mb-4">Application Trends</h5>
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="stat-card">
            <h5 class="mb-4">By Gender</h5>
            <canvas id="genderChart"></canvas>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="mb-4">Applications by State</h5>
            <canvas id="stateChart" height="150"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="stat-card">
            <h5 class="mb-4">By Qualification</h5>
            <canvas id="qualificationChart" height="150"></canvas>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="row">
    <div class="col-12">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Recent Applications</h5>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-primary-custom">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>App Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentApplications as $application)
                        <tr>
                            <td><span class="fw-bold">{{ $application->application_number }}</span></td>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->email }}</td>
                            <td>{{ $application->application_details['position_applying_for'] ?? 'N/A' }}</td>
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
                                <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No recent applications</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Monthly Applications Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const monthlyData = @json($monthlyApplications);
    const months = monthlyData.map(m => {
        const date = new Date(m.year, m.month - 1);
        return date.toLocaleDateString('en-US', { month: 'short' });
    });
    const counts = monthlyData.map(m => m.count);

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Applications',
                data: counts,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gender Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [{{ $stats['male_applicants'] }}, {{ $stats['female_applicants'] }}],
                backgroundColor: ['#3498db', '#e74c3c']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // State Chart
    const stateCtx = document.getElementById('stateChart').getContext('2d');
    const stateData = @json($applicationsByState);
    new Chart(stateCtx, {
        type: 'bar',
        data: {
            labels: stateData.map(s => s.state || 'Unknown'),
            datasets: [{
                label: 'Applications',
                data: stateData.map(s => s.count),
                backgroundColor: '#1e3a5f'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Qualification Chart
    const qualCtx = document.getElementById('qualificationChart').getContext('2d');
    const qualData = @json($applicationsByQualification);
    new Chart(qualCtx, {
        type: 'pie',
        data: {
            labels: qualData.map(q => q.qualification || 'Unknown'),
            datasets: [{
                data: qualData.map(q => q.count),
                backgroundColor: ['#1e3a5f', '#3498db', '#27ae60', '#f39c12', '#e74c3c', '#9b59b6']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
</script>
@endsection