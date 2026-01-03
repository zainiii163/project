<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\CloudStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfflineAccessController extends Controller
{
    protected $cloudStorage;

    public function __construct(CloudStorageService $cloudStorage)
    {
        $this->cloudStorage = $cloudStorage;
    }

    public function index()
    {
        $user = auth()->user();
        
        $courses = $user->courses()
            ->whereHas('lessons', function($q) {
                $q->whereNotNull('content_url')
                  ->where('type', 'video');
            })
            ->with(['lessons' => function($q) {
                $q->whereNotNull('content_url')
                  ->where('type', 'video');
            }])
            ->get();

        return view('offline.index', compact('courses'));
    }

    public function downloadLesson(Lesson $lesson)
    {
        $this->authorize('view', $lesson->course);

        if (!$lesson->content_url) {
            return back()->with('error', 'This lesson has no downloadable content.');
        }

        // Check if file exists
        if (!$this->cloudStorage->exists($lesson->content_url)) {
            return back()->with('error', 'File not found.');
        }

        // Get file URL (temporary for download)
        $url = $this->cloudStorage->getTemporaryUrl($lesson->content_url, 360); // 6 hours

        return redirect($url);
    }

    public function downloadCourseMaterials(Course $course)
    {
        $this->authorize('view', $course);

        $materials = $course->lessons()
            ->whereNotNull('downloadable_materials')
            ->get()
            ->flatMap(function($lesson) {
                $materials = json_decode($lesson->downloadable_materials, true);
                return $materials ?? [];
            });

        if ($materials->isEmpty()) {
            return back()->with('error', 'No downloadable materials available for this course.');
        }

        // TODO: Create ZIP archive of all materials
        // For now, return list
        return view('offline.materials', compact('course', 'materials'));
    }

    public function generateOfflinePackage(Course $course)
    {
        $this->authorize('view', $course);

        // TODO: Generate offline package (ZIP with videos, PDFs, etc.)
        // This would typically be a queued job

        return back()->with('info', 'Offline package generation started. You will be notified when ready.');
    }
}

