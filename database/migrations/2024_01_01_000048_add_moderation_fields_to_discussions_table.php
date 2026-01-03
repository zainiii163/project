<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('parent_id');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->boolean('is_pinned')->default(false)->after('rejection_reason');
            $table->boolean('is_locked')->default(false)->after('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason', 'is_pinned', 'is_locked']);
        });
    }
};

