@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Teacher Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Teacher
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Teachers</h3>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.teachers.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" 
                   name="search" 
                   class="adomx-search-input" 
                   placeholder="Search teachers..." 
                   value="{{ request('search') }}"
                   style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Courses</th>
                    <th>Reviews</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                    <tr>
                        <td>
                            <div class="adomx-table-image">
                                <img src="{{ $teacher->profile_picture ? asset('storage/' . $teacher->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=4f46e5&color=fff' }}" 
                                     alt="{{ $teacher->name }}">
                            </div>
                        </td>
                        <td><strong>{{ $teacher->name }}</strong></td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->taught_courses_count ?? 0 }}</td>
                        <td>{{ $teacher->reviews_count ?? 0 }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $teacher->status ?? 'active' }}">
                                {{ ucfirst($teacher->status ?? 'active') }}
                            </span>
                        </td>
                        <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="adomx-action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($teacher->status == 'suspended')
                                    <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Approve" style="color: var(--success-color);">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.teachers.suspend', $teacher) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Suspend" style="color: var(--warning-color);">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.teachers.payouts', $teacher) }}" class="adomx-action-btn" title="Payouts" style="color: var(--info-color);">
                                    <i class="fas fa-dollar-sign"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="adomx-table-empty">No teachers found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $teachers->links() }}
    </div>
</div>
@endsection

