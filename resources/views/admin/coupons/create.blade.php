@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Coupon</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.coupons.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="code" class="adomx-label">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" id="code" name="code" class="adomx-input @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                    @error('code')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="type" class="adomx-label">Discount Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="adomx-input @error('type') is-invalid @enderror" required>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                    @error('type')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="value" class="adomx-label">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" id="value" name="value" class="adomx-input @error('value') is-invalid @enderror" value="{{ old('value') }}" step="0.01" min="0" required>
                    <small>Enter percentage (e.g., 10) or fixed amount (e.g., 50.00)</small>
                    @error('value')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="min_purchase" class="adomx-label">Minimum Purchase</label>
                    <input type="number" id="min_purchase" name="min_purchase" class="adomx-input @error('min_purchase') is-invalid @enderror" value="{{ old('min_purchase') }}" step="0.01" min="0">
                    @error('min_purchase')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="max_uses" class="adomx-label">Maximum Uses</label>
                    <input type="number" id="max_uses" name="max_uses" class="adomx-input @error('max_uses') is-invalid @enderror" value="{{ old('max_uses') }}" min="1">
                    @error('max_uses')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="valid_from" class="adomx-label">Valid From</label>
                    <input type="date" id="valid_from" name="valid_from" class="adomx-input @error('valid_from') is-invalid @enderror" value="{{ old('valid_from') }}">
                    @error('valid_from')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="valid_until" class="adomx-label">Valid Until</label>
                    <input type="date" id="valid_until" name="valid_until" class="adomx-input @error('valid_until') is-invalid @enderror" value="{{ old('valid_until') }}">
                    @error('valid_until')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="is_active" class="adomx-label">Status</label>
                    <select id="is_active" name="is_active" class="adomx-input @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Create Coupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="adomx-btn adomx-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

