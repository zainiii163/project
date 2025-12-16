@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Download Resources - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('student.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Course
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Course Resources</h3>
        <p style="color: var(--text-secondary); margin-top: 10px;">Download PDFs and other resources from this course</p>
    </div>
    <div class="adomx-card-body">
        @if($resources->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Resource Name</th>
                            <th>Type</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources as $resource)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($resource->type == 'pdf')
                                            <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 20px;"></i>
                                        @elseif($resource->type == 'video')
                                            <i class="fas fa-file-video" style="color: #3b82f6; font-size: 20px;"></i>
                                        @else
                                            <i class="fas fa-file" style="color: var(--text-secondary); font-size: 20px;"></i>
                                        @endif
                                        <strong>{{ $resource->title }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="adomx-status-badge" style="text-transform: uppercase;">
                                        {{ $resource->type }}
                                    </span>
                                </td>
                                <td>
                                    @if($resource->file_path)
                                        {{ number_format(filesize(storage_path('app/public/' . $resource->file_path)) / 1024, 2) }} KB
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($resource->file_path)
                                        <a href="{{ asset('storage/' . $resource->file_path) }}" 
                                           download 
                                           class="adomx-btn adomx-btn-primary" 
                                           style="padding: 8px 15px; font-size: 14px;">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    @elseif($resource->content)
                                        <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $resource->id]) }}" 
                                           class="adomx-btn adomx-btn-primary" 
                                           style="padding: 8px 15px; font-size: 14px;">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    @else
                                        <span style="color: var(--text-secondary);">No file available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-file-download" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">No Resources Available</h3>
                <p style="color: var(--text-secondary);">This course doesn't have any downloadable resources yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

