<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content_url')->nullable()->comment('Video URL or file path');
            $table->enum('type', ['video', 'text', 'quiz', 'file'])->default('video');
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->integer('order')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->index(['course_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};

