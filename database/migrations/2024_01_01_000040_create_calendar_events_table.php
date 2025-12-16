<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('course_id')->nullable();
            $table->uuid('assignment_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->enum('type', ['deadline', 'live_class', 'reminder', 'announcement', 'custom'])->default('reminder');
            $table->string('location')->nullable();
            $table->string('meeting_url')->nullable();
            $table->json('reminder_settings')->nullable();
            $table->boolean('is_all_day')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->index(['user_id', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};




