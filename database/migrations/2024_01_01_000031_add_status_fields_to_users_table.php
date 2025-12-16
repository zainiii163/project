<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active')->after('role');
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'password_changed_at', 'approved_at', 'deleted_at']);
        });
    }
};

