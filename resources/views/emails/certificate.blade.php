<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate Issued</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #28a745;">🎉 Congratulations!</h2>
        
        <p>Dear {{ $user->name }},</p>
        
        <p>We are pleased to inform you that you have successfully completed the course:</p>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; text-align: center;">
            <h3 style="margin-top: 0; color: #007bff;">{{ $course->title }}</h3>
            <p style="color: #666;">Certificate Issued: {{ $certificate->issued_at->format('F d, Y') }}</p>
        </div>

        <p>Your certificate is now available for download. You can view and download it from your dashboard.</p>

        <p style="margin-top: 30px;">
            <a href="{{ route('student.certificates.show', $certificate) }}" 
               style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                View Certificate
            </a>
        </p>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        <p style="color: #999; font-size: 12px;">
            This is an automated email from SmartLearn LMS. Please do not reply to this email.
        </p>
    </div>
</body>
</html>

