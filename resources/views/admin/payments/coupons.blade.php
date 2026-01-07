@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Coupon Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.payments.coupons.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Coupon
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Coupons</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Purchase</th>
                    <th>Usage Limit</th>
                    <th>Used Count</th>
                    <th>Valid From</th>
                    <th>Valid Until</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ ucfirst($coupon->type) }}</td>
                        <td>
                            @if($coupon->type == 'percentage')
                                {{ $coupon->value }}%
                            @else
                                ${{ number_format($coupon->value, 2) }}
                            @endif
                        </td>
                        <td>{{ $coupon->min_purchase ? '$' . number_format($coupon->min_purchase, 2) : 'N/A' }}</td>
                        <td>{{ $coupon->max_uses ?? 'Unlimited' }}</td>
                        <td>{{ $coupon->used_count ?? 0 }}</td>
                        <td>{{ $coupon->valid_from ? $coupon->valid_from->format('M d, Y') : 'N/A' }}</td>
                        <td>{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $coupon->is_active ? 'published' : 'draft' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.payments.coupons.edit', $coupon) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="adomx-table-empty">No coupons found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $coupons->links() }}
    </div>
</div>
@endsection

