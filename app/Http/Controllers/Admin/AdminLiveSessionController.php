<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveSession;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AdminLiveSessionController extends Controller
{
    public function index()
    {
        $sessions = LiveSession::with(['course', 'teacher'])
            ->latest('scheduled_at')
            ->paginate(20);

        return view('admin.live-sessions.index', compact('sessions'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->get();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.live-sessions.create', compact('courses', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
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

        return redirect()->route('admin.live-sessions.index')
            ->with('success', 'Live session created successfully!');
    }

    public function edit(LiveSession $liveSession)
    {
        $courses = Course::where('status', 'published')->get();
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.live-sessions.edit', compact('liveSession', 'courses', 'teachers'));
    }

    public function update(Request $request, LiveSession $liveSession)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'platform' => 'required|in:zoom,google_meet,teams,other',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_url' => 'required|url',
            'meeting_password' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'status' => 'required|in:scheduled,live,completed,cancelled',
        ]);

        $validated['settings'] = json_encode($validated['settings'] ?? []);

        $liveSession->update($validated);

        return redirect()->route('admin.live-sessions.index')
            ->with('success', 'Live session updated successfully!');
    }

    public function destroy(LiveSession $liveSession)
    {
        $liveSession->delete();

        return back()->with('success', 'Live session deleted successfully!');
    }

    public function start(LiveSession $liveSession)
    {
        $liveSession->update([
            'status' => 'live',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Live session started!');
    }

    public function end(LiveSession $liveSession)
    {
        $liveSession->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);

        return back()->with('success', 'Live session ended!');
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

