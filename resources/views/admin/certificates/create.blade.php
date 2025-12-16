@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Certificate</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.certificates.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.certificates.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="user_id" class="adomx-label">User <span class="text-danger">*</span></label>
                    <select id="user_id" name="user_id" class="adomx-input @error('user_id') is-invalid @enderror" required>
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="course_id" class="adomx-label">Course <span class="text-danger">*</span></label>
                    <select id="course_id" name="course_id" class="adomx-input @error('course_id') is-invalid @enderror" required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="certificate_url" class="adomx-label">Certificate URL (Optional - will be auto-generated if empty)</label>
                <input type="text" id="certificate_url" name="certificate_url" class="adomx-input @error('certificate_url') is-invalid @enderror" value="{{ old('certificate_url') }}">
                @error('certificate_url')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Certificate
                </button>
                <a href="{{ route('admin.certificates.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

