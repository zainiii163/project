<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminQuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::with(['course', 'questions']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $quizzes = $query->latest()->paginate(20);
        $courses = Course::all();

        return view('admin.quizzes.index', compact('quizzes', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer',
            'max_attempts' => 'nullable|integer',
        ]);

        Quiz::create($validated);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz created successfully!');
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::all();
        $quiz->load('questions.options');
        return view('admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'pass_score' => 'required|integer|min:0|max:100',
            'time_limit' => 'nullable|integer',
            'max_attempts' => 'nullable|integer',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions.options', 'attempts.user']);
        return view('admin.quizzes.show', compact('quiz'));
    }
}

