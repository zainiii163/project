<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Assignment::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['course', 'student']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('status')) {
            if ($request->status == 'submitted') {
                $query->whereNotNull('submitted_at');
            } elseif ($request->status == 'graded') {
                $query->whereNotNull('grade');
            } else {
                $query->whereNull('submitted_at');
            }
        }

        $assignments = $query->latest()->paginate(20);
        $courses = $teacher->taughtCourses()->get();

        return view('teacher.assignments.index', compact('assignments', 'courses'));
    }

    public function show(Assignment $assignment)
    {
        // Ensure teacher owns this assignment's course
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $assignment->load(['course', 'student']);
        
        return view('teacher.assignments.show', compact('assignment'));
    }

    public function grade(Request $request, Assignment $assignment)
    {
        // Ensure teacher owns this assignment's course
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => 'required_if:evaluation_type,manual|string|max:255',
            'feedback' => 'nullable|string',
            'evaluation_type' => 'required|in:manual,automated',
            'score' => 'nullable|numeric|min:0|max:' . $assignment->max_score,
            'auto_evaluate' => 'nullable|boolean',
            'min_words' => 'nullable|integer|min:0',
        ]);

        // If automated evaluation, calculate score based on criteria
        if ($validated['evaluation_type'] === 'automated' && $request->has('auto_evaluate')) {
            $score = $this->automatedEvaluation($assignment, $request);
            $validated['score'] = $score;
            $validated['grade'] = $this->calculateGrade($score, $assignment->max_score);
        }

        $assignment->update($validated);

        // Award points for assignment completion (if gamification enabled)
        // if (setting('gamification.points_enabled')) {
        //     $assignment->student->increment('points', setting('gamification.points_per_assignment', 10));
        // }

        return back()->with('success', 'Assignment graded successfully!');
    }

    private function automatedEvaluation(Assignment $assignment, Request $request)
    {
        // Automated evaluation logic based on assignment type
        $score = 0;
        
        // Example: For code assignments, check syntax, test cases, etc.
        // For text assignments, check word count, keywords, etc.
        // This is a placeholder - implement based on your specific needs
        
        if ($assignment->submission_type === 'text') {
            $content = $assignment->content ?? '';
            $wordCount = str_word_count($content);
            $minWords = $request->get('min_words', 100);
            
            if ($wordCount >= $minWords) {
                $score = $assignment->max_score * 0.7; // Base score for meeting requirements
            }
        }
        
        return $score;
    }

    private function calculateGrade($score, $maxScore)
    {
        $percentage = ($score / $maxScore) * 100;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'A-';
        if ($percentage >= 75) return 'B+';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 65) return 'B-';
        if ($percentage >= 60) return 'C+';
        if ($percentage >= 55) return 'C';
        if ($percentage >= 50) return 'C-';
        return 'F';
    }

    public function create(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        return view('teacher.assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'due_date' => 'required|date|after:today',
            'max_score' => 'required|numeric|min:0',
            'submission_type' => 'required|in:file,text,code',
        ]);

        $validated['course_id'] = $course->id;
        Assignment::create($validated);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    public function provideFeedback(Request $request, Assignment $assignment)
    {
        if ($assignment->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'feedback' => 'required|string|max:2000',
            'suggestions' => 'nullable|string|max:1000',
        ]);

        $assignment->update([
            'feedback' => $validated['feedback'],
            'feedback_provided_at' => now(),
        ]);

        return back()->with('success', 'Feedback provided successfully!');
    }

    public function flagStrugglingStudents(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        // Find students with low progress or poor quiz scores
        $strugglingStudents = $course->students()
            ->wherePivot('progress', '<', 30)
            ->orWhereHas('attempts', function($q) use ($course) {
                $q->whereHas('quiz', function($q2) use ($course) {
                    $q2->where('course_id', $course->id);
                })->where('score', '<', 50);
            })
            ->get();

        return view('teacher.courses.struggling-students', compact('course', 'strugglingStudents'));
    }

    public function exportReport(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $students = $course->students()
            ->withPivot('enrolled_at', 'progress', 'completed_at')
            ->get()
            ->map(function($student) use ($course) {
                $attempts = \App\Models\Attempt::where('user_id', $student->id)
                    ->whereHas('quiz', function($q) use ($course) {
                        $q->where('course_id', $course->id);
                    })
                    ->get();
                
                return [
                    'name' => $student->name,
                    'email' => $student->email,
                    'enrolled_at' => $student->pivot->enrolled_at,
                    'progress' => $student->pivot->progress,
                    'completed_at' => $student->pivot->completed_at,
                    'avg_quiz_score' => round($attempts->avg('score') ?? 0, 2),
                    'total_attempts' => $attempts->count(),
                ];
            });

        $filename = 'course_report_' . $course->slug . '_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Enrolled At', 'Progress', 'Completed At', 'Avg Quiz Score', 'Total Attempts']);
            
            foreach ($students as $student) {
                fputcsv($file, [
                    $student['name'],
                    $student['email'],
                    $student['enrolled_at'],
                    $student['progress'],
                    $student['completed_at'],
                    $student['avg_quiz_score'],
                    $student['total_attempts'],
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

