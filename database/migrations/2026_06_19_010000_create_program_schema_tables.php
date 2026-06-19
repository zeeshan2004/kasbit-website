<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_schema_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_menu_page_id')->constrained('header_menu_pages')->cascadeOnDelete();
            $table->string('title')->default('Program Schema');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('program_schema_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_schema_table_id')->constrained('program_schema_tables')->cascadeOnDelete();
            $table->string('semester')->nullable();
            $table->string('subject');
            $table->string('credit_hours', 50)->nullable();
            $table->boolean('is_total')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_schema_rows');
        Schema::dropIfExists('program_schema_tables');
    }
};
