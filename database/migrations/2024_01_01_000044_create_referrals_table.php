<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_id');
            $table->uuid('referred_id');
            $table->string('referral_code');
            $table->decimal('reward_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->datetime('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique('referred_id');
            $table->index('referrer_id');
            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};




