@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Student Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.users.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Student
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Students</h3>
    </div>
    
    <!-- Filters -->
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.students.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" 
                   name="search" 
                   class="adomx-search-input" 
                   placeholder="Search students..." 
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
                    <th>Certificates</th>
                    <th>Quiz Attempts</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>
                            <div class="adomx-table-image">
                                <img src="{{ $student->profile_picture ? asset('storage/' . $student->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=10b981&color=fff' }}" 
                                     alt="{{ $student->name }}">
                            </div>
                        </td>
                        <td><strong>{{ $student->name }}</strong></td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->courses_count ?? 0 }}</td>
                        <td>{{ $student->certificates_count ?? 0 }}</td>
                        <td>{{ $student->attempts_count ?? 0 }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $student->status ?? 'active' }}">
                                {{ ucfirst($student->status ?? 'active') }}
                            </span>
                        </td>
                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                <a href="{{ route('admin.students.show', $student) }}" class="adomx-action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.students.activity', $student) }}" class="adomx-action-btn" title="Activity" style="color: var(--info-color);">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('admin.students.feedback', $student) }}" class="adomx-action-btn" title="Feedback" style="color: var(--warning-color);">
                                    <i class="fas fa-comment"></i>
                                </a>
                                @if($student->status == 'suspended')
                                    <form action="{{ route('admin.students.activate', $student) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Activate" style="color: var(--success-color);">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.students.suspend', $student) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Suspend" style="color: var(--warning-color);">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="adomx-table-empty">No students found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $students->links() }}
    </div>
</div>
@endsection

