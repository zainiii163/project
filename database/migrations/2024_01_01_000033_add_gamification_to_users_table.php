<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('xp_points')->default(0)->after('status');
            $table->integer('level')->default(1)->after('xp_points');
            $table->string('referral_code')->unique()->nullable()->after('level');
            $table->uuid('referred_by')->nullable()->after('referral_code');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['xp_points', 'level', 'referral_code', 'referred_by']);
        });
    }
};





