<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quiz_id');
            $table->uuid('user_id');
            $table->integer('score')->default(0);
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->enum('status', ['started', 'completed', 'failed'])->default('started');
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['quiz_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};

