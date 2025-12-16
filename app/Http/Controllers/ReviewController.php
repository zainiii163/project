<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorize('view', $course);

        // Check if user is enrolled
        if (!$course->students()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You must enroll in this course to review it.');
        }

        // Check if user already reviewed
        if ($course->reviews()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already reviewed this course.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $course->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Thank you for your review!');
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();

        return back()->with('success', 'Review deleted successfully!');
    }
}

