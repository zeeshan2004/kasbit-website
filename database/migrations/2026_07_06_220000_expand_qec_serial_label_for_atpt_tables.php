<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE program_schema_tables MODIFY qec_serial_label TEXT NULL');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE program_schema_tables MODIFY qec_serial_label VARCHAR(50) NOT NULL DEFAULT 'S. No'");
    }
};
