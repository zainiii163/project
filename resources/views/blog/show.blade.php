@extends('layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Blog
        </a>
    </div>

    <article class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                @if($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="card-img-top" style="max-height: 400px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="mb-3">
                        @foreach($post->categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->id]) }}" class="badge badge-primary mr-2">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                    <h1 class="mb-3">{{ $post->title }}</h1>
                    <div class="d-flex align-items-center mb-4 text-muted">
                        <div class="mr-3">
                            <i class="fas fa-user"></i> {{ $post->author->name ?? 'Admin' }}
                        </div>
                        <div class="mr-3">
                            <i class="fas fa-calendar"></i> {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                        </div>
                        @if($post->tags->isNotEmpty())
                        <div>
                            <i class="fas fa-tags"></i>
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag->id]) }}" class="text-muted">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Share this article</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Recent Posts -->
            @if($recentPosts->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Recent Posts</h5>
                </div>
                <div class="card-body">
                    @foreach($recentPosts as $recentPost)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6><a href="{{ route('blog.show', $recentPost->slug) }}">{{ $recentPost->title }}</a></h6>
                        <small class="text-muted">{{ $recentPost->published_at ? $recentPost->published_at->format('M d, Y') : $recentPost->created_at->format('M d, Y') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            @php
                $allCategories = \App\Models\Category::all();
            @endphp
            @if($allCategories->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($allCategories as $category)
                        <li class="mb-2">
                            <a href="{{ route('blog.index', ['category' => $category->id]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </article>
</div>
@endsection

@push('head')
@php
    use Illuminate\Support\Str;
@endphp
<!-- SEO Meta Tags -->
<meta name="description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
<meta name="keywords" content="{{ $post->tags->pluck('name')->implode(', ') }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
<meta property="og:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->title }}">
<meta name="twitter:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
<meta name="twitter:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}">
@endpush

