<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Certificate;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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
        // Generate certificate PDF
        // You can use libraries like DomPDF, TCPDF, or mPDF
        $filename = 'certificates/' . uniqid() . '_' . time() . '.pdf';
        
        // Example with DomPDF (uncomment when package is installed):
        // $pdf = \PDF::loadView('certificates.template', $data);
        // Storage::disk('public')->put($filename, $pdf->output());
        
        // For now, create a placeholder file or return path
        // In production, implement actual PDF generation with:
        // - Certificate template design
        // - User name, course title, completion date
        // - Certificate number/ID
        // - Digital signature or seal
        
        return $filename;
    }

    public function autoGenerate(Course $course, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        
        // Check if certificate already exists
        if (Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists()) {
            return;
        }

        // Generate certificate
        $certificateData = [
            'user_name' => $user->name,
            'course_title' => $course->title,
            'completion_date' => now()->format('F d, Y'),
        ];

        $certificateUrl = $this->createCertificatePDF($certificateData);

        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_url' => $certificateUrl,
            'issued_at' => now(),
        ]);

        // Send notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'certificate_issued',
            'title' => 'Certificate Issued',
            'message' => 'Congratulations! You have completed "' . $course->title . '" and received a certificate.',
            'data' => [
                'certificate_id' => $certificate->id,
                'course_id' => $course->id,
            ],
        ]);

        // Send email
        try {
            Mail::send('emails.certificate', [
                'certificate' => $certificate,
                'user' => $user,
                'course' => $course,
            ], function ($message) use ($user, $course) {
                $message->to($user->email, $user->name)
                    ->subject('Certificate Issued: ' . $course->title);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email: ' . $e->getMessage());
        }

        return $certificate;
    }
}

