@extends('layouts.admin')

@section('content')
<div class="adomx-page-header">
    <div class="adomx-page-title">
        <h2>Create Survey</h2>
    </div>
    <div class="adomx-page-actions">
        <a href="{{ route('admin.surveys.index') }}" class="adomx-btn adomx-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Surveys
        </a>
    </div>
</div>

<div class="adomx-card">
    <div class="adomx-card-header">
        <h3>Survey Information</h3>
    </div>
    <div class="adomx-card-body">
        <form action="{{ route('admin.surveys.store') }}" method="POST" id="surveyForm">
            @csrf

            <div class="adomx-form-group">
                <label for="title" class="adomx-label">Survey Title <span class="text-danger">*</span></label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       class="adomx-input @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" 
                       required>
                @error('title')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="adomx-form-group">
                <label for="description" class="adomx-label">Description</label>
                <textarea id="description" 
                          name="description" 
                          class="adomx-input @error('description') is-invalid @enderror" 
                          rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="adomx-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="type" class="adomx-label">Survey Type <span class="text-danger">*</span></label>
                    <select id="type" 
                            name="type" 
                            class="adomx-input @error('type') is-invalid @enderror" 
                            required>
                        <option value="">Select Type</option>
                        <option value="course" {{ old('type') == 'course' ? 'selected' : '' }}>Course Survey</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General Survey</option>
                    </select>
                    @error('type')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group" id="courseField" style="display: none;">
                    <label for="course_id" class="adomx-label">Course</label>
                    <select id="course_id" 
                            name="course_id" 
                            class="adomx-input @error('course_id') is-invalid @enderror">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label for="start_date" class="adomx-label">Start Date</label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           class="adomx-input @error('start_date') is-invalid @enderror" 
                           value="{{ old('start_date', now()->format('Y-m-d')) }}">
                    @error('start_date')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="adomx-form-group">
                    <label for="end_date" class="adomx-label">End Date</label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           class="adomx-input @error('end_date') is-invalid @enderror" 
                           value="{{ old('end_date', now()->addMonths(1)->format('Y-m-d')) }}">
                    @error('end_date')
                        <span class="adomx-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div class="adomx-form-group">
                    <label class="adomx-checkbox">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span>Active Survey</span>
                    </label>
                </div>

                <div class="adomx-form-group">
                    <label class="adomx-checkbox">
                        <input type="checkbox" 
                               name="is_anonymous" 
                               value="1" 
                               {{ old('is_anonymous') ? 'checked' : '' }}>
                        <span>Anonymous Responses</span>
                    </label>
                </div>
            </div>

            <hr style="margin: 30px 0; border-color: var(--border-color);">

            <div class="adomx-form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Survey Questions</h3>
                    <button type="button" class="adomx-btn adomx-btn-secondary" id="addQuestion">
                        <i class="fas fa-plus"></i> Add Question
                    </button>
                </div>

                <div id="questionsContainer">
                    <!-- Questions will be added here dynamically -->
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="adomx-btn adomx-btn-primary">
                    <i class="fas fa-save"></i> Create Survey
                </button>
                <a href="{{ route('admin.surveys.index') }}" class="adomx-btn adomx-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let questionIndex = 0;

document.getElementById('addQuestion').addEventListener('click', function() {
    addQuestion();
});

document.getElementById('type').addEventListener('change', function() {
    const courseField = document.getElementById('courseField');
    if (this.value === 'course') {
        courseField.style.display = 'block';
    } else {
        courseField.style.display = 'none';
        document.getElementById('course_id').value = '';
    }
});

// Show course field if type is course on page load
if (document.getElementById('type').value === 'course') {
    document.getElementById('courseField').style.display = 'block';
}

function addQuestion() {
    questionIndex++;
    const container = document.getElementById('questionsContainer');
    const questionDiv = document.createElement('div');
    questionDiv.className = 'question-item';
    questionDiv.style.cssText = 'padding: 20px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 20px; background: var(--card-bg);';
    
    questionDiv.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h4 style="margin: 0;">Question ${questionIndex}</h4>
            <button type="button" class="adomx-btn adomx-btn-danger" onclick="this.closest('.question-item').remove()">
                <i class="fas fa-trash"></i> Remove
            </button>
        </div>
        
        <div class="adomx-form-group">
            <label class="adomx-label">Question Text <span class="text-danger">*</span></label>
            <input type="text" 
                   name="questions[${questionIndex}][question]" 
                   class="adomx-input" 
                   required 
                   placeholder="Enter your question">
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div class="adomx-form-group">
                <label class="adomx-label">Question Type <span class="text-danger">*</span></label>
                <select name="questions[${questionIndex}][type]" 
                        class="adomx-input question-type" 
                        required 
                        onchange="toggleOptions(this)">
                    <option value="">Select Type</option>
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="radio">Radio (Single Choice)</option>
                    <option value="checkbox">Checkbox (Multiple Choice)</option>
                    <option value="rating">Rating (1-5)</option>
                    <option value="scale">Scale (1-10)</option>
                </select>
            </div>
            
            <div class="adomx-form-group">
                <label class="adomx-checkbox">
                    <input type="checkbox" 
                           name="questions[${questionIndex}][is_required]" 
                           value="1" 
                           checked>
                    <span>Required Question</span>
                </label>
            </div>
        </div>
        
        <div class="adomx-form-group options-container" style="display: none;">
            <label class="adomx-label">Options (one per line) <span class="text-danger">*</span></label>
            <textarea name="questions[${questionIndex}][options_text]" 
                      class="adomx-input" 
                      rows="4" 
                      placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
            <small style="color: var(--text-secondary);">Enter each option on a new line</small>
        </div>
        
        <input type="hidden" name="questions[${questionIndex}][order]" value="${questionIndex}">
    `;
    
    container.appendChild(questionDiv);
}

function toggleOptions(select) {
    const questionItem = select.closest('.question-item');
    const optionsContainer = questionItem.querySelector('.options-container');
    const optionsTextarea = questionItem.querySelector('textarea[name*="[options_text]"]');
    
    if (['radio', 'checkbox'].includes(select.value)) {
        optionsContainer.style.display = 'block';
        optionsTextarea.required = true;
    } else {
        optionsContainer.style.display = 'none';
        optionsTextarea.required = false;
        optionsTextarea.value = '';
    }
}

// Convert options text to array before form submission
document.getElementById('surveyForm').addEventListener('submit', function(e) {
    const questionItems = document.querySelectorAll('.question-item');
    questionItems.forEach((item, index) => {
        const optionsTextarea = item.querySelector('textarea[name*="[options_text]"]');
        const typeSelect = item.querySelector('select[name*="[type]"]');
        
        if (optionsTextarea && ['radio', 'checkbox'].includes(typeSelect.value)) {
            const options = optionsTextarea.value.split('\n')
                .map(opt => opt.trim())
                .filter(opt => opt.length > 0);
            
            // Create hidden input for options array
            options.forEach((option, optIndex) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `questions[${index + 1}][options][${optIndex}]`;
                hiddenInput.value = option;
                item.appendChild(hiddenInput);
            });
        }
    });
});

// Add first question on page load
if (questionIndex === 0) {
    addQuestion();
}
</script>
@endpush
@endsection

