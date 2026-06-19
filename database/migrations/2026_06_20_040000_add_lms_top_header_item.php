<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('top_location_4_name')->nullable()->default('LMS')->after('top_location_3_url');
            $table->text('top_location_4_url')->nullable()->after('top_location_4_name');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['top_location_4_name', 'top_location_4_url']);
        });
    }
};
