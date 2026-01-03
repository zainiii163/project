@extends('layouts.main')

@section('content')
<div class="container py-5">
    @if($hasResponded)
    <div class="alert alert-info">
        You have already submitted this survey. Thank you for your feedback!
    </div>
    @else
    <div class="card">
        <div class="card-header">
            <h2>{{ $survey->title }}</h2>
        </div>
        <div class="card-body">
            <p>{{ $survey->description }}</p>

            <form action="{{ route('surveys.submit', $survey) }}" method="POST">
                @csrf

                @foreach($survey->questions as $question)
                <div class="form-group">
                    <label>{{ $question->question }}</label>
                    @if($question->type == 'text')
                        <input type="text" name="responses[{{ $question->id }}]" class="form-control" required>
                    @elseif($question->type == 'textarea')
                        <textarea name="responses[{{ $question->id }}]" class="form-control" rows="3" required></textarea>
                    @elseif($question->type == 'radio')
                        @foreach($question->options as $option)
                        <div class="custom-control custom-radio">
                            <input type="radio" name="responses[{{ $question->id }}]" value="{{ $option }}" class="custom-control-input" id="option_{{ $question->id }}_{{ $loop->index }}" required>
                            <label class="custom-control-label" for="option_{{ $question->id }}_{{ $loop->index }}">{{ $option }}</label>
                        </div>
                        @endforeach
                    @elseif($question->type == 'checkbox')
                        @foreach($question->options as $option)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="responses[{{ $question->id }}][]" value="{{ $option }}" class="custom-control-input" id="option_{{ $question->id }}_{{ $loop->index }}">
                            <label class="custom-control-label" for="option_{{ $question->id }}_{{ $loop->index }}">{{ $option }}</label>
                        </div>
                        @endforeach
                    @endif
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Submit Survey</button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

