<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ $application->application_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            padding: 8mm;
            background: #fff;
        }
        .page {
            max-width: 210mm;
            min-height: 297mm;
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

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            opacity: 0.08;
            z-index: -1;
        }
        .watermark img { width: 100%; height: auto; }

        /* Header Banner */
        .header-banner {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: #fff;
            border-radius: 8px;
            padding: 5px;
        }
        .header-text {
            color: #fff;
            flex: 1;
        }
        .header-text h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .header-text .portal-name {
            font-size: 11px;
            opacity: 0.95;
        }

        /* QR and App Number */
        .header-right {
            text-align: right;
        }
        .qr-code {
            width: 55px;
            height: 55px;
            background: #fff;
            padding: 3px;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .qr-code img { width: 100%; height: 100%; }
        .app-number {
            background: var(--accent);
            color: #fff;
            padding: 4px 12px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        /* Two Column Layout */
        .main-content { width: 100%; }

        /* Passport - Top Right */
        .passport-box {
            position: absolute;
            top: 8mm;
            right: 8mm;
            width: 100px;
            text-align: center;
        }
        .passport-photo {
            width: 90px;
            height: 110px;
            border: 3px solid var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            overflow: hidden;
        }
        .passport-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Info Sections */
        .section { margin-bottom: 12px; }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background: var(--primary);
            color: #fff;
            padding: 5px 10px;
            text-transform: uppercase;
            border-radius: 3px;
            margin-bottom: 8px;
        }

        /* Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table tr { border-bottom: 1px solid #eee; }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table .label {
            width: 32%;
            font-weight: 600;
            color: var(--primary);
            background: #f9f9f9;
        }
        .info-table .value { padding-left: 10px; }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #888;
        }

        /* Signature */
        .signature-area {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #333;
            padding-top: 8px;
        }
        .signature-box {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 25px;
            font-size: 9px;
        }

        @page { size: A4; margin: 0; }
    </style>
</head>
<body>
    <div class="page">
        <!-- Watermark -->
        <div class="watermark">
            @php
            $logoSrc = null;
            $logoKey = $settings['logo'] ?? null;
            if ($logoKey) {
                $logoSrc = asset($logoKey);
            }
            if (!$logoSrc || !@getimagesize(public_path($logoKey))) {
                if (file_exists(public_path('images/logo.png'))) {
                    $logoSrc = asset('images/logo.png');
                } elseif (file_exists(public_path('images/logo.jpg'))) {
                    $logoSrc = asset('images/logo.jpg');
                }
            }
            @endphp
            @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Watermark">
            @endif
        </div>

        <!-- Passport - Top Right Corner -->
        <div class="passport-box">
            <div class="passport-photo">
                @php
                $passportImg = null;
                $passportDoc = $application->documents->filter(function($doc) {
                    return stripos($doc->document_type, 'passport') !== false;
                })->first();
                if ($passportDoc) {
                    $storagePath = storage_path('app/' . $passportDoc->file_path);
                    if (file_exists($storagePath)) {
                        $passportImg = 'data:' . ($passportDoc->mime_type ?? 'image/jpeg') . ';base64,' . base64_encode(file_get_contents($storagePath));
                    }
                }
                @endphp
                @if($passportImg)
                <img src="{{ $passportImg }}" alt="Passport">
                @else
                <span style="font-size:8px;color:#999;">No Photo</span>
                @endif
            </div>
        </div>

        <!-- Header -->
        <div class="header-banner">
            @php
            $institutionName = $settings['institution_name'] ?? 'EKSCOTECH';
            $portalName = $settings['portal_name'] ?? 'Online Application Portal';
            @endphp

            @php
            $headerLogoSrc = null;
            $logoKey = $settings['logo'] ?? null;
            if ($logoKey) {
                $headerLogoSrc = asset($logoKey);
            }
            if (!$headerLogoSrc || !@getimagesize(public_path($logoKey))) {
                if (file_exists(public_path('images/logo.png'))) {
                    $headerLogoSrc = asset('images/logo.png');
                } elseif (file_exists(public_path('images/logo.jpg'))) {
                    $headerLogoSrc = asset('images/logo.jpg');
                }
            }
            @endphp

            @if($headerLogoSrc)
            <img src="{{ $headerLogoSrc }}" alt="Logo" class="header-logo">
            @endif

            <div class="header-text">
                <h1>{{ $institutionName }}</h1>
                <div class="portal-name">{{ $portalName }}</div>
            </div>

            <div class="header-right">
                @php
                $qrData = json_encode([
                    'app' => $application->application_number,
                    'name' => $application->full_name
                ]);
                $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=' . urlencode($qrData);
                @endphp
                <div class="qr-code">
                    <img src="{{ $qrCodeUrl }}" alt="QR">
                </div>
                <div class="app-number">{{ $application->application_number }}</div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Personal Information -->
            <div class="section">
                <div class="section-title">Personal Information</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Full Name:</td>
                        <td class="value">{{ $application->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Gender:</td>
                        <td class="value">{{ ucfirst($application->gender) ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date of Birth:</td>
                        <td class="value">{{ data_get($application->personal_info, 'date_of_birth') ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Marital Status:</td>
                        <td class="value">{{ ucfirst(data_get($application->personal_info, 'marital_status')) ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nationality:</td>
                        <td class="value">{{ data_get($application->personal_info, 'nationality') ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">State of Origin:</td>
                        <td class="value">{{ $application->state ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">LGA:</td>
                        <td class="value">{{ data_get($application->personal_info, 'local_government') ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Residential Address:</td>
                        <td class="value">{{ data_get($application->personal_info, 'residential_address') ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{ $application->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Phone:</td>
                        <td class="value">{{ $application->phone }}</td>
                    </tr>
                    @if(data_get($application->personal_info, 'alternative_phone'))
                    <tr>
                        <td class="label">Alt. Phone:</td>
                        <td class="value">{{ data_get($application->personal_info, 'alternative_phone') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- Academic Information -->
            <div class="section">
                <div class="section-title">Academic Information</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Highest Qualification:</td>
                        <td class="value">{{ $application->qualification ?: 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Institution:</td>
                        <td class="value">{{ $application->academic_info['institution_attended'] ?? 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Course:</td>
                        <td class="value">{{ $application->academic_info['course_studied'] ?? 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Grade:</td>
                        <td class="value">{{ $application->academic_info['grade_class'] ?? 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Graduation Year:</td>
                        <td class="value">{{ $application->academic_info['graduation_year'] ?? 'Not Provided' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Employment -->
            @if($application->employment_info && count($application->employment_info) > 0)
            <div class="section">
                <div class="section-title">Employment History</div>
                <table class="info-table">
                    @foreach($application->employment_info as $emp)
                    <tr>
                        <td class="label">Employer:</td>
                        <td class="value">{{ $emp['employer'] ?? 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Position:</td>
                        <td class="value">{{ $emp['position'] ?? 'Not Provided' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Years Experience:</td>
                        <td class="value">{{ $emp['years_experience'] ?? '0' }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif

            <!-- Application Details -->
            <div class="section">
                <div class="section-title">Application Details</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Position Applied:</td>
                        <td class="value">
                            @php
                            $position = '';
                            $details = $application->application_details ?? [];
                            $position = $details['position_applying_for'] ?? $details['programme_applying_for'] ?? '';
                            if (empty($position)) {
                                $position = data_get($application->personal_info, 'position_applying_for')
                                    ?? data_get($application->personal_info, 'position')
                                    ?? '';
                            }
                            echo !empty($position) ? $position : 'Not Specified';
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Status:</td>
                        <td class="value" style="font-weight:bold; color: var(--secondary); text-transform:uppercase;">{{ $application->status }}</td>
                    </tr>
                </table>
            </div>

            <!-- Documents -->
            @if($application->documents->count() > 0)
            <div class="section">
                <div class="section-title">Uploaded Documents</div>
                <table class="info-table">
                    @foreach($application->documents as $doc)
                    <tr>
                        <td class="label">{{ $doc->document_type }}:</td>
                        <td class="value">{{ $doc->file_name }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif

            <!-- Signature -->
            <div class="signature-area">
                <div class="signature-box">
                    <div class="signature-line">Applicant's Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Date</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Generated on {{ now()->format('F j, Y') }} | {{ $portalName }} | {{ $institutionName }}
        </div>
    </div>
</body>
</html>