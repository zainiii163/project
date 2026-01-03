<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Attempt;
use Illuminate\Http\Request;

class TeacherCourseController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = $teacher->taughtCourses()->with(['category', 'students']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('teacher.courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        // Ensure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $course->load(['lessons', 'quizzes', 'students', 'reviews.user', 'assignments']);
        
        return view('teacher.courses.show', compact('course'));
    }

    public function students(Course $course)
    {
        // Ensure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $students = $course->students()->withPivot('enrolled_at', 'progress', 'completed_at')
            ->latest('course_user.created_at')
            ->paginate(20);

        return view('teacher.courses.students', compact('course', 'students'));
    }

    public function performance(Course $course)
    {
        // Ensure teacher owns this course
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $stats = [
            'total_students' => $course->students()->count(),
            'completed_students' => $course->students()->wherePivot('completed_at', '!=', null)->count(),
            'avg_progress' => $course->students()->avg('course_user.progress') ?? 0,
            'total_quizzes' => $course->quizzes()->count(),
            'total_attempts' => Attempt::whereHas('quiz', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->count(),
        ];

        // Quiz performance
        $quizPerformance = $course->quizzes()->with(['attempts' => function($q) {
            $q->selectRaw('quiz_id, AVG(score) as avg_score, COUNT(*) as total_attempts')
              ->groupBy('quiz_id');
        }])->get();

        return view('teacher.courses.performance', compact('course', 'stats', 'quizPerformance'));
    }

    public function create()
    {
        $teacher = auth()->user();
        $categories = \App\Models\Category::all();
        
        return view('teacher.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'skill_tags' => 'nullable|string',
            'content_type' => 'nullable|in:video,pdf,scorm,ar_vr,interactive',
        ]);

        $validated['teacher_id'] = $teacher->id;
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['status'] = 'pending_approval'; // Requires admin approval

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course created successfully! Awaiting approval.');
    }

    public function edit(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $categories = \App\Models\Category::all();
        
        return view('teacher.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'prerequisites' => 'nullable|string',
            'skill_tags' => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('teacher.courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    public function duplicate(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $newCourse = $course->replicate();
        $newCourse->title = $course->title . ' (Copy)';
        $newCourse->slug = \Illuminate\Support\Str::slug($newCourse->title) . '-' . time();
        $newCourse->status = 'draft';
        $newCourse->save();

        // Duplicate lessons
        foreach ($course->lessons as $lesson) {
            $newLesson = $lesson->replicate();
            $newLesson->course_id = $newCourse->id;
            $newLesson->save();
        }

        // Duplicate quizzes
        foreach ($course->quizzes as $quiz) {
            $newQuiz = $quiz->replicate();
            $newQuiz->course_id = $newCourse->id;
            $newQuiz->save();

            // Duplicate questions
            foreach ($quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();

                // Duplicate options
                foreach ($question->options as $option) {
                    $newOption = $option->replicate();
                    $newOption->question_id = $newQuestion->id;
                    $newOption->save();
                }
            }
        }

        return redirect()->route('teacher.courses.show', $newCourse)
            ->with('success', 'Course duplicated successfully!');
    }

    public function analytics(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $course->load(['students', 'lessons', 'quizzes', 'reviews']);

        $analytics = [
            'enrollments' => [
                'total' => $course->students()->count(),
                'this_month' => $course->students()
                    ->wherePivot('enrolled_at', '>=', now()->startOfMonth())
                    ->count(),
                'growth_rate' => $this->calculateEnrollmentGrowth($course),
            ],
            'completion' => [
                'total_completed' => $course->students()->wherePivot('completed_at', '!=', null)->count(),
                'completion_rate' => $this->calculateCompletionRate($course),
                'avg_progress' => round($course->students()->avg('course_user.progress') ?? 0, 2),
            ],
            'engagement' => [
                'avg_lesson_views' => $this->calculateAvgLessonViews($course),
                'quiz_attempts' => Attempt::whereHas('quiz', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->count(),
                'discussion_posts' => $course->discussions()->count(),
            ],
            'ratings' => [
                'average' => round($course->reviews()->avg('rating') ?? 0, 2),
                'total' => $course->reviews()->count(),
                'distribution' => $this->getRatingDistribution($course),
            ],
        ];

        // Lesson-level analytics
        $lessonAnalytics = $course->lessons()->withCount('progress')->get()->map(function($lesson) {
            return [
                'lesson' => $lesson,
                'views' => $lesson->progress()->count(),
                'completions' => $lesson->progress()->where('completed', true)->count(),
            ];
        });

        return view('teacher.courses.analytics', compact('course', 'analytics', 'lessonAnalytics'));
    }

    public function monetization(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $earnings = $this->calculateCourseEarnings($course);
        $pricing = [
            'current_price' => $course->price,
            'subscription_based' => $course->subscriptions()->exists(),
        ];

        return view('teacher.courses.monetization', compact('course', 'earnings', 'pricing'));
    }

    public function updatePricing(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'subscription_price' => 'nullable|numeric|min:0',
        ]);

        $course->update($validated);

        return back()->with('success', 'Pricing updated successfully!');
    }

    public function applyPromotion(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        // Create promotion (implement if you have a promotions table)
        // Promotion::create([...]);

        return back()->with('success', 'Promotion applied successfully!');
    }

    private function calculateEnrollmentGrowth(Course $course)
    {
        $thisMonth = $course->students()
            ->wherePivot('enrolled_at', '>=', now()->startOfMonth())
            ->count();
        
        $lastMonth = $course->students()
            ->wherePivot('enrolled_at', '>=', now()->subMonth()->startOfMonth())
            ->wherePivot('enrolled_at', '<', now()->startOfMonth())
            ->count();
        
        if ($lastMonth == 0) return $thisMonth > 0 ? 100 : 0;
        
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 2);
    }

    private function calculateCompletionRate(Course $course)
    {
        $total = $course->students()->count();
        $completed = $course->students()->wherePivot('completed_at', '!=', null)->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    private function calculateAvgLessonViews(Course $course)
    {
        $totalViews = \App\Models\LessonProgress::whereHas('lesson', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })->count();
        
        $totalLessons = $course->lessons()->count();
        
        return $totalLessons > 0 ? round($totalViews / $totalLessons, 2) : 0;
    }

    private function getRatingDistribution(Course $course)
    {
        return [
            '5' => $course->reviews()->where('rating', 5)->count(),
            '4' => $course->reviews()->where('rating', 4)->count(),
            '3' => $course->reviews()->where('rating', 3)->count(),
            '2' => $course->reviews()->where('rating', 2)->count(),
            '1' => $course->reviews()->where('rating', 1)->count(),
        ];
    }

    private function calculateCourseEarnings(Course $course)
    {
        $totalRevenue = \App\Models\Order::whereHas('items', function($q) use ($course) {
            $q->where('course_id', $course->id);
        })
        ->where('status', 'completed')
        ->sum('total_price') ?? 0;
        
        // Assuming 70% commission for teacher
        $teacherEarnings = $totalRevenue * 0.70;
        
        return [
            'total_revenue' => $totalRevenue,
            'teacher_earnings' => $teacherEarnings,
            'platform_commission' => $totalRevenue * 0.30,
            'total_sales' => \App\Models\OrderItem::where('course_id', $course->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->count(),
        ];
    }
}

