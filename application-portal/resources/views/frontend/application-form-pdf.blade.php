<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ $application->application_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 15px;
            background: #fff;
        }
        .page {
            max-width: 210mm;
            margin: 0 auto;
            background: #fff;
        }
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
        .form-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2c3e50;
        }
        .header-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 10px;
            object-fit: contain;
        }
        .institution-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .portal-name {
            font-size: 12px;
            color: #555;
        }
        .app-number {
            text-align: center;
            background: #f0f0f0;
            padding: 10px;
            margin-bottom: 15px;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .app-number strong {
            font-size: 14px;
        }
        .content-wrapper {
            display: flex;
            gap: 20px;
        }
        .main-content {
            flex: 1;
        }
        .passport-section {
            width: 130px;
            flex-shrink: 0;
            text-align: center;
        }
        .passport-photo {
            width: 120px;
            height: 150px;
            border: 2px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
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
        }
        .section {
            margin-bottom: 15px;
            clear: both;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin-bottom: 8px;
            text-transform: uppercase;
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
            width: 35%;
            padding: 3px 5px 3px 0;
            font-weight: 600;
            color: #444;
            background: #f8f8f8;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }
        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 25px;
            padding-top: 5px;
            font-size: 10px;
        }
        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Watermark Background -->
        <div class="watermark">
            @php
            $watermarkPath = null;
            if (!empty($settings['logo'])) {
                $watermarkPath = public_path($settings['logo']);
            } elseif (file_exists(public_path('images/logo.png'))) {
                $watermarkPath = public_path('images/logo.png');
            } elseif (file_exists(public_path('images/logo.jpg'))) {
                $watermarkPath = public_path('images/logo.jpg');
            }
            @endphp
            @if($watermarkPath)
            <img src="{{ $watermarkPath }}" alt="Watermark">
            @endif
        </div>

        <!-- Header with Logo and Institution Name -->
        <div class="form-header">
            @php
            $logoPath = null;
            if (!empty($settings['logo'])) {
                $logoPath = public_path($settings['logo']);
            } elseif (file_exists(public_path('images/logo.png'))) {
                $logoPath = public_path('images/logo.png');
            } elseif (file_exists(public_path('images/logo.jpg'))) {
                $logoPath = public_path('images/logo.jpg');
            }
            @endphp
            @if($logoPath)
            <img src="{{ $logoPath }}" alt="Institution Logo" class="header-logo">
            @endif
            <div class="institution-name">{{ $settings['institution_name'] ?? 'EKSCOTECH' }}</div>
            <div class="portal-name">{{ $settings['portal_name'] ?? 'Application Portal' }}</div>
        </div>

        <div class="app-number">
            Application Number: {{ $application->application_number }}<br>
            <span style="font-size:10px;text-transform:uppercase;">Status: {{ $application->status }}</span>
        </div>

        <div class="content-wrapper">
            <div class="main-content">
                <!-- Personal Information -->
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
                            <div class="info-value">{{ data_get($application->personal_info, 'date_of_birth', 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Marital Status:</div>
                            <div class="info-value">{{ ucfirst(data_get($application->personal_info, 'marital_status', 'N/A')) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nationality:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'nationality', 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">State of Origin:</div>
                            <div class="info-value">{{ $application->state }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Local Government Area:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'local_government', 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Residential Address:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'residential_address', 'N/A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Address:</div>
                            <div class="info-value">{{ $application->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone Number:</div>
                            <div class="info-value">{{ $application->phone }}</div>
                        </div>
                        @if(data_get($application->personal_info, 'alternative_phone'))
                        <div class="info-row">
                            <div class="info-label">Alternative Phone:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'alternative_phone') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="section">
                    <div class="section-title">Academic Information</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Highest Qualification:</div>
                            <div class="info-value">{{ $application->qualification }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Institution Attended:</div>
                            <div class="info-value">{{ $application->academic_info['institution_attended'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Course Studied:</div>
                            <div class="info-value">{{ $application->academic_info['course_studied'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Grade/Class:</div>
                            <div class="info-value">{{ $application->academic_info['grade_class'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Year of Graduation:</div>
                            <div class="info-value">{{ $application->academic_info['graduation_year'] ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                @if($application->employment_info && count($application->employment_info) > 0)
                <div class="section">
                    <div class="section-title">Employment History</div>
                    <div class="info-grid">
                        @foreach($application->employment_info as $emp)
                        <div class="info-row">
                            <div class="info-label">Employer:</div>
                            <div class="info-value">{{ $emp['employer'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Position:</div>
                            <div class="info-value">{{ $emp['position'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Years of Experience:</div>
                            <div class="info-value">{{ $emp['years_experience'] ?? '0' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Application Details -->
                <div class="section">
                    <div class="section-title">Application Details</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Position/Programme Applied:</div>
                            <div class="info-value">
                                @php
                                $position = '';
                                $details = $application->application_details ?? [];
                                $position = $details['position_applying_for'] ?? $details['programme_applying_for'] ?? $details['position'] ?? '';
                                if (empty($position)) {
                                    $position = data_get($application->personal_info, 'position_applying_for')
                                        ?? data_get($application->personal_info, 'position')
                                        ?? data_get($application->personal_info, 'programme');
                                }
                                echo !empty($position) ? $position : 'N/A';
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Documents -->
                @if($application->documents->count() > 0)
                <div class="section">
                    <div class="section-title">Uploaded Documents</div>
                    <div class="info-grid">
                        @foreach($application->documents as $doc)
                        <div class="info-row">
                            <div class="info-label">{{ $doc->document_type }}:</div>
                            <div class="info-value">{{ $doc->file_name }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Declaration & Signature -->
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-line">Applicant's Signature</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line">Date</div>
                    </div>
                </div>
            </div>

            <!-- Passport Photo -->
            <div class="passport-section">
                <div class="passport-photo">
                    @php
                    $passportDoc = $application->documents->filter(function($doc) {
                        return stripos($doc->document_type, 'passport') !== false;
                    })->first();
                    @endphp
                    @if($passportDoc)
                    <img src="{{ public_path('storage/' . $passportDoc->file_path) }}" alt="Passport Photo">
                    @else
                    <span style="font-size:10px;color:#999;">No Photo</span>
                    @endif
                </div>
                <div class="passport-label">Passport Photo</div>
            </div>
        </div>

        <div style="text-align:center;font-size:9px;color:#888;margin-top:20px;">
            Generated on {{ now()->format('F j, Y') }} | {{ $settings['portal_name'] ?? 'Application Portal' }}
        </div>
    </div>
</body>
</html>