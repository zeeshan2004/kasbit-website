<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_calendar_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_menu_page_id')->constrained('header_menu_pages')->cascadeOnDelete();
            $table->string('title');
            $table->string('type', 30)->default('semester');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('academic_calendar_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_calendar_table_id')->constrained('academic_calendar_tables')->cascadeOnDelete();
            $table->string('occasion')->nullable();
            $table->string('days')->nullable();
            $table->string('date_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendar_rows');
        Schema::dropIfExists('academic_calendar_tables');
    }
};
