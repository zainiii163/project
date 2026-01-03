<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Services\ActivityTrackingService;

class AdminContentModerationController extends Controller
{
    protected $activityService;

    public function __construct(ActivityTrackingService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index(Request $request)
    {
        $query = Course::where('status', 'pending_approval')
            ->orWhere('status', 'rejected')
            ->with(['teacher', 'category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $courses = $query->latest()->paginate(20);

        // Get pending lessons and quizzes
        $pendingLessons = Lesson::where('status', 'pending_approval')
            ->with(['course', 'course.teacher'])
            ->latest()
            ->take(10)
            ->get();

        $pendingQuizzes = Quiz::where('status', 'pending_approval')
            ->with(['course', 'course.teacher'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending_courses' => Course::where('status', 'pending_approval')->count(),
            'pending_lessons' => Lesson::where('status', 'pending_approval')->count(),
            'pending_quizzes' => Quiz::where('status', 'pending_approval')->count(),
            'approved_today' => Course::where('status', 'published')
                ->whereDate('approved_at', today())
                ->count(),
            'rejected_today' => Course::where('status', 'rejected')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('admin.moderation.index', compact('courses', 'pendingLessons', 'pendingQuizzes', 'stats'));
    }

    public function approveCourse(Course $course)
    {
        $course->update([
            'status' => 'published',
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        $this->activityService->log('course_approved', $course, null, [
            'course_title' => $course->title,
            'teacher' => $course->teacher->name ?? 'N/A',
            'approved_by' => auth()->user()->name,
        ]);

        // TODO: Send notification to teacher

        return back()->with('success', 'Course approved successfully!');
    }

    public function rejectCourse(Request $request, Course $course)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $course->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $this->activityService->log('course_rejected', $course, null, [
            'course_title' => $course->title,
            'reason' => $validated['rejection_reason'],
            'rejected_by' => auth()->user()->name,
        ]);

        // TODO: Send notification to teacher

        return back()->with('success', 'Course rejected.');
    }

    public function reviewCourse(Course $course)
    {
        $course->load(['teacher', 'category', 'lessons', 'quizzes', 'reviews']);

        return view('admin.moderation.review-course', compact('course'));
    }

    public function approveLesson(Lesson $lesson)
    {
        $lesson->update(['status' => 'published']);

        $this->activityService->log('lesson_approved', $lesson, null, [
            'lesson_title' => $lesson->title,
            'course' => $lesson->course->title,
            'approved_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Lesson approved successfully!');
    }

    public function rejectLesson(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $lesson->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $this->activityService->log('lesson_rejected', $lesson, null, [
            'lesson_title' => $lesson->title,
            'reason' => $validated['rejection_reason'],
            'rejected_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Lesson rejected.');
    }

    public function approveQuiz(Quiz $quiz)
    {
        $quiz->update(['status' => 'published']);

        $this->activityService->log('quiz_approved', $quiz, null, [
            'quiz_title' => $quiz->title,
            'course' => $quiz->course->title,
            'approved_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Quiz approved successfully!');
    }

    public function rejectQuiz(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $quiz->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $this->activityService->log('quiz_rejected', $quiz, null, [
            'quiz_title' => $quiz->title,
            'reason' => $validated['rejection_reason'],
            'rejected_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Quiz rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:courses,lessons,quizzes',
            'ids' => 'required|array',
            'ids.*' => 'required|uuid',
        ]);

        $count = 0;

        if ($validated['type'] === 'courses') {
            $courses = Course::whereIn('id', $validated['ids'])
                ->where('status', 'pending_approval')
                ->get();
            
            foreach ($courses as $course) {
                $course->update([
                    'status' => 'published',
                    'approved_at' => now(),
                ]);
                $count++;
            }
        } elseif ($validated['type'] === 'lessons') {
            $lessons = Lesson::whereIn('id', $validated['ids'])
                ->where('status', 'pending_approval')
                ->get();
            
            foreach ($lessons as $lesson) {
                $lesson->update(['status' => 'published']);
                $count++;
            }
        } elseif ($validated['type'] === 'quizzes') {
            $quizzes = Quiz::whereIn('id', $validated['ids'])
                ->where('status', 'pending_approval')
                ->get();
            
            foreach ($quizzes as $quiz) {
                $quiz->update(['status' => 'published']);
                $count++;
            }
        }

        return back()->with('success', "{$count} items approved successfully!");
    }
}
