@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Audit Logs & Activity Tracking</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.audit-logs.export', request()->all()) }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-download"></i>
            Export CSV
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Audit Logs</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <select name="user_id" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="action" class="adomx-search-input" placeholder="Search action..." value="{{ request('action') }}" style="flex: 1; max-width: 200px;">
            <input type="date" name="date_from" class="adomx-search-input" value="{{ request('date_from') }}" style="max-width: 150px;">
            <input type="date" name="date_to" class="adomx-search-input" value="{{ request('date_to') }}" style="max-width: 150px;">
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>IP Address</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
                        <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td>
                            <a href="{{ route('admin.audit-logs.show', $log) }}" class="adomx-action-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="adomx-table-empty">No audit logs found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $logs->links() }}
    </div>
</div>
@endsection

