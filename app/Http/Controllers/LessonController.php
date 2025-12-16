<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'type' => 'required|in:video,text,quiz,file',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $validated['order'] ?? $course->lessons()->max('order') + 1;

        if ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        $lesson = Lesson::create($validated);

        return back()->with('success', 'Lesson created successfully!');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'type' => 'required|in:video,text,quiz,file',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
        ]);

        if ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        $lesson->update($validated);

        return back()->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Lesson $lesson)
    {
        $this->authorize('update', $lesson->course);
        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully!');
    }

    public function show($courseSlug, Lesson $lesson)
    {
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $this->authorize('view', $course);

        // Check if user is enrolled
        if (auth()->check() && !$course->students()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must enroll in this course first.');
        }

        $lesson->load('course', 'quiz.questions.options');
        $nextLesson = $course->lessons()->where('order', '>', $lesson->order)->first();
        $prevLesson = $course->lessons()->where('order', '<', $lesson->order)->orderBy('order', 'desc')->first();

        // Update progress
        if (auth()->check()) {
            $progress = $lesson->progress()->firstOrCreate([
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
            ], [
                'last_accessed_at' => now(),
            ]);

            $progress->update(['last_accessed_at' => now()]);
        }

        return view('lessons.show', compact('lesson', 'course', 'nextLesson', 'prevLesson'));
    }
}

