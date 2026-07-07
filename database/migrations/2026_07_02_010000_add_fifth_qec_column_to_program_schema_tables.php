<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->string('qec_col5_label')->nullable()->after('qec_show_col4');
            $table->boolean('qec_show_col5')->default(false)->after('qec_col5_label');
        });

        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->string('col5_text', 500)->nullable()->after('col4_text');
        });
    }

    public function down(): void
    {
        Schema::table('program_schema_rows', function (Blueprint $table) {
            $table->dropColumn('col5_text');
        });

        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->dropColumn(['qec_col5_label', 'qec_show_col5']);
        });
    }
};
