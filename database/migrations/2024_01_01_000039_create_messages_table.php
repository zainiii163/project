<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('from_id');
            $table->uuid('to_id');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->datetime('read_at')->nullable();
            $table->timestamps();

            $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['from_id', 'to_id']);
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};




