@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Support Ticket</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('support.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Tickets
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">New Support Ticket</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('support.store') }}" method="POST">
            @csrf

            <div class="adomx-form-group">
                <label class="adomx-form-label">Subject <span class="adomx-required">*</span></label>
                <input type="text" name="subject" id="subject" class="adomx-form-input @error('subject') adomx-input-error @enderror" value="{{ old('subject') }}" required>
                @error('subject')
                <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label class="adomx-form-label">Category <span class="adomx-required">*</span></label>
                <select name="category" id="category" class="adomx-form-input @error('category') adomx-input-error @enderror" required>
                    <option value="">Select Category</option>
                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                    <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account</option>
                    <option value="course" {{ old('category') == 'course' ? 'selected' : '' }}>Course Related</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label class="adomx-form-label">Priority <span class="adomx-required">*</span></label>
                <select name="priority" id="priority" class="adomx-form-input @error('priority') adomx-input-error @enderror" required>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
                @error('priority')
                <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label class="adomx-form-label">Description <span class="adomx-required">*</span></label>
                <textarea name="description" id="description" class="adomx-form-input @error('description') adomx-input-error @enderror" rows="8" required>{{ old('description') }}</textarea>
                <div class="adomx-form-help">Please provide as much detail as possible to help us assist you better.</div>
                @error('description')
                <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

