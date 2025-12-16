@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Verify Certificate</h2>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body" style="text-align: center;">
        @if($certificate)
            <div style="margin-bottom: 30px;">
                <i class="fas fa-check-circle" style="font-size: 80px; color: var(--success-color);"></i>
            </div>
            
            <h2 style="margin-bottom: 20px; color: var(--success-color);">Certificate Verified</h2>
            
            <div style="max-width: 600px; margin: 0 auto; text-align: left; background: var(--card-bg); padding: 20px; border-radius: 8px; margin-top: 30px;">
                <div style="margin-bottom: 15px;">
                    <strong>Student Name:</strong> {{ $certificate->user->name ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Course:</strong> {{ $certificate->course->title ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Issued Date:</strong> {{ $certificate->issued_at->format('F d, Y') ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 15px;">
                    <strong>Certificate ID:</strong> #{{ $certificate->id }}
                </div>
            </div>

            <div style="margin-top: 30px;">
                <p style="color: var(--text-secondary);">This certificate has been verified and is authentic.</p>
            </div>
        @else
            <div style="margin-bottom: 30px;">
                <i class="fas fa-times-circle" style="font-size: 80px; color: var(--danger-color);"></i>
            </div>
            
            <h2 style="margin-bottom: 20px; color: var(--danger-color);">Certificate Not Found</h2>
            <p style="color: var(--text-secondary);">The certificate you are looking for does not exist or is invalid.</p>
        @endif
    </div>
</div>
@endsection

