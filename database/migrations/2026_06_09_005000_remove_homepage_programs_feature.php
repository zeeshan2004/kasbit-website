<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('programs');

        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'programs_bg',
                'programs_title',
                'programs_description',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('programs_bg')->nullable()->after('mission');
            $table->string('programs_title')->nullable()->after('programs_bg');
            $table->text('programs_description')->nullable()->after('programs_title');
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('link')->default('#');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
