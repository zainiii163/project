@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Support Tickets</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('support.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Ticket
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">My Support Tickets</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>#{{ substr($ticket->id, 0, 8) }}</td>
                            <td><strong>{{ $ticket->subject }}</strong></td>
                            <td>
                                <span class="adomx-badge">{{ ucfirst($ticket->category) }}</span>
                            </td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $ticket->priority }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="adomx-status-badge adomx-status-{{ $ticket->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                            <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('support.show', $ticket) }}" class="adomx-btn adomx-btn-sm adomx-btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="adomx-table-empty">
                                <div style="text-align: center; padding: 40px;">
                                    <i class="fas fa-ticket-alt" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                                    <p style="color: var(--text-secondary); margin-bottom: 20px;">No support tickets found.</p>
                                    <a href="{{ route('support.create') }}" class="adomx-btn adomx-btn-primary">Create Your First Ticket</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection

