<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'type' => 'required|in:video,text,quiz,file,pdf',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
            'downloadable_materials' => 'nullable|array',
            'downloadable_materials.*' => 'file|max:10240',
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? $course->lessons()->max('order') + 1;

        // Handle video URL or uploaded video file
        if ($request->has('video_url')) {
            $validated['content_url'] = $request->video_url;
        } elseif ($request->hasFile('video_file')) {
            $validated['content_url'] = $request->file('video_file')->store('lessons/videos', 'public');
        } elseif ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        // Handle downloadable materials (PDFs, files)
        if ($request->hasFile('downloadable_materials')) {
            $materials = [];
            foreach ($request->file('downloadable_materials') as $file) {
                $materials[] = $file->store('lessons/materials', 'public');
            }
            $validated['downloadable_materials'] = json_encode($materials);
        }

        $lesson = Lesson::create($validated);

        return back()->with('success', 'Lesson created successfully!');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'type' => 'required|in:video,text,quiz,file,pdf',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
            'downloadable_materials' => 'nullable|array',
            'downloadable_materials.*' => 'file|max:10240',
        ]);

        // Handle video URL or uploaded video file
        if ($request->has('video_url')) {
            $validated['content_url'] = $request->video_url;
        } elseif ($request->hasFile('video_file')) {
            $validated['content_url'] = $request->file('video_file')->store('lessons/videos', 'public');
        } elseif ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        // Handle downloadable materials (PDFs, files)
        if ($request->hasFile('downloadable_materials')) {
            $materials = [];
            foreach ($request->file('downloadable_materials') as $file) {
                $materials[] = $file->store('lessons/materials', 'public');
            }
            $validated['downloadable_materials'] = json_encode($materials);
        }

        $lesson->update($validated);

        return back()->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully!');
    }

    public function show($courseSlug, Lesson $lesson)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $this->authorize('view', $course);

        // Check if user is enrolled
        if (auth()->check() && !$course->students()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must enroll in this course first.');
        }

        $lesson->load('course', 'quiz.questions.options');
        $nextLesson = $course->lessons()->where('order', '>', $lesson->order)->first();
        $prevLesson = $course->lessons()->where('order', '<', $lesson->order)->orderBy('order', 'desc')->first();

        // Update progress
        if (auth()->check()) {
            $progress = $lesson->progress()->firstOrCreate([
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
            ], [
                'last_accessed_at' => now(),
            ]);

            $progress->update(['last_accessed_at' => now()]);

            // Mark lesson as completed if user watched entire lesson
            if ($request->has('completed') && $request->completed) {
                $progress->update([
                    'is_completed' => true,
                    'completed_at' => now(),
                    'progress_percentage' => 100,
                ]);

                // Award XP for completing lesson
                $gamificationController = new \App\Http\Controllers\GamificationController();
                $gamificationController->awardXp(
                    auth()->user(),
                    10, // 10 XP per lesson
                    $lesson,
                    'Completed lesson: ' . $lesson->title
                );

                // Check if course is completed and generate certificate
                $this->checkCourseCompletion(auth()->user(), $course);
            }
        }

        return view('lessons.show', compact('lesson', 'course', 'nextLesson', 'prevLesson'));
    }

    private function checkCourseCompletion($user, $course)
    {
        // Get total lessons in course
        $totalLessons = $course->lessons()->count();
        
        // Get completed lessons for this user
        $completedLessons = \App\Models\LessonProgress::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('is_completed', true)
            ->count();

        // Check if all lessons are completed
        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            // Check if all required quizzes are passed
            $allQuizzesPassed = true;
            foreach ($course->quizzes as $quiz) {
                $bestAttempt = \App\Models\Attempt::where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->where('status', 'completed')
                    ->max('score');
                
                $totalQuestions = $quiz->questions()->sum('points');
                $percentage = $totalQuestions > 0 ? ($bestAttempt / $totalQuestions) * 100 : 0;
                
                if ($percentage < $quiz->pass_score) {
                    $allQuizzesPassed = false;
                    break;
                }
            }

            // Mark course as completed
            $enrollment = $course->students()->where('user_id', $user->id)->first();
            if ($enrollment && !$enrollment->pivot->completed_at) {
                $course->students()->updateExistingPivot($user->id, [
                    'completed_at' => now(),
                    'progress' => 100,
                ]);

                // Generate certificate automatically if all requirements met
                if ($allQuizzesPassed || $course->quizzes()->count() === 0) {
                    $this->generateCertificate($user, $course);
                }
            }
        }
    }

    private function generateCertificate($user, $course)
    {
        // Check if certificate already exists
        if (\App\Models\Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists()) {
            return;
        }

        // Generate certificate PDF
        $certificateUrl = $this->createCertificatePDF([
            'user_name' => $user->name,
            'course_title' => $course->title,
            'completion_date' => now()->format('F d, Y'),
            'course_duration' => $course->duration ?? 'N/A',
        ]);

        $certificate = \App\Models\Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_url' => $certificateUrl,
            'issued_at' => now(),
        ]);

        // Send notification to user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'type' => 'certificate_issued',
            'title' => 'Certificate Issued',
            'message' => 'Congratulations! You have completed "' . $course->title . '" and received a certificate.',
            'data' => [
                'certificate_id' => $certificate->id,
                'course_id' => $course->id,
            ],
        ]);

        // Send email notification
        try {
            \Illuminate\Support\Facades\Mail::send('emails.certificate', [
                'certificate' => $certificate,
                'user' => $user,
                'course' => $course,
            ], function ($message) use ($user, $course) {
                $message->to($user->email, $user->name)
                    ->subject('Certificate Issued: ' . $course->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email: ' . $e->getMessage());
        }
    }

    private function createCertificatePDF($data)
    {
        // Generate certificate PDF using DomPDF or similar
        // For now, return a placeholder path
        $filename = 'certificates/' . uniqid() . '.pdf';
        
        // TODO: Implement actual PDF generation
        // Example with DomPDF:
        // $pdf = \PDF::loadView('certificates.template', $data);
        // Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }
}

