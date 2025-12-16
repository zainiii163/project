<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminLessonController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::with(['course']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $lessons = $query->latest()->paginate(20);
        $courses = Course::all();

        return view('admin.lessons.index', compact('lessons', 'courses'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.lessons.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_free' => 'nullable|boolean',
            'content_type' => 'required|in:video,text,pdf,link',
        ]);

        Lesson::create($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson created successfully!');
    }

    public function edit(Lesson $lesson)
    {
        $courses = Course::all();
        return view('admin.lessons.edit', compact('lesson', 'courses'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_free' => 'nullable|boolean',
            'content_type' => 'required|in:video,text,pdf,link',
        ]);

        $lesson->update($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson deleted successfully!');
    }
}

