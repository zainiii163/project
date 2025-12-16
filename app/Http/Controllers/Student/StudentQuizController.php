<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentQuizController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        
        // Get quizzes from enrolled courses
        $enrolledCourseIds = $student->courses()->pluck('courses.id');
        
        $query = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'attempts' => function($q) use ($student) {
                $q->where('user_id', $student->id);
            }]);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $quizzes = $query->latest()->paginate(20);
        $courses = $student->courses()->get();

        return view('student.quizzes.index', compact('quizzes', 'courses'));
    }

    public function myAttempts()
    {
        $student = auth()->user();
        
        $attempts = Attempt::where('user_id', $student->id)
            ->with(['quiz.course'])
            ->latest()
            ->paginate(20);

        return view('student.quizzes.attempts', compact('attempts'));
    }

    public function attempt(Quiz $quiz)
    {
        $student = auth()->user();
        
        // Check if student is enrolled
        if (!$student->courses()->where('courses.id', $quiz->course_id)->exists()) {
            abort(403);
        }

        // Check max attempts
        $attemptCount = Attempt::where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->count();
        
        if ($quiz->max_attempts && $attemptCount >= $quiz->max_attempts) {
            return back()->with('error', 'You have reached the maximum number of attempts for this quiz.');
        }

        $quiz->load(['questions.options', 'course']);

        // Adaptive difficulty based on past performance
        $pastAttempts = Attempt::where('user_id', $student->id)
            ->where('quiz_id', $quiz->id)
            ->get();
        
        $avgScore = $pastAttempts->avg('score') ?? 0;
        $difficulty = $this->calculateAdaptiveDifficulty($avgScore);

        return view('student.quizzes.attempt', compact('quiz', 'difficulty', 'attemptCount'));
    }

    public function submitAttempt(Request $request, Quiz $quiz)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $quiz->course_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required',
        ]);

        // Calculate score
        $score = $this->calculateScore($quiz, $validated['answers']);

        // Create attempt
        $attempt = Attempt::create([
            'user_id' => $student->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'answers' => $validated['answers'],
            'completed_at' => now(),
        ]);

        // Award points if passed
        if ($score >= ($quiz->pass_score ?? 60)) {
            // Award points (implement if you have points system)
            // $student->increment('points', 10);
            
            // Check if certificate should be issued
            $this->checkCertificateEligibility($student, $quiz->course);
        }

        return redirect()->route('student.quizzes.result', $attempt)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result(Attempt $attempt)
    {
        $student = auth()->user();
        
        if ($attempt->user_id !== $student->id) {
            abort(403);
        }

        $attempt->load(['quiz.questions.options', 'quiz.course']);

        // Get correct/incorrect answers
        $results = $this->getQuizResults($attempt);

        return view('student.quizzes.result', compact('attempt', 'results'));
    }

    public function trackImprovement()
    {
        $student = auth()->user();
        
        // Track quiz score improvements over time
        $improvements = Attempt::where('user_id', $student->id)
            ->with(['quiz.course'])
            ->select('quiz_id', \DB::raw('AVG(score) as avg_score'), \DB::raw('MAX(score) as max_score'), \DB::raw('COUNT(*) as attempts'))
            ->groupBy('quiz_id')
            ->get()
            ->map(function($item) {
                return [
                    'quiz' => $item->quiz,
                    'avg_score' => round($item->avg_score, 2),
                    'max_score' => $item->max_score,
                    'attempts' => $item->attempts,
                    'improvement' => $this->calculateImprovement($item->quiz_id, $item->avg_score),
                ];
            });

        return view('student.quizzes.improvement', compact('improvements'));
    }

    private function calculateAdaptiveDifficulty($avgScore)
    {
        if ($avgScore >= 80) return 'hard';
        if ($avgScore >= 60) return 'medium';
        return 'easy';
    }

    private function calculateScore(Quiz $quiz, $answers)
    {
        $totalQuestions = $quiz->questions()->count();
        $correctAnswers = 0;

        foreach ($quiz->questions as $question) {
            $studentAnswer = $answers[$question->id] ?? null;
            
            if ($question->type === 'mcq' || $question->type === 'true_false') {
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $studentAnswer == $correctOption->id) {
                    $correctAnswers++;
                }
            } elseif ($question->type === 'essay') {
                // Manual grading required
                $correctAnswers += 0.5; // Partial credit
            }
        }

        return ($correctAnswers / $totalQuestions) * 100;
    }

    private function getQuizResults(Attempt $attempt)
    {
        $results = [];
        
        foreach ($attempt->quiz->questions as $question) {
            $studentAnswer = $attempt->answers[$question->id] ?? null;
            $correctOption = $question->options()->where('is_correct', true)->first();
            
            $results[] = [
                'question' => $question,
                'student_answer' => $studentAnswer,
                'correct_answer' => $correctOption,
                'is_correct' => $studentAnswer == $correctOption->id,
            ];
        }
        
        return $results;
    }

    private function checkCertificateEligibility($student, $course)
    {
        // Check if all quizzes passed and course completed
        $allQuizzesPassed = true;
        foreach ($course->quizzes as $quiz) {
            $bestAttempt = Attempt::where('user_id', $student->id)
                ->where('quiz_id', $quiz->id)
                ->max('score');
            
            if ($bestAttempt < ($quiz->pass_score ?? 60)) {
                $allQuizzesPassed = false;
                break;
            }
        }

        $courseCompleted = $student->courses()
            ->where('courses.id', $course->id)
            ->wherePivot('completed_at', '!=', null)
            ->exists();

        if ($allQuizzesPassed && $courseCompleted) {
            // Issue certificate
            Certificate::firstOrCreate([
                'user_id' => $student->id,
                'course_id' => $course->id,
            ], [
                'certificate_url' => $this->generateCertificate($student, $course),
                'issued_at' => now(),
            ]);
        }
    }

    private function generateCertificate($student, $course)
    {
        // Generate certificate PDF (implement with DomPDF or similar)
        // return storage_path('certificates/' . $student->id . '_' . $course->id . '.pdf');
        return 'certificates/' . $student->id . '_' . $course->id . '.pdf';
    }

    private function calculateImprovement($quizId, $currentAvg)
    {
        // Compare with previous attempts
        return 'improving'; // Placeholder
    }
}

