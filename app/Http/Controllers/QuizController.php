<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\Attempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('quizzes.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'max_attempts' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $validated['course_id'] = $course->id;
        $validated['is_published'] = $request->has('is_published');

        $quiz = Quiz::create($validated);

        return redirect()->route('quizzes.edit', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz->course);
        $quiz->load('questions.options');
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz->course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer',
            'max_attempts' => 'required|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean',
        ]);

        $quiz->update($validated);

        return back()->with('success', 'Quiz updated successfully!');
    }

    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz->course);
        
        $quiz->load('questions.options');
        return view('quizzes.show', compact('quiz'));
    }

    public function take(Quiz $quiz, Attempt $attempt)
    {
        $this->authorize('view', $quiz->course);

        if ($attempt->user_id !== auth()->id() || $attempt->status !== 'started') {
            return redirect()->route('quizzes.show', $quiz)
                ->with('error', 'Invalid attempt.');
        }

        $quiz->load('questions.options');
        return view('quizzes.take', compact('quiz', 'attempt'));
    }

    public function attempt(Request $request, Quiz $quiz)
    {
        $this->authorize('view', $quiz->course);

        $attempt = $quiz->attempts()->create([
            'user_id' => auth()->id(),
            'start_time' => now(),
            'status' => 'started',
        ]);

        return redirect()->route('quizzes.take', ['quiz' => $quiz->id, 'attempt' => $attempt->id]);
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $this->authorize('view', $quiz->course);

        $attempt = $quiz->attempts()->where('user_id', auth()->id())
            ->where('status', 'started')
            ->latest()
            ->firstOrFail();

        $answers = $request->input('answers', []);
        $score = 0;
        $totalPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $selectedOptionId = $answers[$question->id] ?? null;

            if ($selectedOptionId) {
                $option = Option::find($selectedOptionId);
                $isCorrect = $option && $option->is_correct;

                $attempt->answers()->create([
                    'question_id' => $question->id,
                    'option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                ]);

                if ($isCorrect) {
                    $score += $question->points;
                }
            }
        }

        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $passed = $percentage >= $quiz->pass_score;

        $attempt->update([
            'score' => $score,
            'end_time' => now(),
            'submitted_at' => now(),
            'status' => $passed ? 'completed' : 'failed',
        ]);

        return redirect()->route('quizzes.result', $attempt)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function result(Attempt $attempt)
    {
        $this->authorize('view', $attempt->quiz->course);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $attempt->load('quiz.questions.options', 'answers');
        return view('quizzes.result', compact('attempt'));
    }
}
