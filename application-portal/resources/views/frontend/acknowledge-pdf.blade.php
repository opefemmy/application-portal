<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement - {{ $application->application_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            padding: 30px;
            position: relative;
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
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        .header-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 10px;
            object-fit: contain;
        }
        .header h1 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .application-number {
            background: #f8f9fa;
            padding: 12px;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .application-number strong {
            font-size: 16px;
            color: #2c3e50;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            background: #ffc107;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
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
        }
        .section {
            margin-bottom: 20px;
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
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
        @media print {
            body { padding: 0; }
            .watermark { opacity: 0.12; }
        }
    </style>
</head>
<body>
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

    <div class="header">
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
        <h1>{{ $settings['institution_name'] ?? 'EKSCOTECH' }}</h1>
        <p>{{ $settings['portal_name'] ?? 'Application Portal' }} - Acknowledgement Receipt</p>
    </div>

    <div class="content-wrapper">
        <div class="main-content">
            <div class="application-number">
                <strong>Application Number: {{ $application->application_number }}</strong>
                <br>
                <span class="status">{{ ucfirst($application->status) }}</span>
                <br>
                <small>Submitted on {{ $application->created_at->format('F j, Y g:i A') }}</small>
            </div>

            <div class="section">
                <div class="section-title">Personal Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Full Name:</div>
                        <div class="info-value">{{ $application->full_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Gender:</div>
                        <div class="info-value">{{ ucfirst($application->gender) ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date of Birth:</div>
                        <div class="info-value">{{ $application->dateOfBirth ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Marital Status:</div>
                        <div class="info-value">{{ ucfirst($application->maritalStatus) ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nationality:</div>
                        <div class="info-value">{{ $application->nationality ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">State of Origin:</div>
                        <div class="info-value">{{ $application->state ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">LGA:</div>
                        <div class="info-value">{{ $application->localGovernment ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Residential Address:</div>
                        <div class="info-value">{{ $application->residentialAddress ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Postal Address:</div>
                        <div class="info-value">{{ $application->postalAddress ?: 'N/A' }}</div>
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
                        <div class="info-label">Alternative Phone:</div>
                        <div class="info-value">{{ $application->alternativePhone ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Academic Information</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Qualification:</div>
                        <div class="info-value">{{ $application->qualification ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Institution:</div>
                        <div class="info-value">{{ $application->institutionAttended ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Course:</div>
                        <div class="info-value">{{ $application->courseStudied ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Grade:</div>
                        <div class="info-value">{{ $application->gradeClass ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Graduation Year:</div>
                        <div class="info-value">{{ $application->graduationYear ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Application Details</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Position Applying For:</div>
                        <div class="info-value">{{ $application->positionApplyingFor ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Programme:</div>
                        <div class="info-value">{{ $application->programmeApplyingFor ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Department:</div>
                        <div class="info-value">{{ $application->department ?: 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Category:</div>
                        <div class="info-value">{{ $application->category ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>

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
        </div>

        <!-- Passport Photo -->
        <div class="passport-section">
            <div class="passport-photo">
                @php
                $passportDoc = $application->documents->filter(function($doc) {
                    return stripos($doc->document_type, 'passport') !== false;
                })->first();
                $passportImg = null;
                if ($passportDoc) {
                    // Try storage/app path first
                    $storagePath = storage_path('app/' . $passportDoc->file_path);
                    if (!file_exists($storagePath)) {
                        // Try public/storage path
                        $storagePath = public_path('storage/' . $passportDoc->file_path);
                    }
                    if (file_exists($storagePath)) {
                        $mimeType = $passportDoc->mime_type ?? 'image/jpeg';
                        $imageData = @file_get_contents($storagePath);
                        if ($imageData !== false) {
                            $passportImg = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                        }
                    }
                }
                @endphp
                @if($passportImg)
                <img src="{{ $passportImg }}" alt="Passport Photo">
                @else
                <span style="color: #999; font-size: 10px;">No Photo</span>
                @endif
            </div>
            <div class="passport-label">Passport Photo</div>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated document. No signature required.</p>
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>
</html>