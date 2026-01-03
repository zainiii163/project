@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Profile</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.profile.show') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Profile Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="adomx-row">
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="adomx-form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="adomx-form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="adomx-row">
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="adomx-form-control" value="{{ old('username', $user->username) }}">
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="adomx-col-md-6">
                    <div class="adomx-form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="adomx-form-control" value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="adomx-form-group">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" class="adomx-form-control" accept="image/*">
                @if($user->profile_picture)
                    <small>Current: <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" style="width: 50px; height: 50px; border-radius: 50%;"></small>
                @endif
                @error('profile_picture')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label>Bio</label>
                <textarea name="bio" class="adomx-form-control" rows="5">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

