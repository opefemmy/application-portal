<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Accepted</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #38488e 0%, #4052a0 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .application-number {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        .application-number strong {
            color: #38488e;
            font-size: 18px;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #38488e;
            background: #f8f9fa;
        }
        .details h3 {
            margin-top: 0;
            color: #38488e;
        }
        .details ul {
            margin: 0;
            padding-left: 20px;
        }
        .details li {
            margin: 5px 0;
        }
        .cta-button {
            display: inline-block;
            background: #38488e;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Congratulations!</h1>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $application->personal_info['first_name'] }} {{ $application->personal_info['last_name'] }}</strong>,</p>

            <p>We are delighted to inform you that your application has been <strong>ACCEPTED</strong>!</p>

            <div class="application-number">
                <p>Application Number:</p>
                <strong>{{ $application->application_number }}</strong>
            </div>

            <div class="details">
                <h3>Next Steps</h3>
                <ul>
                    @if(!empty($data['start_date']))
                    <li><strong>Start Date:</strong> {{ $data['start_date'] }}</li>
                    @endif
                    @if(!empty($data['position']))
                    <li><strong>Position:</strong> {{ $data['position'] }}</li>
                    @endif
                    @if(!empty($data['department']))
                    <li><strong>Department:</strong> {{ $data['department'] }}</li>
                    @endif
                    @if(!empty($data['venue']))
                    <li><strong>Reporting Venue:</strong> {{ $data['venue'] }}</li>
                    @endif
                    @if(!empty($data['contact_person']))
                    <li><strong>Contact Person:</strong> {{ $data['contact_person'] }}</li>
                    @endif
                    @if(!empty($data['contact_email']))
                    <li><strong>Contact Email:</strong> {{ $data['contact_email'] }}</li>
                    @endif
                    @if(!empty($data['contact_phone']))
                    <li><strong>Contact Phone:</strong> {{ $data['contact_phone'] }}</li>
                    @endif
                </ul>
            </div>

            @if(!empty($data['additional_message']))
            <div class="details">
                <h3>Additional Information</h3>
                <p>{{ $data['additional_message'] }}</p>
            </div>
            @endif

            <p>Please reply to this email or contact us if you have any questions.</p>

            <p>We look forward to welcoming you!</p>

            <p>Best regards,<br>
            <strong>{{ config('app.name', 'Application Portal') }}</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email directly.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Application Portal') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>