@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Surveys Management</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.surveys.create') }}" class="adomx-btn adomx-btn-primary">
            <i class="fas fa-plus"></i>
            Create Survey
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>All Surveys</h3>
    </div>
    <div class="adomx-card-body">
        @if($surveys->count() > 0)
            <div class="adomx-table-container">
                <table class="adomx-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Course</th>
                            <th>Questions</th>
                            <th>Responses</th>
                            <th>Status</th>
                            <th>Date Range</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($surveys as $survey)
                            <tr>
                                <td><strong>{{ $survey->title }}</strong></td>
                                <td>
                                    <span class="adomx-status-badge adomx-status-{{ $survey->type }}">
                                        {{ ucfirst($survey->type) }}
                                    </span>
                                </td>
                                <td>{{ $survey->course->title ?? 'General' }}</td>
                                <td>{{ $survey->questions->count() }}</td>
                                <td>{{ $survey->responses_count ?? 0 }}</td>
                                <td>
                                    <span class="adomx-status-badge adomx-status-{{ $survey->is_active ? 'active' : 'inactive' }}">
                                        {{ $survey->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $survey->start_date->format('M d, Y') }} - 
                                        {{ $survey->end_date->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('admin.surveys.show', $survey) }}" class="adomx-action-btn" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $surveys->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-poll" style="font-size: 64px; color: var(--text-secondary); margin-bottom: 20px;"></i>
                <h3 style="margin-bottom: 10px;">No Surveys Yet</h3>
                <p style="color: var(--text-secondary); margin-bottom: 20px;">Create your first survey to collect feedback from students.</p>
                <a href="{{ route('admin.surveys.create') }}" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-plus"></i> Create Survey
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

