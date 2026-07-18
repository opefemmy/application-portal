<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
<li class="breadcrumb-item active">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1">Total Applications</p>
                    <h3 class="mb-0"><?php echo e(number_format($stats['total_applications'])); ?></h3>
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
                    <h3 class="mb-0"><?php echo e(number_format($stats['today_applications'])); ?></h3>
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
                    <h3 class="mb-0"><?php echo e(number_format($stats['week_applications'])); ?></h3>
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
                    <h3 class="mb-0"><?php echo e(number_format($stats['month_applications'])); ?></h3>
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
            <h4><?php echo e(number_format($stats['pending'])); ?></h4>
            <p class="text-muted mb-0 small">Pending</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="icon bg-success text-white mx-auto mb-2">
                <i class="bi bi-check-circle"></i>
            </div>
            <h4><?php echo e(number_format($stats['shortlisted'])); ?></h4>
            <p class="text-muted mb-0 small">Shortlisted</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card text-center">
            <div class="icon bg-danger text-white mx-auto mb-2">
                <i class="bi bi-x-circle"></i>
            </div>
            <h4><?php echo e(number_format($stats['rejected'])); ?></h4>
            <p class="text-muted mb-0 small">Rejected</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="icon bg-primary text-white mx-auto mb-2">
                <i class="bi bi-gender-male"></i>
            </div>
            <h4><?php echo e(number_format($stats['male_applicants'])); ?></h4>
            <p class="text-muted mb-0 small">Male Applicants</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <div class="icon bg-info text-white mx-auto mb-2">
                <i class="bi bi-gender-female"></i>
            </div>
            <h4><?php echo e(number_format($stats['female_applicants'])); ?></h4>
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
                <a href="<?php echo e(route('admin.applications.index')); ?>" class="btn btn-sm btn-primary-custom">View All</a>
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
                        <?php $__empty_1 = true; $__currentLoopData = $recentApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="fw-bold"><?php echo e($application->application_number); ?></span></td>
                            <td><?php echo e($application->full_name); ?></td>
                            <td><?php echo e($application->email); ?></td>
                            <td><?php echo e($application->application_details['position_applying_for'] ?? 'N/A'); ?></td>
                            <td>
                                <?php switch($application->status):
                                    case ('pending'): ?>
                                        <span class="badge badge-pending">Pending</span>
                                        <?php break; ?>
                                    <?php case ('reviewed'): ?>
                                        <span class="badge badge-reviewed">Reviewed</span>
                                        <?php break; ?>
                                    <?php case ('shortlisted'): ?>
                                        <span class="badge badge-shortlisted">Shortlisted</span>
                                        <?php break; ?>
                                    <?php case ('interview_scheduled'): ?>
                                        <span class="badge badge-interview">Interview</span>
                                        <?php break; ?>
                                    <?php case ('accepted'): ?>
                                        <span class="badge badge-accepted">Accepted</span>
                                        <?php break; ?>
                                    <?php case ('rejected'): ?>
                                        <span class="badge badge-rejected">Rejected</span>
                                        <?php break; ?>
                                    <?php case ('completed'): ?>
                                        <span class="badge badge-completed">Completed</span>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </td>
                            <td><?php echo e($application->created_at->format('M j, Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.applications.show', $application->id)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No recent applications</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Monthly Applications Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const monthlyData = <?php echo json_encode($monthlyApplications, 15, 512) ?>;
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
                data: [<?php echo e($stats['male_applicants']); ?>, <?php echo e($stats['female_applicants']); ?>],
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
    const stateData = <?php echo json_encode($applicationsByState, 15, 512) ?>;
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
    const qualData = <?php echo json_encode($applicationsByQualification, 15, 512) ?>;
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>