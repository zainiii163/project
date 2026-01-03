<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherLessonController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Lesson::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['course']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $lessons = $query->latest()->paginate(20);
        $courses = $teacher->taughtCourses()->get();

        return view('teacher.lessons.index', compact('lessons', 'courses'));
    }

    public function create(Request $request)
    {
        $teacher = auth()->user();
        $courses = $teacher->taughtCourses()->get();
        $courseId = $request->get('course_id');
        
        return view('teacher.lessons.create', compact('courses', 'courseId'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user();
        
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'type' => 'required|in:video,text,quiz,file,pdf',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
            'downloadable_materials' => 'nullable|array',
            'downloadable_materials.*' => 'file|max:10240',
        ]);

        // Verify teacher owns the course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== $teacher->id) {
            return back()->withErrors(['course_id' => 'You can only add lessons to your own courses']);
        }

        $validated['order'] = $validated['order'] ?? $course->lessons()->max('order') + 1;

        // Handle video URL or uploaded video file
        if ($request->has('video_url')) {
            $validated['content_url'] = $request->video_url;
        } elseif ($request->hasFile('video_file')) {
            $validated['content_url'] = $request->file('video_file')->store('lessons/videos', 'public');
        } elseif ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        // Handle downloadable materials
        if ($request->hasFile('downloadable_materials')) {
            $materials = [];
            foreach ($request->file('downloadable_materials') as $file) {
                $materials[] = $file->store('lessons/materials', 'public');
            }
            $validated['downloadable_materials'] = json_encode($materials);
        }

        $lesson = Lesson::create($validated);

        return redirect()->route('teacher.lessons.index', ['course_id' => $course->id])
            ->with('success', 'Lesson created successfully!');
    }

    public function edit(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns the lesson's course
        if ($lesson->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $courses = $teacher->taughtCourses()->get();
        
        return view('teacher.lessons.edit', compact('lesson', 'courses'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns the lesson's course
        if ($lesson->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content_url' => 'nullable|string',
            'video_url' => 'nullable|url',
            'type' => 'required|in:video,text,quiz,file,pdf',
            'duration' => 'nullable|integer',
            'order' => 'nullable|integer',
            'is_preview' => 'boolean',
            'downloadable_materials' => 'nullable|array',
            'downloadable_materials.*' => 'file|max:10240',
        ]);

        // Verify teacher owns the course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== $teacher->id) {
            return back()->withErrors(['course_id' => 'You can only add lessons to your own courses']);
        }

        // Handle video URL or uploaded video file
        if ($request->has('video_url')) {
            $validated['content_url'] = $request->video_url;
        } elseif ($request->hasFile('video_file')) {
            $validated['content_url'] = $request->file('video_file')->store('lessons/videos', 'public');
        } elseif ($request->hasFile('content_file')) {
            $validated['content_url'] = $request->file('content_file')->store('lessons', 'public');
        }

        // Handle downloadable materials
        if ($request->hasFile('downloadable_materials')) {
            $materials = [];
            foreach ($request->file('downloadable_materials') as $file) {
                $materials[] = $file->store('lessons/materials', 'public');
            }
            $validated['downloadable_materials'] = json_encode($materials);
        }

        $lesson->update($validated);

        return redirect()->route('teacher.lessons.index', ['course_id' => $course->id])
            ->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Lesson $lesson)
    {
        $teacher = auth()->user();
        
        // Verify teacher owns the lesson's course
        if ($lesson->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $courseId = $lesson->course_id;
        $lesson->delete();

        return redirect()->route('teacher.lessons.index', ['course_id' => $courseId])
            ->with('success', 'Lesson deleted successfully!');
    }
}

