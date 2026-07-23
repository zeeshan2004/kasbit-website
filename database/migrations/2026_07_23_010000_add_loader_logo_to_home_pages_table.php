<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->string('loader_logo')->nullable()->after('header_logo');
        });
    }

    public function down(): void
    {
        Schema::table('home_pages', function (Blueprint $table) {
            $table->dropColumn('loader_logo');
        });
    }
};
