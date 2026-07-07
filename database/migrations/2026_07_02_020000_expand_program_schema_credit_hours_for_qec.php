<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->string('credit_hours', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->string('credit_hours', 50)->nullable()->change();
        });
    }
};
