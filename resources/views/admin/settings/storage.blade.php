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
                <select class="adomx-form-input @error('driver') adomx-input-error @enderror" name="driver" id="storage-driver" required>
                    <option value="local" {{ old('driver', 'local') == 'local' ? 'selected' : '' }}>Local Storage</option>
                    <option value="s3" {{ old('driver') == 's3' ? 'selected' : '' }}>Amazon S3</option>
                    <option value="gcs" {{ old('driver') == 'gcs' ? 'selected' : '' }}>Google Cloud Storage</option>
                    <option value="spaces" {{ old('driver') == 'spaces' ? 'selected' : '' }}>DigitalOcean Spaces</option>
                </select>
                @error('driver')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
                <small class="adomx-form-text">Select your storage provider. Cloud storage requires additional configuration.</small>
            </div>

            <div id="cloud-storage-config" style="display: none;">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Cloud Storage Configuration:</strong> Configure your cloud storage credentials in the <code>.env</code> file. 
                    The form below allows you to update settings that will be saved to your configuration.
                </div>
            </div>

            <div id="s3-config" class="cloud-config" style="display: none;">
                <h4 class="mt-4 mb-3">Amazon S3 Configuration</h4>
                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">S3 Bucket Name</label>
                    <input type="text" 
                           class="adomx-form-input @error('bucket') adomx-input-error @enderror" 
                           name="bucket" 
                           value="{{ old('bucket', env('AWS_BUCKET')) }}"
                           placeholder="my-bucket-name">
                    @error('bucket')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set AWS_BUCKET in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">AWS Region</label>
                    <input type="text" 
                           class="adomx-form-input @error('region') adomx-input-error @enderror" 
                           name="region" 
                           value="{{ old('region', env('AWS_DEFAULT_REGION')) }}"
                           placeholder="us-east-1">
                    @error('region')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set AWS_DEFAULT_REGION in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">AWS Access Key ID</label>
                    <input type="text" 
                           class="adomx-form-input @error('access_key') adomx-input-error @enderror" 
                           name="access_key" 
                           value="{{ old('access_key') }}"
                           placeholder="AKIAIOSFODNN7EXAMPLE">
                    @error('access_key')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set AWS_ACCESS_KEY_ID in .env file (sensitive)</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">AWS Secret Access Key</label>
                    <input type="password" 
                           class="adomx-form-input @error('secret_key') adomx-input-error @enderror" 
                           name="secret_key" 
                           value="{{ old('secret_key') }}"
                           placeholder="Enter secret key">
                    @error('secret_key')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set AWS_SECRET_ACCESS_KEY in .env file (sensitive)</small>
                </div>
            </div>

            <div id="gcs-config" class="cloud-config" style="display: none;">
                <h4 class="mt-4 mb-3">Google Cloud Storage Configuration</h4>
                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">GCS Bucket Name</label>
                    <input type="text" 
                           class="adomx-form-input @error('bucket') adomx-input-error @enderror" 
                           name="bucket" 
                           value="{{ old('bucket', env('GOOGLE_CLOUD_STORAGE_BUCKET')) }}"
                           placeholder="my-gcs-bucket">
                    @error('bucket')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set GOOGLE_CLOUD_STORAGE_BUCKET in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">GCS Project ID</label>
                    <input type="text" 
                           class="adomx-form-input @error('project_id') adomx-input-error @enderror" 
                           name="project_id" 
                           value="{{ old('project_id', env('GOOGLE_CLOUD_PROJECT_ID')) }}"
                           placeholder="my-project-id">
                    @error('project_id')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set GOOGLE_CLOUD_PROJECT_ID in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">GCS Key File Path</label>
                    <input type="text" 
                           class="adomx-form-input @error('key_file') adomx-input-error @enderror" 
                           name="key_file" 
                           value="{{ old('key_file', env('GOOGLE_CLOUD_KEY_FILE')) }}"
                           placeholder="/path/to/service-account-key.json">
                    @error('key_file')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set GOOGLE_CLOUD_KEY_FILE in .env file (path to service account JSON)</small>
                </div>
            </div>

            <div id="spaces-config" class="cloud-config" style="display: none;">
                <h4 class="mt-4 mb-3">DigitalOcean Spaces Configuration</h4>
                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">Spaces Bucket Name</label>
                    <input type="text" 
                           class="adomx-form-input @error('bucket') adomx-input-error @enderror" 
                           name="bucket" 
                           value="{{ old('bucket', env('DO_SPACES_BUCKET')) }}"
                           placeholder="my-spaces-bucket">
                    @error('bucket')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set DO_SPACES_BUCKET in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">Spaces Region</label>
                    <input type="text" 
                           class="adomx-form-input @error('region') adomx-input-error @enderror" 
                           name="region" 
                           value="{{ old('region', env('DO_SPACES_REGION')) }}"
                           placeholder="nyc3">
                    @error('region')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set DO_SPACES_REGION in .env file (e.g., nyc3, sfo3)</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">Spaces Endpoint</label>
                    <input type="text" 
                           class="adomx-form-input @error('endpoint') adomx-input-error @enderror" 
                           name="endpoint" 
                           value="{{ old('endpoint', env('DO_SPACES_ENDPOINT')) }}"
                           placeholder="https://nyc3.digitaloceanspaces.com">
                    @error('endpoint')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set DO_SPACES_ENDPOINT in .env file</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">Spaces Access Key</label>
                    <input type="text" 
                           class="adomx-form-input @error('access_key') adomx-input-error @enderror" 
                           name="access_key" 
                           value="{{ old('access_key') }}"
                           placeholder="Enter access key">
                    @error('access_key')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set DO_SPACES_KEY in .env file (sensitive)</small>
                </div>

                <div class="adomx-form-group" style="margin-bottom: 20px;">
                    <label class="adomx-form-label">Spaces Secret Key</label>
                    <input type="password" 
                           class="adomx-form-input @error('secret_key') adomx-input-error @enderror" 
                           name="secret_key" 
                           value="{{ old('secret_key') }}"
                           placeholder="Enter secret key">
                    @error('secret_key')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                    <small class="adomx-form-text">Set DO_SPACES_SECRET in .env file (sensitive)</small>
                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const driverSelect = document.getElementById('storage-driver');
    const cloudConfig = document.getElementById('cloud-storage-config');
    const s3Config = document.getElementById('s3-config');
    const gcsConfig = document.getElementById('gcs-config');
    const spacesConfig = document.getElementById('spaces-config');

    function toggleConfig() {
        const value = driverSelect.value;
        cloudConfig.style.display = value !== 'local' ? 'block' : 'none';
        s3Config.style.display = value === 's3' ? 'block' : 'none';
        gcsConfig.style.display = value === 'gcs' ? 'block' : 'none';
        spacesConfig.style.display = value === 'spaces' ? 'block' : 'none';
    }

    driverSelect.addEventListener('change', toggleConfig);
    toggleConfig(); // Initialize on page load
});
</script>
@endsection

