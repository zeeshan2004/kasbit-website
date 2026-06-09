<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->text('location1_map_url')->nullable()->change();
            $table->text('location2_map_url')->nullable()->change();
            $table->text('location3_map_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('location1_map_url')->nullable()->change();
            $table->string('location2_map_url')->nullable()->change();
            $table->string('location3_map_url')->nullable()->change();
        });
    }
};
