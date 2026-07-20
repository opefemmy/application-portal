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
        }
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .print-header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .print-header p {
            font-size: 11px;
            color: #666;
        }
        .application-number {
            background: #f5f5f5;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .application-number strong {
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-reviewed { background: #cce5ff; color: #004085; }
        .status-shortlisted { background: #d4edda; color: #155724; }
        .status-rejected { background: #f8d7da; color: #721c24; }
        .status-accepted { background: #28a745; color: #fff; }

        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 10px;
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
            width: 35%;
            padding: 4px 8px 4px 0;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            display: table-cell;
            padding: 4px 0;
        }

        .documents-section {
            margin-top: 20px;
        }
        .document-item {
            padding: 8px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .print-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #888;
        }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-header">
        <h1>Application Form</h1>
        <p>Generated on {{ now()->format('F j, Y g:i A') }}</p>
    </div>

    <div class="application-number">
        <strong>Application Number: {{ $application->application_number }}</strong>
        <br>
        <span class="status-badge status-{{ $application->status }}">{{ ucfirst($application->status) }}</span>
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
                <div class="info-value">{{ $application->personal_info['date_of_birth'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Marital Status:</div>
                <div class="info-value">{{ ucfirst($application->personal_info['marital_status'] ?? 'N/A') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nationality:</div>
                <div class="info-value">{{ $application->personal_info['nationality'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">State of Origin:</div>
                <div class="info-value">{{ $application->state }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Local Government:</div>
                <div class="info-value">{{ $application->personal_info['local_government'] ?? 'N/A' }}</div>
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
                <div class="info-value">{{ $application->personal_info['residential_address'] ?? 'N/A' }}</div>
            </div>
            @if(isset($application->personal_info['postal_address']))
            <div class="info-row">
                <div class="info-label">Postal Address:</div>
                <div class="info-value">{{ $application->personal_info['postal_address'] }}</div>
            </div>
            @endif
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
                <div class="info-value">{{ $application->application_details['position_applying_for'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Programme:</div>
                <div class="info-value">{{ $application->application_details['programme_applying_for'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">{{ $application->application_details['department'] ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Category:</div>
                <div class="info-value">{{ $application->application_details['category'] ?? 'N/A' }}</div>
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

    <div class="print-footer">
        <p>This is a computer-generated document. No signature required.</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-secondary">Close</button>
    </div>
</body>
</html>