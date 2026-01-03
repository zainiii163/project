@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Activity Logs: {{ $user->name }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.show', $user) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to User
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">User Activity History</h3>
    </div>
    <div class="adomx-card-body">
        <div class="adomx-table-container">
            <table class="adomx-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Action</th>
                        <th>Model Type</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        <td>
                            <span class="badge badge-{{ $log->action === 'created' ? 'success' : ($log->action === 'updated' ? 'info' : ($log->action === 'deleted' ? 'danger' : 'secondary')) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ class_basename($log->model_type ?? 'N/A') }}</td>
                        <td>{{ $log->ip_address ?? 'N/A' }}</td>
                        <td>
                            <span title="{{ $log->user_agent ?? 'N/A' }}">
                                {{ Str::limit($log->user_agent ?? 'N/A', 30) }}
                            </span>
                        </td>
                        <td>
                            @if($log->changes)
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="showChanges({{ $log->id }})">
                                <i class="fas fa-eye"></i> View Changes
                            </button>
                            @else
                            <span class="text-muted">No details</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No activity logs found for this user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="adomx-table-footer">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- Changes Modal -->
<div class="modal fade" id="changesModal" tabindex="-1" role="dialog" aria-labelledby="changesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changesModalLabel">Activity Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="changesContent">
                <!-- Changes will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@php
    $logsData = $logs->map(function($log) {
        return [
            'id' => $log->id,
            'changes' => $log->changes,
        ];
    })->keyBy('id')->toJson();
@endphp

<script>
const logsData = @json($logsData);

function showChanges(logId) {
    const log = logsData[logId];
    if (!log || !log.changes) {
        document.getElementById('changesContent').innerHTML = '<p>No changes recorded.</p>';
    } else {
        const changes = JSON.parse(log.changes);
        let html = '<div class="table-responsive"><table class="table table-sm">';
        html += '<thead><tr><th>Field</th><th>Old Value</th><th>New Value</th></tr></thead><tbody>';
        
        for (const [field, values] of Object.entries(changes)) {
            html += `<tr>
                <td><strong>${field}</strong></td>
                <td><code>${values[0] ?? 'N/A'}</code></td>
                <td><code>${values[1] ?? 'N/A'}</code></td>
            </tr>`;
        }
        
        html += '</tbody></table></div>';
        document.getElementById('changesContent').innerHTML = html;
    }
    
    $('#changesModal').modal('show');
}
</script>
@endsection

