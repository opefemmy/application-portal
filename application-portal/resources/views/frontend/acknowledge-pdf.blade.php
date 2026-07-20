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
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .application-number {
            background: #f5f5f5;
            padding: 12px;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .application-number strong {
            font-size: 16px;
            color: #333;
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
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            width: 40%;
            padding: 5px 10px 5px 0;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
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
        @if(!empty($settings['logo']))
        <img src="{{ public_path($settings['logo']) }}" alt="Watermark">
        @elseif(file_exists(public_path('images/logo.png')))
        <img src="{{ public_path('images/logo.png') }}" alt="Watermark">
        @endif
    </div>

    <div class="header">
        <h1>Application Acknowledgement</h1>
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
    </div>

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
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $application->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value">{{ $application->phone }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Academic Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Qualification:</div>
                <div class="info-value">{{ $application->qualification }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Institution:</div>
                <div class="info-value">{{ $application->academic_info['institution_attended'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Course:</div>
                <div class="info-value">{{ $application->academic_info['course_studied'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Grade:</div>
                <div class="info-value">{{ $application->academic_info['grade_class'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Graduation Year:</div>
                <div class="info-value">{{ $application->academic_info['graduation_year'] ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Application Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Position Applying For:</div>
                <div class="info-value">
                    @php
                    $details = $application->application_details ?? [];
                    $position = $details['position_applying_for'] ?? '';
                    echo !empty($position) ? $position : 'N/A';
                    @endphp
                </div>
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

    <div class="footer">
        <p>This is a computer-generated document. No signature required.</p>
    </div>
</body>
</html>