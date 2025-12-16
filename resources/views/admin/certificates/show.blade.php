@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Certificate Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.certificates.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="margin-bottom: 30px;">
            <h3>Certificate Information</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>User:</strong> {{ $certificate->user->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Course:</strong> {{ $certificate->course->title ?? 'N/A' }}
            </div>
            <div>
                <strong>Issued At:</strong> {{ $certificate->issued_at->format('M d, Y') }}
            </div>
            <div>
                <strong>Certificate URL:</strong> 
                @if($certificate->certificate_url)
                    <a href="{{ asset('storage/' . $certificate->certificate_url) }}" target="_blank">View Certificate</a>
                @else
                    N/A
                @endif
            </div>
        </div>

        <div class="adomx-form-actions">
            <form action="{{ route('admin.certificates.destroy', $certificate) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="adomx-btn" style="background: var(--danger-color);">
                    <i class="fas fa-trash"></i>
                    Delete Certificate
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

