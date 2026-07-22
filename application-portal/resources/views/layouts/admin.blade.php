<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ $settings['portal_name'] ?? 'Application Portal' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <style>
        :root {
            --primary: {{ $settings['primary_color'] ?? '#38488e' }};
            --secondary: {{ $settings['secondary_color'] ?? '#4052a0' }};
            --accent: {{ $settings['accent_color'] ?? '#fcb900' }};
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #c0392b;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --bg-light: #f8f9fa;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, #1a2c42 100%);
            padding-top: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar-menu {
            padding: 15px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--accent);
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 10px 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .top-bar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .notification-badge {
            position: relative;
        }

        .notification-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table thead th {
            background: var(--light);
            border-bottom: 2px solid var(--primary);
            color: var(--dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            background: #152a45;
            transform: translateY(-2px);
        }

        .btn-accent {
            background: var(--accent);
            color: white;
            border: none;
        }

        .btn-accent:hover {
            background: #c0392b;
            color: white;
        }

        /* Badges */
        .badge-pending { background: var(--warning); color: white; }
        .badge-reviewed { background: var(--secondary); color: white; }
        .badge-shortlisted { background: var(--success); color: white; }
        .badge-interview { background: #9b59b6; color: white; }
        .badge-accepted { background: #27ae60; color: white; }
        .badge-rejected { background: var(--danger); color: white; }
        .badge-completed { background: var(--primary); color: white; }

        /* Forms */
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        /* Modal */
        .modal-header {
            background: var(--primary);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        /* Fix button focus state to prevent blinking */
        .btn:focus {
            outline: none;
            box-shadow: none;
        }

        .btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            @if(($settings['logo'] ?? false))
                <img src="{{ asset($settings['logo']) }}" alt="Logo" style="height: 40px;" class="mb-2">
            @else
                <h4><i class="bi bi-mortarboard-fill me-2"></i>EKSCOTECH</h4>
            @endif
            <small style="color: var(--accent);">Admin Portal</small>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.applications.index') }}" class="sidebar-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Applications
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell"></i> Notifications
            </a>

            <div class="sidebar-divider"></div>

            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> General Settings
            </a>

            <a href="{{ route('admin.settings.users') }}" class="sidebar-link {{ request()->routeIs('admin.settings.users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>

            <a href="{{ route('admin.settings.roles') }}" class="sidebar-link {{ request()->routeIs('admin.settings.roles*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i> Roles
            </a>

            <a href="{{ route('admin.settings.application-types') }}" class="sidebar-link {{ request()->routeIs('admin.settings.application-types*') ? 'active' : '' }}">
                <i class="bi bi-files-alt"></i> Application Types
            </a>

            <a href="{{ route('admin.settings.branding') }}" class="sidebar-link {{ request()->routeIs('admin.settings.branding*') ? 'active' : '' }}">
                <i class="bi bi-palette"></i> Branding
            </a>

            <a href="{{ route('admin.settings.pages') }}" class="sidebar-link {{ request()->routeIs('admin.settings.pages*') ? 'active' : '' }}">
                <i class="bi bi-layout-text-window-reverse"></i> Edit Pages
            </a>

            <a href="{{ route('admin.settings.form-builder') }}" class="sidebar-link {{ request()->routeIs('admin.settings.form-builder*') ? 'active' : '' }}">
                <i class="bi bi-ui-checks"></i> Form Builder
            </a>

            <a href="{{ route('admin.settings.programmes') }}" class="sidebar-link {{ request()->routeIs('admin.settings.programmes*') ? 'active' : '' }}">
                <i class="bi bi-bookmark-check"></i> Programmes
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="btn d-lg-none me-3" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center">
                <div class="notification-badge me-4">
                    <a href="{{ route('admin.notifications.index') }}" class="text-dark position-relative">
                        <i class="bi bi-bell fs-5"></i>
                        @if($unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadNotifications->count() }}
                        </span>
                        @endif
                    </a>
                </div>
                <div class="dropdown">
                    <a class="dropdown-toggle d-flex align-items-center text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="avatar me-2">
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Sidebar toggle
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('show');
        });

        // Initialize DataTables
        $('.data-table').DataTable({
            processing: true,
            responsive: true,
            pageLength: 10,
        });

        // CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('scripts')
</body>
</html>