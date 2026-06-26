<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_calendar_tables', function (Blueprint $table) {
            $table->string('col1_label')->nullable()->after('type');
            $table->string('col2_label')->nullable()->after('col1_label');
            $table->string('col3_label')->nullable()->after('col2_label');
        });
    }

    public function down(): void
    {
        Schema::table('academic_calendar_tables', function (Blueprint $table) {
            $table->dropColumn(['col1_label', 'col2_label', 'col3_label']);
        });
    }
};
