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
}

