@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Course
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Discussions</h1>
            <p class="text-muted">{{ $course->title }}</p>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createDiscussionModal">
            <i class="fas fa-plus"></i> Start Discussion
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($discussions as $discussion)
            <div class="border-bottom pb-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            @if($discussion->is_pinned)
                            <span class="badge badge-warning mr-2">
                                <i class="fas fa-thumbtack"></i> Pinned
                            </span>
                            @endif
                            <h4 class="mb-0">{{ $discussion->message }}</h4>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> {{ $discussion->user->name }} 
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> {{ $discussion->created_at->diffForHumans() }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-comments"></i> {{ $discussion->replies->count() }} {{ Str::plural('reply', $discussion->replies->count()) }}
                            </small>
                        </div>
                    </div>
                    <div>
                        @can('update', $discussion)
                        <a href="#" class="btn btn-sm btn-outline-secondary" onclick="editDiscussion({{ $discussion->id }})">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endcan
                        @can('delete', $discussion)
                        <form action="{{ route('discussions.destroy', $discussion) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                <!-- Replies -->
                @if($discussion->replies->isNotEmpty())
                <div class="ml-4 mt-3">
                    <h6 class="mb-3">Replies:</h6>
                    @foreach($discussion->replies as $reply)
                    <div class="border-left pl-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <p class="mb-1">{{ $reply->message }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> {{ $reply->user->name }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> {{ $reply->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @can('delete', $reply)
                            <form action="{{ route('discussions.destroy', $reply) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Reply Form -->
                @if(!$discussion->is_locked)
                <div class="mt-3">
                    <form action="{{ route('discussions.store', $course) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $discussion->id }}">
                        <div class="form-group">
                            <textarea name="message" class="form-control" rows="2" placeholder="Write a reply..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-reply"></i> Reply
                        </button>
                    </form>
                </div>
                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-lock"></i> This discussion is locked. No new replies can be added.
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                <p class="text-muted">No discussions yet. Be the first to start a discussion!</p>
            </div>
            @endforelse
        </div>
        <div class="card-footer">
            {{ $discussions->links() }}
        </div>
    </div>
</div>

<!-- Create Discussion Modal -->
<div class="modal fade" id="createDiscussionModal" tabindex="-1" role="dialog" aria-labelledby="createDiscussionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('discussions.store', $course) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createDiscussionModalLabel">Start New Discussion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message">Message <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control" rows="5" required placeholder="What would you like to discuss?"></textarea>
                        <small class="form-text text-muted">Share your thoughts, ask questions, or start a conversation with your peers.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post Discussion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

