<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('attempt_id');
            $table->uuid('question_id');
            $table->uuid('option_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('attempt_id')->references('id')->on('attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('set null');
            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

