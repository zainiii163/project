@extends('layouts.main')

@section('content')
<main id="main-content" role="main" aria-label="Subscription Plans">
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Membership Plans & Subscriptions</h1>
            <p class="text-muted">Choose a plan that fits your learning needs</p>
        </div>
    </div>

    @if($activeSubscription)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h5><i class="fas fa-check-circle"></i> Active Subscription</h5>
                <p><strong>{{ $activeSubscription->membershipPlan->name }}</strong></p>
                <p>Status: <span class="badge badge-success">{{ ucfirst($activeSubscription->status) }}</span></p>
                <p>Valid until: {{ $activeSubscription->end_date->format('F d, Y') }}</p>
                @if($activeSubscription->next_billing_date)
                    <p>Next billing: {{ $activeSubscription->next_billing_date->format('F d, Y') }}</p>
                @endif
                <form action="{{ route('subscriptions.cancel', $activeSubscription) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel your subscription?')">
                        Cancel Subscription
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        @foreach($plans as $plan)
        <div class="col-md-4 mb-4">
            <div class="card h-100 {{ $plan->is_all_access ? 'border-primary' : '' }}">
                @if($plan->is_all_access)
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">All Access</h5>
                </div>
                @endif
                <div class="card-body">
                    <h3 class="card-title">{{ $plan->name }}</h3>
                    <p class="text-muted">{{ $plan->description }}</p>
                    
                    <div class="mb-3">
                        <h2 class="mb-0">${{ number_format($plan->price, 2) }}</h2>
                        <small class="text-muted">
                            @if($plan->billing_cycle === 'lifetime')
                                One-time payment
                            @else
                                per {{ $plan->billing_cycle }}
                            @endif
                        </small>
                    </div>

                    @if($plan->features)
                    <ul class="list-unstyled">
                        @foreach($plan->features as $feature)
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i> {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if($plan->is_all_access)
                        <p class="text-primary"><strong>Access to all courses</strong></p>
                    @elseif($plan->max_courses)
                        <p><strong>Up to {{ $plan->max_courses }} courses</strong></p>
                    @endif
                </div>
                <div class="card-footer">
                    @if($activeSubscription && $activeSubscription->membershipPlan->id === $plan->id)
                        <button class="btn btn-secondary btn-block" disabled>Current Plan</button>
                    @else
                        <form action="{{ route('subscriptions.subscribe', $plan) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">
                                Subscribe Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</main>
@endsection

