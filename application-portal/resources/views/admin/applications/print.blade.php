<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Print - {{ $application->application_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
            position: relative;
            background: #fff;
        }

        /* Watermark background */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            opacity: 0.08;
            z-index: -1;
            pointer-events: none;
        }
        .watermark img {
            width: 100%;
            height: auto;
        }

        /* Header with logo and institution name */
        .print-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }
        .institution-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            display: block;
        }
        .institution-logo img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        .institution-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .portal-name {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        /* Application number section */
        .application-number {
            background: #f8f9fa;
            padding: 15px 20px;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        .application-number .app-num {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 1px;
        }
        .application-number .status-badge {
            display: inline-block;
            padding: 4px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 8px 0;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-reviewed { background: #cce5ff; color: #004085; }
        .status-shortlisted { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-accepted { background: #28a745; color: #fff; }
        .status-interview_scheduled { background: #6c757d; color: #fff; }
        .status-completed { background: #17a2b8; color: #fff; }

        .application-number .date-info {
            font-size: 10px;
            color: #777;
            margin-top: 5px;
        }

        /* Main content layout with passport */
        .content-wrapper {
            display: flex;
            gap: 25px;
        }

        .main-content {
            flex: 1;
        }

        /* Passport photo section */
        .passport-section {
            width: 150px;
            flex-shrink: 0;
            text-align: center;
        }
        .passport-photo {
            width: 140px;
            height: 170px;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .passport-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .passport-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #2c3e50;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 38%;
            padding: 5px 10px 5px 0;
            font-weight: 600;
            color: #555;
            background: #f8f9fa;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
            border-bottom: 1px dotted #dee2e6;
        }

        .documents-section {
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .document-item {
            padding: 10px;
            border: 1px solid #dee2e6;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
        }

        .print-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .watermark { opacity: 0.1; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Watermark Background -->
    <div class="watermark">
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
        <img src="{{ $watermarkPath }}" alt="Watermark">
        @endif
    </div>

    <!-- Header with Logo and Institution Name -->
    <div class="print-header">
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
        <div class="institution-logo">
            <img src="{{ $logoPath }}" alt="Institution Logo">
        </div>
        @endif
        <div class="institution-name">{{ $settings['institution_name'] ?? 'Institution Name' }}</div>
        <div class="portal-name">{{ $settings['portal_name'] ?? 'Application Portal' }}</div>
    </div>

    <!-- Application Number -->
    <div class="application-number">
        <div class="app-num">Application Number: {{ $application->application_number }}</div>
        <span class="status-badge status-{{ $application->status }}">{{ str_replace('_', ' ', ucwords($application->status, '_')) }}</span>
        <div class="date-info">Submitted on {{ $application->created_at->format('F j, Y g:i A') }}</div>
    </div>

    <!-- Content with Passport Photo -->
    <div class="content-wrapper">
        <!-- Main Information -->
        <div class="main-content">
            <div class="section">
                <div class="section-title">Personal Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">{{ $application->full_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Gender:</div>
                        <div class="info-value">{{ ucfirst($application->gender) }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value">{{ $application->date_of_birth ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Marital Status:</div>
                        <div class="info-value">{{ ucfirst($application->marital_status) ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nationality:</div>
                        <div class="info-value">{{ $application->nationality ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">State of Origin:</div>
                        <div class="info-value">{{ $application->state }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Local Government:</div>
                        <div class="info-value">{{ $application->local_government ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $application->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone:</div>
                        <div class="info-value">{{ $application->phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Residential Address:</div>
                        <div class="info-value">{{ $application->residential_address ?: 'Not Provided' }}</div>
                    </div>
                    @if($application->alternative_phone)
                    <div class="info-row">
                        <div class="info-label">Alternative Phone:</div>
                        <div class="info-value">{{ $application->alternative_phone }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="section">
                <div class="section-title">Academic Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Qualification:</div>
                        <div class="info-value">{{ $application->qualification ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Institution:</div>
                        <div class="info-value">{{ $application->institution_attended ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Course:</div>
                        <div class="info-value">{{ $application->course_studied ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Grade:</div>
                        <div class="info-value">{{ $application->grade_class ?: 'Not Provided' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Graduation Year:</div>
                        <div class="info-value">{{ $application->graduation_year ?: 'Not Provided' }}</div>
                    </div>
                </div>
            </div>

            @if($application->employment_info && ($application->employment_info['employer'] ?? null))
            <div class="section">
                <div class="section-title">Employment Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Employer:</div>
                        <div class="info-value">{{ $application->employment_info['employer'] ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Position:</div>
                        <div class="info-value">{{ $application->employment_info['position'] ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Years of Experience:</div>
                        <div class="info-value">{{ $application->employment_info['years_experience'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="section">
                <div class="section-title">Application Details</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Position Applying For:</div>
                        <div class="info-value">{{ $application->position_applying_for ?: 'Not Specified' }}</div>
                    </div>
                </div>
            </div>

            @if($application->documents->count() > 0)
            <div class="section documents-section">
                <div class="section-title">Uploaded Documents</div>
                @foreach($application->documents as $doc)
                <div class="document-item">
                    <span>{{ $doc->document_type }}</span>
                    <span>{{ $doc->file_name }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Passport Photo -->
        <div class="passport-section">
            <div class="passport-photo">
                @php
                // Search for passport photo - case insensitive
                $passportDoc = $application->documents->filter(function($doc) {
                    return stripos($doc->document_type, 'passport') !== false;
                })->first();
                $passportImg = null;
                if ($passportDoc) {
                    $storagePath = storage_path('app/' . $passportDoc->file_path);
                    if (file_exists($storagePath)) {
                        $passportImg = 'data:' . ($passportDoc->mime_type ?? 'image/jpeg') . ';base64,' . base64_encode(file_get_contents($storagePath));
                    }
                }
                @endphp
                @if($passportImg)
                <img src="{{ $passportImg }}" alt="Passport Photo">
                @else
                <span style="color: #999; font-size: 11px;">No Photo</span>
                @endif
            </div>
            <div class="passport-label">Passport Photo</div>
        </div>
    </div>

    <div class="print-footer">
        <p>This is a computer-generated document. No signature required.</p>
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>
</body>
</html>