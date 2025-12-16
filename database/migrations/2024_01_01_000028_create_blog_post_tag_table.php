<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('blog_post_id');
            $table->uuid('tag_id');
            $table->timestamps();

            $table->foreign('blog_post_id')->references('id')->on('blog_posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unique(['blog_post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_post_tag');
    }
};

