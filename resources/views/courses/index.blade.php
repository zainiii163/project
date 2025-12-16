@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Browse Courses</h2>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">All Published Courses</h3>
    </div>
    
    <div style="padding: 0 25px 20px;">
        <form action="{{ route('courses.index') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" name="search" class="adomx-search-input" placeholder="Search courses..." value="{{ request('search') }}" style="flex: 1; min-width: 200px;">
            <select name="category" class="adomx-search-input" style="max-width: 200px;">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="adomx-btn adomx-btn-primary">Search</button>
        </form>
    </div>

    <div class="adomx-card-body">
        @if($courses->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach($courses as $course)
                    <div class="adomx-card" style="cursor: pointer;" onclick="window.location='{{ route('courses.show', $course->slug) }}'">
                        <div style="position: relative;">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                            @else
                                <div style="width: 100%; height: 200px; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; border-radius: 8px 8px 0 0;">
                                    <i class="fas fa-book" style="font-size: 48px; color: var(--text-secondary);"></i>
                                </div>
                            @endif
                            @if($course->category)
                                <span style="position: absolute; top: 10px; right: 10px; background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px;">
                                    {{ $course->category->name }}
                                </span>
                            @endif
                        </div>
                        <div class="adomx-card-body">
                            <h4 style="margin-bottom: 10px; font-size: 18px;">{{ $course->title }}</h4>
                            <p style="color: var(--text-secondary); font-size: 14px; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">Teacher</div>
                                    <div style="font-weight: 500;">{{ $course->teacher->name ?? 'N/A' }}</div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 20px; font-weight: bold; color: var(--primary-color);">${{ number_format($course->price, 2) }}</div>
                                </div>
                            </div>
                            @if($course->reviews->count() > 0)
                                <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 15px;">
                                    @php
                                        $avgRating = $course->reviews->avg('rating');
                                    @endphp
                                    <div style="color: #fbbf24;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= round($avgRating) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <span style="font-size: 14px; color: var(--text-secondary);">
                                        ({{ $course->reviews->count() }})
                                    </span>
                                </div>
                            @endif
                            <a href="{{ route('courses.show', $course->slug) }}" class="adomx-btn adomx-btn-primary" style="width: 100%;">
                                View Course
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="padding: 20px 0; margin-top: 20px; border-top: 1px solid var(--border-color);">
                {{ $courses->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-book-open" style="font-size: 48px; color: var(--text-secondary); margin-bottom: 15px;"></i>
                <h3 style="margin-bottom: 10px;">No Courses Found</h3>
                <p style="color: var(--text-secondary);">Try adjusting your search or filter criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection

