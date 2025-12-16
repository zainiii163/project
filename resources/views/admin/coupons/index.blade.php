@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Coupon & Discount Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.coupons.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Coupon
        </a>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Coupons</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('admin.coupons.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search by code..." value="{{ request('search') }}" style="flex: 1; max-width: 300px;">
            <select name="status" class="adomx-search-input" style="max-width: 150px;">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Filter</button>
        </form>
    </div>

    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Purchase</th>
                    <th>Used</th>
                    <th>Max Uses</th>
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
                        <td>{{ $coupon->used_count ?? 0 }}</td>
                        <td>{{ $coupon->max_uses ?? 'Unlimited' }}</td>
                        <td>{{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $coupon->is_active ? 'published' : 'draft' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="adomx-action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="adomx-action-btn" title="Delete" style="color: var(--danger-color);">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="adomx-table-empty">No coupons found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $coupons->links() }}
    </div>
</div>
@endsection

