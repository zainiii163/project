<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Announcement</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #007bff;">New Announcement</h2>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0;">{{ $announcement->title }}</h3>
            <div style="color: #666;">
                {!! nl2br(e($announcement->content)) !!}
            </div>
        </div>

        @if($announcement->course)
        <p><strong>Course:</strong> {{ $announcement->course->title }}</p>
        @endif

        <p style="margin-top: 30px;">
            <a href="{{ route('announcements.index') }}" 
               style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                View Announcement
            </a>
        </p>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        <p style="color: #999; font-size: 12px;">
            This is an automated email from SmartLearn LMS. Please do not reply to this email.
        </p>
    </div>
</body>
</html>

