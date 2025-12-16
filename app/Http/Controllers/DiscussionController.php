<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function index(Course $course)
    {
        $this->authorize('view', $course);

        // Check enrollment
        if (auth()->user()->isStudent() && !$course->students()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must enroll in this course to participate in discussions.');
        }

        $discussions = $course->discussions()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return view('discussions.index', compact('course', 'discussions'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('view', $course);

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:discussions,id',
        ]);

        $course->discussions()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Discussion posted successfully!');
    }

    public function update(Request $request, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $discussion->update($validated);

        return back()->with('success', 'Discussion updated successfully!');
    }

    public function destroy(Discussion $discussion)
    {
        $this->authorize('delete', $discussion);
        $discussion->delete();

        return back()->with('success', 'Discussion deleted successfully!');
    }
}

