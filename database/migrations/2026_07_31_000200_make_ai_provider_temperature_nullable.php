<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_providers', function (Blueprint $table) {
            $table->decimal('temperature', 3, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        DB::table('ai_providers')->whereNull('temperature')->update(['temperature' => 0.20]);

        Schema::table('ai_providers', function (Blueprint $table) {
            $table->decimal('temperature', 3, 2)->default(0.20)->nullable(false)->change();
        });
    }
};
