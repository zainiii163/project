@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Notifications</h1>
        <div>
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('filter') !== 'read' ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                        Unread
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('filter') === 'read' ? 'active' : '' }}" href="{{ route('notifications.index', ['filter' => 'read']) }}">
                        Read
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            @forelse($notifications as $notification)
            <div class="border-bottom pb-3 mb-3 {{ !$notification->is_read ? 'bg-light p-3 rounded' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            @if(!$notification->is_read)
                            <span class="badge badge-primary mr-2">New</span>
                            @endif
                            <strong>{{ $notification->title }}</strong>
                            <span class="badge badge-{{ $notification->type === 'announcement' ? 'info' : ($notification->type === 'certificate_issued' ? 'success' : 'secondary') }} ml-2">
                                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                            </span>
                        </div>
                        <p class="mb-2">{{ $notification->message }}</p>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <div class="ml-3">
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Mark as Read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No notifications found.</p>
            </div>
            @endforelse
        </div>
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection

