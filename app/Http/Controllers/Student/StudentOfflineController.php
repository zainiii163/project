<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class StudentOfflineController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        $courses = $student->courses()
            ->with(['lessons', 'teacher'])
            ->get();

        $downloadableCourses = $courses->filter(function($course) {
            return $course->lessons()->whereNotNull('downloadable_materials')->exists();
        });

        return view('student.offline.index', compact('courses', 'downloadableCourses'));
    }

    public function downloadCourse(Course $course)
    {
        $student = auth()->user();

        // Check enrollment
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'You must be enrolled in this course to download materials.');
        }

        $lessons = $course->lessons()
            ->whereNotNull('downloadable_materials')
            ->get();

        if ($lessons->isEmpty()) {
            return back()->with('error', 'No downloadable materials available for this course.');
        }

        // Create zip file
        $zipFileName = 'course_' . $course->slug . '_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'Unable to create download package.');
        }

        // Add course info file
        $courseInfo = "Course: {$course->title}\n";
        $courseInfo .= "Teacher: {$course->teacher->name}\n";
        $courseInfo .= "Downloaded: " . now()->format('Y-m-d H:i:s') . "\n\n";
        $courseInfo .= "Lessons:\n";
        foreach ($lessons as $lesson) {
            $courseInfo .= "- {$lesson->title}\n";
        }
        $zip->addFromString('course_info.txt', $courseInfo);

        // Add lesson materials
        foreach ($lessons as $lesson) {
            $materials = json_decode($lesson->downloadable_materials, true) ?? [];
            
            foreach ($materials as $materialPath) {
                if (Storage::disk('public')->exists($materialPath)) {
                    $fileContent = Storage::disk('public')->get($materialPath);
                    $fileName = basename($materialPath);
                    $zip->addFromString("lessons/{$lesson->order}_{$lesson->slug}/{$fileName}", $fileContent);
                }
            }

            // Add lesson video if available and downloadable
            if ($lesson->content_url && $lesson->type === 'video') {
                // For cloud storage, get temporary URL
                // For local storage, add file directly
                if (Storage::disk('public')->exists($lesson->content_url)) {
                    $videoContent = Storage::disk('public')->get($lesson->content_url);
                    $zip->addFromString("lessons/{$lesson->order}_{$lesson->slug}/video.mp4", $videoContent);
                }
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function downloadLesson(Lesson $lesson)
    {
        $student = auth()->user();
        $course = $lesson->course;

        // Check enrollment
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403, 'You must be enrolled in this course to download materials.');
        }

        $materials = json_decode($lesson->downloadable_materials, true) ?? [];

        if (empty($materials)) {
            return back()->with('error', 'No downloadable materials available for this lesson.');
        }

        // If single file, download directly
        if (count($materials) === 1) {
            $materialPath = $materials[0];
            if (Storage::disk('public')->exists($materialPath)) {
                return Storage::disk('public')->download($materialPath);
            }
        }

        // Multiple files - create zip
        $zipFileName = 'lesson_' . $lesson->slug . '_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'Unable to create download package.');
        }

        foreach ($materials as $materialPath) {
            if (Storage::disk('public')->exists($materialPath)) {
                $fileContent = Storage::disk('public')->get($materialPath);
                $fileName = basename($materialPath);
                $zip->addFromString($fileName, $fileContent);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function syncProgress(Request $request)
    {
        // For offline progress sync when user comes back online
        $validated = $request->validate([
            'progress' => 'required|array',
            'progress.*.lesson_id' => 'required|exists:lessons,id',
            'progress.*.progress_percentage' => 'required|numeric|min:0|max:100',
            'progress.*.last_accessed_at' => 'required|date',
        ]);

        $student = auth()->user();

        foreach ($validated['progress'] as $progressData) {
            $lesson = Lesson::find($progressData['lesson_id']);
            
            if ($student->courses()->where('courses.id', $lesson->course_id)->exists()) {
                $lesson->progress()->updateOrCreate(
                    [
                        'user_id' => $student->id,
                        'lesson_id' => $lesson->id,
                        'course_id' => $lesson->course_id,
                    ],
                    [
                        'progress_percentage' => $progressData['progress_percentage'],
                        'last_accessed_at' => $progressData['last_accessed_at'],
                        'is_completed' => $progressData['progress_percentage'] >= 100,
                    ]
                );
            }
        }

        return response()->json(['message' => 'Progress synced successfully']);
    }
}

