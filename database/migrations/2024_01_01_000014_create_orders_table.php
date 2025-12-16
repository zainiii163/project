<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->datetime('order_date');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->string('coupon_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('order_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

