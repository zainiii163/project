<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('user_id');
            $table->text('message');
            $table->uuid('parent_id')->nullable()->comment('For threaded discussions');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('discussions')->onDelete('cascade');
            $table->index(['course_id', 'parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};

