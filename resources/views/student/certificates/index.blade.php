@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>My Certificates</h2>
    </div>
</div>

<div class="adomx-table-card">
    <div class="adomx-table-header">
        <h3 class="adomx-table-title">All My Certificates</h3>
    </div>
    
    <div class="adomx-table-container">
        <table class="adomx-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Issued Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $certificate)
                    <tr>
                        <td><strong>{{ $certificate->course->title ?? 'N/A' }}</strong></td>
                        <td>{{ $certificate->issued_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('student.certificates.show', $certificate) }}" class="adomx-action-btn" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($certificate->certificate_url)
                                    <a href="{{ asset('storage/' . $certificate->certificate_url) }}" target="_blank" class="adomx-action-btn" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="adomx-table-empty">
                            No certificates yet. Complete a course to earn your first certificate!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 20px 25px; border-top: 1px solid var(--border-color);">
        {{ $certificates->links() }}
    </div>
</div>
@endsection

