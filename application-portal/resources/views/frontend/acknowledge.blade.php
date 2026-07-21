@extends('layouts.frontend')

@section('title', 'Application Submitted')

@section('styles')
<style>
    .acknowledge-card {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        position: relative;
    }
    .success-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--success) 0%, #2ecc71 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .application-details {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 20px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    /* Passport Photo in top right corner */
    .passport-container {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .passport-photo-box {
        width: 100px;
        height: 120px;
        background: white;
        padding: 5px;
        border-radius: 8px;
        border: 2px solid var(--primary);
    }
    .passport-photo-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* QR Code in top left corner */
    .qr-code-container {
        position: absolute;
        top: 15px;
        left: 15px;
    }
    .qr-code-box {
        width: 90px;
        height: 90px;
        background: white;
        padding: 5px;
        border-radius: 8px;
        border: 2px solid var(--primary);
    }
    .qr-code-box img {
        width: 100%;
        height: 100%;
    }
    /* Institution Header */
    .institution-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--primary);
    }
    .institution-logo {
        width: 70px;
        height: 70px;
        margin: 0 auto 10px;
        display: block;
        object-fit: contain;
    }
    .institution-name {
        font-size: 22px;
        font-weight: bold;
        color: var(--primary);
        margin-bottom: 3px;
    }
    .portal-title {
        font-size: 14px;
        color: #666;
    }
    /* Watermark background - behind content */
    .watermark-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .watermark-image {
        opacity: 0.06;
        max-width: 500px;
        max-height: 500px;
        width: 100%;
        height: auto;
    }
    .print-header {
        display: none;
        text-align: center;
        margin-bottom: 20px;
    }
    .print-header img {
        max-height: 60px;
        margin-bottom: 10px;
    }

    @media print {
        .watermark-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.15;
        }
        .watermark-image {
            max-width: 400px;
            max-height: 400px;
        }
        .acknowledge-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
        .no-print {
            display: none;
        }
        .print-header {
            display: block;
        }
    }
</style>
@endsection

@section('content')
<!-- Watermark Background - Behind Content -->
<div class="watermark-container">
    @php
    $watermarkPath = null;
    if (!empty($settings['logo'])) {
        $watermarkPath = asset($settings['logo']);
    } elseif (file_exists(public_path('images/logo.png'))) {
        $watermarkPath = asset('images/logo.png');
    } elseif (file_exists(public_path('images/logo.jpg'))) {
        $watermarkPath = asset('images/logo.jpg');
    }
    @endphp
    @if($watermarkPath)
    <img src="{{ $watermarkPath }}" alt="Watermark" class="watermark-image">
    @endif
</div>

<div class="section py-5">
    <div class="container">
        <div class="acknowledge-card p-5">
            <!-- Institution Header -->
            <div class="institution-header">
                @php
                $logoPath = null;
                if (!empty($settings['logo'])) {
                    $logoPath = asset($settings['logo']);
                } elseif (file_exists(public_path('images/logo.png'))) {
                    $logoPath = asset('images/logo.png');
                } elseif (file_exists(public_path('images/logo.jpg'))) {
                    $logoPath = asset('images/logo.jpg');
                }
                @endphp
                @if($logoPath)
                <img src="{{ $logoPath }}" alt="Institution Logo" class="institution-logo">
                @endif
                <div class="institution-name">{{ $settings['institution_name'] ?? 'EKSCOTECH' }}</div>
                <div class="portal-title">{{ $settings['portal_name'] ?? 'Application Portal' }}</div>
            </div>

            <!-- QR Code - Top Left -->
            <div class="qr-code-container no-print">
                <div class="qr-code-box">
                    @php
                    $qrData = 'APP:' . $application->application_number . '|' . $application->full_name . '|' . ($application->email ?? '');
                    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=' . urlencode($qrData);
                    @endphp
                    <img src="{{ $qrCodeUrl }}" alt="QR Code">
                </div>
            </div>

            <!-- Applicant Passport - Top Right -->
            <div class="passport-container no-print">
                <div class="passport-photo-box">
                    @php
                    $passportDoc = $application->documents->filter(function($doc) {
                        return stripos($doc->document_type, 'passport') !== false;
                    })->first();
                    $passportUrl = null;
                    if ($passportDoc && $passportDoc->file_path) {
                        // File is stored in storage/app/ - use Storage URL
                        $passportUrl = \Illuminate\Support\Facades\Storage::url($passportDoc->file_path);

                        // Check if file actually exists
                        $storagePath = storage_path('app/' . $passportDoc->file_path);
                        if (!file_exists($storagePath)) {
                            $passportUrl = null;
                        }
                    }
                    @endphp
                    @if($passportUrl)
                    <img src="{{ $passportUrl }}" alt="Passport Photo" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div style="width:100%;height:100%;display:none;align-items:center;justify-content:center;background:#f9f9f9;">
                        <span class="text-muted small">Photo unavailable</span>
                    </div>
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f9f9f9;">
                        <span class="text-muted small">No Photo</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="text-center mb-4 mt-4">
                <div class="success-icon mb-4">
                    <i class="bi bi-check-lg text-white fs-1"></i>
                </div>
                <h2 class="fw-bold text-success">Application Submitted Successfully!</h2>
                <p class="text-muted">Thank you for applying. Your application has been received.</p>
            </div>

            <div class="application-details mb-4">
                <h5 class="mb-3">Application Details</h5>
                <div class="detail-row">
                    <span class="text-muted">Application Number</span>
                    <span class="fw-bold text-primary">{{ $application->application_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Applicant Name</span>
                    <span class="fw-bold">{{ $application->full_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Email Address</span>
                    <span>{{ $application->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Phone Number</span>
                    <span>{{ $application->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Position/Programme Applied</span>
                    <span class="fw-bold">
                        @php
                        // Try multiple sources for position
                        $position = '';

                        // From application_details
                        $details = $application->application_details ?? [];
                        $position = $details['position_applying_for'] ?? $details['programme_applying_for'] ?? $details['position'] ?? '';

                        // From personal_info (fallback)
                        if (empty($position)) {
                            $position = data_get($application->personal_info, 'position_applying_for')
                                ?? data_get($application->personal_info, 'position')
                                ?? data_get($application->personal_info, 'programme');
                        }

                        echo !empty($position) ? e($position) : 'N/A';
                        @endphp
                    </span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Date Submitted</span>
                    <span>{{ $application->created_at->format('F j, Y - g:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-warning">{{ ucfirst($application->status) }}</span>
                </div>
            </div>

            @if(session('email_warning'))
            <div class="alert alert-warning">
                <h6><i class="bi bi-exclamation-triangle me-2"></i>Email Notice</h6>
                <p class="mb-0">{{ session('email_warning') }}</p>
            </div>
            @else
            <div class="alert alert-info">
                <h6><i class="bi bi-envelope me-2"></i>Check Your Email</h6>
                <p class="mb-0">A confirmation email has been sent to <strong>{{ $application->email }}</strong>. Please check your inbox (and spam folder) for further updates.</p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection