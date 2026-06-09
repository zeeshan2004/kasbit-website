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
        Schema::create('home_pages', function (Blueprint $table) {
    $table->id();

    // Hero
    $table->string('hero_title')->nullable();
    $table->text('hero_subtitle')->nullable();
    $table->string('hero_image')->nullable();

    // About
    $table->string('about_title')->nullable();
    $table->text('about_description')->nullable();
    $table->string('about_image')->nullable();

    // Vision
    $table->text('vision')->nullable();

    // Mission
    $table->text('mission')->nullable();

    // News Section
    $table->string('news_bg')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pages');
    }
};
