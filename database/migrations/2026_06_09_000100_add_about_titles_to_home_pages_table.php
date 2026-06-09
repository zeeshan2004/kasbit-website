<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('about_label')->nullable()->after('hero_image');
            $table->string('vision_title')->nullable()->after('about_image');
            $table->string('mission_title')->nullable()->after('vision');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['about_label', 'vision_title', 'mission_title']);
        });
    }
};
