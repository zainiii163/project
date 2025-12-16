@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Integration Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-row">
    @foreach($integrations as $key => $name)
    <div class="adomx-col-md-6" style="margin-bottom: 20px;">
        <div class="adomx-card">
            <div class="adomx-card-header">
                <h3>{{ $name }}</h3>
            </div>
            <div class="adomx-card-body">
                <form action="{{ route('admin.settings.integrations.update', $key) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="adomx-form-group" style="margin-bottom: 15px;">
                        <label class="adomx-form-label">
                            <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', false) ? 'checked' : '' }}>
                            Enable Integration
                        </label>
                    </div>
                    
                    <div class="adomx-form-group" style="margin-bottom: 15px;">
                        <label class="adomx-form-label">API Key</label>
                        <input type="text" 
                               class="adomx-form-input" 
                               name="api_key" 
                               value="{{ old('api_key') }}"
                               placeholder="Enter API key">
                    </div>
                    
                    <div class="adomx-form-group" style="margin-bottom: 15px;">
                        <label class="adomx-form-label">API Secret</label>
                        <input type="password" 
                               class="adomx-form-input" 
                               name="api_secret" 
                               value="{{ old('api_secret') }}"
                               placeholder="Enter API secret">
                    </div>
                    
                    <button type="submit" class="adomx-btn adomx-btn-primary">
                        <i class="fas fa-save"></i> Update Integration
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

