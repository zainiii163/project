<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $query = Course::with(['teacher', 'category', 'students']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function create()
    {
        $this->authorize('create', Course::class);
        $categories = Category::all();
        $teachers = \App\Models\User::where('role', 'teacher')->get();
        return view('admin.courses.create', compact('categories', 'teachers'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'teacher_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        $teachers = \App\Models\User::where('role', 'teacher')->get();
        return view('admin.courses.edit', compact('course', 'categories', 'teachers'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'teacher_id' => 'required|exists:users,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    public function publish(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['status' => 'published']);

        return back()->with('success', 'Course published successfully!');
    }

    public function unpublish(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['status' => 'draft']);

        return back()->with('success', 'Course unpublished successfully!');
    }

    public function approve(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['status' => 'published', 'approved_at' => now()]);

        return back()->with('success', 'Course approved successfully!');
    }

    public function reject(Course $course, Request $request)
    {
        $this->authorize('update', $course);
        
        $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);
        
        $course->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Course rejected successfully!');
    }

    public function moderate(Course $course)
    {
        $this->authorize('update', $course);
        
        $course->load(['teacher', 'lessons', 'quizzes', 'reviews', 'students']);
        
        return view('admin.courses.moderate', compact('course'));
    }

    public function updateVisibility(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'visibility' => 'required|in:public,private,subscription_only,restricted',
        ]);
        
        $course->update(['visibility' => $validated['visibility']]);

        return back()->with('success', 'Course visibility updated successfully!');
    }

    public function schedulePublication(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'publish_at' => 'required|date|after:now',
        ]);
        
        $course->update([
            'scheduled_publish_at' => $validated['publish_at'],
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Course scheduled for publication!');
    }

    public function archive(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['status' => 'archived', 'archived_at' => now()]);

        return back()->with('success', 'Course archived successfully!');
    }

    public function unarchive(Course $course)
    {
        $this->authorize('update', $course);
        $course->update(['status' => 'draft', 'archived_at' => null]);

        return back()->with('success', 'Course unarchived successfully!');
    }

    public function qualityCheck(Course $course)
    {
        $this->authorize('update', $course);
        
        $course->load(['lessons', 'quizzes', 'assignments']);
        
        $qualityScore = 0;
        $issues = [];
        
        // Check for required elements
        if (!$course->description) {
            $issues[] = 'Missing course description';
            $qualityScore -= 10;
        }
        
        if ($course->lessons->count() < 3) {
            $issues[] = 'Course has less than 3 lessons';
            $qualityScore -= 15;
        }
        
        if ($course->quizzes->count() === 0) {
            $issues[] = 'No quizzes found';
            $qualityScore -= 10;
        }
        
        if (!$course->thumbnail) {
            $issues[] = 'Missing course thumbnail';
            $qualityScore -= 5;
        }
        
        $qualityScore = max(0, min(100, 100 + $qualityScore));
        
        return view('admin.courses.quality-check', compact('course', 'qualityScore', 'issues'));
    }
}

