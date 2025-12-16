<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Discussion;
use App\Models\User;
use Illuminate\Http\Request;

class StudentCommunityController extends Controller
{
    public function discussions(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        $discussions = $course->discussions()
            ->with(['user', 'replies.user'])
            ->latest()
            ->paginate(20);

        return view('student.community.discussions', compact('course', 'discussions'));
    }

    public function createDiscussion(Request $request, Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        $discussion = Discussion::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('student.community.discussions', $course)
            ->with('success', 'Discussion created successfully!');
    }

    public function replyDiscussion(Request $request, Discussion $discussion)
    {
        $student = auth()->user();
        
        // Check if student is enrolled in the course
        if (!$student->courses()->where('courses.id', $discussion->course_id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Create reply (implement if you have a replies table)
        // Reply::create([...]);

        return back()->with('success', 'Reply posted successfully!');
    }

    public function qa(Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        // Get Q&A for this course (implement if you have a Q&A table)
        $questions = []; // Placeholder

        return view('student.community.qa', compact('course', 'questions'));
    }

    public function askQuestion(Request $request, Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        // Create question (implement if you have a questions table separate from quiz questions)
        // Question::create([...]);

        return back()->with('success', 'Question submitted! The teacher will respond soon.');
    }

    public function rateCourse(Request $request, Course $course)
    {
        $student = auth()->user();
        
        if (!$student->courses()->where('courses.id', $course->id)->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        \App\Models\Review::updateOrCreate(
            [
                'course_id' => $course->id,
                'user_id' => $student->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
                'status' => 'pending', // May require moderation
            ]
        );

        return back()->with('success', 'Review submitted successfully!');
    }

    public function followTeacher(User $teacher)
    {
        $student = auth()->user();
        
        if ($teacher->role !== 'teacher') {
            return back()->with('error', 'You can only follow teachers.');
        }

        // Toggle follow (implement if you have a follows table)
        // Follow::firstOrCreate(['follower_id' => $student->id, 'following_id' => $teacher->id]);
        
        return back()->with('success', 'Now following ' . $teacher->name);
    }

    public function followStudent(User $student)
    {
        $currentStudent = auth()->user();
        
        if ($student->role !== 'student') {
            return back()->with('error', 'You can only follow other students.');
        }

        if ($student->id === $currentStudent->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Toggle follow
        // Follow::firstOrCreate(['follower_id' => $currentStudent->id, 'following_id' => $student->id]);
        
        return back()->with('success', 'Now following ' . $student->name);
    }

    public function message(User $user)
    {
        $student = auth()->user();
        
        // Get messages between current user and target user
        // $messages = Message::where(function($q) use ($student, $user) {
        //     $q->where('from_id', $student->id)->where('to_id', $user->id);
        // })->orWhere(function($q) use ($student, $user) {
        //     $q->where('from_id', $user->id)->where('to_id', $student->id);
        // })->latest()->get();

        return view('student.community.messages', compact('user'));
    }

    public function sendMessage(Request $request, User $user)
    {
        $student = auth()->user();
        
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // Create message (implement if you have a messages table)
        // Message::create([
        //     'from_id' => $student->id,
        //     'to_id' => $user->id,
        //     'content' => $validated['message'],
        // ]);

        return back()->with('success', 'Message sent successfully!');
    }
}

