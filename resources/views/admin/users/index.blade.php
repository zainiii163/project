@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>User Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create User
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Users</h3>
        <i class="fas fa-cog"></i>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" 
                   name="search" 
                   class="adomx-search-input" 
                   placeholder="Search users..." 
                   value="{{ request('search') }}"
                   style="flex: 1; max-width: 300px;">
            <select name="role" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Roles</option>
                <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
            </select>
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
            <a href="{{ route('admin.users.index', ['export' => 'csv']) }}" class="adomx-btn adomx-btn-secondary">Export CSV</a>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="adomx-checkbox">
                    </th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Last Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="adomx-checkbox">
                        </td>
                        <td>
                            <div class="adomx-table-image">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=4f46e5&color=fff' }}" 
                                     alt="{{ $user->name }}">
                            </div>
                        </td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username ?? 'N/A' }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $user->role }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td>{{ $user->registration_date ? $user->registration_date->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.users.show', $user) }}" class="adomx-action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->status == 'suspended')
                                    <form action="{{ route('admin.users.activate', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Activate" style="color: var(--success-color);">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @elseif($user->status == 'active')
                                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Suspend" style="color: var(--warning-color);">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($user->role == 'teacher' && !$user->approved_at)
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Approve Teacher" style="color: var(--success-color);">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Reset password for this user?')">
                                    @csrf
                                    <button type="submit" class="adomx-action-btn" title="Reset Password" style="color: var(--info-color);">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>
                                @can('delete', $user)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adomx-action-btn" title="Delete" style="color: var(--danger-color);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="adomx-table-empty">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $users->links() }}
    </div>
</div>

<style>
.adomx-status-super_admin {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-color);
}

.adomx-status-admin {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning-color);
}

.adomx-status-teacher {
    background: rgba(59, 130, 246, 0.1);
    color: var(--info-color);
}

.adomx-status-student {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-color);
}
</style>
@endsection
