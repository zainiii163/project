<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminMembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::orderBy('sort_order')->paginate(20);

        return view('admin.membership-plans.index', compact('plans'));
    }

    public function create()
    {
        $courses = Course::where('status', 'published')->get();

        return view('admin.membership-plans.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:membership_plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'duration_days' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'is_all_access' => 'boolean',
            'max_courses' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $plan = MembershipPlan::create($validated);

        if ($request->has('course_ids')) {
            $plan->courses()->attach($request->course_ids);
        }

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan created successfully!');
    }

    public function edit(MembershipPlan $membershipPlan)
    {
        $courses = Course::where('status', 'published')->get();
        $selectedCourses = $membershipPlan->courses->pluck('id')->toArray();

        return view('admin.membership-plans.edit', compact('membershipPlan', 'courses', 'selectedCourses'));
    }

    public function update(Request $request, MembershipPlan $membershipPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:membership_plans,slug,' . $membershipPlan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly,lifetime',
            'duration_days' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'is_all_access' => 'boolean',
            'max_courses' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $membershipPlan->update($validated);

        if ($request->has('course_ids')) {
            $membershipPlan->courses()->sync($request->course_ids);
        }

        return redirect()->route('admin.membership-plans.index')
            ->with('success', 'Membership plan updated successfully!');
    }

    public function destroy(MembershipPlan $membershipPlan)
    {
        $membershipPlan->delete();

        return back()->with('success', 'Membership plan deleted successfully!');
    }
}

