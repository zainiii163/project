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
            'priority' => 'nullable|in:low,medium,high',
            'send_email' => 'nullable|boolean',
            'send_push' => 'nullable|boolean',
        ]);

        $announcement = Announcement::create($validated);

        // Get recipients
        $recipients = collect();
        if ($validated['scope'] === 'all') {
            $recipients = User::all();
        } elseif ($validated['scope'] === 'course') {
            $recipients = Course::find($validated['course_id'])->students;
        } else {
            $recipients = collect([User::find($validated['user_id'])]);
        }

        // Attach recipients
        $announcement->recipients()->attach($recipients->pluck('id'));

        // Send notifications
        \Illuminate\Support\Facades\DB::transaction(function () use ($announcement, $recipients, $request, $validated) {
            foreach ($recipients as $user) {
                // Create in-app notification
                \App\Models\Notification::create([
                    'user_id' => $user->id,
                    'type' => 'announcement',
                    'title' => $announcement->title,
                    'message' => substr(strip_tags($announcement->content), 0, 200),
                    'data' => [
                        'announcement_id' => $announcement->id,
                        'priority' => $validated['priority'] ?? 'medium',
                    ],
                ]);

                // Send email if enabled
                if ($request->has('send_email') && $request->send_email) {
                    try {
                        \Illuminate\Support\Facades\Mail::send('emails.announcement', [
                            'announcement' => $announcement,
                            'user' => $user,
                        ], function ($message) use ($user, $announcement) {
                            $message->to($user->email, $user->name)
                                ->subject('New Announcement: ' . $announcement->title);
                        });
                    } catch (\Exception $e) {
                        \Log::error('Failed to send announcement email: ' . $e->getMessage());
                    }
                }
            }
        });

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created and notifications sent successfully!');
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

