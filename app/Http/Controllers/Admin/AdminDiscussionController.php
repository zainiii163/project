<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminDiscussionController extends Controller
{
    public function index(Request $request)
    {
        $query = Discussion::with(['user', 'course'])->whereNull('parent_id');

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('search')) {
            $query->where('message', 'like', '%' . $request->search . '%');
        }

        $discussions = $query->latest()->paginate(20);
        $courses = Course::all();

        return view('admin.discussions.index', compact('discussions', 'courses'));
    }

    public function show(Discussion $discussion)
    {
        $discussion->load(['user', 'course', 'replies.user']);
        return view('admin.discussions.show', compact('discussion'));
    }

    public function destroy(Discussion $discussion)
    {
        // Delete all replies first
        $discussion->replies()->delete();
        $discussion->delete();

        return redirect()->route('admin.discussions.index')
            ->with('success', 'Discussion deleted successfully!');
    }

    public function approve(Discussion $discussion)
    {
        $discussion->update(['status' => 'approved']);

        // Notify user that their discussion was approved
        \App\Models\Notification::create([
            'user_id' => $discussion->user_id,
            'type' => 'discussion_approved',
            'title' => 'Discussion Approved',
            'message' => 'Your discussion in "' . $discussion->course->title . '" has been approved.',
            'data' => ['discussion_id' => $discussion->id, 'course_id' => $discussion->course_id],
        ]);

        return back()->with('success', 'Discussion approved successfully!');
    }

    public function reject(Discussion $discussion, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $discussion->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
        ]);

        // Notify user that their discussion was rejected
        \App\Models\Notification::create([
            'user_id' => $discussion->user_id,
            'type' => 'discussion_rejected',
            'title' => 'Discussion Rejected',
            'message' => 'Your discussion in "' . $discussion->course->title . '" has been rejected.' . 
                        ($validated['rejection_reason'] ? ' Reason: ' . $validated['rejection_reason'] : ''),
            'data' => [
                'discussion_id' => $discussion->id,
                'course_id' => $discussion->course_id,
                'rejection_reason' => $validated['rejection_reason'] ?? null,
            ],
        ]);

        return back()->with('success', 'Discussion rejected successfully!');
    }

    public function pin(Discussion $discussion)
    {
        $discussion->update(['is_pinned' => true]);

        return back()->with('success', 'Discussion pinned successfully!');
    }

    public function unpin(Discussion $discussion)
    {
        $discussion->update(['is_pinned' => false]);

        return back()->with('success', 'Discussion unpinned successfully!');
    }

    public function lock(Discussion $discussion)
    {
        $discussion->update(['is_locked' => true]);

        return back()->with('success', 'Discussion locked successfully!');
    }

    public function unlock(Discussion $discussion)
    {
        $discussion->update(['is_locked' => false]);

        return back()->with('success', 'Discussion unlocked successfully!');
    }
}

