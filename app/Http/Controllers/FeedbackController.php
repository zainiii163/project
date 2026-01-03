<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Course;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $feedbacks = Feedback::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->paginate(20);

        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        $courses = auth()->user()->courses;

        return view('feedback.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'type' => 'required|in:general,technical,content,billing,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'course_id' => $validated['course_id'] ?? null,
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'rating' => $validated['rating'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('feedback.index')
            ->with('success', 'Feedback submitted successfully!');
    }

    public function show(Feedback $feedback)
    {
        $this->authorize('view', $feedback);

        $feedback->load('course', 'responder');

        return view('feedback.show', compact('feedback'));
    }
}

