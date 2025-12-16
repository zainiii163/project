<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $announcements = Announcement::with(['course', 'targetUser'])->latest()->paginate(20);
        } elseif ($user->isTeacher()) {
            $announcements = Announcement::where('scope', 'all')
                ->orWhere(function($q) use ($user) {
                    $q->where('scope', 'course')
                      ->whereIn('course_id', $user->taughtCourses()->pluck('id'));
                })
                ->with(['course', 'targetUser'])
                ->latest()
                ->paginate(20);
        } else {
            $announcements = $user->announcements()
                ->with(['course', 'targetUser'])
                ->latest()
                ->paginate(20);
        }

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $this->authorize('create', Announcement::class);
        $courses = Course::where('status', 'published')->get();
        $users = User::where('role', 'student')->get();
        return view('announcements.create', compact('courses', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Announcement::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scope' => 'required|in:all,course,user',
            'course_id' => 'required_if:scope,course|exists:courses,id',
            'user_id' => 'required_if:scope,user|exists:users,id',
        ]);

        $announcement = Announcement::create($validated);

        // Attach recipients based on scope
        if ($validated['scope'] === 'all') {
            $users = User::all();
            $announcement->recipients()->attach($users->pluck('id'));
        } elseif ($validated['scope'] === 'course') {
            $users = Course::find($validated['course_id'])->students;
            $announcement->recipients()->attach($users->pluck('id'));
        } else {
            $announcement->recipients()->attach($validated['user_id']);
        }

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created and sent successfully!');
    }

    public function markAsRead(Announcement $announcement)
    {
        $announcement->recipients()->updateExistingPivot(auth()->id(), [
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Marked as read');
    }
}

