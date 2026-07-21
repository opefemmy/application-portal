<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Status Update</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #6c757d 0%, #95a5a6 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">Application Update</h1>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px;">
        <p>Dear <strong>{{ $application->personal_info['first_name'] ?? 'Applicant' }}</strong>,</p>

        <p>Thank you for taking the time to apply for the position at {{ config('app.name', 'our institution') }}.</p>

        <p>We have carefully reviewed your application, and while your qualifications are impressive, we regret to inform you that you were not shortlisted for this particular position.</p>

        @if($message)
        <div style="background: white; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <strong>Additional Message:</strong>
            <p class="mb-0">{{ $message }}</p>
        </div>
        @endif

        <p>We encourage you to apply for future openings that match your skills and experience.</p>

        <p>Application Number: <strong>{{ $application->application_number }}</strong></p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="color: #666; margin: 0;">Best regards,</p>
            <p style="font-weight: bold; margin: 5px 0;">Registrar</p>
        </div>
    </div>
</body>
</html>