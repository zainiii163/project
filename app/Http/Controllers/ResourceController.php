<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Course;
use App\Services\CloudStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    protected $cloudStorage;

    public function __construct(CloudStorageService $cloudStorage)
    {
        $this->cloudStorage = $cloudStorage;
    }

    public function index(Request $request)
    {
        $query = Resource::with('uploader');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if (!auth()->user() || !auth()->user()->isAdmin()) {
            $query->where('is_public', true);
        }

        $resources = $query->latest()->paginate(20);

        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        $this->authorize('create', Resource::class);
        $courses = \App\Models\Course::where('status', 'published')->get();
        return view('resources.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Resource::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:102400', // 100MB max
            'category' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'is_public' => 'boolean',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $file = $request->file('file');
        $filePath = $this->cloudStorage->upload($file, 'resources');

        $resource = Resource::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'category' => $validated['category'],
            'tags' => $validated['tags'] ?? [],
            'is_public' => $validated['is_public'] ?? false,
            'uploaded_by' => auth()->id(),
        ]);

        if ($request->has('course_ids')) {
            $resource->courses()->attach($request->course_ids);
        }

        return redirect()->route('resources.index')
            ->with('success', 'Resource uploaded successfully!');
    }

    public function download(Resource $resource)
    {
        if (!$resource->is_public && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(403);
        }

        $resource->increment('download_count');

        $url = $this->cloudStorage->getTemporaryUrl($resource->file_path, 60);

        return redirect($url);
    }

    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);

        $this->cloudStorage->delete($resource->file_path);
        $resource->delete();

        return back()->with('success', 'Resource deleted successfully!');
    }
}

