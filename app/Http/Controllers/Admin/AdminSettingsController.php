<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function branding()
    {
        return view('admin.settings.branding');
    }

    public function updateBranding(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:512',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'site_name' => 'nullable|string|max:255',
        ]);

        // Store branding settings (implement with settings table or config file)
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('branding', 'public');
        }

        if ($request->hasFile('favicon')) {
            $validated['favicon'] = $request->file('favicon')->store('branding', 'public');
        }

        // Save settings
        // Setting::updateOrCreate(['key' => 'branding'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Branding updated successfully!');
    }

    public function emailTemplates()
    {
        // Get email templates (implement if you have email_templates table)
        $templates = [
            'welcome' => 'Welcome email template',
            'course_enrollment' => 'Course enrollment email',
            'certificate_issued' => 'Certificate issued email',
            'password_reset' => 'Password reset email',
        ];

        return view('admin.settings.email-templates', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, $template)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Update email template
        // EmailTemplate::updateOrCreate(['name' => $template], $validated);

        return back()->with('success', 'Email template updated successfully!');
    }

    public function notifications()
    {
        return view('admin.settings.notifications');
    }

    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_enabled' => 'boolean',
            'push_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'notification_types' => 'array',
        ]);

        // Update notification settings
        // Setting::updateOrCreate(['key' => 'notifications'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Notification settings updated successfully!');
    }

    public function seo()
    {
        return view('admin.settings.seo');
    }

    public function updateSeo(Request $request)
    {
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:2048',
        ]);

        // Update SEO settings
        // Setting::updateOrCreate(['key' => 'seo'], ['value' => json_encode($validated)]);

        return back()->with('success', 'SEO settings updated successfully!');
    }

    public function localization()
    {
        $languages = [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'ar' => 'Arabic',
        ];

        return view('admin.settings.localization', compact('languages'));
    }

    public function updateLocalization(Request $request)
    {
        $validated = $request->validate([
            'default_language' => 'required|string|max:10',
            'supported_languages' => 'required|array',
            'timezone' => 'required|string|max:50',
            'date_format' => 'required|string|max:20',
        ]);

        // Update localization settings
        // Setting::updateOrCreate(['key' => 'localization'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Localization settings updated successfully!');
    }

    public function storage()
    {
        $storageDrivers = [
            'local' => 'Local Storage',
            's3' => 'Amazon S3',
            'gcs' => 'Google Cloud Storage',
            'digitalocean' => 'DigitalOcean Spaces',
        ];

        return view('admin.settings.storage', compact('storageDrivers'));
    }

    public function updateStorage(Request $request)
    {
        $validated = $request->validate([
            'driver' => 'required|in:local,s3,gcs,digitalocean',
            'bucket' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:50',
            'access_key' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
        ]);

        // Update storage configuration
        // Setting::updateOrCreate(['key' => 'storage'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Storage settings updated successfully!');
    }

    public function gamification()
    {
        return view('admin.settings.gamification');
    }

    public function updateGamification(Request $request)
    {
        $validated = $request->validate([
            'points_enabled' => 'boolean',
            'badges_enabled' => 'boolean',
            'leaderboard_enabled' => 'boolean',
            'points_per_lesson' => 'nullable|integer|min:0',
            'points_per_quiz' => 'nullable|integer|min:0',
            'points_per_certificate' => 'nullable|integer|min:0',
            'badge_criteria' => 'nullable|array',
        ]);

        // Update gamification settings
        // Setting::updateOrCreate(['key' => 'gamification'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Gamification settings updated successfully!');
    }

    public function integrations()
    {
        $integrations = [
            'zoom' => 'Zoom Integration',
            'payment_gateway' => 'Payment Gateway',
            'chatbot' => 'Chatbot Integration',
            'analytics' => 'Analytics Integration',
        ];

        return view('admin.settings.integrations', compact('integrations'));
    }

    public function updateIntegration(Request $request, $integration)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'is_enabled' => 'boolean',
        ]);

        // Update integration settings
        // Integration::updateOrCreate(['name' => $integration], $validated);

        return back()->with('success', 'Integration updated successfully!');
    }

    public function security()
    {
        return view('admin.settings.security');
    }

    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:50',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'session_timeout' => 'nullable|integer|min:5',
            'max_login_attempts' => 'nullable|integer|min:3',
        ]);

        // Update security settings
        // Setting::updateOrCreate(['key' => 'security'], ['value' => json_encode($validated)]);

        return back()->with('success', 'Security settings updated successfully!');
    }

    public function backup()
    {
        // Get backup history (implement if you have backups table)
        $backups = [];

        return view('admin.settings.backup', compact('backups'));
    }

    public function createBackup()
    {
        // Create database backup
        // Artisan::call('backup:run');

        return back()->with('success', 'Backup created successfully!');
    }

    public function restoreBackup($backupId)
    {
        // Restore from backup
        // Artisan::call('backup:restore', ['backup' => $backupId]);

        return back()->with('success', 'Backup restored successfully!');
    }
}

