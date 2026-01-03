<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with(['user', 'course']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $feedbacks = $query->latest()->paginate(20);

        $stats = [
            'pending' => Feedback::where('status', 'pending')->count(),
            'responded' => Feedback::where('status', 'responded')->count(),
            'resolved' => Feedback::where('status', 'resolved')->count(),
        ];

        return view('admin.feedback.index', compact('feedbacks', 'stats'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load(['user', 'course', 'responder']);

        return view('admin.feedback.show', compact('feedback'));
    }

    public function respond(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:5000',
        ]);

        $feedback->update([
            'admin_response' => $validated['admin_response'],
            'responded_by' => auth()->id(),
            'responded_at' => now(),
            'status' => 'responded',
        ]);

        // TODO: Send email notification to user

        return back()->with('success', 'Response sent successfully!');
    }

    public function resolve(Feedback $feedback)
    {
        $feedback->update(['status' => 'resolved']);

        return back()->with('success', 'Feedback marked as resolved.');
    }
}

