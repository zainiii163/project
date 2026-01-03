<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('course')
            ->latest()
            ->paginate(20);

        return view('surveys.index', compact('surveys'));
    }

    public function show(Survey $survey)
    {
        if (!$survey->is_active || $survey->end_date < now()) {
            abort(404);
        }

        $survey->load('questions');

        // Check if user already responded
        $hasResponded = auth()->check() && $survey->responses()
            ->where('user_id', auth()->id())
            ->exists();

        return view('surveys.show', compact('survey', 'hasResponded'));
    }

    public function submit(Request $request, Survey $survey)
    {
        if (!$survey->is_active || $survey->end_date < now()) {
            return back()->with('error', 'This survey is no longer active.');
        }

        if (auth()->check() && $survey->responses()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already submitted this survey.');
        }

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*' => 'required',
        ]);

        foreach ($validated['responses'] as $questionId => $response) {
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'question_id' => $questionId,
                'user_id' => auth()->id(),
                'response' => is_array($response) ? $response : [$response],
            ]);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Survey submitted successfully!');
    }
}

