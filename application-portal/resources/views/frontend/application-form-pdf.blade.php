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
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            padding: 10px;
            background: #fff;
        }
        .page {
            max-width: 210mm;
            margin: 0 auto;
            background: #fff;
            position: relative;
        }

        /* Project Colors */
        :root {
            --primary: #82103c;
            --secondary: #247d57;
            --accent: #a48613;
        }

        /* Watermark Background */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 350px;
            opacity: 0.06;
            z-index: -1;
            pointer-events: none;
        }
        .watermark img {
            width: 100%;
            height: auto;
        }

        /* Header */
        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 3px;
        }
        .header-text h1 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .header-text p {
            font-size: 9px;
            opacity: 0.9;
        }

        /* QR Code Section */
        .header-right {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }
        .qr-code {
            width: 70px;
            height: 70px;
            background: white;
            padding: 3px;
            border-radius: 5px;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
        }
        .app-number-badge {
            background: var(--accent);
            color: #fff;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        /* Main Content Layout */
        .content-wrapper {
            display: flex;
            gap: 15px;
        }
        .main-content {
            flex: 1;
        }

        /* Passport Section - Top Right */
        .passport-section {
            width: 110px;
            flex-shrink: 0;
            text-align: center;
            position: absolute;
            top: 80px;
            right: 15px;
        }
        .passport-photo {
            width: 100px;
            height: 120px;
            border: 3px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            overflow: hidden;
            margin-bottom: 5px;
        }
        .passport-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Info Grid */
        .section {
            margin-bottom: 12px;
            clear: both;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background: var(--primary);
            color: white;
            padding: 4px 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
            border-radius: 3px;
        }
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 32%;
            padding: 3px 5px 3px 0;
            font-weight: 600;
            color: var(--primary);
            background: #f8f8f8;
            border-bottom: 1px solid #eee;
        }
        .info-value {
            display: table-cell;
            padding: 3px 0;
            border-bottom: 1px solid #eee;
        }

        /* Signature */
        .signature-section {
            margin-top: 25px;
            display: table;
            width: 100%;
            border-top: 2px solid var(--secondary);
            padding-top: 10px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 20px;
            padding-top: 5px;
            font-size: 9px;
        }

        /* Footer */
        .form-footer {
            text-align: center;
            font-size: 8px;
            color: #888;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        @page {
            size: A4;
            margin: 8mm;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Watermark Background -->
        <div class="watermark">
            @php
            $watermarkPath = null;
            // Check settings logo first
            if (!empty($settings['logo'])) {
                $watermarkPath = $settings['logo'];
            }
            // Fallback to common logo locations
            if (!$watermarkPath || !file_exists(public_path($watermarkPath))) {
                if (file_exists(public_path('images/logo.png'))) {
                    $watermarkPath = 'images/logo.png';
                } elseif (file_exists(public_path('images/logo.jpg'))) {
                    $watermarkPath = 'images/logo.jpg';
                } elseif (file_exists(public_path('images/logo.jpeg'))) {
                    $watermarkPath = 'images/logo.jpeg';
                }
            }
            @endphp
            @if($watermarkPath && file_exists(public_path($watermarkPath)))
            <img src="{{ asset($watermarkPath) }}" alt="Watermark">
            @endif
        </div>

        <!-- Header with Logo, Institution Name & QR Code -->
        <div class="form-header">
            <div class="header-left">
                @php
                $logoPath = null;
                // Check settings logo first
                if (!empty($settings['logo'])) {
                    $logoPath = $settings['logo'];
                }
                // Fallback to common logo locations
                if (!$logoPath || !file_exists(public_path($logoPath))) {
                    if (file_exists(public_path('images/logo.png'))) {
                        $logoPath = 'images/logo.png';
                    } elseif (file_exists(public_path('images/logo.jpg'))) {
                        $logoPath = 'images/logo.jpg';
                    } elseif (file_exists(public_path('images/logo.jpeg'))) {
                        $logoPath = 'images/logo.jpeg';
                    }
                }
                @endphp
                @if($logoPath && file_exists(public_path($logoPath)))
                <img src="{{ asset($logoPath) }}" alt="Institution Logo" class="header-logo">
                @endif
                <div class="header-text">
                    <h1>{{ $settings['institution_name'] ?? 'EKSCOTECH' }}</h1>
                    <p>{{ $settings['portal_name'] ?? 'Online Application Portal' }}</p>
                </div>
            </div>
            <div class="header-right">
                @php
                // Generate QR code data
                $qrData = json_encode([
                    'app' => $application->application_number,
                    'name' => $application->full_name,
                    'status' => $application->status
                ]);
                $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($qrData);
                @endphp
                <div class="qr-code">
                    <img src="{{ $qrCodeUrl }}" alt="QR Code">
                </div>
                <div class="app-number-badge">{{ $application->application_number }}</div>
            </div>
        </div>

        <!-- Passport Photo - Top Right -->
        <div class="passport-section">
            <div class="passport-photo">
                @php
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
                <span style="font-size:9px;color:#999;">Not Uploaded</span>
                @endif
            </div>
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
                            <div class="info-value">{{ data_get($application->personal_info, 'date_of_birth') ?: 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Marital Status:</div>
                            <div class="info-value">{{ ucfirst(data_get($application->personal_info, 'marital_status')) ?: 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Nationality:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'nationality') ?: 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">State of Origin:</div>
                            <div class="info-value">{{ $application->state }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Local Government Area:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'local_government') ?: 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Residential Address:</div>
                            <div class="info-value">{{ data_get($application->personal_info, 'residential_address') ?: 'Not Provided' }}</div>
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
                            <div class="info-value">{{ $application->qualification ?: 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Institution Attended:</div>
                            <div class="info-value">{{ $application->academic_info['institution_attended'] ?? 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Course Studied:</div>
                            <div class="info-value">{{ $application->academic_info['course_studied'] ?? 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Grade/Class:</div>
                            <div class="info-value">{{ $application->academic_info['grade_class'] ?? 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Year of Graduation:</div>
                            <div class="info-value">{{ $application->academic_info['graduation_year'] ?? 'Not Provided' }}</div>
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
                            <div class="info-value">{{ $emp['employer'] ?? 'Not Provided' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Position:</div>
                            <div class="info-value">{{ $emp['position'] ?? 'Not Provided' }}</div>
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
                                echo !empty($position) ? $position : 'Not Specified';
                                @endphp
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Application Status:</div>
                            <div class="info-value" style="text-transform: uppercase; font-weight: bold; color: #247d57;">{{ $application->status }}</div>
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
        </div>

        <div class="form-footer">
            Generated on {{ now()->format('F j, Y') }} | {{ $settings['portal_name'] ?? 'Application Portal' }} | {{ $settings['institution_name'] ?? 'EKSCOTECH' }}
        </div>
    </div>
</body>
</html>