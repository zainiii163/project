@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Edit Coupon</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.coupons') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Coupon Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.payments.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Coupon Code <span class="adomx-required">*</span></label>
                <input type="text" 
                       class="adomx-form-input @error('code') adomx-input-error @enderror" 
                       name="code" 
                       value="{{ old('code', $coupon->code) }}" 
                       required>
                @error('code')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Discount Type <span class="adomx-required">*</span></label>
                    <select class="adomx-form-input @error('type') adomx-input-error @enderror" name="type" required>
                        <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                    @error('type')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Discount Value <span class="adomx-required">*</span></label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="adomx-form-input @error('value') adomx-input-error @enderror" 
                           name="value" 
                           value="{{ old('value', $coupon->value) }}" 
                           required>
                    @error('value')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Minimum Purchase</label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="adomx-form-input @error('min_purchase') adomx-input-error @enderror" 
                           name="min_purchase" 
                           value="{{ old('min_purchase', $coupon->min_purchase) }}">
                    @error('min_purchase')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Maximum Discount</label>
                    <input type="number" 
                           step="0.01" 
                           min="0" 
                           class="adomx-form-input @error('max_discount') adomx-input-error @enderror" 
                           name="max_discount" 
                           value="{{ old('max_discount', $coupon->max_discount ?? '') }}">
                    @error('max_discount')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Usage Limit</label>
                <input type="number" 
                       min="1" 
                       class="adomx-form-input @error('usage_limit') adomx-input-error @enderror" 
                       name="usage_limit" 
                       value="{{ old('usage_limit', $coupon->max_uses) }}"
                       placeholder="Leave empty for unlimited">
                @error('usage_limit')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Valid From <span class="adomx-required">*</span></label>
                    <input type="date" 
                           class="adomx-form-input @error('valid_from') adomx-input-error @enderror" 
                           name="valid_from" 
                           value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d') : '') }}" 
                           required>
                    @error('valid_from')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Valid Until <span class="adomx-required">*</span></label>
                    <input type="date" 
                           class="adomx-form-input @error('valid_until') adomx-input-error @enderror" 
                           name="valid_until" 
                           value="{{ old('valid_until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : '') }}" 
                           required>
                    @error('valid_until')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Coupon
                </button>
                <a href="{{ route('admin.payments.coupons') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

