@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Payout</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payouts.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Payout Information</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.payouts.store') }}" method="POST">
                    @csrf

                    <div class="adomx-form-group">
                        <label for="teacher_id" class="adomx-label">Teacher <span class="text-danger">*</span></label>
                        <select name="teacher_id" id="teacher_id" class="adomx-input" required onchange="loadPendingCommissions(this.value)">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ old('teacher_id', $teacher?->id) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} ({{ $t->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <span class="adomx-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="adomx-form-group">
                        <label for="amount" class="adomx-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" id="amount" name="amount" class="adomx-input" value="{{ old('amount') }}" required>
                        @error('amount')
                        <span class="adomx-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="adomx-form-group">
                        <label for="payment_method" class="adomx-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="adomx-input" required>
                            <option value="">Select Method</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paypal" {{ old('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="stripe" {{ old('payment_method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Check</option>
                        </select>
                        @error('payment_method')
                        <span class="adomx-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="adomx-form-group">
                        <label for="notes" class="adomx-label">Notes</label>
                        <textarea name="notes" id="notes" class="adomx-input" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                        <span class="adomx-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="adomx-form-group">
                        <button type="submit" class="adomx-btn adomx-btn-primary">
                            <i class="fas fa-save"></i> Create Payout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="adomx-col-md-4">
        @if($teacher && $pendingCommissions->isNotEmpty())
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3 class="adomx-card-title">Pending Commissions</h3>
            </div>
            <div class="adomx-card-body">
                <p class="text-muted">Total Pending: <strong>${{ number_format($pendingCommissions->sum('amount'), 2) }}</strong></p>
                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach($pendingCommissions as $commission)
                    <div style="padding: 10px; border-bottom: 1px solid var(--border-color);">
                        <div style="font-weight: 500;">{{ $commission->course->title ?? 'N/A' }}</div>
                        <div style="font-size: 12px; color: var(--text-secondary);">
                            Order #{{ substr($commission->order->id, 0, 8) }} - ${{ number_format($commission->amount, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function loadPendingCommissions(teacherId) {
    if (teacherId) {
        window.location.href = '{{ route("admin.payouts.create") }}?teacher_id=' + teacherId;
    }
}
</script>
@endsection

