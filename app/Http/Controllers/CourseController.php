<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['teacher', 'category', 'reviews'])
            ->where('status', 'published');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->paginate(12);
        $categories = Category::all();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show($slug)
    {
        $course = Course::with(['teacher', 'category', 'lessons', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $isEnrolled = auth()->check() && $course->students()->where('user_id', auth()->id())->exists();

        // Load first lesson for continue learning button
        $firstLesson = $course->lessons()->orderBy('order')->first();

        return view('courses.show', compact('course', 'isEnrolled', 'firstLesson'));
    }

    public function create()
    {
        $this->authorize('create', Course::class);
        $categories = Category::all();
        return view('courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        $validated['teacher_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['status'] = 'draft';

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration' => 'nullable|integer',
            'thumbnail' => 'nullable|image|max:2048',
            'objectives' => 'nullable|string',
            'requirements' => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    public function publish(Course $course)
    {
        $this->authorize('update', $course);
        $course->publish();

        return back()->with('success', 'Course published successfully!');
    }
}

