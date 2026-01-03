@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Survey Details</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.surveys.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Surveys
        </a>
    </div>
</div>

<div class="adomx-row">
    <div class="adomx-col-md-8">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>{{ $survey->title }}</h3>
            </div>
            <div class="adomx-card-body">
                <p>{{ $survey->description }}</p>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin: 20px 0;">
                    <div>
                        <strong>Type:</strong> {{ ucfirst($survey->type) }}
                    </div>
                    <div>
                        <strong>Course:</strong> {{ $survey->course->title ?? 'General' }}
                    </div>
                    <div>
                        <strong>Status:</strong> 
                        <span class="adomx-status-badge adomx-status-{{ $survey->is_active ? 'active' : 'inactive' }}">
                            {{ $survey->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <strong>Anonymous:</strong> {{ $survey->is_anonymous ? 'Yes' : 'No' }}
                    </div>
                    <div>
                        <strong>Start Date:</strong> {{ $survey->start_date->format('M d, Y') }}
                    </div>
                    <div>
                        <strong>End Date:</strong> {{ $survey->end_date->format('M d, Y') }}
                    </div>
                </div>

                <hr style="margin: 30px 0; border-color: var(--border-color);">

                <h4 style="margin-bottom: 20px;">Questions & Responses</h4>
                
                @foreach($aggregatedResponses as $questionData)
                    <div style="padding: 20px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 20px; background: var(--card-bg);">
                        <h5 style="margin-bottom: 15px;">{{ $questionData['question']->question }}</h5>
                        <p style="color: var(--text-secondary); margin-bottom: 15px;">
                            <small>Type: {{ ucfirst($questionData['question']->type) }} | 
                            Required: {{ $questionData['question']->is_required ? 'Yes' : 'No' }} | 
                            Responses: {{ $questionData['responses']->count() }}</small>
                        </p>
                        
                        @if(in_array($questionData['question']->type, ['rating', 'scale']))
                            <div style="padding: 15px; background: var(--dark-bg-light); border-radius: 8px;">
                                <strong>Average:</strong> {{ $questionData['summary']['average'] ?? 'N/A' }} / 
                                {{ $questionData['question']->type == 'rating' ? '5' : '10' }}
                            </div>
                        @elseif(in_array($questionData['question']->type, ['radio', 'checkbox']))
                            <div style="padding: 15px; background: var(--dark-bg-light); border-radius: 8px;">
                                @foreach($questionData['summary'] as $option => $count)
                                    <div style="margin-bottom: 10px;">
                                        <strong>{{ $option }}:</strong> {{ $count }} responses
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="max-height: 200px; overflow-y: auto;">
                                @foreach($questionData['responses']->take(10) as $response)
                                    <div style="padding: 10px; margin-bottom: 10px; background: var(--dark-bg-light); border-radius: 4px;">
                                        {{ is_array($response->response) ? implode(', ', $response->response) : $response->response }}
                                        @if(!$survey->is_anonymous && $response->user)
                                            <small style="color: var(--text-secondary);"> - {{ $response->user->name }}</small>
                                        @endif
                                    </div>
                                @endforeach
                                @if($questionData['responses']->count() > 10)
                                    <p style="color: var(--text-secondary);">... and {{ $questionData['responses']->count() - 10 }} more responses</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="adomx-col-md-4">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>Survey Statistics</h3>
            </div>
            <div class="adomx-card-body">
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 48px; font-weight: bold; color: var(--primary-color);">
                        {{ $survey->responses->count() }}
                    </div>
                    <div style="color: var(--text-secondary); margin-top: 10px;">Total Responses</div>
                </div>
                
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 48px; font-weight: bold; color: var(--success-color);">
                        {{ $survey->questions->count() }}
                    </div>
                    <div style="color: var(--text-secondary); margin-top: 10px;">Total Questions</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

