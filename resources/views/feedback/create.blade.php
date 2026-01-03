@extends('layouts.main')

@section('content')
<div class="container py-5">
    <h1>Submit Feedback</h1>

    <form action="{{ route('feedback.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Course (Optional)</label>
            <select name="course_id" class="form-control">
                <option value="">Select a course...</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Type <span class="text-danger">*</span></label>
            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="general">General</option>
                <option value="technical">Technical</option>
                <option value="content">Content</option>
                <option value="billing">Billing</option>
                <option value="other">Other</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
            @error('subject')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Message <span class="text-danger">*</span></label>
            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
            @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Rating (Optional)</label>
            <select name="rating" class="form-control">
                <option value="">No rating</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>
@endsection

