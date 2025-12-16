@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Course Monetization - {{ $course->title }}</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('teacher.courses.show', $course) }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <!-- Earnings Summary -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Earnings Summary</h3>
            </div>
            <div class="adomx-card-body">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;">
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Revenue</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--primary-color);">${{ number_format($earnings['total_revenue'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Your Earnings</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--success-color);">${{ number_format($earnings['teacher_earnings'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Platform Commission</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--warning-color);">${{ number_format($earnings['platform_commission'], 2) }}</div>
                    </div>
                    <div style="padding: 20px; background: var(--card-bg); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 10px;">Total Sales</div>
                        <div style="font-size: 28px; font-weight: bold; color: var(--info-color);">{{ $earnings['total_sales'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="adomx-col-md-6">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Current Pricing</h3>
            </div>
            <div class="adomx-card-body">
                <div style="padding: 20px;">
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Course Price</div>
                        <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);">${{ number_format($pricing['current_price'], 2) }}</div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Subscription Based</div>
                        <div style="font-size: 18px; font-weight: bold; color: {{ $pricing['subscription_based'] ? 'var(--success-color)' : 'var(--text-secondary)' }};">
                            {{ $pricing['subscription_based'] ? 'Yes' : 'No' }}
                        </div>
                    </div>
                    <a href="#update-pricing" class="adomx-btn adomx-btn-primary" onclick="showPricingForm()">
                        <i class="fas fa-edit"></i> Update Pricing
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Pricing Form -->
    <div class="adomx-col-md-6" id="update-pricing" style="display: none;">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Update Pricing</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('teacher.courses.update-pricing', $course) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom: 20px;">
                        <label class="adomx-label">Price ($) <span class="adomx-required">*</span></label>
                        <input type="number" 
                               name="price" 
                               class="adomx-input" 
                               step="0.01" 
                               min="0" 
                               value="{{ $course->price }}" 
                               required>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="adomx-label">Subscription Price ($)</label>
                        <input type="number" 
                               name="subscription_price" 
                               class="adomx-input" 
                               step="0.01" 
                               min="0" 
                               value="{{ $course->subscription_price ?? 0 }}">
                    </div>
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-save"></i> Update Pricing
                    </button>
                    <button type="button" class="adomx-btn adomx-btn-secondary" onclick="hidePricingForm()">
                        Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Apply Promotion -->
    <div class="adomx-col-md-12">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Apply Promotion</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('teacher.courses.apply-promotion', $course) }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label class="adomx-label">Discount Type <span class="adomx-required">*</span></label>
                            <select name="discount_type" class="adomx-input" required>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>
                        <div>
                            <label class="adomx-label">Discount Value <span class="adomx-required">*</span></label>
                            <input type="number" name="discount_value" class="adomx-input" step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="adomx-label">Valid From <span class="adomx-required">*</span></label>
                            <input type="date" name="valid_from" class="adomx-input" required>
                        </div>
                        <div>
                            <label class="adomx-label">Valid Until <span class="adomx-required">*</span></label>
                            <input type="date" name="valid_until" class="adomx-input" required>
                        </div>
                    </div>
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-tag"></i> Apply Promotion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showPricingForm() {
    document.getElementById('update-pricing').style.display = 'block';
    document.getElementById('update-pricing').scrollIntoView({ behavior: 'smooth' });
}

function hidePricingForm() {
    document.getElementById('update-pricing').style.display = 'none';
}
</script>
@endsection

