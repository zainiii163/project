@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Notification Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Notification Preferences</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="email_enabled" value="1" {{ old('email_enabled', true) ? 'checked' : '' }}>
                    Enable Email Notifications
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="push_enabled" value="1" {{ old('push_enabled', true) ? 'checked' : '' }}>
                    Enable Push Notifications
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="sms_enabled" value="1" {{ old('sms_enabled', false) ? 'checked' : '' }}>
                    Enable SMS Notifications
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Notification Types</label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 10px;">
                    <label><input type="checkbox" name="notification_types[]" value="course_enrollment" checked> Course Enrollment</label>
                    <label><input type="checkbox" name="notification_types[]" value="course_completion" checked> Course Completion</label>
                    <label><input type="checkbox" name="notification_types[]" value="certificate_issued" checked> Certificate Issued</label>
                    <label><input type="checkbox" name="notification_types[]" value="quiz_grade" checked> Quiz Grade</label>
                    <label><input type="checkbox" name="notification_types[]" value="assignment_feedback" checked> Assignment Feedback</label>
                    <label><input type="checkbox" name="notification_types[]" value="announcement" checked> Announcements</label>
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

