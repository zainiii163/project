<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherDiscussionController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Discussion::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->whereNull('parent_id')
          ->with(['user', 'course', 'replies']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $discussions = $query->latest()->paginate(20);
        $courses = $teacher->taughtCourses()->get();

        return view('teacher.discussions.index', compact('discussions', 'courses'));
    }

    public function show(Discussion $discussion)
    {
        // Ensure teacher owns this discussion's course
        if ($discussion->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $discussion->load(['user', 'course', 'replies.user']);
        
        return view('teacher.discussions.show', compact('discussion'));
    }

    public function reply(Request $request, Discussion $discussion)
    {
        // Ensure teacher owns this discussion's course
        if ($discussion->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        Discussion::create([
            'course_id' => $discussion->course_id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'parent_id' => $discussion->id,
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }
}

