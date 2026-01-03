<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->decimal('score', 8, 2)->nullable()->after('grade');
            $table->enum('evaluation_type', ['manual', 'automated'])->default('manual')->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['score', 'evaluation_type']);
        });
    }
};

