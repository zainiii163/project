@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h1>Surveys</h1>
    <p class="text-muted">Participate in surveys to help us improve</p>

    <div class="row">
        @forelse($surveys as $survey)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $survey->title }}</h5>
                    <p class="card-text">{{ Str::limit($survey->description, 150) }}</p>
                    @if($survey->course)
                        <p class="text-muted"><small>Course: {{ $survey->course->title }}</small></p>
                    @endif
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> 
                            {{ $survey->start_date->format('M d, Y') }} - {{ $survey->end_date->format('M d, Y') }}
                        </small>
                    </div>
                    <a href="{{ route('surveys.show', $survey) }}" class="btn btn-primary">
                        Take Survey
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="alert alert-info">No active surveys available.</div>
        </div>
        @endforelse
    </div>

    {{ $surveys->links() }}
</div>
@endsection

