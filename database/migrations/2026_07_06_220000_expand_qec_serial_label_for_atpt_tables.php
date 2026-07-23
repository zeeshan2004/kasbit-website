<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->text('qec_serial_label')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('program_schema_tables', function (Blueprint $table) {
            $table->string('qec_serial_label', 50)->nullable(false)->default('S. No')->change();
        });
    }
};
