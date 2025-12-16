@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Certificate Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.certificates.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body" style="text-align: center;">
        <div style="margin-bottom: 30px;">
            <i class="fas fa-certificate" style="font-size: 80px; color: var(--warning-color);"></i>
        </div>
        
        <h2 style="margin-bottom: 10px;">Certificate of Completion</h2>
        <p style="font-size: 18px; margin-bottom: 30px;">This certifies that</p>
        <h3 style="margin-bottom: 30px;">{{ auth()->user()->name }}</h3>
        <p style="font-size: 16px; margin-bottom: 10px;">has successfully completed</p>
        <h4 style="margin-bottom: 30px; color: var(--primary-color);">{{ $certificate->course->title ?? 'N/A' }}</h4>
        
        <div style="margin-top: 40px;">
            <p style="color: var(--text-secondary);">Issued on {{ $certificate->issued_at->format('F d, Y') }}</p>
        </div>

        @if($certificate->certificate_url)
            <div style="margin-top: 30px;">
                <a href="{{ asset('storage/' . $certificate->certificate_url) }}" target="_blank" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-download"></i>
                    Download Certificate
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

