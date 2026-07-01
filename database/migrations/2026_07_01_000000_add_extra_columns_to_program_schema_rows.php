<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->string('col3_text', 500)->nullable()->after('credit_hours');
            $table->string('col4_text', 500)->nullable()->after('col3_text');
        });
    }

    public function down(): void
    {
        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->dropColumn(['col3_text', 'col4_text']);
        });
    }
};
