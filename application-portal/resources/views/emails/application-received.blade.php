<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Application Received</h1>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Dear <strong>{{ $application->personal_info['first_name'] ?? 'Applicant' }}</strong>,</p>

        <p>Your application has been received successfully. Below is your application details:</p>

        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; color: #666;">Application Number</td>
                    <td style="padding: 8px 0; font-weight: bold; color: #1e3a5f;">{{ $application->application_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Full Name</td>
                    <td style="padding: 8px 0;">{{ $application->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Email</td>
                    <td style="padding: 8px 0;">{{ $application->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Position Applied</td>
                    <td style="padding: 8px 0;">{{ $application->application_details['position_applying_for'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Application Date</td>
                    <td style="padding: 8px 0;">{{ $application->created_at->format('F j, Y') }}</td>
                </tr>
            </table>
        </div>

        <p>Please keep your application number <strong>{{ $application->application_number }}</strong> for future reference.</p>

        <p>You will receive further communication if you are shortlisted for the next stage.</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="color: #666; margin: 0;">Best regards,</p>
            <p style="font-weight: bold; margin: 5px 0;">Registrar</p>
            <p style="color: #999; margin: 0;">{{ config('app.name', 'Application Portal') }}</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px; color: #999; font-size: 12px;">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>