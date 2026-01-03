@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('student.certificates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to My Certificates
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Certificate of Completion</h2>
        </div>
        <div class="card-body text-center">
            <div class="mb-4">
                <i class="fas fa-certificate fa-5x text-warning mb-3"></i>
                <h3>{{ $certificate->course->title ?? 'Course Title' }}</h3>
            </div>

            <div class="mb-4">
                <p class="lead">This is to certify that</p>
                <h2 class="mb-3">{{ $certificate->user->name }}</h2>
                <p class="lead">has successfully completed the course</p>
                <h4 class="mb-3">{{ $certificate->course->title ?? 'Course Title' }}</h4>
            </div>

            <div class="mb-4">
                <p class="text-muted">
                    <strong>Issued on:</strong> {{ $certificate->issued_at->format('F d, Y') }}
                </p>
                <p class="text-muted">
                    <strong>Certificate ID:</strong> {{ substr($certificate->id, 0, 8) }}
                </p>
            </div>

            <div class="mt-4">
                <a href="{{ route('certificates.download', $certificate) }}" class="btn btn-primary btn-lg mr-2">
                    <i class="fas fa-download"></i> Download Certificate
                </a>
                <a href="{{ route('student.certificates.share', ['certificate' => $certificate, 'platform' => 'linkedin']) }}" class="btn btn-info btn-lg mr-2" target="_blank">
                    <i class="fab fa-linkedin"></i> Share on LinkedIn
                </a>
                <a href="{{ route('student.certificates.share', ['certificate' => $certificate, 'platform' => 'twitter']) }}" class="btn btn-info btn-lg mr-2" target="_blank">
                    <i class="fab fa-twitter"></i> Share on Twitter
                </a>
                <a href="{{ route('student.certificates.share', ['certificate' => $certificate, 'platform' => 'facebook']) }}" class="btn btn-primary btn-lg" target="_blank">
                    <i class="fab fa-facebook"></i> Share on Facebook
                </a>
            </div>

            <div class="mt-4">
                <a href="{{ route('student.certificates.verify', substr($certificate->id, 0, 8)) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-shield-alt"></i> Verify Certificate
                </a>
            </div>
        </div>
    </div>

    <!-- Certificate Preview (if PDF exists) -->
    @if($certificate->certificate_url)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Certificate Preview</h5>
        </div>
        <div class="card-body">
            <div class="text-center">
                <iframe src="{{ asset('storage/' . $certificate->certificate_url) }}" style="width: 100%; height: 600px; border: 1px solid #ddd;"></iframe>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

