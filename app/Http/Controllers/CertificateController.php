<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function generate(Course $course)
    {
        $user = auth()->user();
        
        // Check if user completed the course
        $enrollment = $course->students()->where('user_id', $user->id)->first();
        if (!$enrollment || !$enrollment->pivot->completed_at) {
            return back()->with('error', 'You must complete the course to receive a certificate.');
        }

        // Check if certificate already exists
        if ($course->certificates()->where('user_id', $user->id)->exists()) {
            return back()->with('info', 'Certificate already generated.');
        }

        // Generate certificate (simplified - you can use a PDF library like DomPDF or TCPDF)
        $certificateData = [
            'user_name' => $user->name,
            'course_title' => $course->title,
            'completion_date' => $enrollment->pivot->completed_at->format('F d, Y'),
        ];

        // For now, we'll create a placeholder. In production, generate actual PDF
        $certificateUrl = $this->createCertificatePDF($certificateData);

        Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_url' => $certificateUrl,
            'issued_at' => now(),
        ]);

        return redirect()->route('certificates.show', $course->certificates()->where('user_id', $user->id)->first())
            ->with('success', 'Certificate generated successfully!');
    }

    public function show(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        return view('certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        
        if (Storage::disk('public')->exists($certificate->certificate_url)) {
            return Storage::disk('public')->download($certificate->certificate_url);
        }

        return back()->with('error', 'Certificate file not found.');
    }

    private function createCertificatePDF($data)
    {
        // Placeholder - implement actual PDF generation
        // You can use libraries like DomPDF, TCPDF, or mPDF
        $filename = 'certificates/' . uniqid() . '.pdf';
        
        // TODO: Implement actual PDF generation
        // For now, return a placeholder path
        return $filename;
    }
}

