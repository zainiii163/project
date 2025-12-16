<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user();
        
        $query = $student->courses()->with(['teacher', 'category']);

        if ($request->has('status')) {
            if ($request->status == 'completed') {
                $query->wherePivot('completed_at', '!=', null);
            } else {
                $query->wherePivot('completed_at', null);
            }
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest('course_user.created_at')->paginate(20);

        return view('student.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $student = auth()->user();
        
        // Check if student is enrolled
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course');
        }

        $course->load(['lessons', 'quizzes', 'teacher', 'category', 'reviews.user']);
        
        // Get student's progress
        $enrollment = $student->courses()->where('courses.id', $course->id)->first();
        $progress = $enrollment->pivot->progress ?? 0;
        
        // Get completed lessons
        $completedLessons = $student->lessonProgress()
            ->whereHas('lesson', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->where('completed', true)
            ->pluck('lesson_id');

        return view('student.courses.show', compact('course', 'progress', 'completedLessons'));
    }

    public function bookmark(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            return back()->with('error', 'You must be enrolled to bookmark this course.');
        }

        // Toggle bookmark (implement if you have a bookmarks table)
        // Bookmark::firstOrCreate(['user_id' => $student->id, 'course_id' => $course->id]);
        
        return back()->with('success', 'Course bookmarked!');
    }

    public function resume(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        // Get last watched lesson
        $lastLesson = $student->lessonProgress()
            ->whereHas('lesson', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->where('completed', false)
            ->latest()
            ->first();

        if ($lastLesson) {
            return redirect()->route('student.lessons.show', $lastLesson->lesson_id);
        }

        // If no progress, start from first lesson
        $firstLesson = $course->lessons()->orderBy('order')->first();
        
        if ($firstLesson) {
            return redirect()->route('student.lessons.show', $firstLesson->id);
        }

        return back()->with('info', 'No lessons available in this course.');
    }

    public function downloadResources(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        // Get downloadable resources (PDFs, etc.)
        $resources = $course->lessons()
            ->where('type', 'pdf')
            ->orWhere('type', 'downloadable')
            ->get();

        return view('student.courses.download-resources', compact('course', 'resources'));
    }

    public function recommendations()
    {
        $student = auth()->user();
        
        // AI-based recommendations (simplified version)
        $enrolledCourseIds = $student->courses()->pluck('courses.id');
        
        // Get courses in same categories
        $categoryIds = $student->courses()->pluck('category_id')->filter();
        
        $recommendations = Course::where('status', 'published')
            ->whereNotIn('id', $enrolledCourseIds)
            ->where(function($q) use ($categoryIds) {
                if ($categoryIds->isNotEmpty()) {
                    $q->whereIn('category_id', $categoryIds);
                }
            })
            ->withCount('students')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('students_count')
            ->limit(10)
            ->get();

        return view('student.courses.recommendations', compact('recommendations'));
    }

    public function learningPath()
    {
        $student = auth()->user();
        
        // Track skill development
        $enrolledCourses = $student->courses()
            ->with(['category', 'lessons'])
            ->get();
        
        $skills = [];
        foreach ($enrolledCourses as $course) {
            if ($course->skill_tags) {
                $tags = explode(',', $course->skill_tags);
                foreach ($tags as $tag) {
                    $skills[trim($tag)] = ($skills[trim($tag)] ?? 0) + 1;
                }
            }
        }

        // Suggest next courses based on skills
        $suggestedCourses = Course::where('status', 'published')
            ->whereNotIn('id', $enrolledCourses->pluck('id'))
            ->where(function($q) use ($skills) {
                foreach (array_keys($skills) as $skill) {
                    $q->orWhere('skill_tags', 'like', '%' . $skill . '%');
                }
            })
            ->withCount('students')
            ->orderByDesc('students_count')
            ->limit(5)
            ->get();

        return view('student.courses.learning-path', compact('skills', 'suggestedCourses', 'enrolledCourses'));
    }
}

