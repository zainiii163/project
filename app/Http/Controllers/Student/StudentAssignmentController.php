<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class StudentAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        
        $query = $student->assignments()->with(['course']);

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

        return view('student.assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $student = auth()->user();
        
        // Check if student is enrolled in the course
        if (!$student->courses()->where('courses.id', $assignment->course_id)->exists()) {
            abort(403);
        }

        $assignment->load(['course']);
        
        return view('student.assignments.show', compact('assignment'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $student = auth()->user();
        
        // Check if student is enrolled in the course
        if (!$student->courses()->where('courses.id', $assignment->course_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required_if:submission_type,text|string',
            'file' => 'required_if:submission_type,file|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment->update([
            'student_id' => $student->id,
            'content' => $validated['content'] ?? $assignment->content,
            'file_path' => $validated['file_path'] ?? $assignment->file_path,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Assignment submitted successfully!');
    }
}

