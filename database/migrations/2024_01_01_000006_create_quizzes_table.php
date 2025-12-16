<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('lesson_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->integer('max_attempts')->default(1);
            $table->integer('pass_score')->default(70);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

