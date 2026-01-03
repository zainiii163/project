@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Ticket #{{ substr($supportTicket->id, 0, 8) }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.support.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <div class="adomx-card mb-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">{{ $supportTicket->subject }}</h3>
            </div>
            <div class="adomx-card-body">
                <div class="mb-3">
                    <strong>User:</strong> {{ $supportTicket->user->name }} ({{ $supportTicket->user->email }})
                </div>
                <div class="mb-3">
                    <strong>Category:</strong> 
                    <span class="badge badge-info">{{ ucfirst($supportTicket->category) }}</span>
                </div>
                <div class="mb-3">
                    <strong>Priority:</strong> 
                    <span class="badge badge-{{ $supportTicket->priority === 'urgent' ? 'danger' : ($supportTicket->priority === 'high' ? 'warning' : ($supportTicket->priority === 'medium' ? 'info' : 'secondary')) }}">
                        {{ ucfirst($supportTicket->priority) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> 
                    <span class="badge badge-{{ $supportTicket->status === 'open' ? 'success' : ($supportTicket->status === 'in_progress' ? 'info' : ($supportTicket->status === 'resolved' ? 'secondary' : 'dark')) }}">
                        {{ ucfirst(str_replace('_', ' ', $supportTicket->status)) }}
                    </span>
                </div>
                <div class="mb-3">
                    <strong>Created:</strong> {{ $supportTicket->created_at->format('M d, Y H:i') }}
                </div>
                @if($supportTicket->resolved_at)
                <div class="mb-3">
                    <strong>Resolved:</strong> {{ $supportTicket->resolved_at->format('M d, Y H:i') }}
                </div>
                @endif
                <div>
                    <strong>Description:</strong>
                    <p class="mt-2">{{ $supportTicket->description }}</p>
                </div>
            </div>
        </div>

        <div class="adomx-card mb-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Conversation</h3>
            </div>
            <div class="adomx-card-body">
                @forelse($supportTicket->replies as $reply)
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>{{ $reply->user->name }}</strong>
                        <small class="text-muted">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    <p class="mb-0">{{ $reply->message }}</p>
                    @if($reply->is_internal)
                    <small class="text-muted"><i class="fas fa-lock"></i> Internal Note</small>
                    @endif
                </div>
                @empty
                <p class="text-muted">No replies yet.</p>
                @endforelse
            </div>
        </div>

        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Add Reply</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.support.reply', $supportTicket) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-control" rows="5" required placeholder="Type your reply here..."></textarea>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_internal" id="is_internal" class="form-check-input" value="1">
                            <label class="form-check-label" for="is_internal">Internal Note (not visible to user)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-4">
        <div class="adomx-card mb-4">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Quick Actions</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.support.assign', $supportTicket) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group">
                        <label>Assign To</label>
                        <select name="assigned_to" class="form-control">
                            <option value="">Unassigned</option>
                            @foreach($supportStaff as $staff)
                            <option value="{{ $staff->id }}" {{ $supportTicket->assigned_to == $staff->id ? 'selected' : '' }}>
                                {{ $staff->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Assign</button>
                </form>

                <form action="{{ route('admin.support.update-status', $supportTicket) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group">
                        <label>Update Status</label>
                        <select name="status" class="form-control">
                            <option value="open" {{ $supportTicket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $supportTicket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $supportTicket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $supportTicket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>

                <form action="{{ route('admin.support.update-priority', $supportTicket) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group">
                        <label>Update Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low" {{ $supportTicket->priority == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $supportTicket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $supportTicket->priority == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ $supportTicket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Priority</button>
                </form>

                <form action="{{ route('admin.support.destroy', $supportTicket) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Delete Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

