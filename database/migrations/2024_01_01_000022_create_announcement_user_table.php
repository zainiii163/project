<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('announcement_id');
            $table->uuid('user_id');
            $table->boolean('is_read')->default(false);
            $table->datetime('read_at')->nullable();
            $table->timestamps();

            $table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['announcement_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_user');
    }
};

