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

        $query = $course->discussions()
            ->whereNull('parent_id')
            ->where('status', 'approved'); // Only show approved discussions

        // Show pinned discussions first
        $discussions = $query->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->with(['user', 'replies' => function($q) {
                $q->where('status', 'approved')->with('user');
            }])
            ->paginate(20);

        return view('discussions.index', compact('course', 'discussions'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('view', $course);

        // Check if discussion is locked
        if ($request->has('parent_id')) {
            $parentDiscussion = \App\Models\Discussion::find($request->parent_id);
            if ($parentDiscussion && $parentDiscussion->is_locked) {
                return back()->with('error', 'This discussion is locked. No new replies can be added.');
            }
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:discussions,id',
        ]);

        // Set status based on moderation settings (pending if moderation enabled)
        $status = 'approved'; // Default to approved, can be changed based on settings
        // if (setting('discussions.require_moderation', false)) {
        //     $status = 'pending';
        // }

        $discussion = $course->discussions()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => $status,
        ]);

        // Notify course teacher if it's a question (parent discussion)
        if (!$validated['parent_id'] && $course->teacher) {
            \App\Models\Notification::create([
                'user_id' => $course->teacher_id,
                'type' => 'new_discussion',
                'title' => 'New Discussion in ' . $course->title,
                'message' => auth()->user()->name . ' started a new discussion',
                'data' => [
                    'discussion_id' => $discussion->id,
                    'course_id' => $course->id,
                ],
            ]);
        }

        if ($status === 'pending') {
            return back()->with('info', 'Discussion submitted and pending approval.');
        }

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

