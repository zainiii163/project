@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Gamification Settings</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3 class="adomx-card-title">Gamification Configuration</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.settings.gamification.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="points_enabled" value="1" {{ old('points_enabled', true) ? 'checked' : '' }}>
                    Enable Points System
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="badges_enabled" value="1" {{ old('badges_enabled', true) ? 'checked' : '' }}>
                    Enable Badges System
                </label>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">
                    <input type="checkbox" name="leaderboard_enabled" value="1" {{ old('leaderboard_enabled', true) ? 'checked' : '' }}>
                    Enable Leaderboard
                </label>
            </div>

            <div class="adomx-form-row">
                <div class="adomx-form-group">
                    <label class="adomx-form-label">Points per Lesson</label>
                    <input type="number" 
                           min="0" 
                           class="adomx-form-input @error('points_per_lesson') adomx-input-error @enderror" 
                           name="points_per_lesson" 
                           value="{{ old('points_per_lesson', 10) }}">
                    @error('points_per_lesson')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-form-label">Points per Quiz</label>
                    <input type="number" 
                           min="0" 
                           class="adomx-form-input @error('points_per_quiz') adomx-input-error @enderror" 
                           name="points_per_quiz" 
                           value="{{ old('points_per_quiz', 20) }}">
                    @error('points_per_quiz')
                        <div class="adomx-form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="adomx-form-group" style="margin-bottom: 20px;">
                <label class="adomx-form-label">Points per Certificate</label>
                <input type="number" 
                       min="0" 
                       class="adomx-form-input @error('points_per_certificate') adomx-input-error @enderror" 
                       name="points_per_certificate" 
                       value="{{ old('points_per_certificate', 50) }}">
                @error('points_per_certificate')
                    <div class="adomx-form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="adomx-form-actions">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i>
                    Update Gamification Settings
                </button>
                <a href="{{ route('admin.settings.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

