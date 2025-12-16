<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('teacher_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('scheduled_at');
            $table->integer('duration_minutes')->default(60);
            $table->enum('platform', ['zoom', 'google_meet', 'teams', 'custom'])->default('zoom');
            $table->string('meeting_id')->nullable();
            $table->string('meeting_url')->nullable();
            $table->string('meeting_password')->nullable();
            $table->json('settings')->nullable();
            $table->enum('status', ['scheduled', 'live', 'completed', 'cancelled'])->default('scheduled');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['course_id', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_sessions');
    }
};




