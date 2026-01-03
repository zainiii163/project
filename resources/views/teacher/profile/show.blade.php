@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Profile</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.profile.edit') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Profile Info Card -->
    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Profile Information</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=150&background=4f46e5&color=fff' }}" 
                         alt="{{ $user->name }}" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                </div>
                <table class="adomx-info-table">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td>{{ $user->username ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-teacher">Teacher</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td><span class="adomx-status-badge adomx-status-{{ $user->status ?? 'active' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Registered:</strong></td>
                        <td>{{ $user->registration_date ? $user->registration_date->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Last Login:</strong></td>
                        <td>{{ $user->last_login ? $user->last_login->format('M d, Y H:i') : 'Never' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Stats & Details -->
    <div class="adomx-col-md-8">
        <div class="adomx-row">
            <div class="adomx-col-md-6">
                <div class="adomx-card">
                    <div class="adomx-card-body">
                        <h4>My Courses</h4>
                        <h2 style="color: var(--primary); margin: 10px 0;">{{ $user->taughtCourses()->count() }}</h2>
                        <a href="{{ route('teacher.courses.index') }}" class="adomx-btn adomx-btn-sm adomx-btn-primary">View All</a>
                    </div>
                </div>
            </div>
            <div class="adomx-col-md-6">
                <div class="adomx-card">
                    <div class="adomx-card-body">
                        <h4>Total Students</h4>
                        <h2 style="color: var(--primary); margin: 10px 0;">{{ $user->taughtCourses()->withCount('students')->get()->sum('students_count') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        @if($user->bio)
        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Bio</h3>
            </div>
            <div class="adomx-card-body">
                <p>{{ $user->bio }}</p>
            </div>
        </div>
        @endif

        <div class="adomx-card" style="margin-top: 20px;">
            <div class="adomx-card-header">
                <h3>Change Password</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('teacher.profile.update-password') }}" method="POST">
                    @csrf
                    <div class="adomx-form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="adomx-form-control" required>
                    </div>
                    <div class="adomx-form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="adomx-form-control" required>
                    </div>
                    <div class="adomx-form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="adomx-form-control" required>
                    </div>
                    <button type="submit" class="adomx-btn adomx-btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

