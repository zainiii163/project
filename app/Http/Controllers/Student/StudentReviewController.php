<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Review;

class StudentReviewController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        $reviews = $student->reviews()
            ->with('course')
            ->latest()
            ->paginate(20);

        return view('student.reviews.index', compact('reviews'));
    }
}

