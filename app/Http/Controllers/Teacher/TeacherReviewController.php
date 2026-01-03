<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherReviewController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = Review::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->with(['user', 'course']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->latest()->paginate(20);
        $courses = $teacher->taughtCourses()->get();

        return view('teacher.reviews.index', compact('reviews', 'courses'));
    }

    public function show(Review $review)
    {
        // Ensure teacher owns this review's course
        if ($review->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $review->load(['user', 'course']);
        
        return view('teacher.reviews.show', compact('review'));
    }

    public function respond(Request $request, Review $review)
    {
        // Ensure teacher owns this review's course
        if ($review->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'teacher_response' => 'required|string|max:2000',
        ]);

        $review->update([
            'teacher_response' => $validated['teacher_response'],
            'teacher_response_at' => now(),
        ]);

        // Notify the student
        \App\Models\Notification::create([
            'user_id' => $review->user_id,
            'type' => 'review_response',
            'title' => 'Teacher Responded to Your Review',
            'message' => auth()->user()->name . ' responded to your review for "' . $review->course->title . '"',
            'data' => [
                'review_id' => $review->id,
                'course_id' => $review->course_id,
            ],
        ]);

        return back()->with('success', 'Response posted successfully!');
    }

    public function updateResponse(Request $request, Review $review)
    {
        // Ensure teacher owns this review's course
        if ($review->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'teacher_response' => 'required|string|max:2000',
        ]);

        $review->update([
            'teacher_response' => $validated['teacher_response'],
        ]);

        return back()->with('success', 'Response updated successfully!');
    }

    public function deleteResponse(Review $review)
    {
        // Ensure teacher owns this review's course
        if ($review->course->teacher_id !== auth()->id()) {
            abort(403);
        }

        $review->update([
            'teacher_response' => null,
            'teacher_response_at' => null,
        ]);

        return back()->with('success', 'Response deleted successfully!');
    }
}

