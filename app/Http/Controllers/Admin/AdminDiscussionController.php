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

        return back()->with('success', 'Discussion approved successfully!');
    }

    public function reject(Discussion $discussion)
    {
        $discussion->update(['status' => 'rejected']);

        return back()->with('success', 'Discussion rejected successfully!');
    }
}

