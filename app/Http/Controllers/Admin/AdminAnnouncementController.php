<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AdminAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::withCount('users');

        if ($request->has('scope')) {
            $query->where('scope', $request->scope);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->latest()->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.announcements.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scope' => 'required|in:all,students,teachers,admins',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
            'send_email' => 'nullable|boolean',
            'send_push' => 'nullable|boolean',
        ]);

        $announcement = Announcement::create($validated);

        // Get recipients based on scope
        $recipients = collect();
        if ($validated['scope'] === 'all') {
            $recipients = User::all();
        } elseif (!empty($validated['recipient_ids'])) {
            $recipients = User::whereIn('id', $validated['recipient_ids'])->get();
        } else {
            // Attach based on scope
            $roleMap = [
                'students' => 'student',
                'teachers' => 'teacher',
                'admins' => ['admin', 'super_admin'],
            ];

            $role = $roleMap[$validated['scope']] ?? null;
            if ($role) {
                if (is_array($role)) {
                    $recipients = User::whereIn('role', $role)->get();
                } else {
                    $recipients = User::where('role', $role)->get();
                }
            }
        }

        // Attach recipients
        $announcement->users()->attach($recipients->pluck('id'));

        // Send notifications
        DB::transaction(function () use ($announcement, $recipients, $validated) {
            foreach ($recipients as $user) {
                // Create in-app notification
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'announcement',
                    'title' => $announcement->title,
                    'message' => substr(strip_tags($announcement->content), 0, 200),
                    'data' => [
                        'announcement_id' => $announcement->id,
                        'priority' => $validated['priority'] ?? 'medium',
                    ],
                    'is_read' => false,
                ]);

                // Send email notification if enabled
                if ($request->has('send_email') && $request->send_email) {
                    try {
                        Mail::send('emails.announcement', [
                            'announcement' => $announcement,
                            'user' => $user,
                        ], function ($message) use ($user, $announcement) {
                            $message->to($user->email, $user->name)
                                ->subject('New Announcement: ' . $announcement->title);
                        });
                    } catch (\Exception $e) {
                        // Log error but don't fail the request
                        \Log::error('Failed to send announcement email: ' . $e->getMessage());
                    }
                }

                // Send push notification if enabled (requires push notification service)
                if ($request->has('send_push') && $request->send_push) {
                    // Integrate with push notification service (Firebase, Pusher, etc.)
                    // Example: Firebase Cloud Messaging
                    // $this->sendPushNotification($user, $announcement);
                }
            }
        });

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created and notifications sent successfully!');
    }

    public function edit(Announcement $announcement)
    {
        $users = User::all();
        $announcement->load('users');
        return view('admin.announcements.edit', compact('announcement', 'users'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scope' => 'required|in:all,students,teachers,admins',
            'recipient_ids' => 'nullable|array',
            'recipient_ids.*' => 'exists:users,id',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $announcement->update($validated);

        // Update recipients
        $announcement->users()->detach();

        if ($validated['scope'] === 'all') {
            $users = User::all();
            $announcement->users()->attach($users->pluck('id'));
        } elseif (!empty($validated['recipient_ids'])) {
            $announcement->users()->attach($validated['recipient_ids']);
        } else {
            $roleMap = [
                'students' => 'student',
                'teachers' => 'teacher',
                'admins' => ['admin', 'super_admin'],
            ];

            $role = $roleMap[$validated['scope']] ?? null;
            if ($role) {
                if (is_array($role)) {
                    $users = User::whereIn('role', $role)->get();
                } else {
                    $users = User::where('role', $role)->get();
                }
                $announcement->users()->attach($users->pluck('id'));
            }
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->users()->detach();
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}

