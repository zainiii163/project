<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with(['course', 'questions'])
            ->withCount('responses')
            ->latest()
            ->paginate(20);

        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->get();

        return view('admin.surveys.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'type' => 'required|in:course,general',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_anonymous' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|max:500',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,rating,scale',
            'questions.*.options' => 'required_if:questions.*.type,radio,checkbox|array',
            'questions.*.is_required' => 'boolean',
            'questions.*.order' => 'required|integer',
        ]);

        $survey = Survey::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'course_id' => $validated['course_id'] ?? null,
            'type' => $validated['type'],
            'is_active' => $validated['is_active'] ?? true,
            'start_date' => $validated['start_date'] ?? now(),
            'end_date' => $validated['end_date'] ?? now()->addMonths(1),
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        foreach ($validated['questions'] as $questionData) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'] ?? [],
                'is_required' => $questionData['is_required'] ?? false,
                'order' => $questionData['order'],
            ]);
        }

        return redirect()->route('admin.surveys.index')
            ->with('success', 'Survey created successfully!');
    }

    public function show(Survey $survey)
    {
        $survey->load(['questions', 'responses.user', 'course']);

        // Aggregate responses
        $aggregatedResponses = [];
        foreach ($survey->questions as $question) {
            $responses = $question->responses;
            $aggregatedResponses[$question->id] = [
                'question' => $question,
                'responses' => $responses,
                'summary' => $this->aggregateResponses($question, $responses),
            ];
        }

        return view('admin.surveys.show', compact('survey', 'aggregatedResponses'));
    }

    private function aggregateResponses($question, $responses)
    {
        if (in_array($question->type, ['rating', 'scale'])) {
            return [
                'average' => round($responses->avg(function($r) {
                    return is_array($r->response) ? $r->response[0] : $r->response;
                }), 2),
                'count' => $responses->count(),
            ];
        }

        if (in_array($question->type, ['radio', 'checkbox'])) {
            $counts = [];
            foreach ($responses as $response) {
                $values = is_array($response->response) ? $response->response : [$response->response];
                foreach ($values as $value) {
                    $counts[$value] = ($counts[$value] ?? 0) + 1;
                }
            }
            return $counts;
        }

        return ['count' => $responses->count()];
    }
}

