<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('top_location_1_name')->nullable()->default('SMCHS')->after('header_email');
            $table->text('top_location_1_url')->nullable()->after('top_location_1_name');
            $table->string('top_location_2_name')->nullable()->default('HYDERI')->after('top_location_1_url');
            $table->text('top_location_2_url')->nullable()->after('top_location_2_name');
            $table->string('top_location_3_name')->nullable()->default('GULSHAN')->after('top_location_2_url');
            $table->text('top_location_3_url')->nullable()->after('top_location_3_name');
            $table->text('header_facebook_url')->nullable()->after('top_location_3_url');
            $table->text('header_twitter_url')->nullable()->after('header_facebook_url');
            $table->text('header_instagram_url')->nullable()->after('header_twitter_url');
            $table->boolean('top_header_is_active')->default(true)->after('header_instagram_url');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn([
                'top_location_1_name',
                'top_location_1_url',
                'top_location_2_name',
                'top_location_2_url',
                'top_location_3_name',
                'top_location_3_url',
                'header_facebook_url',
                'header_twitter_url',
                'header_instagram_url',
                'top_header_is_active',
            ]);
        });
    }
};
