<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('bookmarkable_id');
            $table->string('bookmarkable_type'); // 'App\Models\Course' or 'App\Models\Lesson'
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'bookmarkable_id', 'bookmarkable_type']);
            $table->index(['bookmarkable_id', 'bookmarkable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};




