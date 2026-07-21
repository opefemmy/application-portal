<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Print - {{ $application->application_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4; margin: 8mm; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #333;
            padding: 8mm;
            background: #fff;
        }

        :root {
            --primary: #82103c;
            --secondary: #247d57;
            --accent: #a48613;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 250px; height: 250px;
            opacity: 0.06; z-index: -1;
        }
        .watermark img { width: 100%; height: auto; }

        /* Header */
        .print-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        .header-left { display: flex; align-items: center; gap: 12px; }
        .institution-logo {
            width: 50px; height: 50px;
            background: #fff; border-radius: 50%;
            padding: 3px;
        }
        .institution-logo img { width: 100%; height: 100%; object-fit: contain; }
        .header-text h1 {
            font-size: 14px; font-weight: bold;
            text-transform: uppercase; margin-bottom: 2px;
        }
        .header-text p { font-size: 9px; opacity: 0.9; }

        /* App Number */
        .header-right { text-align: right; }
        .app-num-badge {
            background: var(--accent); color: #fff;
            padding: 4px 10px; border-radius: 3px;
            font-size: 11px; font-weight: bold;
            display: inline-block; margin-bottom: 3px;
        }
        .status-badge {
            display: inline-block; padding: 2px 8px;
            border-radius: 10px; font-size: 9px;
            font-weight: bold; text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-reviewed { background: #cce5ff; color: #004085; }
        .status-shortlisted { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-accepted { background: #28a745; color: #fff; }

        /* Passport - Top Right */
        .passport-box {
            position: absolute;
            top: 8mm; right: 10mm;
            width: 85px; text-align: center;
            z-index: 10;
        }
        .passport-photo {
            width: 80px; height: 100px;
            border: 2px solid var(--primary);
            display: flex; align-items: center;
            justify-content: center;
            background: #f5f5f5;
            overflow: hidden;
        }
        .passport-photo img { width: 100%; height: 100%; object-fit: cover; }

        /* Application Number under passport */
        .app-num-under-passport {
            margin-top: 6px;
            background: var(--accent); color: #fff;
            padding: 5px 8px; border-radius: 3px;
            font-size: 9px; font-weight: bold;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        /* Sections */
        .section { margin-bottom: 6px; }
        .section-title {
            font-size: 9px; font-weight: bold;
            background: var(--primary); color: #fff;
            padding: 2px 6px; text-transform: uppercase;
            border-radius: 2px; margin-bottom: 4px;
        }

        /* Table */
        .info-table {
            width: 100%; border-collapse: collapse;
        }
        .info-table tr { border-bottom: 1px solid #eee; }
        .info-table td {
            padding: 2px 4px; vertical-align: top;
        }
        .info-table .label {
            width: 32%; font-weight: 600;
            color: var(--primary);
            background: #f9f9f9;
        }

        /* Footer */
        .print-footer {
            margin-top: 10px; padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: center; font-size: 8px; color: #888;
        }

        @media print {
            body { padding: 5mm; }
            .no-print { display: none; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        @php
        $logoSrc = null;
        if (!empty($settings['logo'])) {
            $logoSrc = asset($settings['logo']);
        } elseif (file_exists(public_path('images/logo.png'))) {
            $logoSrc = asset('images/logo.png');
        }
        @endphp
        @if($logoSrc)
        <img src="{{ $logoSrc }}" alt="Watermark">
        @endif
    </div>

    <!-- Passport - Top Right -->
    <div class="passport-box">
        <div class="passport-photo">
            @php
            $passportImg = null;
            $passportDoc = $application->documents->filter(function($doc) {
                return stripos($doc->document_type, 'passport') !== false;
            })->first();
            if ($passportDoc) {
                // Try storage/app path first
                $storagePath = storage_path('app/' . $passportDoc->file_path);
                if (!file_exists($storagePath)) {
                    // Try public/storage path
                    $storagePath = public_path('storage/' . $passportDoc->file_path);
                }
                if (file_exists($storagePath)) {
                    $mimeType = $passportDoc->mime_type ?? 'image/jpeg';
                    // Verify it's a valid image
                    $imageData = @file_get_contents($storagePath);
                    if ($imageData !== false) {
                        $passportImg = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                    }
                }
            }
            @endphp
            @if($passportImg)
            <img src="{{ $passportImg }}" alt="Passport">
            @else
            <span style="font-size:8px;color:#999;">No Photo</span>
            @endif
        </div>
        <!-- Application Number under passport -->
        <div class="app-num-under-passport">{{ $application->application_number }}</div>
    </div>

    <!-- Header -->
    <div class="print-header">
        <div class="header-left">
            @php
            $logoSrc = null;
            if (!empty($settings['logo'])) {
                $logoSrc = asset($settings['logo']);
            } elseif (file_exists(public_path('images/logo.png'))) {
                $logoSrc = asset('images/logo.png');
            }
            @endphp
            @if($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo" class="institution-logo">
            @endif
            <div class="header-text">
                <h1>{{ $settings['institution_name'] ?? 'Institution' }}</h1>
                <p>{{ $settings['portal_name'] ?? 'Application Portal' }}</p>
            </div>
        </div>
        <div class="header-right">
            <span class="status-badge status-{{ $application->status }}">{{ ucwords(str_replace('_', ' ', $application->status)) }}</span>
        </div>
    </div>

    <!-- Personal Info -->
    <div class="section">
        <div class="section-title">Personal Information</div>
        <table class="info-table">
            <tr><td class="label">Full Name:</td><td>{{ $application->full_name }}</td></tr>
            <tr><td class="label">Gender:</td><td>{{ ucfirst($application->gender) ?: 'N/A' }}</td></tr>
            <tr><td class="label">Date of Birth:</td><td>{{ $application->dateOfBirth ?: 'N/A' }}</td></tr>
            <tr><td class="label">Marital Status:</td><td>{{ ucfirst($application->maritalStatus) ?: 'N/A' }}</td></tr>
            <tr><td class="label">Nationality:</td><td>{{ $application->nationality ?: 'N/A' }}</td></tr>
            <tr><td class="label">State of Origin:</td><td>{{ $application->state ?: 'N/A' }}</td></tr>
            <tr><td class="label">LGA:</td><td>{{ $application->localGovernment ?: 'N/A' }}</td></tr>
            <tr><td class="label">Address:</td><td>{{ $application->residentialAddress ?: 'N/A' }}</td></tr>
            <tr><td class="label">Postal Address:</td><td>{{ $application->postalAddress ?: 'N/A' }}</td></tr>
            <tr><td class="label">Email:</td><td>{{ $application->email }}</td></tr>
            <tr><td class="label">Phone:</td><td>{{ $application->phone }}</td></tr>
            <tr><td class="label">Alternative Phone:</td><td>{{ $application->alternativePhone ?: 'N/A' }}</td></tr>
        </table>
    </div>

    <!-- Academic Info -->
    <div class="section">
        <div class="section-title">Academic Information</div>
        <table class="info-table">
            <tr><td class="label">Qualification:</td><td>{{ $application->qualification ?: 'N/A' }}</td></tr>
            <tr><td class="label">Institution:</td><td>{{ $application->institutionAttended ?: 'N/A' }}</td></tr>
            <tr><td class="label">Course:</td><td>{{ $application->courseStudied ?: 'N/A' }}</td></tr>
            <tr><td class="label">Grade/Class:</td><td>{{ $application->gradeClass ?: 'N/A' }}</td></tr>
            <tr><td class="label">Graduation Year:</td><td>{{ $application->graduationYear ?: 'N/A' }}</td></tr>
        </table>
    </div>

    <!-- Employment History -->
    @if($showEmployment && $application->employment_info && count($application->employment_info) > 0)
    <div class="section">
        <div class="section-title">Employment History</div>
        <table class="info-table">
            @foreach($application->employment_info as $emp)
            <tr><td class="label">Employer:</td><td>{{ $emp['employer'] ?? '' }}</td></tr>
            <tr><td class="label">Position:</td><td>{{ $emp['position'] ?? '' }}</td></tr>
            <tr><td class="label">Years Exp:</td><td>{{ $emp['years_experience'] ?? '' }}</td></tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Application Details -->
    <div class="section">
        <div class="section-title">Application Details</div>
        <table class="info-table">
            <tr><td class="label">Position Applied:</td><td>{{ $application->positionApplyingFor ?: 'N/A' }}</td></tr>
        </table>
    </div>

    <!-- Documents -->
    @if($application->documents->count() > 0)
    <div class="section">
        <div class="section-title">Uploaded Documents</div>
        <table class="info-table">
            @foreach($application->documents as $doc)
            <tr><td class="label">{{ $doc->document_type }}:</td><td>{{ $doc->file_name }}</td></tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="print-footer">
        Generated on {{ now()->format('F j, Y') }} | {{ $settings['institution_name'] ?? 'Institution' }}
    </div>

    <div class="no-print" style="margin-top:15px;text-align:center;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</body>
</html>