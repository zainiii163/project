<?php

namespace App\Http\Controllers;

use App\Models\LiveSession;
use App\Models\Course;
use Illuminate\Http\Request;

class LiveSessionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isTeacher()) {
            $sessions = LiveSession::where('teacher_id', $user->id)
                ->with('course')
                ->latest('scheduled_at')
                ->paginate(20);
        } else {
            $sessions = LiveSession::whereHas('course', function($q) use ($user) {
                    $q->whereHas('students', function($q2) use ($user) {
                        $q2->where('users.id', $user->id);
                    });
                })
                ->with('course', 'teacher')
                ->latest('scheduled_at')
                ->paginate(20);
        }

        return view('live-sessions.index', compact('sessions'));
    }

    public function show(LiveSession $liveSession)
    {
        $this->authorize('view', $liveSession);

        $liveSession->load('course', 'teacher');

        return view('live-sessions.show', compact('liveSession'));
    }

    public function join(LiveSession $liveSession)
    {
        $this->authorize('view', $liveSession);

        if ($liveSession->status !== 'scheduled' && $liveSession->status !== 'live') {
            return back()->with('error', 'This session is not available to join.');
        }

        if ($liveSession->scheduled_at > now() && $liveSession->status === 'scheduled') {
            return back()->with('error', 'This session has not started yet.');
        }

        // Redirect to meeting URL
        return redirect($liveSession->meeting_url);
    }

    public function create()
    {
        $user = auth()->user();
        
        if (!$user->isTeacher()) {
            abort(403, 'Only teachers can create live sessions.');
        }

        $courses = Course::where('teacher_id', $user->id)
            ->where('status', 'published')
            ->get();

        return view('live-sessions.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->isTeacher()) {
            abort(403, 'Only teachers can create live sessions.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'platform' => 'required|in:zoom,google_meet,teams,other',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_url' => 'required|url',
            'meeting_password' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
        ]);

        // Verify teacher owns the course
        $course = Course::findOrFail($validated['course_id']);
        if ($course->teacher_id !== $user->id) {
            return back()->with('error', 'You can only create sessions for your own courses.');
        }

        $validated['teacher_id'] = $user->id;
        $validated['status'] = 'scheduled';
        $validated['settings'] = json_encode($validated['settings'] ?? []);

        // Generate meeting if using Zoom/Google Meet API
        if ($validated['platform'] === 'zoom' && empty($validated['meeting_id'])) {
            $meeting = $this->createZoomMeeting($validated);
            $validated['meeting_id'] = $meeting['id'] ?? null;
            $validated['meeting_url'] = $meeting['join_url'] ?? $validated['meeting_url'];
        } elseif ($validated['platform'] === 'google_meet' && empty($validated['meeting_id'])) {
            $meeting = $this->createGoogleMeetMeeting($validated);
            $validated['meeting_id'] = $meeting['id'] ?? null;
            $validated['meeting_url'] = $meeting['conference_link'] ?? $validated['meeting_url'];
        }

        LiveSession::create($validated);

        if ($user->isTeacher()) {
            return redirect()->route('teacher.live-sessions.index')
                ->with('success', 'Live session created successfully!');
        } else {
            return redirect()->route('student.live-sessions.index')
                ->with('success', 'Live session created successfully!');
        }
    }

    private function createZoomMeeting($data)
    {
        // TODO: Implement Zoom API integration
        // Example: Use zoom/zoom-api-php package
        return [
            'id' => 'ZOOM-' . uniqid(),
            'join_url' => $data['meeting_url'],
        ];
    }

    private function createGoogleMeetMeeting($data)
    {
        // TODO: Implement Google Meet API integration
        // Example: Use Google Calendar API to create event with Meet link
        return [
            'id' => 'MEET-' . uniqid(),
            'conference_link' => $data['meeting_url'],
        ];
    }
}

