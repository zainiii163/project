<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->uuid('teacher_id');
            $table->uuid('category_id')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('duration')->nullable()->comment('Duration in hours');
            $table->string('thumbnail')->nullable();
            $table->text('objectives')->nullable();
            $table->text('requirements')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->index(['status', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

