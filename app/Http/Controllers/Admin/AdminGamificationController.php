<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class AdminGamificationController extends Controller
{
    public function index()
    {
        $badges = Badge::withCount('users')->latest()->paginate(20);
        $totalUsers = User::where('role', 'student')->count();
        $totalXp = User::where('role', 'student')->sum('xp_points') ?? 0;
        $avgLevel = User::where('role', 'student')->avg('level') ?? 1;

        return view('admin.gamification.index', compact('badges', 'totalUsers', 'totalXp', 'avgLevel'));
    }

    public function create()
    {
        return view('admin.gamification.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:achievement,completion,participation,special',
            'required_xp' => 'nullable|integer|min:0',
            'criteria' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.gamification.index')
            ->with('success', 'Badge created successfully!');
    }

    public function edit(Badge $badge)
    {
        return view('admin.gamification.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:achievement,completion,participation,special',
            'required_xp' => 'nullable|integer|min:0',
            'criteria' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.gamification.index')
            ->with('success', 'Badge updated successfully!');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();

        return back()->with('success', 'Badge deleted successfully!');
    }

    public function leaderboard()
    {
        $leaderboard = User::where('role', 'student')
            ->orderByDesc('xp_points')
            ->orderByDesc('level')
            ->withCount('badges')
            ->paginate(50);

        return view('admin.gamification.leaderboard', compact('leaderboard'));
    }
}

