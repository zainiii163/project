@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Audit Log Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.audit-logs.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div style="margin-bottom: 30px;">
            <h3>Log Information</h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
            <div>
                <strong>User:</strong> {{ $auditLog->user->name ?? 'System' }}
            </div>
            <div>
                <strong>Action:</strong> {{ $auditLog->action }}
            </div>
            <div>
                <strong>IP Address:</strong> {{ $auditLog->ip_address ?? 'N/A' }}
            </div>
            <div>
                <strong>User Agent:</strong> {{ $auditLog->user_agent ?? 'N/A' }}
            </div>
            <div>
                <strong>Model Type:</strong> {{ $auditLog->model_type ?? 'N/A' }}
            </div>
            <div>
                <strong>Model ID:</strong> {{ $auditLog->model_id ?? 'N/A' }}
            </div>
            <div>
                <strong>Created At:</strong> {{ $auditLog->created_at->format('M d, Y H:i:s') }}
            </div>
        </div>

        @if($auditLog->old_values)
            <div style="margin-bottom: 20px;">
                <strong>Old Values:</strong>
                <pre style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px; overflow-x: auto;">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        @if($auditLog->new_values)
            <div style="margin-bottom: 20px;">
                <strong>New Values:</strong>
                <pre style="margin-top: 10px; padding: 15px; background: var(--bg-secondary); border-radius: 8px; overflow-x: auto;">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </div>
</div>
@endsection

