<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo e($settings['portal_name'] ?? 'Application Portal'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php if($settings['favicon'] ?? false): ?>
    <link rel="icon" type="image/*" href="<?php echo e(asset($settings['favicon'])); ?>">
    <?php endif; ?>
    <style>
        :root {
            --primary-color: <?php echo e($settings['primary_color'] ?? '#38488e'); ?>;
            --secondary-color: <?php echo e($settings['secondary_color'] ?? '#4052a0'); ?>;
            --accent-color: <?php echo e($settings['accent_color'] ?? '#fcb900'); ?>;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        .login-image {
            <?php if($settings['login_background'] ?? false): ?>
            background: url('<?php echo e(asset($settings['login_background'])); ?>') center/cover;
            <?php else: ?>
            background: url('https://images.unsplash.com/photo-1562774053-701939374585?w=600') center/cover;
            <?php endif; ?>
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
            background: linear-gradient(135deg, rgba(56,72,142,0.9) 0%, rgba(64,82,160,0.7) 100%);
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
            border: 2px solid #e9ecef;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(56,72,142, 0.25);
        }
        .btn-login {
            background: var(--primary-color);
            color: white;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            border: none;
        }
        .btn-login:hover {
            background: var(--secondary-color);
            color: white;
        }
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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
                                <?php if($settings['logo'] ?? false): ?>
                                    <img src="<?php echo e(asset($settings['logo'])); ?>" alt="Logo" height="60" class="mb-3">
                                <?php else: ?>
                                    <i class="bi bi-mortarboard-fill fs-1 mb-3"></i>
                                <?php endif; ?>
                                <h2><?php echo e($settings['institution_name'] ?? 'Institution Name'); ?></h2>
                                <p>Online Application Portal</p>
                                <small><?php echo e($settings['portal_name'] ?? 'Application Portal'); ?></small>
                            </div>
                        </div>
                        <div class="col-lg-7 p-5">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold" style="color: var(--primary-color);">
                                    <i class="bi bi-person-badge me-2"></i>Admin Portal
                                </h3>
                                <p class="text-muted">Sign in to your account</p>
                            </div>

                            <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo e(route('admin.login')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-envelope" style="color: var(--primary-color);"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control border-start-0" placeholder="admin@example.com" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-lock" style="color: var(--primary-color);"></i>
                                        </span>
                                        <input type="password" name="password" class="form-control border-start-0" placeholder="Enter your password" required>
                                    </div>
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
                                <a href="<?php echo e(route('admin.forgot-password')); ?>" class="text-muted text-decoration-none" style="color: var(--primary-color);">
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
</html><?php /**PATH C:\Users\Dwealth\Documents\application\application-portal\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>