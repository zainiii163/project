<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $tickets = SupportTicket::where('user_id', $user->id)
            ->with('assignedTo')
            ->latest()
            ->paginate(20);

        return view('support.index', compact('tickets'));
    }

    public function create()
    {
        return view('support.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required|in:technical,billing,account,course,other',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        $ticket = SupportTicket::create($validated);

        // TODO: Auto-assign to support staff based on category/priority
        // TODO: Send notification to support team

        return redirect()->route('support.show', $ticket)
            ->with('success', 'Support ticket created successfully!');
    }

    public function show(SupportTicket $supportTicket)
    {
        $this->authorize('view', $supportTicket);

        $supportTicket->load(['replies.user', 'assignedTo', 'user']);

        return view('support.show', compact('supportTicket'));
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        $this->authorize('view', $supportTicket);

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ticket_id'] = $supportTicket->id;
        $validated['is_internal'] = $validated['is_internal'] ?? false;

        TicketReply::create($validated);

        // Update ticket status if user replied
        if (auth()->id() === $supportTicket->user_id && $supportTicket->status === 'resolved') {
            $supportTicket->update(['status' => 'reopened']);
        } elseif (auth()->id() !== $supportTicket->user_id && $supportTicket->status === 'open') {
            $supportTicket->update(['status' => 'in_progress']);
        }

        // TODO: Send email notification

        return back()->with('success', 'Reply sent successfully!');
    }

    public function close(SupportTicket $supportTicket)
    {
        $this->authorize('update', $supportTicket);

        $supportTicket->update([
            'status' => 'closed',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Ticket closed successfully!');
    }
}

