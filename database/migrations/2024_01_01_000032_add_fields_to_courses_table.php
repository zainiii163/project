<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private', 'subscription_only', 'restricted'])->default('public')->after('status');
            $table->timestamp('scheduled_publish_at')->nullable()->after('visibility');
            $table->timestamp('approved_at')->nullable()->after('scheduled_publish_at');
            $table->timestamp('archived_at')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('archived_at');
            $table->text('prerequisites')->nullable()->after('requirements');
            $table->string('skill_tags')->nullable()->after('prerequisites');
            $table->enum('content_type', ['video', 'pdf', 'scorm', 'ar_vr', 'interactive'])->default('video')->after('skill_tags');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'visibility',
                'scheduled_publish_at',
                'approved_at',
                'archived_at',
                'rejection_reason',
                'prerequisites',
                'skill_tags',
                'content_type'
            ]);
        });
    }
};

