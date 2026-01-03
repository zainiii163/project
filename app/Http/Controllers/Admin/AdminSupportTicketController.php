<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by assigned to
        if ($request->has('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        $tickets = $query->latest()->paginate(20);

        $supportStaff = User::whereIn('role', ['admin', 'super_admin'])
            ->orWhere('role', 'support')
            ->get();

        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        return view('admin.support.index', compact('tickets', 'supportStaff', 'stats'));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load(['replies.user', 'assignedTo', 'user']);

        $supportStaff = User::whereIn('role', ['admin', 'super_admin'])
            ->orWhere('role', 'support')
            ->get();

        return view('admin.support.show', compact('supportTicket', 'supportStaff'));
    }

    public function assign(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $supportTicket->update([
            'assigned_to' => $validated['assigned_to'],
            'status' => 'in_progress',
        ]);

        // TODO: Send notification to assigned staff

        return back()->with('success', 'Ticket assigned successfully!');
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'resolved' || $validated['status'] === 'closed') {
            $updateData['resolved_at'] = now();
        }

        $supportTicket->update($updateData);

        // TODO: Send notification to user

        return back()->with('success', 'Ticket status updated successfully!');
    }

    public function updatePriority(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $supportTicket->update(['priority' => $validated['priority']]);

        return back()->with('success', 'Ticket priority updated successfully!');
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ticket_id'] = $supportTicket->id;
        $validated['is_internal'] = $validated['is_internal'] ?? false;

        TicketReply::create($validated);

        // Update ticket status
        if ($supportTicket->status === 'open') {
            $supportTicket->update(['status' => 'in_progress']);
        }

        // TODO: Send email notification to user if not internal

        return back()->with('success', 'Reply sent successfully!');
    }

    public function destroy(SupportTicket $supportTicket)
    {
        $supportTicket->delete();

        return redirect()->route('admin.support.index')
            ->with('success', 'Ticket deleted successfully!');
    }

    public function analytics()
    {
        $stats = [
            'total_tickets' => SupportTicket::count(),
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'resolved')->count(),
            'avg_resolution_time' => $this->calculateAvgResolutionTime(),
            'tickets_by_category' => SupportTicket::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->get(),
            'tickets_by_priority' => SupportTicket::selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->get(),
            'tickets_by_status' => SupportTicket::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
        ];

        // Tickets over time (last 6 months)
        $ticketsOverTime = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $ticketsOverTime[] = [
                'month' => $month->format('M Y'),
                'opened' => SupportTicket::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'resolved' => SupportTicket::whereBetween('resolved_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        return view('admin.support.analytics', compact('stats', 'ticketsOverTime'));
    }

    private function calculateAvgResolutionTime()
    {
        $resolvedTickets = SupportTicket::whereNotNull('resolved_at')
            ->whereNotNull('created_at')
            ->get();

        if ($resolvedTickets->isEmpty()) {
            return 0;
        }

        $totalHours = $resolvedTickets->sum(function($ticket) {
            return $ticket->created_at->diffInHours($ticket->resolved_at);
        });

        return round($totalHours / $resolvedTickets->count(), 2);
    }
}

