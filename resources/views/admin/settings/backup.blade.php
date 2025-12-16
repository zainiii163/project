@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Backup Management</h2>
    </div>
    <div class="adomx-page-actions">
        <form action="{{ route('admin.settings.backup.create') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="adomx-btn adomx-btn-primary">
                <i class="fas fa-plus"></i>
                Create Backup
            </button>
        </form>
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Backup History</h3>
    </div>
    <div class="adomx-card-body">
        @if(count($backups) > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Backup ID</th>
                            <th>Created At</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr>
                                <td><strong>{{ $backup['id'] ?? 'N/A' }}</strong></td>
                                <td>{{ isset($backup['created_at']) ? \Carbon\Carbon::parse($backup['created_at'])->format('M d, Y H:i') : 'N/A' }}</td>
                                <td>{{ $backup['size'] ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('admin.settings.backup.restore', $backup['id'] ?? '') }}" method="POST" style="display: inline;" onsubmit="return confirm('Restore from this backup? This will overwrite current data.')">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Restore" style="color: var(--warning-color);">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-database" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">No Backups Available</h3>
                <p style="color: var(--text-secondary); margin-bottom: 20px;">Create your first backup to protect your data.</p>
                <form action="{{ route('admin.settings.backup.create') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-plus"></i> Create Backup
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

