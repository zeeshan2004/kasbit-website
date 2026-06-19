<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('header_menu_pages', function (Blueprint $table) {
            $table->string('pdf_file')->nullable()->after('image');
            $table->string('pdf_original_name')->nullable()->after('pdf_file');
        });
    }

    public function down(): void
    {
        Schema::table('header_menu_pages', function (Blueprint $table) {
            $table->dropColumn(['pdf_file', 'pdf_original_name']);
        });
    }
};
