<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('student_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->datetime('due_date')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->enum('submission_type', ['text', 'file'])->default('text');
            $table->integer('max_score')->default(100);
            $table->string('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};

