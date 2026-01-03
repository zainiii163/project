@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Membership Plans</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.membership-plans.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i> Create Plan
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Billing Cycle</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Subscribers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>${{ number_format($plan->price, 2) }}</td>
                        <td>{{ ucfirst($plan->billing_cycle) }}</td>
                        <td>
                            @if($plan->is_all_access)
                                <span class="badge badge-success">All Access</span>
                            @else
                                <span class="badge badge-info">Limited</span>
                            @endif
                        </td>
                        <td>
                            @if($plan->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $plan->subscriptions()->count() }}</td>
                        <td>
                            <a href="{{ route('admin.membership-plans.edit', $plan) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.membership-plans.destroy', $plan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No membership plans found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $plans->links() }}
    </div>
</div>
@endsection

