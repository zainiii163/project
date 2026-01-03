@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Support Tickets</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.support.analytics') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="adomx-row mb-4">
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--success-color);">{{ $stats['open'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Open</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--info-color);">{{ $stats['in_progress'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">In Progress</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--secondary-color);">{{ $stats['resolved'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Resolved</p>
            </div>
        </div>
    </div>
    <div class="adomx-col-md-3">
        <div class="adomx-card">
            <div class="adomx-card-body">
                <h3 style="font-size: 32px; margin: 0; color: var(--danger-color);">{{ $stats['urgent'] }}</h3>
                <p style="margin: 5px 0 0 0; color: var(--text-secondary);">Urgent</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="adomx-card mb-4">
    <div class="adomx-card-body">
        <form method="GET" action="{{ route('admin.support.index') }}" class="row g-3">
            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Category</label>
                <select name="category" class="form-control">
                    <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                    <option value="account" {{ request('category') == 'account' ? 'selected' : '' }}>Account</option>
                    <option value="course" {{ request('category') == 'course' ? 'selected' : '' }}>Course</option>
                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Assigned To</label>
                <select name="assigned_to" class="form-control">
                    <option value="">All</option>
                    <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                    @foreach($supportStaff as $staff)
                    <option value="{{ $staff->id }}" {{ request('assigned_to') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tickets Table -->
<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Tickets</h3>
    </div>
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Subject</th>
                        <th>User</th>
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
                        <td>{{ $ticket->user->name }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($ticket->category) }}</span></td>
                        <td>
                            <span class="badge badge-{{ $ticket->priority === 'urgent' ? 'danger' : ($ticket->priority === 'high' ? 'warning' : ($ticket->priority === 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'in_progress' ? 'info' : ($ticket->status === 'resolved' ? 'secondary' : 'dark')) }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.support.show', $ticket) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No tickets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $tickets->links() }}
    </div>
</div>
@endsection

