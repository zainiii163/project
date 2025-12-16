<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Course $course)
    {
        $this->authorize('view', $course);
        
        $assignments = $course->assignments()
            ->with('student')
            ->latest()
            ->paginate(20);

        return view('assignments.index', compact('course', 'assignments'));
    }

    public function create(Course $course)
    {
        $this->authorize('update', $course);
        return view('assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'max_score' => 'required|integer|min:1',
            'submission_type' => 'required|in:text,file',
        ]);

        $validated['course_id'] = $course->id;

        Assignment::create($validated);

        return redirect()->route('assignments.index', $course)
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment->course);
        $assignment->load('course', 'student');
        return view('assignments.show', compact('assignment'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $this->authorize('view', $assignment->course);

        $validated = $request->validate([
            'content' => 'required_if:submission_type,text|string',
            'file' => 'required_if:submission_type,file|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update([
            'student_id' => auth()->id(),
            'content' => $validated['content'] ?? null,
            'file_path' => $validated['file_path'] ?? $assignment->file_path,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Assignment submitted successfully!');
    }

    public function grade(Request $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment->course);

        $validated = $request->validate([
            'grade' => 'required|string',
            'feedback' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return back()->with('success', 'Assignment graded successfully!');
    }
}

