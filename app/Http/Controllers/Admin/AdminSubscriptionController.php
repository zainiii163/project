<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $subscriptions = $query->latest()->paginate(20);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $users = User::where('role', 'student')->get();
        return view('admin.subscriptions.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired',
        ]);

        Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function edit(Subscription $subscription)
    {
        $users = User::where('role', 'student')->get();
        return view('admin.subscriptions.edit', compact('subscription', 'users'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
}

