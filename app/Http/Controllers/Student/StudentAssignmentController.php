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
        
        // Ensure student owns this assignment
        if ($assignment->student_id !== $student->id) {
            abort(403);
        }

        $assignment->load(['course']);
        
        return view('student.assignments.show', compact('assignment'));
    }
}

