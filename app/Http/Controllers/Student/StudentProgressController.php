<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentProgressController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        $courses = $student->courses()->with(['teacher', 'category'])
            ->withCount('lessons')
            ->get();

        $progressData = [];
        foreach ($courses as $course) {
            $completedLessons = $student->lessonProgress()
                ->whereHas('lesson', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->where('is_completed', true)
                ->count();
            
            $totalLessons = $course->lessons_count;
            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $progressData[] = [
                'course' => $course,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'progress' => round($progress, 2),
            ];
        }

        return view('student.progress.index', compact('progressData'));
    }

    public function dashboard()
    {
        $student = auth()->user();
        
        // Overall Statistics
        $stats = [
            'enrolled_courses' => $student->courses()->count(),
            'completed_courses' => $student->courses()->wherePivotNotNull('completed_at')->count(),
            'in_progress_courses' => $student->courses()->wherePivotNull('completed_at')->count(),
            'total_lessons_completed' => $student->lessonProgress()->where('is_completed', true)->count(),
            'total_quizzes_taken' => $student->attempts()->count(),
            'certificates' => $student->certificates()->count(),
            'xp_points' => $student->xp_points ?? 0,
            'level' => $student->level ?? 1,
            'badges' => $student->badges()->count(),
        ];

        // Progress Chart Data (Last 6 Months)
        $progressChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $lessonsCompleted = LessonProgress::where('user_id', $student->id)
                ->where('is_completed', true)
                ->whereBetween('completed_at', [$monthStart, $monthEnd])
                ->count();
            
            $coursesCompleted = DB::table('course_user')
                ->where('user_id', $student->id)
                ->whereNotNull('completed_at')
                ->whereBetween('completed_at', [$monthStart, $monthEnd])
                ->count();

            $progressChartData[] = [
                'month' => $month->format('M Y'),
                'lessons' => $lessonsCompleted,
                'courses' => $coursesCompleted,
            ];
        }

        // Course Progress Breakdown
        $courseProgress = $student->courses()
            ->with(['teacher', 'category'])
            ->withCount('lessons')
            ->get()
            ->map(function($course) use ($student) {
                $completedLessons = $student->lessonProgress()
                    ->whereHas('lesson', function($q) use ($course) {
                        $q->where('course_id', $course->id);
                    })
                    ->where('is_completed', true)
                    ->count();
                
                $totalLessons = $course->lessons_count;
                $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

                return [
                    'course' => $course,
                    'progress' => round($progress, 2),
                    'completed_lessons' => $completedLessons,
                    'total_lessons' => $totalLessons,
                ];
            })
            ->sortByDesc('progress');

        // Quiz Performance
        $quizPerformance = Attempt::where('user_id', $student->id)
            ->select('quiz_id', DB::raw('AVG(score) as avg_score'), DB::raw('MAX(score) as max_score'), DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->with('quiz.course')
            ->get();

        // Time Spent Learning (Last 30 Days)
        $timeSpent = LessonProgress::where('user_id', $student->id)
            ->where('last_accessed_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(last_accessed_at) as date'), DB::raw('COUNT(*) as lessons_accessed'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent Activity
        $recentActivity = collect()
            ->merge(
                $student->lessonProgress()
                    ->where('is_completed', true)
                    ->with('lesson.course')
                    ->latest('completed_at')
                    ->limit(10)
                    ->get()
                    ->map(function($progress) {
                        return [
                            'type' => 'lesson_completed',
                            'title' => $progress->lesson->title ?? 'Unknown',
                            'course' => $progress->lesson->course->title ?? 'Unknown',
                            'date' => $progress->completed_at,
                        ];
                    })
            )
            ->merge(
                $student->attempts()
                    ->with('quiz.course')
                    ->latest('completed_at')
                    ->limit(10)
                    ->get()
                    ->map(function($attempt) {
                        return [
                            'type' => 'quiz_completed',
                            'title' => $attempt->quiz->title ?? 'Unknown',
                            'course' => $attempt->quiz->course->title ?? 'Unknown',
                            'score' => $attempt->score,
                            'date' => $attempt->completed_at,
                        ];
                    })
            )
            ->sortByDesc('date')
            ->take(15);

        return view('student.progress.dashboard', compact(
            'stats',
            'progressChartData',
            'courseProgress',
            'quizPerformance',
            'timeSpent',
            'recentActivity'
        ));
    }

    public function courseProgress(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        $course->load(['lessons', 'quizzes']);

        $lessonProgress = [];
        foreach ($course->lessons as $lesson) {
            $progress = $student->lessonProgress()
                ->where('lesson_id', $lesson->id)
                ->first();
            
            $lessonProgress[] = [
                'lesson' => $lesson,
                'progress' => $progress,
                'is_completed' => $progress && $progress->is_completed,
                'last_accessed' => $progress ? $progress->last_accessed_at : null,
            ];
        }

        $quizProgress = [];
        foreach ($course->quizzes as $quiz) {
            $attempts = $student->attempts()
                ->where('quiz_id', $quiz->id)
                ->orderByDesc('score')
                ->get();
            
            $bestAttempt = $attempts->first();
            
            $quizProgress[] = [
                'quiz' => $quiz,
                'attempts' => $attempts,
                'best_score' => $bestAttempt ? $bestAttempt->score : null,
                'is_passed' => $bestAttempt && $bestAttempt->score >= ($quiz->pass_score ?? 60),
            ];
        }

        $overallProgress = $this->calculateCourseProgress($student, $course);

        return view('student.progress.course', compact(
            'course',
            'lessonProgress',
            'quizProgress',
            'overallProgress'
        ));
    }

    private function calculateCourseProgress($student, $course)
    {
        $totalLessons = $course->lessons()->count();
        $completedLessons = $student->lessonProgress()
            ->whereHas('lesson', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->where('is_completed', true)
            ->count();

        $lessonProgress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        // Quiz progress
        $totalQuizzes = $course->quizzes()->count();
        $passedQuizzes = 0;
        foreach ($course->quizzes as $quiz) {
            $bestAttempt = $student->attempts()
                ->where('quiz_id', $quiz->id)
                ->max('score');
            
            if ($bestAttempt && $bestAttempt >= ($quiz->pass_score ?? 60)) {
                $passedQuizzes++;
            }
        }
        $quizProgress = $totalQuizzes > 0 ? ($passedQuizzes / $totalQuizzes) * 100 : 0;

        // Overall progress (weighted: 70% lessons, 30% quizzes)
        $overallProgress = ($lessonProgress * 0.7) + ($quizProgress * 0.3);

        return [
            'lesson_progress' => round($lessonProgress, 2),
            'quiz_progress' => round($quizProgress, 2),
            'overall_progress' => round($overallProgress, 2),
            'completed_lessons' => $completedLessons,
            'total_lessons' => $totalLessons,
            'passed_quizzes' => $passedQuizzes,
            'total_quizzes' => $totalQuizzes,
        ];
    }
}
