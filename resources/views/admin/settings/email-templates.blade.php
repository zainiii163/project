@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Email Templates</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    @foreach($templates as $key => $name)
    <div class="adomx-col-md-6" style="margin-bottom: 20px;">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>{{ $name }}</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.settings.email-templates.update', $key) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="adomx-form-group" style="margin-bottom: 15px;">
                        <label class="adomx-form-label">Subject <span class="adomx-required">*</span></label>
                        <input type="text" 
                               class="adomx-form-input" 
                               name="subject" 
                               value="{{ old('subject', 'Email Subject') }}" 
                               required>
                    </div>
                    
                    <div class="adomx-form-group" style="margin-bottom: 15px;">
                        <label class="adomx-form-label">Body <span class="adomx-required">*</span></label>
                        <textarea class="adomx-form-input" 
                                  name="body" 
                                  rows="8" 
                                  required>{{ old('body', 'Email body content...') }}</textarea>
                    </div>
                    
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-save"></i> Update Template
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

