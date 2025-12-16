@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Subscription</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.subscriptions.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group">
                <label for="user_id" class="adomx-label">User <span class="text-danger">*</span></label>
                <select id="user_id" name="user_id" class="adomx-input @error('user_id') is-invalid @enderror" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $subscription->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="plan" class="adomx-label">Plan <span class="text-danger">*</span></label>
                    <input type="text" id="plan" name="plan" class="adomx-input @error('plan') is-invalid @enderror" value="{{ old('plan', $subscription->plan) }}" required>
                    @error('plan')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="amount" class="adomx-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" id="amount" name="amount" class="adomx-input @error('amount') is-invalid @enderror" value="{{ old('amount', $subscription->amount) }}" step="0.01" min="0" required>
                    @error('amount')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="start_date" class="adomx-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" id="start_date" name="start_date" class="adomx-input @error('start_date') is-invalid @enderror" value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="end_date" class="adomx-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" id="end_date" name="end_date" class="adomx-input @error('end_date') is-invalid @enderror" value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required>
                    @error('end_date')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group">
                <label for="status" class="adomx-label">Status <span class="text-danger">*</span></label>
                <select id="status" name="status" class="adomx-input @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status', $subscription->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $subscription->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ old('status', $subscription->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Subscription
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

