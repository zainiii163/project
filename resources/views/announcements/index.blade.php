@extends(auth()->user()->isAdmin() || auth()->user()->isTeacher() ? 'layouts.admin' : 'layouts.main')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Announcements</h2>
    </div>
    @can('create', App\Models\Announcement::class)
    <div class="adomx-page-actions">
        <a href="{{ route('announcements.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Announcement
        </a>
    </div>
    @endcan
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All Announcements</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Scope</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td><strong>{{ $announcement->title }}</strong></td>
                        <td>
                            <span class="adomx-status-badge adomx-status-{{ $announcement->scope }}">
                                {{ ucfirst($announcement->scope) }}
                            </span>
                        </td>
                        <td>{{ $announcement->course->title ?? 'N/A' }}</td>
                        <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                        <td>
                            @php
                                $isRead = false;
                                if (auth()->check() && method_exists($announcement, 'recipients')) {
                                    try {
                                        $isRead = $announcement->recipients()
                                            ->where('user_id', auth()->id())
                                            ->wherePivot('is_read', true)
                                            ->exists();
                                    } catch (\Exception $e) {
                                        $isRead = false;
                                    }
                                }
                            @endphp
                            @if($isRead)
                                <span class="adomx-status-badge adomx-status-published">Read</span>
                            @else
                                <span class="adomx-status-badge adomx-status-draft">Unread</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button type="button" class="adomx-action-btn" onclick="showAnnouncement({{ $announcement->id }})" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!$isRead && auth()->check())
                                    <form action="{{ route('announcements.read', $announcement) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="adomx-action-btn" title="Mark as Read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="adomx-table-empty">No announcements found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $announcements->links() }}
    </div>
</div>

<!-- Announcement Modal -->
<div id="announcementModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;" class="d-flex">
    <div style="background: white; padding: 30px; border-radius: 8px; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 id="modalTitle"></h3>
            <button onclick="closeAnnouncement()" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <div id="modalContent"></div>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
            <button onclick="closeAnnouncement()" class="adomx-btn adomx-btn-secondary">Close</button>
        </div>
    </div>
</div>

@php
    $announcementsData = $announcements->map(function($announcement) {
        return [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'content' => $announcement->content,
            'scope' => $announcement->scope,
            'course' => $announcement->course->title ?? null,
        ];
    })->values()->toArray();
@endphp

<script>
const announcements = @json($announcementsData);

function showAnnouncement(id) {
    const announcement = announcements.find(a => a.id === id);
    if (announcement) {
        document.getElementById('modalTitle').textContent = announcement.title;
        const content = announcement.content.replace(/\n/g, '<br>').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        document.getElementById('modalContent').innerHTML = '<p>' + content + '</p>';
        document.getElementById('announcementModal').style.display = 'flex';
    }
}

function closeAnnouncement() {
    document.getElementById('announcementModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('announcementModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAnnouncement();
    }
});
</script>
@endsection

