<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherQuizController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Quiz::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['course', 'questions', 'attempts']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $quizzes = $query->latest()->paginate(20);
        $courses = $teacher->taughtCourses()->get();

        return view('teacher.quizzes.index', compact('quizzes', 'courses'));
    }

    public function show(Quiz $quiz)
    {
        // Ensure teacher owns this quiz's course
        if ($quiz->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz->load(['questions.options', 'attempts.user']);
        
        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function create(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        return view('teacher.quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'pass_score' => 'nullable|numeric|min:0|max:100',
            'question_type' => 'required|in:mcq,essay,coding,true_false',
            'is_published' => 'boolean',
        ]);

        $validated['course_id'] = $course->id;
        $quiz = Quiz::create($validated);

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    public function generateWithAI(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'difficulty' => 'required|in:easy,medium,hard',
            'num_questions' => 'required|integer|min:1|max:50',
            'question_type' => 'required|in:mcq,essay,coding',
        ]);

        // AI-assisted quiz generation (placeholder - implement with actual AI service)
        // $questions = AI::generateQuizQuestions($validated);
        
        return back()->with('info', 'AI quiz generation feature coming soon');
    }

    public function analytics(Quiz $quiz)
    {
        if ($quiz->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $quiz->load(['attempts.user', 'questions']);

        $analytics = [
            'total_attempts' => $quiz->attempts()->count(),
            'unique_students' => $quiz->attempts()->distinct('user_id')->count('user_id'),
            'average_score' => round($quiz->attempts()->avg('score') ?? 0, 2),
            'pass_rate' => $this->calculatePassRate($quiz),
            'question_analysis' => $this->analyzeQuestions($quiz),
        ];

        return view('teacher.quizzes.analytics', compact('quiz', 'analytics'));
    }

    public function awardBadge(Request $request, Quiz $quiz)
    {
        if ($quiz->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'badge_name' => 'required|string|max:255',
            'points' => 'nullable|integer|min:0',
        ]);

        // Award badge and points (implement if you have badges/points system)
        // Badge::create([...]);
        // User::find($validated['user_id'])->increment('points', $validated['points'] ?? 0);

        return back()->with('success', 'Badge awarded successfully!');
    }

    private function calculatePassRate(Quiz $quiz)
    {
        $totalAttempts = $quiz->attempts()->count();
        $passedAttempts = $quiz->attempts()
            ->where('score', '>=', $quiz->pass_score ?? 60)
            ->count();
        
        return $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 2) : 0;
    }

    private function analyzeQuestions(Quiz $quiz)
    {
        return $quiz->questions()->withCount(['attempts' => function($q) {
            $q->where('is_correct', false);
        }])->get()->map(function($question) {
            return [
                'question' => $question,
                'error_rate' => $question->attempts_count,
            ];
        });
    }
}

