<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('video_tour_title')->nullable();
            $table->string('video_tour_file')->nullable();
            $table->text('video_tour_url')->nullable();
            $table->string('video_tour_poster')->nullable();
            $table->boolean('video_tour_is_active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'video_tour_title',
                'video_tour_file',
                'video_tour_url',
                'video_tour_poster',
                'video_tour_is_active',
            ]);
        });
    }
};
