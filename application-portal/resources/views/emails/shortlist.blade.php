<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shortlist Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Congratulations!</h1>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Dear <strong>{{ $application->personal_info['first_name'] ?? 'Applicant' }}</strong>,</p>

        <p>Congratulations! You have been shortlisted for the next stage of the application process.</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h5 style="margin-bottom: 15px;">Interview Details</h5>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; color: #666;">Date</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $data['interview_date'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Time</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $data['interview_time'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Venue</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $data['venue'] }}</td>
                </tr>
                @if(isset($data['meeting_link']))
                <tr>
                    <td style="padding: 8px 0; color: #666;">Meeting Link</td>
                    <td style="padding: 8px 0;"><a href="{{ $data['meeting_link'] }}">{{ $data['meeting_link'] }}</a></td>
                </tr>
                @endif
            </table>
        </div>

        @if(isset($data['instructions']))
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <strong>Instructions:</strong>
            <p class="mb-0">{{ $data['instructions'] }}</p>
        </div>
        @endif

        <p>Please ensure you arrive on time or join the meeting link at the scheduled time.</p>

        <p>Application Number: <strong>{{ $application->application_number }}</strong></p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="color: #666; margin: 0;">Best regards,</p>
            <p style="font-weight: bold; margin: 5px 0;">Registrar</p>
        </div>
    </div>
</body>
</html>