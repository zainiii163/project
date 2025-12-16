<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class StudentProgressController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        $courses = $student->courses()->with(['teacher', 'category'])
            ->withCount('lessons')
            ->get();

        $progressData = [];
        foreach ($courses as $course) {
            $completedLessons = $student->lessonProgress()
                ->whereHas('lesson', function($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->where('completed', true)
                ->count();
            
            $totalLessons = $course->lessons_count;
            $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $progressData[] = [
                'course' => $course,
                'completed_lessons' => $completedLessons,
                'total_lessons' => $totalLessons,
                'progress' => round($progress, 2),
            ];
        }

        return view('student.progress.index', compact('progressData'));
    }
}

