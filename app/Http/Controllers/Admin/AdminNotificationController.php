<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

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
        ]);

        $validated['data'] = json_encode($validated['data'] ?? []);

        Notification::create($validated);

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
        Notification::whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read!');
    }
}

