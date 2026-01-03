@extends('layouts.main')

@section('content')
<div class="container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    <!-- Lesson Header -->
    <div style="margin-bottom: 30px;">
        <nav aria-label="breadcrumb" style="margin-bottom: 20px;">
            <ol class="breadcrumb" style="background: transparent; padding: 0;">
                <li class="breadcrumb-item"><a href="{{ route('courses.index') }}" style="color: var(--primary-color);">Courses</a></li>
                <li class="breadcrumb-item"><a href="{{ route('courses.show', $course->slug) }}" style="color: var(--primary-color);">{{ $course->title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $lesson->title }}</li>
            </ol>
        </nav>

        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 10px; color: #1a1a1a;">{{ $lesson->title }}</h1>
                <p style="color: #666; font-size: 16px; margin: 0;">{{ $course->title }}</p>
            </div>
            <a href="{{ route('student.courses.show', $course) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Course
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        <!-- Main Content -->
        <div>
            <!-- Lesson Description -->
            @if($lesson->description)
            <div class="card" style="margin-bottom: 30px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 25px;">
                    <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 15px;">Lesson Overview</h3>
                    <div style="color: #555; line-height: 1.8;">
                        {!! nl2br(e($lesson->description)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Lesson Content -->
            <div class="card" style="margin-bottom: 30px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 25px;">
                    <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 20px;">Lesson Content</h3>
                    
                    @if($lesson->type === 'video' && $lesson->content_url)
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; margin-bottom: 20px;">
                            @if(str_contains($lesson->content_url, 'youtube.com') || str_contains($lesson->content_url, 'youtu.be'))
                                <iframe 
                                    src="{{ str_replace('watch?v=', 'embed/', str_replace('youtu.be/', 'youtube.com/embed/', $lesson->content_url)) }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                </iframe>
                            @else
                                <video controls style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                    <source src="{{ asset('storage/' . $lesson->content_url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                    @elseif($lesson->type === 'text' && $lesson->content_url)
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                            <iframe src="{{ asset('storage/' . $lesson->content_url) }}" style="width: 100%; height: 600px; border: none;"></iframe>
                        </div>
                    @elseif($lesson->type === 'file' && $lesson->content_url)
                        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px;">
                            <i class="fas fa-file-pdf" style="font-size: 64px; color: #dc3545; margin-bottom: 20px;"></i>
                            <p style="color: #666; margin-bottom: 20px;">Download the lesson material</p>
                            <a href="{{ asset('storage/' . $lesson->content_url) }}" download class="btn btn-primary">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    @else
                        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
                            <i class="fas fa-book-open" style="font-size: 64px; color: #6c757d; margin-bottom: 20px;"></i>
                            <p style="color: #666;">No content available for this lesson.</p>
                        </div>
                    @endif

                    @if($lesson->duration)
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                            <small style="color: #666;">
                                <i class="fas fa-clock"></i> Duration: {{ gmdate('H:i', $lesson->duration * 60) }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quiz Section -->
            @if($lesson->quiz)
            <div class="card" style="margin-bottom: 30px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 25px;">
                    <h3 style="font-size: 20px; font-weight: 600; margin-bottom: 15px;">
                        <i class="fas fa-question-circle"></i> Lesson Quiz
                    </h3>
                    <p style="color: #666; margin-bottom: 20px;">Test your understanding of this lesson</p>
                    <a href="{{ route('quizzes.show', $lesson->quiz) }}" class="btn btn-primary">
                        <i class="fas fa-play"></i> Take Quiz
                    </a>
                </div>
            </div>
            @endif

            <!-- Navigation -->
            <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                @if($prevLesson)
                    <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $prevLesson->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Previous Lesson
                    </a>
                @else
                    <div></div>
                @endif

                @if($nextLesson)
                    <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}" class="btn btn-primary">
                        Next Lesson <i class="fas fa-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('student.courses.show', $course) }}" class="btn btn-success">
                        Complete Course <i class="fas fa-check"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Course Progress -->
            <div class="card" style="margin-bottom: 20px; border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 20px;">
                    <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 15px;">Course Progress</h4>
                    @php
                        $totalLessons = $course->lessons()->count();
                        $completedLessons = auth()->user()->lessonProgress()
                            ->whereHas('lesson', function($q) use ($course) {
                                $q->where('course_id', $course->id);
                            })
                            ->where('completed', true)
                            ->count();
                        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                    @endphp
                    <div style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span style="font-size: 14px; color: #666;">Progress</span>
                            <strong style="font-size: 14px;">{{ $progress }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 4px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%; background: #007bff;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <p style="font-size: 13px; color: #666; margin: 0;">
                        {{ $completedLessons }} of {{ $totalLessons }} lessons completed
                    </p>
                </div>
            </div>

            <!-- Course Lessons -->
            <div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 20px;">
                    <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 15px;">Course Lessons</h4>
                    <div style="max-height: 500px; overflow-y: auto;">
                        @foreach($course->lessons->sortBy('order') as $courseLesson)
                            <div style="padding: 12px; margin-bottom: 8px; border-radius: 6px; background: {{ $courseLesson->id === $lesson->id ? '#e7f3ff' : '#f8f9fa' }}; border-left: 3px solid {{ $courseLesson->id === $lesson->id ? '#007bff' : 'transparent' }};">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="flex: 1;">
                                        <a href="{{ route('lessons.show', ['course' => $course->slug, 'lesson' => $courseLesson->id]) }}" 
                                           style="color: {{ $courseLesson->id === $lesson->id ? '#007bff' : '#333' }}; text-decoration: none; font-weight: {{ $courseLesson->id === $lesson->id ? '600' : '400' }}; font-size: 14px;">
                                            {{ $courseLesson->title }}
                                        </a>
                                        <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                            {{ ucfirst($courseLesson->type) }}
                                        </div>
                                    </div>
                                    @php
                                        $isCompleted = auth()->user()->lessonProgress()
                                            ->where('lesson_id', $courseLesson->id)
                                            ->where('completed', true)
                                            ->exists();
                                    @endphp
                                    @if($isCompleted)
                                        <i class="fas fa-check-circle" style="color: #28a745; font-size: 16px;"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .breadcrumb-item + .breadcrumb-item::before {
        content: ">";
        padding: 0 8px;
        color: #6c757d;
    }
</style>
@endsection

