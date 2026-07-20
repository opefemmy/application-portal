@extends('layouts.frontend')

@section('title', 'Application Submitted')

@section('styles')
<style>
    .acknowledge-card {
        max-width: 650px;
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
    /* QR Code in top right corner */
    .qr-code-container {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .qr-code {
        background: white;
        padding: 10px;
        border-radius: 8px;
        border: 2px solid var(--primary);
    }
    .qr-code img {
        width: 80px;
        height: 80px;
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
    @if(!empty($settings['logo']))
    <img src="{{ asset($settings['logo']) }}" alt="Watermark" class="watermark-image">
    @elseif(file_exists(public_path('images/logo.png')))
    <img src="{{ asset('images/logo.png') }}" alt="Watermark" class="watermark-image">
    @endif
</div>

<div class="section py-5">
    <div class="container">
        <div class="acknowledge-card p-5">
            <!-- QR Code - Top Right -->
            <div class="qr-code-container no-print">
                <div class="qr-code">
                    <img src="https://api.qrserver.online/v1/create-qr-code/?size=100x100&data={{ urlencode(route('track') . '?app=' . $application->application_number) }}&bgcolor=FFFFFF&color=000000" alt="QR Code" onerror="this.style.display='none'">
                </div>
            </div>

            <!-- Print Header - Shows only when printing -->
            <div class="print-header">
                @if(!empty($settings['logo']))
                <img src="{{ asset($settings['logo']) }}" alt="Institution Logo">
                @endif
                <h4>{{ $settings['institution_name'] ?? 'Institution Name' }}</h4>
                <p>{{ $settings['portal_name'] ?? 'Online Application Portal' }}</p>
            </div>

            <div class="text-center mb-4">
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
                        $details = $application->application_details ?? [];
                        $position = $details['position_applying_for'] ?? '';
                        echo !empty($position) ? $position : 'N/A';
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

            <div class="text-center mb-4 no-print">
                <p class="small text-muted">Scan QR code or use application number to track your application</p>
            </div>

            <div class="alert alert-info">
                <h6><i class="bi bi-envelope me-2"></i>Check Your Email</h6>
                <p class="mb-0">A confirmation email has been sent to <strong>{{ $application->email }}</strong>. Please check your inbox (and spam folder) for further updates.</p>
            </div>

            <div class="d-grid gap-2 no-print">
                <button onclick="window.print()" class="btn btn-primary-custom">
                    <i class="bi bi-printer me-2"></i>Print Acknowledgement
                </button>
                <a href="{{ route('application.download', $application->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
                <a href="{{ route('track') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-search me-2"></i>Track Application
                </a>
            </div>
        </div>
    </div>
</div>
@endsection