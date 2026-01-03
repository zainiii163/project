@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h1>Offline Access</h1>
    <p class="text-muted">Download course materials for offline viewing</p>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Available Courses for Download</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Teacher</th>
                                    <th>Downloadable Materials</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                <tr>
                                    <td>
                                        <strong>{{ $course->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $course->category->name ?? 'Uncategorized' }}</small>
                                    </td>
                                    <td>{{ $course->teacher->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $downloadableCount = $course->lessons()
                                                ->whereNotNull('downloadable_materials')
                                                ->count();
                                        @endphp
                                        {{ $downloadableCount }} lesson(s) with materials
                                    </td>
                                    <td>
                                        @if($downloadableCount > 0)
                                            <a href="{{ route('offline.download-course', $course) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i> Download All
                                            </a>
                                        @else
                                            <span class="text-muted">No materials available</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No courses with downloadable materials available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($downloadableCourses->isNotEmpty())
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Download Individual Lessons</h5>
                </div>
                <div class="card-body">
                    @foreach($downloadableCourses as $course)
                    <div class="mb-4">
                        <h6>{{ $course->title }}</h6>
                        <ul class="list-group">
                            @foreach($course->lessons as $lesson)
                                @if($lesson->downloadable_materials)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $lesson->title }}</span>
                                    <a href="{{ route('offline.download-lesson', $lesson) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle"></i> Offline Access Tips</h6>
                <ul class="mb-0">
                    <li>Downloaded materials are available for offline viewing</li>
                    <li>Progress will sync automatically when you're back online</li>
                    <li>Large files may take time to download</li>
                    <li>Ensure you have sufficient storage space</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
