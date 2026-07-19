<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ $settings['portal_name'] ?? 'Application Portal' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if($settings['favicon'] ?? false)
    <link rel="icon" type="image/*" href="{{ asset($settings['favicon']) }}">
    @endif
    <style>
        :root {
            --primary-color: {{ $settings['primary_color'] ?? '#38488e' }};
            --secondary-color: {{ $settings['secondary_color'] ?? '#4052a0' }};
            --accent-color: {{ $settings['accent_color'] ?? '#fcb900' }};
        }
        body {
            font-family: 'Poppins', sans-serif;
            /* Glassmorphism background - subtle gradient */
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        /* Animated background shapes */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(56,72,142,0.15) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(64,82,160,0.1) 0%, transparent 50%);
            animation: float 25s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, 30px) rotate(5deg); }
        }
        .login-card {
            /* Glassmorphism effect */
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        .login-image {
            @if($settings['login_background'] ?? false)
            background: url('{{ asset($settings['login_background']) }}') center/cover;
            @else
            background: url('https://images.unsplash.com/photo-1562774053-701939374585?w=600') center/cover;
            @endif
            min-height: 400px;
            position: relative;
        }
        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(26,26,46,0.85) 0%, rgba(22,33,62,0.75) 100%);
        }
        .login-image .brand-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 2;
            color: white;
        }
        .login-image .brand-overlay h2 {
            font-weight: 700;
            font-size: 1.8rem;
        }
        .login-image .brand-overlay p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(252, 185, 0, 0.25);
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }
        .input-group-text {
            background: rgba(255, 255, 255, 0.08);
            border: 2px solid rgba(255, 255, 255, 0.15);
            border-right: none;
            color: rgba(255, 255, 255, 0.7);
        }
        .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(252, 185, 0, 0.25);
            background: rgba(255, 255, 255, 0.12);
            color: white;
        }
        .form-select option {
            background: #1a1a2e;
            color: white;
        }
        .form-label {
            color: rgba(255, 255, 255, 0.9);
        }
        .form-check-label {
            color: rgba(255, 255, 255, 0.8);
        }
        .btn-login {
            background: var(--accent-color);
            color: #1a1a2e;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            border: none;
        }
        .btn-login:hover {
            background: #e5a800;
            color: #1a1a2e;
        }
        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        .text-muted {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        .alert-success {
            background: rgba(25, 135, 84, 0.2);
            border: 1px solid rgba(25, 135, 84, 0.3);
            color: #fff;
        }
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="login-card">
                    <div class="row g-0">
                        <div class="col-lg-5 d-none d-lg-block login-image">
                            <div class="brand-overlay">
                                @if($settings['logo'] ?? false)
                                    <img src="{{ asset($settings['logo']) }}" alt="Logo" style="max-height: 80px;" class="mb-3">
                                @else
                                    <i class="bi bi-mortarboard-fill fs-1 mb-3"></i>
                                @endif
                                <h2>{{ $settings['institution_name'] ?? 'Institution Name' }}</h2>
                                <p>Online Application Portal</p>
                                <small>{{ $settings['portal_name'] ?? 'Application Portal' }}</small>
                            </div>
                        </div>
                        <div class="col-lg-7 p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold" style="color: white;">
                                    <i class="bi bi-person-badge me-2"></i>Admin Portal
                                </h3>
                                <p class="text-muted">Sign in to your account</p>
                            </div>

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

                            <form method="POST" action="{{ route('admin.login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror" placeholder="admin@example.com" required value="{{ old('email') }}">
                                    </div>
                                    @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" name="password" class="form-control border-start-0 @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                                    </div>
                                    @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>
                                <button type="submit" class="btn btn-login w-100 mb-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                </button>
                            </form>

                            <div class="text-center">
                                <a href="{{ route('admin.forgot-password') }}" class="text-muted text-decoration-none" style="color: var(--accent-color);">
                                    <i class="bi bi-question-circle me-1"></i>Forgot your password?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>