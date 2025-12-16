<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'course']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $reviews = $query->latest()->paginate(20);
        $courses = Course::all();

        return view('admin.reviews.index', compact('reviews', 'courses'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'course']);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully!');
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        return back()->with('success', 'Review approved successfully!');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        return back()->with('success', 'Review rejected successfully!');
    }
}

