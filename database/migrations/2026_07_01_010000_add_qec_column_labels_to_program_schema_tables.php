<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->string('qec_serial_label', 50)->default('S. No')->after('title');
            $table->string('qec_col1_label')->default('Title of Event')->after('qec_serial_label');
            $table->string('qec_col2_label')->default('Date Held')->after('qec_col1_label');
            $table->string('qec_col3_label')->default('Host')->after('qec_col2_label');
            $table->string('qec_col4_label')->nullable()->after('qec_col3_label');
            $table->boolean('qec_show_col4')->default(false)->after('qec_col4_label');
        });

        DB::table('program_schema_tables')
            ->where('title', 'Contribution by QEC')
            ->update([
                'qec_col1_label' => 'Title of Workshop/Seminar',
                'qec_col2_label' => 'Contributed by',
                'qec_col3_label' => 'Venue',
                'qec_col4_label' => 'Date Held',
                'qec_show_col4' => true,
            ]);
    }

    public function down(): void
    {
        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->dropColumn([
                'qec_serial_label',
                'qec_col1_label',
                'qec_col2_label',
                'qec_col3_label',
                'qec_col4_label',
                'qec_show_col4',
            ]);
        });
    }
};
