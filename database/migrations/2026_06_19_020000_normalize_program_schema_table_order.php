<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pageIds = DB::table('program_schema_tables')
            ->distinct()
            ->pluck('header_menu_page_id');

        foreach ($pageIds as $pageId) {
            $tableIds = DB::table('program_schema_tables')
                ->where('header_menu_page_id', $pageId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->pluck('id');

            foreach ($tableIds as $index => $tableId) {
                DB::table('program_schema_tables')
                    ->where('id', $tableId)
                    ->update(['sort_order' => $index + 1]);
            }
        }
    }

    public function down(): void
    {
        // The original inconsistent display order cannot be restored reliably.
    }
};
