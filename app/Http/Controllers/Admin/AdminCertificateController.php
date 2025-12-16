<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'course']);

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $certificates = $query->latest()->paginate(20);
        $courses = Course::all();
        $users = User::where('role', 'student')->get();

        return view('admin.certificates.index', compact('certificates', 'courses', 'users'));
    }

    public function create()
    {
        $courses = Course::all();
        $users = User::where('role', 'student')->get();
        return view('admin.certificates.create', compact('courses', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'certificate_url' => 'nullable|string',
        ]);

        // Generate certificate if URL not provided
        if (empty($validated['certificate_url'])) {
            // This would typically call a certificate generation service
            $validated['certificate_url'] = 'certificates/' . uniqid() . '.pdf';
        }

        $validated['issued_at'] = now();

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate created successfully!');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'course']);
        return view('admin.certificates.show', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully!');
    }
}

