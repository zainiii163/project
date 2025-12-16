@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Storage Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Storage Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.storage.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Storage Driver <span class="adomx-required">*</span></label>
                <select class="adomx-form-input @error('driver') adomx-input-error @enderror" name="driver" required>
                    @foreach($storageDrivers as $key => $name)
                        <option value="{{ $key }}" {{ old('driver', 'local') == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('driver')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Bucket Name</label>
                <input type="text" 
                       class="adomx-form-input @error('bucket') adomx-input-error @enderror" 
                       name="bucket" 
                       value="{{ old('bucket') }}"
                       placeholder="Enter bucket name">
                @error('bucket')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Region</label>
                <input type="text" 
                       class="adomx-form-input @error('region') adomx-input-error @enderror" 
                       name="region" 
                       value="{{ old('region') }}"
                       placeholder="e.g., us-east-1">
                @error('region')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Access Key</label>
                <input type="text" 
                       class="adomx-form-input @error('access_key') adomx-input-error @enderror" 
                       name="access_key" 
                       value="{{ old('access_key') }}"
                       placeholder="Enter access key">
                @error('access_key')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Secret Key</label>
                <input type="password" 
                       class="adomx-form-input @error('secret_key') adomx-input-error @enderror" 
                       name="secret_key" 
                       value="{{ old('secret_key') }}"
                       placeholder="Enter secret key">
                @error('secret_key')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Storage Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

