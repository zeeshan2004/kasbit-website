<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->boolean('loader_is_active')->default(true)->after('header_logo');
            $table->string('loader_text', 100)->nullable()->default('Loading...')->after('loader_is_active');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['loader_is_active', 'loader_text']);
        });
    }
};
