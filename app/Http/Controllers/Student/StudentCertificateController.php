<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;

class StudentCertificateController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        
        $certificates = $student->certificates()
            ->with('course')
            ->latest()
            ->paginate(20);

        return view('student.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate)
    {
        $student = auth()->user();
        
        // Ensure student owns this certificate
        if ($certificate->user_id !== $student->id) {
            abort(403);
        }

        $certificate->load('course');
        
        return view('student.certificates.show', compact('certificate'));
    }

    public function share(Certificate $certificate, $platform)
    {
        $student = auth()->user();
        
        if ($certificate->user_id !== $student->id) {
            abort(403);
        }

        $certificate->load('course');
        
        $shareUrl = route('student.certificates.show', $certificate);
        $message = "I just completed the course: {$certificate->course->title}! Check out my certificate.";

        $shareLinks = [
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($shareUrl),
            'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode($message) . "&url=" . urlencode($shareUrl),
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($shareUrl),
        ];

        if (isset($shareLinks[$platform])) {
            return redirect($shareLinks[$platform]);
        }

        return back()->with('error', 'Invalid platform');
    }

    public function download(Certificate $certificate)
    {
        $student = auth()->user();
        
        if ($certificate->user_id !== $student->id) {
            abort(403);
        }

        // Download certificate PDF
        if (Storage::exists($certificate->certificate_url)) {
            return Storage::download($certificate->certificate_url);
        }

        return back()->with('error', 'Certificate file not found');
    }

    public function verify($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        $certificate->load(['user', 'course']);

        return view('student.certificates.verify', compact('certificate'));
    }
}

