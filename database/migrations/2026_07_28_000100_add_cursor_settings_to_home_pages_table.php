<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->boolean('cursor_is_active')->default(true)->after('loader_text');
            $table->string('cursor_color', 7)->default('#ffcc00')->after('cursor_is_active');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn(['cursor_is_active', 'cursor_color']);
        });
    }
};
