<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

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
        ]);

        $announcement = Announcement::create($validated);

        // Attach recipients based on scope
        if ($validated['scope'] === 'all') {
            $users = User::all();
            $announcement->users()->attach($users->pluck('id'));
        } elseif (!empty($validated['recipient_ids'])) {
            $announcement->users()->attach($validated['recipient_ids']);
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
                    $users = User::whereIn('role', $role)->get();
                } else {
                    $users = User::where('role', $role)->get();
                }
                $announcement->users()->attach($users->pluck('id'));
            }
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
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

