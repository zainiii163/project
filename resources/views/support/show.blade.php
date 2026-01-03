@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Support Ticket #{{ substr($supportTicket->id, 0, 8) }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('support.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
        @if($supportTicket->status !== 'closed' && auth()->id() === $supportTicket->user_id)
        <form action="{{ route('support.close', $supportTicket) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="adomx-btn adomx-btn-warning">
                <i class="fas fa-times"></i> Close Ticket
            </button>
        </form>
        @endif
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 class="adomx-card-title">{{ $supportTicket->subject }}</h3>
            <div style="font-size: 14px; color: var(--text-secondary); margin-top: 5px;">
                Created: {{ $supportTicket->created_at->format('M d, Y H:i') }}
            </div>
        </div>
        <div>
            <span class="adomx-status-badge adomx-status-{{ $supportTicket->status }}">
                {{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}
            </span>
        </div>
    </div>
    <div class="adomx-card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 5px;">Category</div>
                <div><span class="adomx-badge">{{ ucfirst($supportTicket->category) }}</span></div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 5px;">Priority</div>
                <div>
                    <span class="adomx-status-badge adomx-status-{{ $supportTicket->priority }}">
                        {{ ucfirst($supportTicket->priority) }}
                    </span>
                </div>
            </div>
            <div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 5px;">Assigned To</div>
                <div>{{ $supportTicket->assignedTo->name ?? 'Unassigned' }}</div>
            </div>
        </div>
        <div>
            <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 5px;">Description</div>
            <div style="padding: 15px; background: var(--bg-light); border-radius: 4px; white-space: pre-wrap;">{{ $supportTicket->description }}</div>
        </div>
    </div>
</div>

<div class="adomx-card" style="margin-bottom: 20px;">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Conversation</h3>
    </div>
    <div class="adomx-card-body">
        @forelse($supportTicket->replies as $reply)
        <div style="padding: 15px; margin-bottom: 15px; border-bottom: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <strong>{{ $reply->user->name }}</strong>
                <div style="font-size: 12px; color: var(--text-secondary);">
                    {{ $reply->created_at->format('M d, Y H:i') }}
                    @if($reply->is_internal)
                    <span class="adomx-badge" style="margin-left: 10px;">
                        <i class="fas fa-lock"></i> Internal Note
                    </span>
                    @endif
                </div>
            </div>
            <div style="white-space: pre-wrap;">{{ $reply->message }}</div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: var(--text-secondary);">
            <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px;"></i>
            <p>No replies yet.</p>
        </div>
        @endforelse
    </div>
</div>

@if($supportTicket->status !== 'closed')
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Add Reply</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('support.reply', $supportTicket) }}" method="POST">
            @csrf
            <div class="adomx-form-group">
                <textarea name="message" id="message" class="adomx-form-input" rows="5" required placeholder="Type your message here..."></textarea>
            </div>
            <div class="adomx-form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-paper-plane"></i> Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@else
<div class="adomx-alert adomx-alert-secondary">
    <i class="fas fa-lock"></i> This ticket is closed. Please create a new ticket if you need further assistance.
</div>
@endif
@endsection

