<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminSeoController extends Controller
{
    public function index()
    {
        $seoMetas = SeoMeta::with('model')
            ->latest()
            ->paginate(20);

        return view('admin.seo.index', compact('seoMetas'));
    }

    public function edit(SeoMeta $seoMeta)
    {
        $seoMeta->load('model');
        return view('admin.seo.edit', compact('seoMeta'));
    }

    public function generateForCourse(Course $course)
    {
        // Auto-generate SEO meta if not exists
        if (!$course->seoMeta) {
            $seoMeta = SeoMeta::create([
                'model_type' => Course::class,
                'model_id' => $course->id,
                'meta_title' => $course->title . ' - SmartLearn LMS',
                'meta_description' => substr(strip_tags($course->description ?? ''), 0, 160),
                'meta_keywords' => $this->extractKeywords($course),
                'og_title' => $course->title,
                'og_description' => substr(strip_tags($course->description ?? ''), 0, 200),
                'og_image' => $course->thumbnail,
                'canonical_url' => route('courses.show', $course->slug),
            ]);
        }

        return back()->with('success', 'SEO meta generated successfully!');
    }

    public function update(Request $request, SeoMeta $seoMeta)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|array',
            'og_title' => 'nullable|string|max:60',
            'og_description' => 'nullable|string|max:200',
            'og_image' => 'nullable|url',
            'twitter_card' => 'nullable|in:summary,summary_large_image',
            'canonical_url' => 'nullable|url',
            'schema_markup' => 'nullable|array',
        ]);

        if ($request->hasFile('og_image')) {
            $validated['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        $seoMeta->update($validated);

        return back()->with('success', 'SEO meta updated successfully!');
    }

    public function bulkGenerate()
    {
        $courses = Course::where('status', 'published')
            ->whereDoesntHave('seoMeta')
            ->get();

        foreach ($courses as $course) {
            SeoMeta::create([
                'model_type' => Course::class,
                'model_id' => $course->id,
                'meta_title' => $course->title . ' - SmartLearn LMS',
                'meta_description' => substr(strip_tags($course->description ?? ''), 0, 160),
                'meta_keywords' => $this->extractKeywords($course),
                'og_title' => $course->title,
                'og_description' => substr(strip_tags($course->description ?? ''), 0, 200),
                'og_image' => $course->thumbnail,
                'canonical_url' => route('courses.show', $course->slug),
            ]);
        }

        return back()->with('success', "SEO meta generated for {$courses->count()} courses!");
    }

    private function extractKeywords(Course $course)
    {
        $keywords = [];
        
        if ($course->category) {
            $keywords[] = $course->category->name;
        }
        
        if ($course->skill_tags) {
            $tags = explode(',', $course->skill_tags);
            $keywords = array_merge($keywords, $tags);
        }
        
        $keywords[] = $course->level;
        $keywords[] = 'online course';
        $keywords[] = 'e-learning';

        return array_unique(array_filter($keywords));
    }
}

