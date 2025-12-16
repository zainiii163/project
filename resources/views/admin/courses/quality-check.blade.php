@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Quality Check - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.courses.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Quality Score -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quality Score</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; padding: 30px;">
                    <div style="font-size: 72px; font-weight: bold; color: {{ $qualityScore >= 80 ? 'var(--success-color)' : ($qualityScore >= 60 ? 'var(--warning-color)' : 'var(--danger-color)') }}; margin-bottom: 10px;">
                        {{ $qualityScore }}
                    </div>
                    <div style="font-size: 18px; color: var(--text-secondary);">out of 100</div>
                    <div class="adomx-progress-bar" style="margin-top: 20px;">
                        <div class="adomx-progress-fill" style="width: {{ $qualityScore }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Found -->
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Quality Issues</h3>
            </div>
            <div class="adomx-card-body">
                @if(count($issues) > 0)
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach($issues as $issue)
                            <div style="padding: 15px; background: rgba(239, 68, 68, 0.1); border-left: 4px solid var(--danger-color); border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-exclamation-triangle" style="color: var(--danger-color);"></i>
                                    <span>{{ $issue }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px;">
                        <i class="fas fa-check-circle" style="font-size: 64px; color: var(--success-color); margin-bottom: 20px;"></i>
                        <h3 style="margin-bottom: 10px;">No Issues Found</h3>
                        <p style="color: var(--text-secondary);">This course meets all quality standards!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Course Content Details -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Content Checklist</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            @if($course->description)
                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            @endif
                            <strong>Description</strong>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ $course->description ? 'Present' : 'Missing' }}
                        </div>
                    </div>

                    <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            @if($course->lessons->count() >= 3)
                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            @endif
                            <strong>Lessons ({{ $course->lessons->count() }})</strong>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ $course->lessons->count() >= 3 ? 'Sufficient' : 'Need at least 3 lessons' }}
                        </div>
                    </div>

                    <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            @if($course->quizzes->count() > 0)
                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            @endif
                            <strong>Quizzes ({{ $course->quizzes->count() }})</strong>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ $course->quizzes->count() > 0 ? 'Present' : 'No quizzes found' }}
                        </div>
                    </div>

                    <div style="padding: 15px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            @if($course->thumbnail)
                                <i class="fas fa-check-circle" style="color: var(--success-color);"></i>
                            @else
                                <i class="fas fa-times-circle" style="color: var(--danger-color);"></i>
                            @endif
                            <strong>Thumbnail</strong>
                        </div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            {{ $course->thumbnail ? 'Present' : 'Missing' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

