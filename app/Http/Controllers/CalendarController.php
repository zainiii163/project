<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $start = $request->input('start', now()->startOfMonth()->toDateString());
        $end = $request->input('end', now()->endOfMonth()->toDateString());

        // For admin, show all events; for others, show only their events
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            $events = CalendarEvent::whereBetween('start_date', [$start, $end])
                ->with(['course', 'assignment', 'user'])
                ->get();
        } else {
            $events = CalendarEvent::where('user_id', $user->id)
                ->whereBetween('start_date', [$start, $end])
                ->with(['course', 'assignment'])
                ->get();
        }

        $events = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date->toIso8601String(),
                'end' => $event->end_date ? $event->end_date->toIso8601String() : null,
                'allDay' => $event->is_all_day,
                'type' => $event->type,
                'location' => $event->location,
                'meeting_url' => $event->meeting_url,
                'description' => $event->description,
            ];
        });

        // Add course deadlines (for students and teachers)
        if ($user->isStudent() || $user->isTeacher()) {
            $courseDeadlines = $user->courses()
                ->with('assignments')
                ->get()
                ->flatMap(function($course) {
                    return $course->assignments()
                        ->whereNotNull('due_date')
                        ->get()
                        ->map(function($assignment) use ($course) {
                            return [
                                'id' => 'assignment-' . $assignment->id,
                                'title' => $assignment->title . ' (Due)',
                                'start' => $assignment->due_date->toIso8601String(),
                                'end' => $assignment->due_date->toIso8601String(),
                                'allDay' => true,
                                'type' => 'assignment_deadline',
                                'course' => $course->title,
                                'description' => $assignment->description,
                            ];
                        });
                });

            // Add live sessions
            if ($user->isStudent()) {
                $liveSessions = \App\Models\LiveSession::whereHas('course', function($q) use ($user) {
                        $q->whereHas('students', function($q2) use ($user) {
                            $q2->where('users.id', $user->id);
                        });
                    })
                    ->where('status', 'scheduled')
                    ->get();
            } else {
                // Teacher sees their own sessions
                $liveSessions = \App\Models\LiveSession::where('teacher_id', $user->id)
                    ->where('status', 'scheduled')
                    ->get();
            }

            $liveSessions = $liveSessions->map(function($session) {
                return [
                    'id' => 'live-session-' . $session->id,
                    'title' => $session->title,
                    'start' => $session->scheduled_at->toIso8601String(),
                    'end' => $session->scheduled_at->addMinutes($session->duration_minutes)->toIso8601String(),
                    'allDay' => false,
                    'type' => 'live_session',
                    'meeting_url' => $session->meeting_url,
                    'description' => $session->description,
                ];
            });

            $allEvents = $events->merge($courseDeadlines)->merge($liveSessions);
        } else {
            // Admin sees all live sessions
            $liveSessions = \App\Models\LiveSession::whereBetween('scheduled_at', [$start, $end])
                ->where('status', 'scheduled')
                ->get()
                ->map(function($session) {
                    return [
                        'id' => 'live-session-' . $session->id,
                        'title' => $session->title,
                        'start' => $session->scheduled_at->toIso8601String(),
                        'end' => $session->scheduled_at->addMinutes($session->duration_minutes)->toIso8601String(),
                        'allDay' => false,
                        'type' => 'live_session',
                        'meeting_url' => $session->meeting_url,
                        'description' => $session->description,
                    ];
                });
            $allEvents = $events->merge($liveSessions);
        }

        if ($request->wantsJson()) {
            return response()->json($allEvents->values());
        }

        $routePrefix = $user->isAdmin() || $user->isSuperAdmin() ? 'admin.' : ($user->isTeacher() ? 'teacher.' : 'student.');
        $viewPath = ($user->isAdmin() || $user->isSuperAdmin()) ? 'admin.calendar.index' : 'calendar.index';
        
        return view($viewPath, compact('allEvents', 'start', 'end'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'type' => 'required|in:personal,deadline,live_session,reminder',
            'course_id' => 'nullable|exists:courses,id',
            'assignment_id' => 'nullable|exists:assignments,id',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url',
            'reminder_settings' => 'nullable|array',
            'is_all_day' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['reminder_settings'] = json_encode($validated['reminder_settings'] ?? []);

        $event = CalendarEvent::create($validated);

        // Schedule reminders if configured
        if (!empty($validated['reminder_settings'])) {
            $this->scheduleReminders($event);
        }

        $user = auth()->user();
        $routePrefix = $user->isAdmin() || $user->isSuperAdmin() ? 'admin.' : ($user->isTeacher() ? 'teacher.' : 'student.');
        
        return redirect()->route($routePrefix . 'calendar.index')
            ->with('success', 'Event created successfully!');
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        if (!auth()->check()) {
            abort(403);
        }
        $this->authorize('update', $calendarEvent);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'type' => 'required|in:personal,deadline,live_session,reminder',
            'course_id' => 'nullable|exists:courses,id',
            'assignment_id' => 'nullable|exists:assignments,id',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url',
            'reminder_settings' => 'nullable|array',
            'is_all_day' => 'boolean',
        ]);

        $validated['reminder_settings'] = json_encode($validated['reminder_settings'] ?? []);

        $calendarEvent->update($validated);

        // Reschedule reminders
        if (!empty($validated['reminder_settings'])) {
            $this->scheduleReminders($calendarEvent);
        }

        $user = auth()->user();
        $routePrefix = $user->isAdmin() || $user->isSuperAdmin() ? 'admin.' : ($user->isTeacher() ? 'teacher.' : 'student.');
        
        return redirect()->route($routePrefix . 'calendar.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $this->authorize('delete', $calendarEvent);

        $calendarEvent->delete();

        return back()->with('success', 'Event deleted successfully!');
    }

    private function scheduleReminders($event)
    {
        $reminderSettings = json_decode($event->reminder_settings, true);
        
        if (!$reminderSettings) {
            return;
        }

        // TODO: Implement actual reminder scheduling
        // Example: Use Laravel's notification system or queue jobs
        // For now, this is a placeholder
    }
}

