<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with(['user']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('read')) {
            if ($request->read == 'read') {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $notifications = $query->latest()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array',
            'send_email' => 'nullable|boolean',
            'send_push' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $validated['data'] = json_encode($validated['data'] ?? []);

        DB::transaction(function () use ($validated, $user, $request) {
            // Create in-app notification
            $notification = Notification::create($validated);

            // Send email notification if enabled
            if ($request->has('send_email') && $request->send_email) {
                try {
                    Mail::send('emails.notification', [
                        'notification' => $notification,
                        'user' => $user,
                    ], function ($message) use ($user, $notification) {
                        $message->to($user->email, $user->name)
                            ->subject($notification->title);
                    });
                } catch (\Exception $e) {
                    \Log::error('Failed to send notification email: ' . $e->getMessage());
                }
            }

            // Send push notification if enabled
            if ($request->has('send_push') && $request->send_push) {
                // Integrate with push notification service
                // $this->sendPushNotification($user, $notification);
            }
        });

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully!');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully!');
    }

    public function markAllAsRead()
    {
        Notification::whereNull('read_at')->update([
            'read_at' => now(),
            'is_read' => true,
        ]);

        return back()->with('success', 'All notifications marked as read!');
    }

    public function sendBulkNotification(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array',
            'send_email' => 'nullable|boolean',
            'send_push' => 'nullable|boolean',
        ]);

        $users = User::whereIn('id', $validated['user_ids'])->get();
        $validated['data'] = json_encode($validated['data'] ?? []);

        DB::transaction(function () use ($validated, $users, $request) {
            foreach ($users as $user) {
                $notificationData = $validated;
                $notificationData['user_id'] = $user->id;
                unset($notificationData['user_ids']);

                $notification = Notification::create($notificationData);

                // Send email if enabled
                if ($request->has('send_email') && $request->send_email) {
                    try {
                        Mail::send('emails.notification', [
                            'notification' => $notification,
                            'user' => $user,
                        ], function ($message) use ($user, $notification) {
                            $message->to($user->email, $user->name)
                                ->subject($notification->title);
                        });
                    } catch (\Exception $e) {
                        \Log::error('Failed to send bulk notification email: ' . $e->getMessage());
                    }
                }
            }
        });

        return back()->with('success', 'Bulk notifications sent successfully!');
    }
}

