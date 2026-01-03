@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Badge</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.gamification.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Badge Details</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.gamification.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="icon">Icon (Font Awesome class)</label>
                        <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', 'fas fa-trophy') }}" placeholder="fas fa-trophy">
                        <small class="form-text text-muted">Example: fas fa-trophy, fas fa-star, fas fa-medal</small>
                        @error('icon')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color">Color (Hex code)</label>
                        <input type="color" name="color" id="color" class="form-control" value="{{ old('color', '#007bff') }}">
                        @error('color')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="type">Type <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-control" required>
                    <option value="achievement" {{ old('type') == 'achievement' ? 'selected' : '' }}>Achievement</option>
                    <option value="completion" {{ old('type') == 'completion' ? 'selected' : '' }}>Completion</option>
                    <option value="participation" {{ old('type') == 'participation' ? 'selected' : '' }}>Participation</option>
                    <option value="special" {{ old('type') == 'special' ? 'selected' : '' }}>Special</option>
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="required_xp">Required XP</label>
                <input type="number" name="required_xp" id="required_xp" class="form-control" value="{{ old('required_xp') }}" min="0">
                <small class="form-text text-muted">Minimum XP points required to earn this badge (optional)</small>
                @error('required_xp')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Criteria (JSON format)</label>
                <textarea name="criteria" id="criteria" class="form-control" rows="5" placeholder='{"courses_completed": 5, "quizzes_passed": 10}'>{{ old('criteria') ? json_encode(old('criteria'), JSON_PRETTY_PRINT) : '' }}</textarea>
                <small class="form-text text-muted">Optional JSON criteria for badge eligibility</small>
                @error('criteria')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Create Badge
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

