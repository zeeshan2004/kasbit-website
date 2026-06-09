<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('location_title')->nullable();
            $table->text('location_description')->nullable();
            
            // Location 1 - SMCHS
            $table->string('location1_name')->nullable();
            $table->string('location1_image')->nullable();
            
            // Location 2 - Hyderi
            $table->string('location2_name')->nullable();
            $table->string('location2_image')->nullable();
            
            // Location 3 - Gulshan
            $table->string('location3_name')->nullable();
            $table->string('location3_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'location_title',
                'location_description',
                'location1_name',
                'location1_image',
                'location2_name',
                'location2_image',
                'location3_name',
                'location3_image',
            ]);
        });
    }
};
